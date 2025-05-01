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

// Formdan gelen verileri al
$fields = ['TC', 'ADI', 'SOYADI', 'DOGUMTARIHI', 'NUFUSIL', 'NUFUSILCE', 'ANNEADI', 'ANNETC', 'BABAADI', 'BABATC', 'UYRUK'];
$conditions = [];

foreach ($fields as $field) {
    if (!empty($_POST[$field])) {
        $conditions[] = "$field = '" . $conn->real_escape_string($_POST[$field]) . "'";
    }
}

// SQL sorgusunu oluştur
$sql = "SELECT * FROM 101m";
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
    <h1>101m Veritabanı</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='result-table'><tr><th>TC</th><th>ADI</th><th>SOYADI</th><th>DOĞUM TARİHİ</th><th>NUFUS IL</th><th>NUFUS ILCE</th><th>ANNE ADI</th><th>ANNE TC</th><th>BABA ADI</th><th>BABA TC</th><th>UYRUK</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["TC"] . "</td><td>" . $row["ADI"] . "</td><td>" . $row["SOYADI"] . "</td><td>" . $row["DOGUMTARIHI"] . "</td><td>" . $row["NUFUSIL"] . "</td><td>" . $row["NUFUSILCE"] . "</td><td>" . $row["ANNEADI"] . "</td><td>" . $row["ANNETC"] . "</td><td>" . $row["BABAADI"] . "</td><td>" . $row["BABATC"] . "</td><td>" . $row["UYRUK"] . "</td></tr>";
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
