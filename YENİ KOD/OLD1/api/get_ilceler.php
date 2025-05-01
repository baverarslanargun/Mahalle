<?php
// api/get_ilceler.php?il_id=123
require_once __DIR__ . '/../inc/db.php';
$il_id = isset($_GET['il_id']) ? (int)$_GET['il_id'] : 0;
$stmt = $db->prepare("SELECT id, isim FROM ilce WHERE il_id = ? ORDER BY isim");
$stmt->execute([$il_id]);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
