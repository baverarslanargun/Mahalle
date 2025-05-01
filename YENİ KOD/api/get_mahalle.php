<?php
/**
 * API: Mahalle detaylarını ve değerlendirmelerini döndürür
 * 
 * Bu API, belirli bir mahallenin değerlendirme detaylarını,
 * kategori ortalamalarını ve yorumlarını sayfalama ile döndürür.
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
    
    // Parametreleri al ve güvenli hale getir
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = (int)($_GET['per_page'] ?? 10);
    $offset = ($page - 1) * $perPage;
    
    // Mahalle ID kontrolü
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Geçersiz mahalle ID değeri'
        ]);
        exit;
    }
    
    // Mahallenin varlığını kontrol et
    $checkStmt = $db->prepare("SELECT id, isim, latitude, longitude FROM mahalle WHERE id = :id");
    $checkStmt->execute([':id' => $id]);
    $mahalleInfo = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mahalleInfo) {
        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'Mahalle bulunamadı'
        ]);
        exit;
    }
    
    // 1) Genel istatistikler: yalnızca o mahalledeki review'lardan
    $statsSql = "
      SELECT
        COALESCE(ROUND(AVG(rr.puan),2), 0)  AS genel_ortalama,
        COUNT(DISTINCT r.id)                AS toplam_yorum
      FROM review r
      JOIN review_rating rr 
        ON rr.review_id = r.id
      WHERE r.mahalle_id = :mid
    ";
    $stmt = $db->prepare($statsSql);
    $stmt->execute([':mid' => $id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Varsayılan değerler
    if ($stats['genel_ortalama'] === null) {
        $stats['genel_ortalama'] = '0.00';
    }
    
    // 2) Kategori bazlı ortalamalar
    $catSql = "
      SELECT
        k.id,
        k.isim,
        COALESCE(
          ROUND(
            AVG(
              CASE 
                WHEN r.mahalle_id = :mid THEN rr.puan 
                ELSE NULL 
              END
            )
          ,2), 0
        ) AS ortalama
      FROM kategori k
      LEFT JOIN review_rating rr 
        ON rr.kategori_id = k.id
      LEFT JOIN review r 
        ON r.id = rr.review_id
      GROUP BY k.id
      ORDER BY k.isim ASC
    ";
    $stmt = $db->prepare($catSql);
    $stmt->execute([':mid' => $id]);
    $categoryAverages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 3) O mahalleye ait yorum listesi (pagination)
    $comSql = "
      SELECT
        r.id,
        r.yorum,
        r.created_at,
        COALESCE(ROUND(AVG(rr.puan),2), 0) AS ort
      FROM review r
      JOIN review_rating rr 
        ON rr.review_id = r.id
      WHERE r.mahalle_id = :mid
      GROUP BY r.id
      ORDER BY r.created_at DESC
      LIMIT :off, :lim
    ";
    $stmt = $db->prepare($comSql);
    $stmt->bindValue(':mid', $id, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 4) Toplam sayfa sayısı
    $countSql = "
      SELECT COUNT(*) 
      FROM review 
      WHERE mahalle_id = :mid
    ";
    $stmt = $db->prepare($countSql);
    $stmt->execute([':mid' => $id]);
    $totalReviews = (int)$stmt->fetchColumn();
    $totalPages = ceil($totalReviews / $perPage);
    
    // Konum bilgilerini ekle
    $coordinates = [
        'lat' => (float)$mahalleInfo['latitude'],
        'lng' => (float)$mahalleInfo['longitude']
    ];
    
    // JSON olarak döndür
    echo json_encode([
      'id' => $id,
      'isim' => $mahalleInfo['isim'],
      'coordinates' => $coordinates,
      'stats' => $stats,
      'categories' => $categoryAverages,
      'comments' => $comments,
      'page' => $page,
      'perPage' => $perPage,
      'totalPages' => $totalPages,
      'totalReviews' => $totalReviews
    ]);
} catch (PDOException $e) {
    // Hata durumunda
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Veritabanı hatası: ' . $e->getMessage()
    ]);
}