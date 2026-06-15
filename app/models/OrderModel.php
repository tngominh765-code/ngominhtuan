<?php
/**
 * OrderModel - Order data access + transaction + status/voucher fields.
 */
class OrderModel {
    private $conn;
    private $orderColumns = null;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureEnhancedColumns();
    }

    public function createOrder($name, $phone, $address, $cart, $extra = []) {
        try {
            $this->conn->beginTransaction();
            $totals = $extra['totals'] ?? getCartTotals($cart);
            $accountId = (int)($extra['account_id'] ?? 0);
            $hasAccountId = $this->hasColumns(['account_id']);

            if ($this->hasColumns(['email', 'note', 'payment_method', 'subtotal_amount', 'discount_amount', 'shipping_fee', 'total_amount', 'status'])) {
                $accountColumn = $hasAccountId ? 'account_id, ' : '';
                $accountValue = $hasAccountId ? ':account_id, ' : '';
                $stmt = $this->conn->prepare(
                    "INSERT INTO orders ({$accountColumn}name, phone, email, address, note, payment_method, subtotal_amount, discount_amount, shipping_fee, total_amount, status, created_at)
                     VALUES ({$accountValue}:name, :phone, :email, :address, :note, :payment_method, :subtotal_amount, :discount_amount, :shipping_fee, :total_amount, :status, CURRENT_TIMESTAMP)"
                );
                $params = [
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $extra['email'] ?? '',
                    'address' => $address,
                    'note' => $extra['note'] ?? '',
                    'payment_method' => $extra['payment_method'] ?? 'COD',
                    'subtotal_amount' => $totals['subtotal'] ?? 0,
                    'discount_amount' => $totals['discount'] ?? 0,
                    'shipping_fee' => $totals['shipping'] ?? 0,
                    'total_amount' => $totals['total'] ?? 0,
                    'status' => 'new'
                ];
                if ($hasAccountId) {
                    $params['account_id'] = $accountId > 0 ? $accountId : null;
                }
                $stmt->execute($params);
            } else {
                $accountColumn = $hasAccountId ? 'account_id, ' : '';
                $accountValue = $hasAccountId ? ':account_id, ' : '';
                $stmt = $this->conn->prepare(
                    "INSERT INTO orders ({$accountColumn}name, phone, address, created_at)
                     VALUES ({$accountValue}:name, :phone, :address, CURRENT_TIMESTAMP)"
                );
                $params = [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address
                ];
                if ($hasAccountId) {
                    $params['account_id'] = $accountId > 0 ? $accountId : null;
                }
                $stmt->execute($params);
            }

            $orderId = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare(
                "INSERT INTO order_details (order_id, product_id, quantity, price)
                 VALUES (:order_id, :product_id, :quantity, :price)"
            );

            foreach ($cart as $item) {
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->conn->prepare(
            "SELECT od.*, p.name AS product_name, p.image, p.category_id
             FROM order_details od
             JOIN product p ON od.product_id = p.id
             WHERE od.order_id = :order_id"
        );
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function isOrderOwnedByUser($orderId, $userId, $fullname = '') {
        [$where, $params] = $this->buildUserOrderFilter((int)$userId, $fullname);
        $params[':id'] = (int)$orderId;
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders o WHERE o.id = :id AND {$where}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getRecentOrders($keyword = '', $limit = 30) {
        $params = [];
        $where = '';
        $hasEnhanced = $this->hasColumns(['total_amount', 'status', 'payment_method']);

        if ($keyword !== '') {
            $where = "WHERE (o.name LIKE :kw OR o.phone LIKE :kw OR CAST(o.id AS CHAR) LIKE :kw";
            if ($this->hasColumns(['email'])) {
                $where .= " OR o.email LIKE :kw";
            }
            $where .= ")";
            $params[':kw'] = '%' . $keyword . '%';
        }

        $totalExpr = $hasEnhanced ? 'COALESCE(NULLIF(o.total_amount, 0), od.detail_total, 0)' : 'COALESCE(od.detail_total, 0)';
        $statusExpr = $this->hasColumns(['status']) ? 'o.status' : "'new'";
        $paymentExpr = $this->hasColumns(['payment_method']) ? 'o.payment_method' : "'COD'";

        $sql = "SELECT
                    o.*,
                    {$statusExpr} AS order_status,
                    {$paymentExpr} AS payment_label,
                    COALESCE(od.total_items, 0) AS total_items,
                    {$totalExpr} AS total_amount
                FROM orders o
                LEFT JOIN (
                    SELECT order_id, SUM(quantity) AS total_items, SUM(quantity * price) AS detail_total
                    FROM order_details
                    GROUP BY order_id
                ) od ON od.order_id = o.id
                {$where}
                ORDER BY o.created_at DESC
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($id, $status) {
        if (!$this->hasColumns(['status'])) {
            return false;
        }
        $allowed = ['new', 'processing', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function getOrderStats() {
        $stats = [
            'total_orders' => 0,
            'today_orders' => 0,
            'total_items_sold' => 0,
            'total_revenue' => 0,
            'new_orders' => 0,
            'completed_orders' => 0,
        ];

        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_orders FROM orders");
        $stmt->execute();
        $row = $stmt->fetch();
        $stats['total_orders'] = (int) ($row['total_orders'] ?? 0);

        $stmt = $this->conn->prepare("SELECT COUNT(*) AS today_orders FROM orders WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $row = $stmt->fetch();
        $stats['today_orders'] = (int) ($row['today_orders'] ?? 0);

        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total_items_sold, COALESCE(SUM(quantity * price), 0) AS detail_revenue FROM order_details");
        $stmt->execute();
        $row = $stmt->fetch();
        $stats['total_items_sold'] = (int) ($row['total_items_sold'] ?? 0);
        $stats['total_revenue'] = (float) ($row['detail_revenue'] ?? 0);

        if ($this->hasColumns(['total_amount'])) {
            $stmt = $this->conn->prepare("SELECT COALESCE(SUM(total_amount), 0) AS total_revenue FROM orders");
            $stmt->execute();
            $row = $stmt->fetch();
            if ((float) ($row['total_revenue'] ?? 0) > 0) {
                $stats['total_revenue'] = (float) $row['total_revenue'];
            }
        }

        if ($this->hasColumns(['status'])) {
            $stmt = $this->conn->prepare("SELECT status, COUNT(*) AS total FROM orders GROUP BY status");
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                if ($row['status'] === 'new') {
                    $stats['new_orders'] = (int) $row['total'];
                }
                if ($row['status'] === 'completed') {
                    $stats['completed_orders'] = (int) $row['total'];
                }
            }
        }

        return $stats;
    }


    public function countOrders($keyword = '') {
        $params = []; $where = '';
        if ($keyword !== '') {
            $where = "WHERE (name LIKE :kw OR phone LIKE :kw OR CAST(id AS CHAR) LIKE :kw" . ($this->hasColumns(['email']) ? " OR email LIKE :kw" : "") . ")";
            $params[':kw'] = '%' . $keyword . '%';
        }
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders {$where}");
        foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
        $stmt->execute(); return (int)$stmt->fetchColumn();
    }

    public function getOrders($keyword = '', $page = 1, $perPage = 15) {
        return $this->searchOrders($keyword, $page, $perPage)['orders'];
    }

    public function searchOrders($keyword = '', $page = 1, $perPage = 15) {
        $params = []; $where = '';
        if ($keyword !== '') {
            $where = "WHERE (o.name LIKE :kw OR o.phone LIKE :kw OR CAST(o.id AS CHAR) LIKE :kw" . ($this->hasColumns(['email']) ? " OR o.email LIKE :kw" : "") . ")";
            $params[':kw'] = '%' . $keyword . '%';
        }
        $offset = max(0, ((int)$page - 1) * (int)$perPage);
        $totalExpr = $this->hasColumns(['total_amount']) ? 'COALESCE(NULLIF(o.total_amount, 0), od.detail_total, 0)' : 'COALESCE(od.detail_total, 0)';
        $statusExpr = $this->hasColumns(['status']) ? 'o.status' : "'new'";
        $paymentExpr = $this->hasColumns(['payment_method']) ? 'o.payment_method' : "'COD'";
        $sql = "SELECT o.*, {$statusExpr} AS order_status, {$paymentExpr} AS payment_label, COALESCE(od.total_items, 0) AS total_items, {$totalExpr} AS total_amount
                FROM orders o
                LEFT JOIN (SELECT order_id, SUM(quantity) AS total_items, SUM(quantity * price) AS detail_total FROM order_details GROUP BY order_id) od ON od.order_id = o.id
                {$where} ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['orders'=>$stmt->fetchAll(PDO::FETCH_ASSOC), 'total'=>$this->countOrders($keyword)];
    }

    public function getRevenueLast7Days() {
        $stmt = $this->conn->prepare("SELECT DATE(o.created_at) AS day, COALESCE(SUM(CASE WHEN o.total_amount IS NOT NULL AND o.total_amount > 0 THEN o.total_amount ELSE od.detail_total END), 0) AS revenue FROM orders o LEFT JOIN (SELECT order_id, SUM(quantity * price) AS detail_total FROM order_details GROUP BY order_id) od ON od.order_id=o.id WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(o.created_at) ORDER BY day ASC");
        $stmt->execute(); $map = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) $map[$row['day']] = (float)$row['revenue'];
        $labels = []; $data = [];
        for ($i = 6; $i >= 0; $i--) { $day = date('Y-m-d', strtotime("-{$i} day")); $labels[] = date('d/m', strtotime($day)); $data[] = $map[$day] ?? 0; }
        return [$labels, $data];
    }

    public function getOrderStatsByUser($userId, $fullname = '') {
        $stats = [
            'total_orders' => 0,
            'today_orders' => 0,
            'total_items_sold' => 0,
            'total_revenue' => 0,
            'new_orders' => 0,
            'completed_orders' => 0,
        ];

        [$userWhere, $params] = $this->buildUserOrderFilter((int)$userId, $fullname);
        $totalExpr = $this->hasColumns(['total_amount']) ? 'COALESCE(NULLIF(o.total_amount, 0), od.detail_total, 0)' : 'COALESCE(od.detail_total, 0)';
        $statusExpr = $this->hasColumns(['status']) ? 'o.status' : "'new'";
        $sql = "SELECT
                    COUNT(DISTINCT o.id) AS total_orders,
                    SUM(CASE WHEN DATE(o.created_at) = CURDATE() THEN 1 ELSE 0 END) AS today_orders,
                    COALESCE(SUM(od.total_items), 0) AS total_items_sold,
                    COALESCE(SUM({$totalExpr}), 0) AS total_revenue,
                    SUM(CASE WHEN {$statusExpr} = 'new' THEN 1 ELSE 0 END) AS new_orders,
                    SUM(CASE WHEN {$statusExpr} = 'completed' THEN 1 ELSE 0 END) AS completed_orders
                FROM orders o
                LEFT JOIN (
                    SELECT order_id, SUM(quantity) AS total_items, SUM(quantity * price) AS detail_total
                    FROM order_details
                    GROUP BY order_id
                ) od ON od.order_id = o.id
                WHERE {$userWhere}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        foreach ($stats as $key => $value) {
            $stats[$key] = in_array($key, ['total_revenue'], true) ? (float)($row[$key] ?? 0) : (int)($row[$key] ?? 0);
        }
        return $stats;
    }

    public function countOrdersByUser($userId, $fullname = '', $keyword = '') {
        [$userWhere, $params] = $this->buildUserOrderFilter((int)$userId, $fullname);
        $where = "WHERE {$userWhere}";
        if ($keyword !== '') {
            $where .= " AND (o.phone LIKE :kw OR CAST(o.id AS CHAR) LIKE :kw";
            if ($this->hasColumns(['email'])) $where .= " OR o.email LIKE :kw";
            $where .= ")";
            $params[':kw'] = '%' . $keyword . '%';
        }
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders o {$where}");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getOrdersByUser($userId, $fullname = '', $keyword = '', $page = 1, $perPage = 15) {
        [$userWhere, $params] = $this->buildUserOrderFilter((int)$userId, $fullname);
        $where = "WHERE {$userWhere}";
        if ($keyword !== '') {
            $where .= " AND (o.phone LIKE :kw OR CAST(o.id AS CHAR) LIKE :kw";
            if ($this->hasColumns(['email'])) $where .= " OR o.email LIKE :kw";
            $where .= ")";
            $params[':kw'] = '%' . $keyword . '%';
        }
        $offset = max(0, ((int)$page - 1) * (int)$perPage);
        $totalExpr = $this->hasColumns(['total_amount']) ? 'COALESCE(NULLIF(o.total_amount, 0), od.detail_total, 0)' : 'COALESCE(od.detail_total, 0)';
        $statusExpr = $this->hasColumns(['status']) ? 'o.status' : "'new'";
        $paymentExpr = $this->hasColumns(['payment_method']) ? 'o.payment_method' : "'COD'";
        $sql = "SELECT o.*, {$statusExpr} AS order_status, {$paymentExpr} AS payment_label, COALESCE(od.total_items, 0) AS total_items, {$totalExpr} AS total_amount
                FROM orders o
                LEFT JOIN (SELECT order_id, SUM(quantity) AS total_items, SUM(quantity * price) AS detail_total FROM order_details GROUP BY order_id) od ON od.order_id = o.id
                {$where} ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildUserOrderFilter($userId, $fullname = '') {
        if ($this->hasColumns(['account_id'])) {
            $where = '(o.account_id = :user_id';
            $params = [':user_id' => (int)$userId];

            // Hỗ trợ các đơn cũ đã tạo trước khi có cột account_id.
            if ($fullname !== '') {
                $where .= ' OR (o.account_id IS NULL AND o.name = :fullname)';
                $params[':fullname'] = $fullname;
            }

            $where .= ')';
            return [$where, $params];
        }

        return ['o.name = :fullname', [':fullname' => $fullname]];
    }

    private function ensureEnhancedColumns() {
        try {
            $columns = $this->getOrderColumns(true);
            $driver = $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);

            $needed = [
                'account_id'      => "INT NULL",
                'email'           => "VARCHAR(255) NULL",
                'note'            => "TEXT NULL",
                'payment_method'  => "VARCHAR(50) DEFAULT 'COD'",
                'payment_status'  => "VARCHAR(20) DEFAULT 'unpaid'",
                'subtotal_amount' => "DOUBLE DEFAULT 0",
                'discount_amount' => "DOUBLE DEFAULT 0",
                'shipping_fee'    => "DOUBLE DEFAULT 0",
                'total_amount'    => "DOUBLE DEFAULT 0",
                'status'          => "VARCHAR(30) DEFAULT 'new'",
            ];

            if ($driver === 'sqlite') {
                // SQLite: ALTER TABLE chỉ thêm từng cột một, không hỗ trợ AFTER
                foreach ($needed as $col => $def) {
                    if (!in_array($col, $columns, true)) {
                        try { $this->conn->exec("ALTER TABLE orders ADD COLUMN {$col} {$def}"); } catch (Exception $e) {}
                    }
                }
            } else {
                $adds = [];
                $afterMap = [
                    'account_id'=>'id','email'=>'phone','note'=>'address',
                    'payment_method'=>'note','payment_status'=>'payment_method',
                    'subtotal_amount'=>'payment_status','discount_amount'=>'subtotal_amount',
                    'shipping_fee'=>'discount_amount','total_amount'=>'shipping_fee','status'=>'total_amount',
                ];
                foreach ($needed as $col => $def) {
                    if (!in_array($col, $columns, true)) {
                        $after = isset($afterMap[$col]) ? " AFTER {$afterMap[$col]}" : '';
                        $adds[] = "ADD COLUMN {$col} {$def}{$after}";
                    }
                }
                if (!empty($adds)) {
                    $this->conn->exec("ALTER TABLE orders " . implode(', ', $adds));
                }
            }
            $this->orderColumns = null;
        } catch (Exception $e) {
            // Shared hosting may block ALTER TABLE; the model gracefully falls back to legacy columns.
        }
    }

    private function getOrderColumns($force = false) {
        if ($this->orderColumns !== null && !$force) {
            return $this->orderColumns;
        }
        try {
            $driver = $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $this->conn->query("PRAGMA table_info(orders)");
                $this->orderColumns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name');
            } else {
                $stmt = $this->conn->prepare("SHOW COLUMNS FROM orders");
                $stmt->execute();
                $this->orderColumns = array_map(function ($row) {
                    return $row['Field'];
                }, $stmt->fetchAll());
            }
        } catch (Exception $e) {
            $this->orderColumns = [];
        }
        return $this->orderColumns;
    }

    private function hasColumns($columns) {
        $existing = $this->getOrderColumns();
        foreach ($columns as $column) {
            if (!in_array($column, $existing, true)) {
                return false;
            }
        }
        return true;
    }
}
