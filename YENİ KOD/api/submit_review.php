<?php
require_once __DIR__ . '/../inc/db.php';
// Beklenen POST: mahalle_id, yorum (isteğe bağlı), puan[kategori_id]=1..5
$data = json_decode(file_get_contents('php://input'), true);
$mahalle_id = (int)$data['mahalle_id'];
$yorum      = trim($data['yorum'] ?? '');

$db->beginTransaction();
// 1) review kaydı
$stmt = $db->prepare("INSERT INTO review (mahalle_id, yorum) VALUES (?,?)");
$stmt->execute([$mahalle_id, $yorum]);
$review_id = $db->lastInsertId();

// 2) her kategori için puan
foreach ($data['puan'] as $kategori_id => $puan) {
    $stmt = $db->prepare("
      INSERT INTO review_rating (review_id, kategori_id, puan)
      VALUES (?,?,?)
    ");
    $stmt->execute([$review_id, (int)$kategori_id, (int)$puan]);
}

$db->commit();
header('Content-Type: application/json');
echo json_encode(['success'=>true, 'review_id'=>$review_id]);
