<?php
// Veritabanı bağlantı bilgileri
$host = "localhost"; // veya sizin veritabanı sunucu adresiniz
$user = "root"; // veritabanı kullanıcı adı
$password = ""; // veritabanı şifresi
$db1 = "data";
$db2 = "data";
$db3 = "data";
$db4 = "data";
$db5 = "veri";

// Kullanıcıdan gelen TC kimlik numarasını al
$tc = $_POST['tc'];

// MySQL bağlantısını oluştur
$conn1 = new mysqli($host, $user, $password, $db1);
$conn2 = new mysqli($host, $user, $password, $db2);
$conn3 = new mysqli($host, $user, $password, $db3);
$conn4 = new mysqli($host, $user, $password, $db4);
$conn5 = new mysqli($host, $user, $password, $db5);

// Bağlantı hatalarını kontrol et
if ($conn5->connect_error || $conn1->connect_error || $conn2->connect_error || $conn3->connect_error || $conn4->connect_error) {
    die("Bağlantı hatası: " . $conn1->connect_error . $conn2->connect_error . $conn3->connect_error . $conn4->connect_error . $conn5->connect_error);
}

// Sorguları oluştur
$query1 = "SELECT * FROM 101m WHERE TC = '$tc'";
$query2 = "SELECT * FROM datam WHERE KimlikNo = '$tc'";
$query3 = "SELECT * FROM nicknamepro WHERE TC = '$tc'";
$query4 = "SELECT * FROM data WHERE TC = '$tc'";
$query5 = "SELECT * FROM datam WHERE KimlikNo = '$tc'";


// Sorguları çalıştır ve sonuçları al
$result1 = $conn1->query($query1);
$result2 = $conn2->query($query2);
$result3 = $conn3->query($query3);
$result4 = $conn4->query($query4);
$result5 = $conn5->query($query5);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Sorgu Sonuçları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h1 {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            margin-bottom: 20px;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .result-table th, .result-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .result-table th {
            background-color: #333;
            color: white;
        }
        .no-result {
            text-align: center;
            padding: 10px;
            background-color: #ffdddd;
            color: #a00;
            border: 1px solid #a00;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>HSYS TC Veritabanı</h1>
    <?php
    if ($result1->num_rows > 0) {
        echo "<table class='result-table'><tr><th>TC</th><th>ADI</th><th>SOYADI</th><th>DOĞUM TARİHİ</th><th>NUFUS IL</th><th>NUFUS ILCE</th><th>ANNE ADI</th><th>ANNE TC</th><th>BABA ADI</th><th>BABA TC</th><th>UYRUK</th></tr>";
        while($row = $result1->fetch_assoc()) {
            echo "<tr><td>" . $row["TC"]. "</td><td>" . $row["ADI"]. "</td><td>" . $row["SOYADI"]. "</td><td>" . $row["DOGUMTARIHI"]. "</td><td>" . $row["NUFUSIL"]. "</td><td>" . $row["NUFUSILCE"]. "</td><td>" . $row["ANNEADI"]. "</td><td>" . $row["ANNETC"]. "</td><td>" . $row["BABAADI"]. "</td><td>" . $row["BABATC"]. "</td><td>" . $row["UYRUK"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-result'>Sonuç bulunamadı.</div>";
    }
    ?>

    <h1>Adres 1 Veritabanı</h1>
    <?php
    if ($result2->num_rows > 0) {
        echo "<table class='result-table'><tr><th>Kimlik No</th><th>Ad Soyad</th><th>Doğum Yeri</th><th>Vergi Numarası</th><th>İkametgah</th></tr>";
        while($row = $result2->fetch_assoc()) {
            echo "<tr><td>" . $row["KimlikNo"]. "</td><td>" . $row["AdSoyad"]. "</td><td>" . $row["DogumYeri"]. "</td><td>" . $row["VergiNumarasi"]. "</td><td>" . $row["Ikametgah"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-result'>Sonuç bulunamadı.</div>";
    }
    ?>

	<h1>Adres 2 Veritabanı</h1>
    <?php
    if ($result5->num_rows > 0) {
        echo "<table class='result-table'><tr><th>Kimlik No</th><th>Ad Soyad</th><th>Doğum Yeri</th><th>Vergi Numarası</th><th>İkametgah</th></tr>";
        while($row = $result5->fetch_assoc()) {
            echo "<tr><td>" . $row["KimlikNo"]. "</td><td>" . $row["AdSoyad"]. "</td><td>" . $row["DogumYeri"]. "</td><td>" . $row["VergiNumarasi"]. "</td><td>" . $row["Ikametgah"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-result'>Sonuç bulunamadı.</div>";
    }
    ?>

    <h1>Tarbil Ayrıntılı Veritabanı</h1>
    <?php
    if ($result3->num_rows > 0) {
        echo "<table class='result-table'><tr><th>TC</th><th>AD</th><th>SOYAD</th><th>GSM</th><th>BABA ADI</th><th>BABA TC</th><th>ANNE ADI</th><th>ANNE TC</th><th>DOĞUM TARİHİ</th><th>ÖLÜM TARİHİ</th><th>DOĞUM YERİ</th><th>MEMLEKET IL</th><th>MEMLEKET ILCE</th><th>MEMLEKET KÖY</th><th>ADRES IL</th><th>ADRES ILCE</th><th>AİLE SIRA NO</th><th>BİREY SIRA NO</th><th>MEDENİ HAL</th><th>CİNSİYET</th></tr>";
        while($row = $result3->fetch_assoc()) {
            echo "<tr><td>" . $row["TC"]. "</td><td>" . $row["AD"]. "</td><td>" . $row["SOYAD"]. "</td><td>" . $row["GSM"]. "</td><td>" . $row["BABAADI"]. "</td><td>" . $row["BABATC"]. "</td><td>" . $row["ANNEADI"]. "</td><td>" . $row["ANNETC"]. "</td><td>" . $row["DOGUMTARIHI"]. "</td><td>" . $row["OLUMTARIHI"]. "</td><td>" . $row["DOGUMYERI"]. "</td><td>" . $row["MEMLEKETIL"]. "</td><td>" . $row["MEMLEKETILCE"]. "</td><td>" . $row["MEMLEKETKOY"]. "</td><td>" . $row["ADRESIL"]. "</td><td>" . $row["ADRESILCE"]. "</td><td>" . $row["AILESIRANO"]. "</td><td>" . $row["BIREYSIRANO"]. "</td><td>" . $row["MEDENIHAL"]. "</td><td>" . $row["CINSIYET"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-result'>Sonuç bulunamadı.</div>";
    }
    ?>

    <h1>GSM Veritabanı</h1>
    <?php
    if ($result4->num_rows > 0) {
        echo "<table class='result-table'><tr><th>TC</th><th>GSM</th></tr>";
        while($row = $result4->fetch_assoc()) {
            echo "<tr><td>" . $row["TC"]. "</td><td>" . $row["GSM"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-result'>Sonuç bulunamadı.</div>";
    }
    ?>

</div>

</body>
</html>

<?php
// Bağlantıları kapat
$conn1->close();
$conn2->close();
$conn3->close();
$conn4->close();
?>
