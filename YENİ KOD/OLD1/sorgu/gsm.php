<!DOCTYPE html>
<html>
<head>
	
    <meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
	
   <style>
	   body {
		    margin: 0;
            font-family: "Arial, Helvetica, sans-serif"; /* Kullanmak istediğiniz font ailesini belirtin */
            font-size: 12px; /* Kullanmak istediğiniz varsayılan font boyutunu belirtin */
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            max-width: 600px; /* Konteynerin maksimum genişliği */
            margin: 0 auto; /* Ortala */
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: "Arial", Helvetica, sans-serif; /* Daha okunaklı bir font */

        }
        
        .grid-item {
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .grid-item label {
            margin-bottom: 5px;
        }

        .grid-item input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .submit-button {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
	<br><br>
	<br><br>
    <form method="post" action="gsmapi.php"> <!-- Form, POST yöntemi ile gönderilecek -->
        <div class="grid-container">
			 <div class="grid-item">
                <label for="TC">TC:</label>
                <input type="text" name="TC" id="TC">
            </div>
			<input align="center" style="justify-content: center; width: 62px; text-align: center; display:flex; align-content: center; align-items: center;" type="submit" value="Gönder">
        </div>
        
    </form>
</body>
</html>
