<?php
// api/get_mahalleler_by_ilce.php?ilce_id=456
require_once __DIR__ . '/../inc/db.php';
$ilce_id = isset($_GET['ilce_id']) ? (int)$_GET['ilce_id'] : 0;
$stmt = $db->prepare("SELECT id, isim FROM mahalle WHERE ilce_id = ? ORDER BY isim");
$stmt->execute([$ilce_id]);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
