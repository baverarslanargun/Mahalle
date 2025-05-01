<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data";

// Veritabanına bağlanma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Formdan gelen veriler ile veritabanı sütunlarını eşleştirme
$mapping = [
    'TC' => 'TC',
    'ADI' => 'AD',
    'SOYADI' => 'SOYAD',
    'DOGUMTARIHI' => 'DOGUMTARIHI',
    'NUFUSIL' => 'ADRESIL',
    'NUFUSILCE' => 'ADRESILCE',
    'ANNEADI' => 'ANNEADI',
    'ANNETC' => 'ANNETC',
    'BABAADI' => 'BABAADI',
    'BABATC' => 'BABATC',
    'UYRUK' => 'UYRUK'
];

// Formdan gelen verileri al
$conditions = [];

foreach ($mapping as $postField => $dbField) {
    if (!empty($_POST[$postField])) {
        $conditions[] = "$dbField = '" . $conn->real_escape_string($_POST[$postField]) . "'";
    }
}

// SQL sorgusunu oluştur
$sql = "SELECT * FROM nicknamepro";
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$result = $conn->query($sql);
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
    <h1>Nicknamepro Veritabanı</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='result-table'><tr>
            <th>TC</th><th>AD</th><th>SOYAD</th><th>GSM</th><th>BABA ADI</th><th>BABA TC</th>
            <th>ANNE ADI</th><th>ANNE TC</th><th>DOĞUM TARİHİ</th><th>ÖLÜM TARİHİ</th>
            <th>DOĞUM YERİ</th><th>MEMLEKET IL</th><th>MEMLEKET ILCE</th><th>MEMLEKET KÖY</th>
            <th>ADRES IL</th><th>ADRES ILCE</th><th>AİLE SIRA NO</th><th>MEDENİ HAL</th><th>CİNSİYET</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["TC"] . "</td><td>" . $row["AD"] . "</td><td>" . $row["SOYAD"] . "</td><td>" . $row["GSM"] . "</td>
            <td>" . $row["BABAADI"] . "</td><td>" . $row["BABATC"] . "</td><td>" . $row["ANNEADI"] . "</td><td>" . $row["ANNETC"] . "</td>
            <td>" . $row["DOGUMTARIHI"] . "</td><td>" . $row["OLUMTARIHI"] . "</td><td>" . $row["DOGUMYERI"] . "</td><td>" . $row["MEMLEKETIL"] . "</td>
            <td>" . $row["MEMLEKETILCE"] . "</td><td>" . $row["MEMLEKETKOY"] . "</td><td>" . $row["ADRESIL"] . "</td><td>" . $row["ADRESILCE"] . "</td>
            <td>" . $row["AILESIRANO"] . "</td><td>" . $row["MEDENIHAL"] . "</td><td>" . $row["CINSIYET"] . "</td></tr>";
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
$conn->close();
?>
