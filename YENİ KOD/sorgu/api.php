<?php
// Veritabanı bağlantı ayarları
$servername = "localhost";
$username = "root"; // Veritabanı kullanıcı adınız
$password = ""; // Veritabanı şifreniz
$dbname = "veri"; // Veritabanı adınız

// Veritabanına bağlantı kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// POST yöntemi ile gelen TC değerini al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tc = $_POST['TC'];
    
    // Veritabanı sorgusu
    $sql = "SELECT KimlikNo, AdSoyad, DogumYeri, VergiNumarasi, Ikametgah FROM datam WHERE KimlikNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tc);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Sonuçları kontrol et ve listele
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Kimlik No</th><th>Ad Soyad</th><th>Doğum Yeri</th><th>Vergi Numarası</th><th>İkametgah</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["KimlikNo"]."</td><td>".$row["AdSoyad"]."</td><td>".$row["DogumYeri"]."</td><td>".$row["VergiNumarasi"]."</td><td>".$row["Ikametgah"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Kayıt bulunamadı.";
    }
    $stmt->close();
}

$conn->close();
?>
