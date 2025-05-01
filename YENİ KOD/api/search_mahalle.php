<?php
/**
 * API: Mahalle ismine göre arama yapar
 * 
 * Bu API, isim tabanlı mahalle araması yapar ve sonuçları
 * puanlama ve konum bilgileri ile döndürür.
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
    
    // Arama terimini al
    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    
    // Arama terimi kontrolü
    if (empty($term) || strlen($term) < 2) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Arama terimi en az 2 karakter olmalıdır'
        ]);
        exit;
    }
    
    // Arama sorgusu
    $sql = "
    SELECT 
        m.id, 
        m.isim,
        m.latitude, 
        m.longitude,
        i.isim AS ilce_isim,
        il.isim AS il_isim,
        COALESCE(ROUND(AVG(rr.puan),2), 0) AS ortalama,
        COUNT(DISTINCT r.id) AS yorum_sayisi
    FROM mahalle m
    LEFT JOIN ilce i ON i.id = m.ilce_id
    LEFT JOIN il il ON il.id = i.il_id
    LEFT JOIN review r ON r.mahalle_id = m.id
    LEFT JOIN review_rating rr ON rr.review_id = r.id
    WHERE m.isim LIKE :term OR i.isim LIKE :term OR il.isim LIKE :term
    GROUP BY m.id
    ORDER BY 
        CASE 
            WHEN m.isim LIKE :exact THEN 1 
            WHEN m.isim LIKE :start THEN 2
            ELSE 3 
        END,
        yorum_sayisi DESC
    LIMIT :limit
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);
    $stmt->bindValue(':exact', $term, PDO::PARAM_STR);
    $stmt->bindValue(':start', $term . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
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