<?php
// scripts/geocode_mahalle.php

require __DIR__.'/../inc/config.php';
require __DIR__.'/../inc/db.php';

$userAgent = 'MahallemApp/1.0 (baverarslanargun1@gmail.com)'; 
// Nominatim kullanım koşullarına göre geçerli bir email içermeli.

$stmt = $db->query("SELECT m.id, m.isim AS mah, ilce.isim AS ilce, il.isim AS il
                    FROM mahalle m
                    JOIN ilce ON ilce.id = m.ilce_id
                    JOIN il    ON il.id   = ilce.il_id
                    WHERE (m.latitude = 0 OR m.longitude = 0)
                    LIMIT 30");  // istersen batch’le

$toUpdate = $stmt->fetchAll();

foreach ($toUpdate as $row) {
    $query = urlencode("{$row['mah']}, {$row['ilce']}, {$row['il']}, Türkiye");
    $url   = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";

    $opts = [
        "http" => [
            "header" => "User-Agent: {$userAgent}\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $result  = @file_get_contents($url, false, $context);
    $data    = $result ? json_decode($result, true) : null;

    if (!empty($data[0])) {
        $lat = (float)$data[0]['lat'];
        $lon = (float)$data[0]['lon'];

        $upd = $db->prepare("UPDATE mahalle SET latitude = ?, longitude = ? WHERE id = ?");
        $upd->execute([$lat, $lon, $row['id']]);

        echo "Güncellendi: {$row['mah']} → {$lat},{$lon}\n";
    } else {
        echo "Konum bulunamadı: {$row['mah']}, {$row['ilce']}\n";
    }

    // Nominatim rate limit’e saygı için kısa uyku
    sleep(1);
}

echo "Geocoding tamam.\n";
