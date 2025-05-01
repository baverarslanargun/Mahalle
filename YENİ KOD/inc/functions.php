<?php
// ortak fonksiyonlar
require_once __DIR__ . '/db.php';

/**
 * @return array of ['id'=>int,'isim'=>string]
 */
function getIlList() {
    global $db;
    return $db->query("SELECT id, isim FROM il ORDER BY isim")->fetchAll();
}

function getIlceList($il_id) {
    global $db;
    $stmt = $db->prepare("SELECT id, isim FROM ilce WHERE il_id = ? ORDER BY isim");
    $stmt->execute([$il_id]);
    return $stmt->fetchAll();
}

function getMahalleList($ilce_id) {
    global $db;
    $stmt = $db->prepare("SELECT id, isim FROM mahalle WHERE ilce_id = ? ORDER BY isim");
    $stmt->execute([$ilce_id]);
    return $stmt->fetchAll();
}

function getCategories() {
    global $db;
    return $db->query("SELECT id, isim FROM kategori ORDER BY id")->fetchAll();
}
