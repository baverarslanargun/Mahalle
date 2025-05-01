<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TC Sorgulama</title>
</head>
<body>
    <form action="tcnwork.php" method="post">
        <label for="tc">TC Kimlik Numarası:</label>
        <input type="text" id="tc" name="tc">
		<br><br>
		<label for="ad">Ad:</label>
		<input type="text" id="ad" name="ad">
		<br><br>
		<label for="soyad">Soyad:</label>
		<input type="text" id="soyad" name="soyad">
        <button type="submit">Sorgula</button>
    </form>
</body>
</html>
