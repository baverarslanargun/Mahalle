<?php
/**
 * API: Mahalle koordinatlarını döndürür
 * 
 * Bu API, belirli bir mahalle ID'sine göre koordinat 
 * bilgilerini döndürür.
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
    
    // Mahalle ID'sini al
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // ID kontrolü
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Geçersiz mahalle ID değeri'
        ]);
        exit;
    }
    
    // Koordinatları sorgula
    $sql = "
    SELECT 
        id,
        isim,
        latitude, 
        longitude
    FROM mahalle
    WHERE id = ?
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'Mahalle bulunamadı'
        ]);
        exit;
    }
    
    // Float değerlere dönüştür
    $result['latitude'] = (float)$result['latitude'];
    $result['longitude'] = (float)$result['longitude'];
    
    // Sonuçları döndür
    echo json_encode($result);
} catch (PDOException $e) {
    // Hata durumunda
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Veritabanı hatası: ' . $e->getMessage()
    ]);
}s