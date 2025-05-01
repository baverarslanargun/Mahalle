<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mahallem</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet CSS -->
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --text-color: #374151;
            --light-bg: #f3f4f6;
            --white: #ffffff;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --success: #10b981;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: var(--light-bg);
        }
        </style>

  <!-- Custom Styles -->
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="/index.php">🗺️ Mahallem</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="/index.php">Ana Sayfa</a></li>
          <li class="nav-item"><a class="nav-link active" href="/public/map.php">Harita</a></li>
          <li class="nav-item"><a class="nav-link" href="/public/review.php">Değerlendirme</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container-fluid p-0">


    