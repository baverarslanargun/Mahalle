<?php
// api/get_mahalle.php
require_once __DIR__ . '/../inc/db.php';

$id       = isset($_GET['id'])   ? (int)$_GET['id']   : 0;
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = (int)($_GET['per_page'] ?? 10);
$offset   = ($page - 1) * $perPage;

// 1) Genel istatistikler: yalnızca o mahalledeki review’lardan
$statsSql = "
  SELECT
    ROUND(AVG(rr.puan),2)           AS genel_ortalama,
    COUNT(DISTINCT r.id)            AS toplam_yorum
  FROM review r
  JOIN review_rating rr 
    ON rr.review_id = r.id
  WHERE r.mahalle_id = :mid
";
$stmt = $db->prepare($statsSql);
$stmt->execute([':mid' => $id]);
$stats = $stmt->fetch();

// 2) Kategori bazlı ortalamalar: diğer mahalleleri işin dışı bırakacak CASE WHEN
$catSql = "
  SELECT
    k.id,
    k.isim,
    ROUND(
      AVG(
        CASE 
          WHEN r.mahalle_id = :mid THEN rr.puan 
          ELSE NULL 
        END
      )
    ,2) AS ortalama
  FROM kategori k
  LEFT JOIN review_rating rr 
    ON rr.kategori_id = k.id
  LEFT JOIN review r 
    ON r.id = rr.review_id
  GROUP BY k.id
";
$stmt = $db->prepare($catSql);
$stmt->execute([':mid' => $id]);
$categoryAverages = $stmt->fetchAll();

// 3) O mahalleye ait yorum listesi (pagination)
$comSql = "
  SELECT
    r.id,
    r.yorum,
    r.created_at,
    ROUND(AVG(rr.puan),2) AS ort
  FROM review r
  JOIN review_rating rr 
    ON rr.review_id = r.id
  WHERE r.mahalle_id = :mid
  GROUP BY r.id
  ORDER BY r.created_at DESC
  LIMIT :off, :lim
";
$stmt = $db->prepare($comSql);
$stmt->bindValue(':mid', $id,      PDO::PARAM_INT);
$stmt->bindValue(':off', $offset,  PDO::PARAM_INT);
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll();

// 4) Toplam sayfa sayısı
$countSql = "
  SELECT COUNT(*) 
  FROM review 
  WHERE mahalle_id = :mid
";
$stmt = $db->prepare($countSql);
$stmt->execute([':mid' => $id]);
$totalReviews = (int)$stmt->fetchColumn();
$totalPages   = ceil($totalReviews / $perPage);

// JSON olarak döndür
header('Content-Type: application/json');
echo json_encode([
  'stats'      => $stats,
  'categories' => $categoryAverages,
  'comments'   => $comments,
  'page'       => $page,
  'totalPages' => $totalPages
]);
