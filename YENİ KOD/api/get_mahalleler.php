<?php
/**
 * API: Harita alanındaki mahalleleri döndürür
 * 
 * Bu API, haritanın görünüm alanındaki mahalleleri ve 
 * ortalama puanlarını döndürür.
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
    
    // Parametreleri al
    $southWestLat = isset($_GET['southWestLat']) ? (float)$_GET['southWestLat'] : 0;
    $southWestLng = isset($_GET['southWestLng']) ? (float)$_GET['southWestLng'] : 0;
    $northEastLat = isset($_GET['northEastLat']) ? (float)$_GET['northEastLat'] : 0;
    $northEastLng = isset($_GET['northEastLng']) ? (float)$_GET['northEastLng'] : 0;
    
    // Parametre kontrolü
    if (!$southWestLat || !$southWestLng || !$northEastLat || !$northEastLng) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Eksik veya geçersiz harita koordinatları'
        ]);
        exit;
    }
    
    // Limitleri genişlet - sınırda kalabilecek mahalleleri de dahil et
    $southWestLat -= 0.05;
    $southWestLng -= 0.05;
    $northEastLat += 0.05;
    $northEastLng += 0.05;
    
    // Ana sorgu
    $sql = "
    SELECT 
        m.id, 
        m.isim,
        m.latitude, 
        m.longitude,
        i.isim AS ilce_isim,
        il.isim AS il_isim,
        COALESCE(ROUND(AVG(rr.puan),2), 0) AS ortalama,
        COUNT(rr.id) AS puan_sayisi
    FROM mahalle m
    LEFT JOIN ilce i ON i.id = m.ilce_id
    LEFT JOIN il il ON il.id = i.il_id
    LEFT JOIN review r ON r.mahalle_id = m.id
    LEFT JOIN review_rating rr ON rr.review_id = r.id
    WHERE m.latitude BETWEEN :swLat AND :neLat
      AND m.longitude BETWEEN :swLng AND :neLng
    GROUP BY m.id
    HAVING COUNT(rr.id) > 0
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':swLat' => $southWestLat,
        ':neLat' => $northEastLat,
        ':swLng' => $southWestLng,
        ':neLng' => $northEastLng
    ]);
    
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