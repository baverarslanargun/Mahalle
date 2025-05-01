<?php
// scripts/import_locations.php

require __DIR__ . '/inc/config.php';
require __DIR__ . '/inc/db.php';   // burada $db adında PDO bağlantısı var

$db->beginTransaction();

// JSON dosyasını oku ve diziye çevir
$json = file_get_contents(__DIR__ . '/data/il-ilce-mahalle-temiz.json');
$data = json_decode($json, true);

if (!isset($data['iller']) || !is_array($data['iller'])) {
    echo "JSON içinde 'iller' dizisi bulunamadı veya yanlış formatta.\n";
    exit(1);
}

try {
    foreach ($data['iller'] as $ilItem) {
        // İl
        $ilisim = $ilItem['il'];
        $stmt = $db->prepare("INSERT IGNORE INTO il (isim) VALUES (:isim)");
        $stmt->execute([':isim' => $ilisim]);
        $il_id = $db->lastInsertId() ?: 
                 $db->query("SELECT id FROM il WHERE isim = " . $db->quote($ilisim))->fetchColumn();

        // İlçeler
        if (!empty($ilItem['ilceler']) && is_array($ilItem['ilceler'])) {
            foreach ($ilItem['ilceler'] as $ilceItem) {
                $ilceisim = $ilceItem['ilce'];
                $stmt = $db->prepare("INSERT IGNORE INTO ilce (il_id, isim) VALUES (:il_id, :isim)");
                $stmt->execute([
                    ':il_id' => $il_id,
                    ':isim'  => $ilceisim
                ]);
                $ilce_id = $db->lastInsertId() ?: 
                           $db->query("
                             SELECT id FROM ilce 
                             WHERE il_id = $il_id 
                               AND isim = " . $db->quote($ilceisim)
                           )->fetchColumn();

                // Mahalleler (sadece isimler)
                if (!empty($ilceItem['mahalleler']) && is_array($ilceItem['mahalleler'])) {
                    foreach ($ilceItem['mahalleler'] as $mahName) {
                        $stmt = $db->prepare("
                          INSERT IGNORE INTO mahalle (ilce_id, isim, latitude, longitude)
                          VALUES (:ilce_id, :isim, :lat, :lon)
                        ");
                        $stmt->execute([
                          ':ilce_id' => $ilce_id,
                          ':isim'    => $mahName,
                          ':lat'     => 0.0,
                          ':lon'     => 0.0
                        ]);
                    }
                }
            }
        }
    }

    $db->commit();
    echo "İller, ilçeler ve mahalleler başarıyla içeri aktarıldı.\n";
} catch (Exception $e) {
    $db->rollBack();
    echo "Import sırasında hata: " . $e->getMessage() . "\n";
    exit(1);
}
