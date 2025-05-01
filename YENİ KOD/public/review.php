<?php 
require_once __DIR__.'/../inc/functions.php';
$iller      = getIlList();
$kategoriler= getCategories();
require_once __DIR__.'/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahalle Değerlendirme Sistemi</title>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <style>
    :root {
        --primary-color: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --secondary-color: #10b981;
        --accent-color: #f59e0b;
        --text-color: #1f2937;
        --text-light: #6b7280;
        --light-bg: #f9fafb;
        --white: #ffffff;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #0ea5e9;
        --success: #10b981;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition-fast: all 0.2s ease;
        --transition-normal: all 0.3s ease;
    }
    
    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        background-color: var(--light-bg);
        line-height: 1.6;
    }
    
    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        position: relative;
    }
    
    .page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        border-radius: 2px;
    }
    
    .page-title {
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 2.25rem;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--text-light);
        font-size: 1.1rem;
    }
    
    .card-shadow {
        box-shadow: var(--card-shadow);
        transition: var(--transition-normal);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-shadow:hover {
        transform: translateY(-5px);
    }
    
    /* Map container */
    .map-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2.5rem;
    }
    
    #map {
        height: 500px;
        width: 100%;
    }
    
    /* Rating stars styling */
    .rating-container {
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
    }
    
    .rating-stars {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.75rem;
        justify-content: center;
        flex-direction: row-reverse;
    }
    
    .star {
        font-size: 1.75rem;
        cursor: pointer;
        transition: var(--transition-normal);
        color: #d1d5db;
        position: relative;
    }
    
    .star:hover,
    .star.active {
        color: var(--accent-color);
    }
    
    /* Improved star hover effects */
    .rating-stars:not(:hover) .star.active ~ .star {
        color: var(--accent-color);
    }
    
    .rating-stars:hover .star {
        color: #d1d5db;
    }
    
    .rating-stars .star:hover ~ .star {
        color: var(--accent-color);
    }
    
    .rating-stars .star:hover {
        color: var(--accent-color);
        transform: scale(1.2);
    }
    
    /* Star animation */
    @keyframes pop {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.3);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @keyframes glow {
        0% {
            text-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
        }
        50% {
            text-shadow: 0 0 15px rgba(245, 158, 11, 0.8);
        }
        100% {
            text-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
        }
    }
    
    .star.active {
        animation: pop 0.3s ease, glow 1.5s infinite;
    }
    
    /* Category cards */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    
    .category-card {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 1.75rem 1.25rem;
        transition: var(--transition-normal);
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        opacity: 0;
        transition: var(--transition-normal);
    }
    
    .category-card:hover::before {
        opacity: 1;
    }
    
    .category-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }
    
    .category-card.invalid {
        border-color: var(--danger);
        animation: shake 0.5s ease;
    }
    
    @keyframes shake {
        0%, 100% {transform: translateX(0);}
        10%, 30%, 50%, 70%, 90% {transform: translateX(-7px);}
        20%, 40%, 60%, 80% {transform: translateX(7px);}
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 1.25rem;
        color: var(--primary-color);
        height: 5rem;
        width: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(79, 70, 229, 0.1);
        border-radius: 50%;
        transition: var(--transition-normal);
    }
    
    .category-card:hover .category-icon {
        background-color: rgba(79, 70, 229, 0.2);
        transform: scale(1.05);
    }
    
    .category-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        text-align: center;
        color: var(--primary-dark);
        font-size: 1.1rem;
    }
    
    /* Form styling */
    .form-container {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
    }
    
    .form-title {
        color: var(--primary-color);
        margin-bottom: 2rem;
        font-weight: 700;
        text-align: center;
        font-size: 1.75rem;
        position: relative;
        display: inline-block;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .form-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--accent-color);
        border-radius: 3px;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-select, 
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        transition: var(--transition-fast);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .form-select:focus, 
    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
        outline: none;
    }
    
    .form-select {
        background-position: right 1rem center;
    }
    
    /* Button styling */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 0.75rem 1.75rem;
        font-weight: 600;
        border-radius: 10px;
        transition: var(--transition-fast);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        transition: var(--transition-fast);
        z-index: -1;
    }
    
    .btn-primary:hover::before {
        left: 0;
    }
    
    .btn-primary:hover {
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        transform: translateY(-2px);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        background-color: transparent;
        font-weight: 500;
        border-radius: 10px;
        transition: var(--transition-fast);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: var(--white);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }
    
    .btn-outline-primary.active {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    /* Search and filter section */
    .search-section {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        margin-bottom: 2.5rem;
    }
    
    .search-title {
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    
    /* Results styling */
    .results-container {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        margin-bottom: 2.5rem;
    }
    
    .results-title {
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
        position: relative;
        display: inline-block;
    }
    
    .results-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 3px;
        background-color: var(--accent-color);
        border-radius: 3px;
    }
    
    .stats-card {
        border-radius: 14px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: var(--transition-normal);
        background-color: #f8fafc;
        border-top: 3px solid var(--primary-light);
    }
    
    .stats-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        color: var(--primary-color);
    }
    
    .stats-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--text-color);
    }
    
    .stats-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    /* Comment card */
    .comment-card {
        border-radius: 14px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        transition: var(--transition-normal);
        border-left: 4px solid var(--primary-light);
    }
    
    .comment-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        transform: translateX(5px);
    }
    
    .comment-text {
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .comment-date {
        font-size: 0.85rem;
        color: var(--text-light);
    }
    
    /* Chart container */
    .chart-container {
        background-color: var(--white);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        margin-bottom: 2.5rem;
    }
    
    /* Rating display in result cards */
    .rating-display {
        display: flex;
        gap: 3px;
    }
    
    .star-filled {
        color: var(--accent-color);
    }
    
    /* Loading spinner */
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(79, 70, 229, 0.1);
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        background-color: var(--white);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 400px;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification.success {
        border-left: 5px solid var(--success);
    }
    
    .notification.error {
        border-left: 5px solid var(--danger);
    }
    
    .notification.warning {
        border-left: 5px solid var(--warning);
    }
    
    .notification.info {
        border-left: 5px solid var(--info);
    }
    
    .notification-icon {
        font-size: 1.5rem;
    }
    
    .notification.success .notification-icon {
        color: var(--success);
    }
    
    .notification.error .notification-icon {
        color: var(--danger);
    }
    
    .notification.warning .notification-icon {
        color: var(--warning);
    }
    
    .notification.info .notification-icon {
        color: var(--info);
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .notification-message {
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    .notification-close {
        color: var(--text-light);
        cursor: pointer;
        transition: var(--transition-fast);
    }
    
    .notification-close:hover {
        color: var(--text-color);
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .form-container,
        .search-section,
        .results-container,
        .chart-container {
            padding: 1.75rem;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        #map {
            height: 350px;
        }
        
        .page-title {
            font-size: 1.75rem;
        }
        
        .form-container,
        .search-section,
        .results-container,
        .chart-container {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .category-grid {
            grid-template-columns: 1fr;
        }
        
        .form-container, 
        .search-section, 
        .results-container,
        .chart-container {
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .form-title {
            font-size: 1.3rem;
        }
    }

    /* Custom section for floating help button */
    .help-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: var(--transition-normal);
        z-index: 99;
    }
    
    .help-button:hover {
        transform: scale(1.1);
        background-color: var(--primary-dark);
    }
    
    .help-button i {
        font-size: 1.5rem;
    }
    
    .help-content {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 300px;
        background-color: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        z-index: 98;
        transform: scale(0);
        transform-origin: bottom right;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .help-content.show {
        transform: scale(1);
    }
    
    .help-content h4 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .help-content p {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    
    .help-content button {
        margin-top: 0.5rem;
    }
</style>
</head>
<body>
<!-- Rating Form -->
<div class="main-container">
    <div class="page-header">
        <h1 class="page-title">Mahalle Değerlendirme Sistemi</h1>
        <p class="page-subtitle">Yaşadığınız mahalleyi değerlendirin, diğer insanların yorumlarını görün</p>
    </div>
    
    <!-- Rating Form -->
    <div class="form-container">
        <h3 class="form-title">Mahalle Değerlendirmesi Yap</h3>
        
        <form id="reviewForm" action="../api/submit_review.php" method="post" class="row g-3">
            <!-- Location Selection -->
            <div class="col-md-4 mb-3">
                <label class="form-label"><i class="fas fa-map-marker-alt me-2"></i>İl:</label>                        
                <select class="form-select" name="il" id="ilSelect">
                    <option value="">Seçiniz</option>
                    <?php foreach($iller as $il): ?>
                        <option value="<?= $il['id'] ?>"><?= htmlspecialchars($il['isim']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Lütfen bir il seçin</div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label"><i class="fas fa-city me-2"></i>İlçe:</label>
                <select name="ilce" class="form-select" id="ilceSelect" disabled>
                    <option value="">Önce il seçin</option>
                </select>
                <div class="invalid-feedback">Lütfen bir ilçe seçin</div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label"><i class="fas fa-home me-2"></i>Mahalle:</label>
                <select name="mahalle" class="form-select" id="mahSelect" disabled>
                    <option value="">Önce ilçe seçin</option>
                </select>
                <div class="invalid-feedback">Lütfen bir mahalle seçin</div>
            </div>

            <!-- Rating Categories (Dinamik) -->
            <div class="col-12">
                <h5 class="mt-3 mb-3 text-center">Kategorilere Göre Puanlama</h5>
                <div class="category-grid">
                    <?php
                        $kategoriIkonlari = [
                            "Ulaşım"            => "fas fa-bus",
                            "Güvenlik"          => "fas fa-shield-alt",
                            "Yeşil Alan"        => "fas fa-tree",
                            "Komşuluk"          => "fas fa-users",
                            "Okullar"           => "fas fa-school",
                            "Sağlık"            => "fas fa-clinic-medical",
                            "Sosyal İmkanlar"   => "fas fa-theater-masks",
                            "Çevresel Kirlilik" => "fas fa-smog",
                            "Temizlik"          => "fas fa-broom"
                        ];
                    ?>
                    <?php foreach($kategoriler as $kat): ?>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="<?= $kategoriIkonlari[$kat['isim']] ?? 'fas fa-star' ?>"></i>
                        </div>
                        <div class="category-title"><?= htmlspecialchars($kat['isim']) ?></div>
                        <div class="rating-stars" data-rating-for="kategori_<?= $kat['id'] ?>">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <span class="star" data-value="<?= $i ?>"><i class="fas fa-star"></i></span>
                            <?php endfor; ?>
                            <input type="hidden" name="puan[<?= $kat['id'] ?>]" value="" required>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Comment -->
            <div class="col-12 mb-4">
                <label class="form-label"><i class="fas fa-comment me-2"></i>Yorum:</label>
                <textarea name="yorum" class="form-control" rows="3" placeholder="Mahalle hakkındaki düşüncelerinizi yazın..." required></textarea>
                <div class="invalid-feedback">Lütfen bir yorum yazın</div>
            </div>

            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-primary" id="submitButton">
                    <i class="fas fa-save me-2"></i>Değerlendirmeyi Kaydet
                </button>
            </div>
        </form>
    </div>
    
    <!-- Search Section -->
    <div class="search-section">
        <h4 class="search-title"><i class="fas fa-search me-2"></i>Mahalle Ara</h4>
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" class="form-control" id="ara" list="mahalleler" placeholder="Mahalle adı yazın...">
                <datalist id="mahalleler"></datalist>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" onclick="yorumGetir()">
                    <i class="fas fa-search me-2"></i>Ara
                </button>
            </div>
            <div class="col-12 mt-3">
                <div class="btn-group">
                    <button class="btn btn-outline-primary active" onclick="yorumGetir()">Tüm Zamanlar</button>
                    <button class="btn btn-outline-primary" onclick="yorumGetir(30)">Son 1 Ay</button>
                    <button class="btn btn-outline-primary" onclick="yorumGetir(90)">Son 3 Ay</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results Container -->
    <div id="sonuc" class="results-container d-none">
        <!-- Results will be loaded dynamically -->
    </div>
    
    <!-- Chart Container -->
    <div id="chartContainer" class="chart-container d-none">
        <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i>Puan Dağılımı</h4>
        <canvas id="puanGrafik" width="400" height="200"></canvas>
    </div>
    
    <!-- Help Button -->
    <div class="help-button" id="helpButton">
        <i class="fas fa-question"></i>
    </div>
    
    <!-- Help Content -->
    <div class="help-content" id="helpContent">
        <h4>Nasıl Kullanılır?</h4>
        <p><strong>1.</strong> İl, ilçe ve mahalle seçin</p>
        <p><strong>2.</strong> Her kategori için yıldız puanı verin</p>
        <p><strong>3.</strong> Mahalleyle ilgili yorumunuzu yazın</p>
        <p><strong>4.</strong> "Değerlendirmeyi Kaydet" butonuna tıklayın</p>
        <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('helpContent').classList.remove('show')">Kapat</button>
    </div>
    
    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">Başarılı</div>
            <div class="notification-message">İşlem başarıyla tamamlandı.</div>
        </div>
                        <div class="notification-close" onclick="hideNotification()">
                    <i class="fas fa-times"></i>
                </div>
            </div>

            <!-- Add loading spinner that appears during form submission -->
            <div id="loadingSpinner" class="d-none">
                <div class="spinner"></div>
                <p class="text-center text-muted">İşleminiz gerçekleştiriliyor...</p>
            </div>
        </div>
                <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script src="/assets/js/review.js"></script>
<?php require_once __DIR__.'/../templates/footer.php'; ?>

<script>
            // Helper function to show notifications
            function showNotification(type, title, message, duration = 5000) {
                const notification = document.getElementById('notification');
                
                // Set content
                notification.querySelector('.notification-title').textContent = title;
                notification.querySelector('.notification-message').textContent = message;
                
                // Set icon based on type
                const iconElement = notification.querySelector('.notification-icon i');
                iconElement.className = ''; // Clear existing classes
                
                // Add appropriate classes
                notification.className = 'notification show ' + type;
                
                switch(type) {
                    case 'success':
                        iconElement.className = 'fas fa-check-circle';
                        break;
                    case 'error':
                        iconElement.className = 'fas fa-exclamation-circle';
                        break;
                    case 'warning':
                        iconElement.className = 'fas fa-exclamation-triangle';
                        break;
                    case 'info':
                    default:
                        iconElement.className = 'fas fa-info-circle';
                        break;
                }
                
                // Show notification
                notification.classList.add('show');
                
                // Hide after duration
                if (duration > 0) {
                    setTimeout(hideNotification, duration);
                }
            }
            
            // Hide notification
            function hideNotification() {
                const notification = document.getElementById('notification');
                notification.classList.remove('show');
            }
            
            // Help button functionality
            document.getElementById('helpButton').addEventListener('click', function() {
                document.getElementById('helpContent').classList.toggle('show');
            });
            
            // Star rating functionality (enhanced)
            document.addEventListener('DOMContentLoaded', function() {
                // Star rating functionality
                initializeStarRatings();
                
                // Form validation 
                initializeFormValidation();
                
                // Location dropdowns
                initializeLocationDropdowns();
                
                // Initialize filter buttons
                initializeFilterButtons();
                
                // Initialize autocomplete
                initializeAutocomplete();
                
                // Initialize charts (if any results are showing)
                if (!document.getElementById('chartContainer').classList.contains('d-none')) {
                    initializeCharts();
                }
            });
            
            // Initialize star ratings
            function initializeStarRatings() {
                const ratingGroups = document.querySelectorAll('.rating-stars');
                
                ratingGroups.forEach(group => {
                    const stars = Array.from(group.querySelectorAll('.star'));
                    const hiddenInput = group.querySelector('input[type="hidden"]');
                    
                    // Add click handler for each star
                    stars.forEach((star) => {
                        star.addEventListener('click', () => {
                            const value = star.getAttribute('data-value');
                            
                            // Update hidden input value
                            hiddenInput.value = value;
                            
                            // Reset all stars
                            stars.forEach(s => s.classList.remove('active'));
                            
                            // Set active stars up to clicked star (reversed)
                            let activated = false;
                            stars.forEach(s => {
                                if (s === star || activated) {
                                    s.classList.add('active');
                                    activated = true;
                                }
                            });
                            
                            // Add animation
                            star.style.animation = 'none';
                            setTimeout(() => {
                                star.style.animation = 'pop 0.3s ease, glow 1.5s infinite';
                            }, 5);
                        });
                    });
                });
            }
            
            // Initialize form validation
            function initializeFormValidation() {
                const form = document.getElementById('reviewForm');
                
                if (form) {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        let valid = true;
                        
                        // Check if il, ilce, mahalle are selected
                        const ilSelect = document.getElementById('ilSelect');
                        const ilceSelect = document.getElementById('ilceSelect');
                        const mahSelect = document.getElementById('mahSelect');
                        
                        if (!ilSelect.value) {
                            ilSelect.classList.add('is-invalid');
                            valid = false;
                        } else {
                            ilSelect.classList.remove('is-invalid');
                        }
                        
                        if (!ilceSelect.value) {
                            ilceSelect.classList.add('is-invalid');
                            valid = false;
                        } else {
                            ilceSelect.classList.remove('is-invalid');
                        }
                        
                        if (!mahSelect.value) {
                            mahSelect.classList.add('is-invalid');
                            valid = false;
                        } else {
                            mahSelect.classList.remove('is-invalid');
                        }
                        
                        // Kategori puanlamayı zorunlu tutmuyoruz - opsiyonel hale getiriyoruz
                        const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
                        let hasAnyRating = false;
                        
                        hiddenInputs.forEach(input => {
                            if (input.value) {
                                hasAnyRating = true;
                            }
                        });
                        
                        // Eğer hiçbir kategori puanlanmadıysa, kullanıcıya bilgi verelim ama formu engellemeyelim
                        if (!hasAnyRating) {
                            showNotification('info', 'Bilgi', 'Hiçbir kategori puanlamadınız. Devam etmek istediğinize emin misiniz?', 3000);
                            // Not: Formu engelleme - sadece bilgilendirme yap
                            // valid = false;
                        }
                        
                        // Check if comment is provided
                        const commentTextarea = form.querySelector('textarea[name="yorum"]');
                        if (!commentTextarea.value.trim()) {
                            commentTextarea.classList.add('is-invalid');
                            valid = false;
                        } else {
                            commentTextarea.classList.remove('is-invalid');
                        }
                        
                        if (!valid) {
                            showNotification('error', 'Hata', 'Lütfen tüm alanları doldurun ve her kategoriyi değerlendirin.');
                            return;
                        }
                        
                        // If all validation passed, submit the form via AJAX
                        submitForm(form);
                    });
                }
            }
            
            // Submit form via AJAX
            function submitForm(form) {
                // Show loading spinner
                document.getElementById('loadingSpinner').classList.remove('d-none');
                
                // Disable submit button
                const submitButton = document.getElementById('submitButton');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
                
                // Prepare form data
                const formData = new FormData(form);
                const payload = {
                    mahalle_id: formData.get('mahalle'),
                    yorum: formData.get('yorum'),
                    puan: {}
                };
                
                // Get all puan fields
                formData.forEach((value, key) => {
                    if (key.startsWith('puan[')) {
                        const match = key.match(/\[(\d+)\]/);
                        if (match && match[1]) {
                            payload.puan[match[1]] = value;
                        }
                    }
                });
                
                // Send AJAX request
                fetch('/api/submit_review.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    
                    // Enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Değerlendirmeyi Kaydet';
                    
                    if (data.success) {
                        // Show success notification
                        showNotification('success', 'Başarılı', 'Değerlendirmeniz başarıyla kaydedildi. Teşekkür ederiz!');
                        
                        // Reset form
                        form.reset();
                        
                        // Reset star ratings
                        const stars = document.querySelectorAll('.star');
                        stars.forEach(star => star.classList.remove('active'));
                        
                        // Reset select boxes
                        document.getElementById('ilceSelect').innerHTML = '<option value="">Önce il seçin</option>';
                        document.getElementById('ilceSelect').disabled = true;
                        document.getElementById('mahSelect').innerHTML = '<option value="">Önce ilçe seçin</option>';
                        document.getElementById('mahSelect').disabled = true;
                    } else {
                        // Show error notification
                        showNotification('error', 'Hata', data.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Hide loading spinner
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    
                    // Enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Değerlendirmeyi Kaydet';
                    
                    // Show error notification
                    showNotification('error', 'Bağlantı Hatası', 'Sunucuyla bağlantı kurulamadı. Lütfen tekrar deneyin.');
                });
            }
            
            // Initialize location dropdowns
            function initializeLocationDropdowns() {
                const ilSelect = document.getElementById('ilSelect');
                const ilceSelect = document.getElementById('ilceSelect');
                const mahSelect = document.getElementById('mahSelect');
                
                if (ilSelect) {
                    ilSelect.addEventListener('change', function() {
                        // Reset and disable ilce and mahalle selects
                        ilceSelect.innerHTML = '<option value="">Seçiniz</option>';
                        ilceSelect.disabled = false;
                        mahSelect.innerHTML = '<option value="">Önce ilçe seçin</option>';
                        mahSelect.disabled = true;
                        
                        // Clear validation styling
                        ilSelect.classList.remove('is-invalid');
                        
                        if (!this.value) return;
                        
                        // Show loading in select
                        ilceSelect.innerHTML = '<option value="">Yükleniyor...</option>';
                        
                        // Fetch ilce data
                        fetch(`/api/get_ilceler.php?il_id=${this.value}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Server error');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Populate ilce select
                                ilceSelect.innerHTML = '<option value="">Seçiniz</option>';
                                data.forEach(ilce => {
                                    const option = document.createElement('option');
                                    option.value = ilce.id;
                                    option.textContent = ilce.isim;
                                    ilceSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                ilceSelect.innerHTML = '<option value="">Hata oluştu</option>';
                                showNotification('error', 'Hata', 'İlçeler yüklenirken bir hata oluştu.');
                            });
                    });
                    
                    ilceSelect.addEventListener('change', function() {
                        // Reset and disable mahalle select
                        mahSelect.innerHTML = '<option value="">Seçiniz</option>';
                        mahSelect.disabled = false;
                        
                        // Clear validation styling
                        ilceSelect.classList.remove('is-invalid');
                        
                        if (!this.value) return;
                        
                        // Show loading in select
                        mahSelect.innerHTML = '<option value="">Yükleniyor...</option>';
                        
                        // Fetch mahalle data
                        fetch(`/api/get_mahalleler_by_ilce.php?ilce_id=${this.value}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Server error');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Populate mahalle select
                                mahSelect.innerHTML = '<option value="">Seçiniz</option>';
                                data.forEach(mahalle => {
                                    const option = document.createElement('option');
                                    option.value = mahalle.id;
                                    option.textContent = mahalle.isim;
                                    mahSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                mahSelect.innerHTML = '<option value="">Hata oluştu</option>';
                                showNotification('error', 'Hata', 'Mahalleler yüklenirken bir hata oluştu.');
                            });
                    });
                    
                    mahSelect.addEventListener('change', function() {
                        // Clear validation styling
                        mahSelect.classList.remove('is-invalid');
                    });
                }
            }
            
            // Initialize filter buttons
            function initializeFilterButtons() {
                const filterButtons = document.querySelectorAll('.btn-outline-primary');
                filterButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            }
            
            // Initialize autocomplete
            function initializeAutocomplete() {
                const searchInput = document.getElementById('ara');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.trim();
                        if (query.length < 2) return; // Don't search for less than 2 chars
                        
                        fetch(`/api/getir.php?autocomplete=1&q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                const datalist = document.getElementById('mahalleler');
                                datalist.innerHTML = ''; // Clear previous suggestions
                                
                                data.forEach(item => {
                                    const option = document.createElement('option');
                                    option.value = item; // If you get object from DB, use item.name or similar
                                    datalist.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Autocomplete error:', error);
                            });
                    });
                }
            }
            
            // Get and display neighborhood reports
            function yorumGetir(gun = null) {
                const mahalle = document.getElementById('ara').value.trim();
                const sonucDiv = document.getElementById('sonuc');
                const chartContainer = document.getElementById('chartContainer');
                
                if (!mahalle) {
                    showNotification('warning', 'Uyarı', 'Lütfen bir mahalle adı girin.');
                    return;
                }
                
                // Show loading state
                sonucDiv.classList.remove('d-none');
                sonucDiv.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner"></div>
                        <p class="mt-3 text-muted">Bilgiler yükleniyor...</p>
                    </div>
                `;
                
                // Construct API URL
                let apiUrl = `/api/getir.php?mahalle=${encodeURIComponent(mahalle)}`;
                if (gun) {
                    apiUrl += `&gun=${gun}`;
                }
                
                // Fetch data
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server error');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Handle error
                        if (data.hata) {
                            showNotification('warning', 'Sonuç Bulunamadı', data.hata);
                            sonucDiv.innerHTML = `
                                <div class="text-center py-4">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <p>${data.hata}</p>
                                    <p class="text-muted">Farklı bir mahalle adı ile aramayı deneyebilirsiniz.</p>
                                </div>
                            `;
                            chartContainer.classList.add('d-none');
                            return;
                        }
                        
                        // Process data
                        displayNeighborhoodResults(data, mahalle);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Hata', 'Veriler yüklenirken bir hata oluştu.');
                        sonucDiv.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Veriler alınamadı. Lütfen daha sonra tekrar deneyin.
                            </div>
                        `;
                        chartContainer.classList.add('d-none');
                    });
            }
            
            // Display neighborhood results
            function displayNeighborhoodResults(data, mahalleName) {
                const sonucDiv = document.getElementById('sonuc');
                const chartContainer = document.getElementById('chartContainer');
                
                // Prepare rating data
                const ratings = {
                    guvenlik: data.guvenlik || 0,
                    temizlik: data.temizlik || 0,
                    ulasim: data.ulasim || 0,
                    komsuluk: data.komsuluk || 0
                };
                
                const categories = [
                    { key: 'guvenlik', icon: 'shield-alt', title: 'Güvenlik' },
                    { key: 'temizlik', icon: 'broom', title: 'Temizlik' },
                    { key: 'ulasim', icon: 'bus', title: 'Ulaşım' },
                    { key: 'komsuluk', icon: 'users', title: 'Komşuluk' }
                ];
                
                // Generate rating cards HTML
                const ratingCards = categories.map(category => `
                    <div class="col-6 col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-${category.icon}"></i>
                            </div>
                            <div class="stats-title">${category.title}</div>
                            <div class="stats-value">
                                ${renderStars(ratings[category.key])}
                            </div>
                        </div>
                    </div>
                `).join('');
                
                // Generate comments HTML
                const comments = data.yorumlar || [];
                let commentsHTML = '';
                
                if (comments.length > 0) {
                    commentsHTML = comments.map(comment => `
                        <div class="comment-card">
                            <div class="card-body">
                                <p class="comment-text">${comment.metin}</p>
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <small class="comment-date">${comment.tarih || ''}</small>
                                    <div class="d-flex gap-3 mt-2 mt-sm-0">
                                        ${categories.map(cat => `
                                            <span title="${cat.title}" data-bs-toggle="tooltip">
                                                <i class="fas fa-${cat.icon} text-muted me-1"></i>
                                                <small>${comment[cat.key]}</small>
                                            </span>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    commentsHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bu mahalle için henüz yorum bulunmamaktadır.
                        </div>
                    `;
                }
                
                // Update results container
                sonucDiv.innerHTML = `
                    <div class="mb-4">
                        <h4 class="results-title">
                            <i class="fas fa-chart-line me-2"></i>
                            ${mahalleName} Mahallesi Değerlendirmesi
                        </h4>
                        <div class="row">${ratingCards}</div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-comments me-2"></i>
                            Yorumlar ${comments.length ? `(${comments.length})` : ''}
                        </h5>
                        ${commentsHTML}
                    </div>
                `;
                
                // Initialize tooltips
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    tooltips.forEach(tooltip => {
                        new bootstrap.Tooltip(tooltip);
                    });
                }
                
                // Create charts if we have data
                if (comments.length > 0) {
                    chartContainer.classList.remove('d-none');
                    createCharts(ratings, mahalleName);
                } else {
                    chartContainer.classList.add('d-none');
                }
                
                // Scroll to results
                sonucDiv.scrollIntoView({ behavior: 'smooth' });
            }
            
            // Create rating charts
            function createCharts(ratings, neighborhoodName) {
                const radarCtx = document.getElementById('puanGrafik').getContext('2d');
                
                // Destroy existing charts if they exist
                if (window.puanChart) {
                    window.puanChart.destroy();
                }
                
                // Create radar chart
                window.puanChart = new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: ['Güvenlik', 'Temizlik', 'Ulaşım', 'Komşuluk'],
                        datasets: [{
                            label: neighborhoodName,
                            data: [
                                ratings.guvenlik,
                                ratings.temizlik,
                                ratings.ulasim,
                                ratings.komsuluk
                            ],
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(79, 70, 229, 1)',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                angleLines: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                pointLabels: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 14
                                },
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw.toFixed(1)}/5`;
                                    }
                                }
                            }
                        },
                        elements: {
                            line: {
                                tension: 0.1
                            }
                        }
                    }
                });
                
                // Create bar chart (below radar chart)
                // First remove any existing bar chart
                const existingBarChart = document.getElementById('barChart');
                if (existingBarChart) {
                    existingBarChart.remove();
                }
                
                // Create new canvas for bar chart
                const barCanvas = document.createElement('canvas');
                barCanvas.id = 'barChart';
                barCanvas.height = 250;
                document.getElementById('chartContainer').appendChild(barCanvas);
                
                // Create bar chart
                new Chart(barCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Güvenlik', 'Temizlik', 'Ulaşım', 'Komşuluk'],
                        datasets: [{
                            label: 'Ortalama Puan',
                            data: [
                                ratings.guvenlik,
                                ratings.temizlik,
                                ratings.ulasim,
                                ratings.komsuluk
                            ],
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(99, 102, 241, 0.7)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(99, 102, 241, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    stepSize: 1,
                                    callback: function(value) {
                                        return value + ' ★';
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.raw.toFixed(1)} / 5`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }
            
            // Helper function to render star ratings
            function renderStars(rating) {
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.5;
                const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
                
                let starsHTML = '';
                
                // Full stars
                for (let i = 0; i < fullStars; i++) {
                    starsHTML += '<i class="fas fa-star text-warning"></i>';
                }
                
                // Half star
                if (halfStar) {
                    starsHTML += '<i class="fas fa-star-half-alt text-warning"></i>';
                }
                
                // Empty stars
                for (let i = 0; i < emptyStars; i++) {
                    starsHTML += '<i class="far fa-star text-warning"></i>';
                }
                
                return `${starsHTML} <small class="text-muted">(${parseFloat(rating).toFixed(1)})</small>`;
            }
        </script>

</body>