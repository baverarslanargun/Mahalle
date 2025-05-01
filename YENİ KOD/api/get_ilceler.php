<?php
/**
 * API: İle göre ilçeleri döndürür
 * 
 * Bu API, belirli bir il ID'sine göre ilçeleri alfabetik sırayla döndürür.
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
    
    // İl ID'sini al ve güvenli hale getir
    $il_id = isset($_GET['il_id']) ? (int)$_GET['il_id'] : 0;
    
    // İl ID kontrolü
    if ($il_id <= 0) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Geçersiz il ID değeri'
        ]);
        exit;
    }
    
    // İlin varlığını kontrol et
    $checkStmt = $db->prepare("SELECT id FROM il WHERE id = ?");
    $checkStmt->execute([$il_id]);
    if (!$checkStmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'İl bulunamadı'
        ]);
        exit;
    }
    
    // İlçeleri sorgula - içinde mahalle sayısı bilgisi ile
    $sql = "
    SELECT 
        i.id, 
        i.isim,
        COUNT(m.id) AS mahalle_sayisi
    FROM ilce i
    LEFT JOIN mahalle m ON m.ilce_id = i.id
    WHERE i.il_id = ?
    GROUP BY i.id
    ORDER BY i.isim ASC
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$il_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sonuçları döndür
    echo json_encode($result);
} catch (PDOException $e) {
    // Hata durumunda
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Veritabanı hatası: ' . $e->getMessage()
    ]);
}