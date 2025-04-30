<?php
header("Content-Type: application/json");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Access-Control-Allow-Origin: *");

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "", "test");
$mysqli->set_charset("utf8mb4");

// Bağlantı hatası kontrolü
if ($mysqli->connect_errno) {
    echo json_encode([
        "hata" => "Veritabanı bağlantı hatası: " . $mysqli->connect_error
    ]);
    exit;
}

// Parametreleri al
$mahalle = isset($_GET["mahalle"]) ? trim($_GET["mahalle"]) : '';
$gun = isset($_GET["gun"]) ? (int)$_GET["gun"] : 0;

// Mahalle adı boşsa hata döndür
if (empty($mahalle)) {
    echo json_encode([
        "hata" => "Mahalle adı boş olamaz."
    ]);
    exit;
}

// SQL sorgusu hazırla
$sql = "SELECT * FROM mahallem WHERE mahalle = ?";
$params = [$mahalle];
$types = "s";

// Gün filtresi eklendi ise sorguyu güncelle
if ($gun > 0) {
    $sql .= " AND tarih >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    $params[] = $gun;
    $types .= "i";
}

// Sorguyu çalıştır
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "hata" => "Sorgu hazırlanırken bir hata oluştu: " . $mysqli->error
    ]);
    exit;
}

// Parametreleri bağla
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode([
        "hata" => "Sorgu çalıştırılırken bir hata oluştu: " . $mysqli->error
    ]);
    exit;
}

// Verileri düzenle
$yorumlar = [];
$puanlar = ["guvenlik" => 0, "temizlik" => 0, "ulasim" => 0, "komsuluk" => 0];
$sayac = 0;

while ($row = $result->fetch_assoc()) {
    $yorumlar[] = [
        "metin" => $row["yorum"],
        "tarih" => date("d.m.Y", strtotime($row["tarih"])),
        "guvenlik" => (int)$row["guvenlik"],
        "temizlik" => (int)$row["temizlik"],
        "ulasim" => (int)$row["ulasim"],
        "komsuluk" => (int)$row["komsuluk"]
    ];

    $puanlar["guvenlik"] += (int)$row["guvenlik"];
    $puanlar["temizlik"] += (int)$row["temizlik"];
    $puanlar["ulasim"] += (int)$row["ulasim"];
    $puanlar["komsuluk"] += (int)$row["komsuluk"];
    $sayac++;
}

// Ortalama hesapla
if ($sayac > 0) {
    foreach ($puanlar as $key => $value) {
        $puanlar[$key] = round($value / $sayac, 1);
    }
}

// Sonuçları döndür
echo json_encode([
    "guvenlik" => $puanlar["guvenlik"],
    "temizlik" => $puanlar["temizlik"],
    "ulasim" => $puanlar["ulasim"],
    "komsuluk" => $puanlar["komsuluk"],
    "yorumlar" => $yorumlar,
    "toplam_yorum" => $sayac
]);

// Bağlantıyı kapat
$stmt->close();
$mysqli->close();
?>