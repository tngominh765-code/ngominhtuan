<?php
class SearchController {
    public function suggest() {
        header('Content-Type: application/json; charset=UTF-8');
        $q = trim($_GET['q'] ?? '');
        if (textLengthUtf8($q) < 2) { echo json_encode([]); exit; }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT p.id, p.name, p.price, p.image, p.category_id, c.name AS category_name FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.name LIKE :q OR p.description LIKE :q2 ORDER BY p.id DESC LIMIT 6");
        $like = '%' . $q . '%';
        $stmt->execute(['q'=>$like,'q2'=>$like]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = array_map(function($r) { return ['id'=>(int)$r['id'], 'name'=>$r['name'], 'price'=>moneyVnd($r['price']), 'category'=>$r['category_name'] ?? '', 'img'=>getImageSrc($r['image'], $r['name'], $r['category_id']), 'url'=>'index.php?url=product/show/' . (int)$r['id']]; }, $rows);
        echo json_encode($result, JSON_UNESCAPED_UNICODE); exit;
    }
}
