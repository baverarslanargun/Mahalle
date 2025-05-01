<?php
// Veritabanı bağlantı ayarları
$servername = "localhost";
$username = "root"; // Veritabanı kullanıcı adınız
$password = ""; // Veritabanı şifreniz
$dbname = "data"; // Veritabanı adınız

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
    $sql = "SELECT TC, GSM FROM data WHERE TC = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tc);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Sonuçları kontrol et ve listele
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Kimlik No</th><th>GSM</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["TC"]."</td><td>".$row["GSM"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Kayıt bulunamadı.";
    }
    $stmt->close();
}

$conn->close();
?>
