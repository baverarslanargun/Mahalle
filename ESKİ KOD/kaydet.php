<?php
header("Content-Type: application/json");
$response = ["success" => false, "message" => ""];

// Veritabanı bağlantı bilgileri
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Bağlantı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4"); // Türkçe karakterler için UTF-8 ayarı

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    $response["message"] = "Veritabanı bağlantı hatası: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

// Form verilerini al ve temizle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen veriler
    $il = isset($_POST['il']) ? trim($_POST['il']) : '';
    $ilce = isset($_POST['ilce']) ? trim($_POST['ilce']) : '';
    $mahalle = isset($_POST['mahalle']) ? trim($_POST['mahalle']) : '';
    $guvenlik = isset($_POST['guvenlik']) ? (int)$_POST['guvenlik'] : 0;
    $temizlik = isset($_POST['temizlik']) ? (int)$_POST['temizlik'] : 0;
    $ulasim = isset($_POST['ulasim']) ? (int)$_POST['ulasim'] : 0;
    $komsuluk = isset($_POST['komsuluk']) ? (int)$_POST['komsuluk'] : 0;
    $yorum = isset($_POST['yorum']) ? trim($_POST['yorum']) : '';
    
    // Veri doğrulama
    if (empty($il) || empty($ilce) || empty($mahalle)) {
        $response["message"] = "İl, ilçe ve mahalle alanları zorunludur.";
        echo json_encode($response);
        exit;
    }
    
    // Puan doğrulama (1-5 arası olmalı)
    if ($guvenlik < 1 || $guvenlik > 5 || 
        $temizlik < 1 || $temizlik > 5 || 
        $ulasim < 1 || $ulasim > 5 || 
        $komsuluk < 1 || $komsuluk > 5) {
        $response["message"] = "Puanlar 1 ile 5 arasında olmalıdır.";
        echo json_encode($response);
        exit;
    }
    
    // Yorum alanı için minimum uzunluk kontrolü
    if (strlen($yorum) < 10) {
        $response["message"] = "Yorum en az 10 karakter olmalıdır.";
        echo json_encode($response);
        exit;
    }
    
    try {
        // SQL injection koruması için prepared statement kullan
        $stmt = $conn->prepare("INSERT INTO mahallem (il, ilce, mahalle, guvenlik, temizlik, ulasim, komsuluk, yorum, tarih) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        if (!$stmt) {
            throw new Exception("Sorgu hazırlanırken hata oluştu: " . $conn->error);
        }
        
        // Parametreleri bağla
        $stmt->bind_param("sssiiiis", $il, $ilce, $mahalle, $guvenlik, $temizlik, $ulasim, $komsuluk, $yorum);
        
        // Sorguyu çalıştır
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Değerlendirmeniz başarıyla kaydedildi. Teşekkür ederiz!";
        } else {
            throw new Exception("Kayıt sırasında hata oluştu: " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $response["message"] = $e->getMessage();
    }
} else {
    $response["message"] = "Geçersiz istek yöntemi. Lütfen formu kullanın.";
}

// Bağlantıyı kapat
$conn->close();

// JSON yanıtını döndür
echo json_encode($response);
?>