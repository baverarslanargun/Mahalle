<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Sorgu Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="date"] {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .switch-container {
            margin-bottom: 15px;
            text-align: center;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #007BFF;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .switch-text {
            margin: 0 10px;
            line-height: 34px;
            font-weight: bold;
            color: #333;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Veritabanı Sorgu Paneli</h1>
        <div class="switch-container">
            <label class="switch">
                <input type="checkbox" id="toggleSwitch">
                <span class="slider"></span>
            </label>
            <div class="switch-text">
                <span id="switchText">HSYS</span>
            </div>
        </div>
        <form id="queryForm" action="adsoyad.php" method="post" onsubmit="return validateForm()">
            <label for="TC">TC:</label>
            <input type="text" id="TC" name="TC">

            <label for="ADI">Adı:</label>
            <input type="text" id="ADI" name="ADI">

            <label for="SOYADI">Soyadı:</label>
            <input type="text" id="SOYADI" name="SOYADI">

            <label for="DOGUMTARIHI">Doğum Tarihi:</label>
            <input type="date" id="DOGUMTARIHI" name="DOGUMTARIHI">

            <label for="NUFUSIL">Nüfus İl:</label>
            <input type="text" id="NUFUSIL" name="NUFUSIL">

            <label for="NUFUSILCE">Nüfus İlçe:</label>
            <input type="text" id="NUFUSILCE" name="NUFUSILCE">

            <label for="ANNEADI">Anne Adı:</label>
            <input type="text" id="ANNEADI" name="ANNEADI">

            <label for="ANNETC">Anne TC:</label>
            <input type="text" id="ANNETC" name="ANNETC">

            <label for="BABAADI">Baba Adı:</label>
            <input type="text" id="BABAADI" name="BABAADI">

            <label for="BABATC">Baba TC:</label>
            <input type="text" id="BABATC" name="BABATC">

            <label for="UYRUK">Uyruk:</label>
            <input type="text" id="UYRUK" name="UYRUK">

            <input type="submit" value="Sorgula">
        </form>
    </div>

    <!-- Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="error-message"></p>
        </div>
    </div>

    <script>
        const toggleSwitch = document.getElementById('toggleSwitch');
        const switchText = document.getElementById('switchText');
        const form = document.getElementById('queryForm');
        const errorModal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('error-message');

        toggleSwitch.addEventListener('change', () => {
            if (toggleSwitch.checked) {
                form.action = 'adsoyad2.php';
                switchText.textContent = 'TARBIL';
            } else {
                form.action = 'adsoyad.php';
                switchText.textContent = 'HSYS';
            }
        });

        function validateForm() {
            const inputs = form.querySelectorAll('input[type="text"], input[type="date"]');
            const adi = document.getElementById('ADI').value.trim();
            const soyadi = document.getElementById('SOYADI').value.trim();
            
            // Check if any input is filled
            let anyFilled = false;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    anyFilled = true;
                }
            });

            // If no inputs are filled, check if Adı and Soyadı are filled
            if (!anyFilled) {
                if (adi === '' || soyadi === '') {
                    errorMessage.textContent = 'Adı ve Soyadı alanları doldurulmalıdır.';
                    errorModal.style.display = 'block'; // Show the modal
                    return false; // Prevent form submission
                }
            }

            // Clear error message if validation passes
            errorMessage.textContent = '';
            return true; // Allow form submission
        }

        function closeModal() {
            errorModal.style.display = 'none'; // Hide the modal
        }

        // Close the modal when clicking outside of the modal
        window.onclick = function(event) {
            if (event.target === errorModal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
