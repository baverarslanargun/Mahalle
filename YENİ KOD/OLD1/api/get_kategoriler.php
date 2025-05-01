<?php
// api/get_kategoriler.php
require_once __DIR__ . '/../inc/db.php';

$stmt = $db->query("SELECT COUNT(*) AS sayi FROM kategori");
echo json_encode($stmt->fetch());
