<?php
/**
 * API: Kategori sayısını döndürür
 * 
 * Bu API kategorilerin toplam sayısını JSON formatında döndürür.
 * Mahalle değerlendirme sistemi için gerekli olan verileri sağlar.
 */

// Hata raporlamasını ayarla
ini_set('display_errors', 0);
error_reporting(E_ALL);

// CORS header'ları
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

try {
    // Veritabanı bağlantısını yükle
    require_once __DIR__ . '/../inc/db.php';
    
    // Kategori sayısını sorgula
    $stmt = $db->query("SELECT COUNT(*) AS sayi FROM kategori");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Kategori detaylarını da ekle
    $kategoriler = array();
    $detayStmt = $db->query("SELECT id, isim FROM kategori ORDER BY isim");
    while ($row = $detayStmt->fetch(PDO::FETCH_ASSOC)) {
        $kategoriler[] = $row;
    }
    
    // Sonucu hazırla
    $response = array(
        'sayi' => $result['sayi'], 
        'kategoriler' => $kategoriler
    );
    
    // JSON olarak döndür
    echo json_encode($response);
} catch (PDOException $e) {
    // Hata durumunda
    http_response_code(500);
    echo json_encode(array(
        'error' => true,
        'message' => 'Veritabanı hatası: ' . $e->getMessage()
    ));
    exit;
}