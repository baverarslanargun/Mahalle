<?php
// scripts/geocode_mahalle_improved.php

require __DIR__.'/../inc/config.php';
require __DIR__.'/../inc/db.php';

$userAgent = 'MahallemApp/1.0 (baverarslanargun1@gmail.com)'; 
// Nominatim kullanım koşullarına göre geçerli bir email içermeli.

// Random mahalle seçimi için
$stmt = $db->query("SELECT m.id, m.isim AS mah, ilce.isim AS ilce, il.isim AS il
                    FROM mahalle m
                    JOIN ilce ON ilce.id = m.ilce_id
                    JOIN il ON il.id = ilce.il_id
                    WHERE (m.latitude = 0 OR m.longitude = 0)
                    ORDER BY RAND()  
                    LIMIT 100");

$toUpdate = $stmt->fetchAll();

// API çağrılarını hızlandırmak için curl çoklu istek kullanımı
function batchGeocode($locations, $userAgent) {
    $mh = curl_multi_init();
    $curlHandles = [];
    $results = [];
    
    // Her lokasyon için bir curl isteği oluştur
    foreach ($locations as $index => $location) {
        $query = urlencode("{$location['mah']}, {$location['ilce']}, {$location['il']}, Türkiye");
        $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $curlHandles[$index] = $ch;
        $results[$index] = [
            'handle' => $ch,
            'location' => $location,
            'response' => null
        ];
        
        curl_multi_add_handle($mh, $ch);
    }
    
    // İstekleri çalıştır
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        // Her 200ms'de bir API sınırlamasına saygı için
        if ($running) {
            curl_multi_select($mh, 0.2);
        }
    } while ($running);
    
    // Yanıtları topla
    foreach ($results as $index => &$result) {
        $result['response'] = curl_multi_getcontent($result['handle']);
        curl_multi_remove_handle($mh, $result['handle']);
    }
    
    curl_multi_close($mh);
    return $results;
}

// 5'er gruplar halinde işlem yapalım - Nominatim rate limit'e uymak için
$chunks = array_chunk($toUpdate, 5);
$totalUpdated = 0;
$totalNotFound = 0;

foreach ($chunks as $chunk) {
    $responses = batchGeocode($chunk, $userAgent);
    
    foreach ($responses as $item) {
        $location = $item['location'];
        $data = json_decode($item['response'], true);
        
        if (!empty($data[0])) {
            $lat = (float)$data[0]['lat'];
            $lon = (float)$data[0]['lon'];
            
            $upd = $db->prepare("UPDATE mahalle SET latitude = ?, longitude = ? WHERE id = ?");
            $upd->execute([$lat, $lon, $location['id']]);
            
            echo "Güncellendi: {$location['mah']} → {$lat},{$lon}\n";
            $totalUpdated++;
        } else {
            echo "Konum bulunamadı: {$location['mah']}, {$location['ilce']}\n";
            $totalNotFound++;
        }
    }
    
    // Her grup sonrası 5 saniye bekle - Nominatim rate limit'e saygı için
    sleep(1);
}

echo "Geocoding tamamlandı. Güncellenen: $totalUpdated, Bulunamayan: $totalNotFound\n";