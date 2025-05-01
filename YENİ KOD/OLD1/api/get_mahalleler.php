<?php
// api/get_mahalleler.php
require_once __DIR__ . '/../inc/db.php';

// GET parametreleri: southWestLat, southWestLng, northEastLat, northEastLng
extract($_GET);

$sql = "
SELECT m.id, m.latitude, m.longitude,
       ROUND(AVG(rr.puan),2) AS ortalama,
       COUNT(rr.id) AS puan_sayisi
FROM mahalle m
INNER JOIN review r ON r.mahalle_id = m.id
INNER JOIN review_rating rr ON rr.review_id = r.id
WHERE m.latitude  BETWEEN :swLat AND :neLat
  AND m.longitude BETWEEN :swLng AND :neLng
GROUP BY m.id
HAVING COUNT(rr.id) > 0
";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':swLat'=>$southWestLat,
    ':neLat'=>$northEastLat,
    ':swLng'=>$southWestLng,
    ':neLng'=>$northEastLng
]);

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
