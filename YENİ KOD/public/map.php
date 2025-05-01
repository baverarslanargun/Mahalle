<?php 
// public/map.php
require_once __DIR__.'/../templates/header.php';
?>

<!-- Leaflet CSS (CDN) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<!-- Chart.js için CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<style>
    :root {
        --primary-color: #4338ca;
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
    
    /* Map container */
    .map-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    #map {
        height: 500px;
        width: 100%;
    }
    
    /* Modal styles */
    #infoModal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    
    .modal-content {
        background-color: var(--white);
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .close:hover {
        color: var(--danger);
    }
    
    .badge {
        padding: 0.35em 0.65em;
        border-radius: 6px;
        font-size: 0.85em;
        font-weight: 600;
        color: white;
    }
    
    .bg-success { background-color: var(--success); }
    .bg-info { background-color: var(--info); }
    .bg-warning { background-color: var(--warning); color: #000; }
    .bg-danger { background-color: var(--danger); }
    
    .category-scores {
        list-style: none;
        padding-left: 0;
    }
    
    .category-scores li {
        margin-bottom: 10px;
        padding: 8px;
        border-radius: 8px;
        background-color: rgba(59, 130, 246, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .category-name {
        font-weight: 500;
    }
    
    .comment-container {
        margin-top: 15px;
        max-height: 350px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .comment-container p {
        background-color: rgba(243, 244, 246, 0.7);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 10px;
        border-left: 4px solid var(--primary-color);
    }
    
    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
    }
    
    /* Form styling */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 1px solid #d1d5db;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }
    
    /* Button styling */
    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    /* Search section */
    .search-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .search-title {
        color: var(--primary-dark);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    /* Chart container */
    .chart-container {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
    }
    
    .alert-info {
        background-color: rgba(6, 182, 212, 0.1);
        color: var(--info);
        border-left: 4px solid var(--info);
    }
    
    /* Stats styling */
    .stats-card {
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }
    
    .stats-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .stats-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #map {
            height: 350px;
        }
        
        .modal-content {
            width: 95%;
            margin: 10% auto;
        }
        
        .btn {
            padding: 0.5rem 1rem;
        }
    }
    
</style>

<div class="container my-4">
    <!-- Page Header -->
    <div class="page-header text-center mb-4">
        <h2 class="page-title fw-bold">Mahalle Değerlendirme Haritası</h2>
        <p class="text-muted">Mahallelerin değerlendirmelerini görüntüleyin ve katkıda bulunun</p>
    </div>

    <!-- Map Container -->
    <div class="map-container">
        <div id="map"></div>
    </div>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Haritada mahalleleri görüntüleyebilir ve değerlendirmeleri inceleyebilirsiniz. Yakınlaştırmak için haritaya tıklayın.
    </div>

    <!-- Mahalle Arama Formu -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Mahalleye Göre Ara</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="selIl" class="form-label">İl</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <select id="selIl" class="form-select">
                            <option value="">Seçiniz</option>
                            <?php 
                            require_once __DIR__.'/../inc/functions.php';
                            foreach(getIlList() as $il): 
                            ?>
                                <option value="<?= $il['id'] ?>"><?= htmlspecialchars($il['isim']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="selIlce" class="form-label">İlçe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                        <select id="selIlce" class="form-select" disabled>
                            <option value="">Önce il seçin</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="selMahalle" class="form-label">Mahalle</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                        <select id="selMahalle" class="form-select" disabled>
                            <option value="">Önce ilçe seçin</option>
                        </select>
                    </div>
                </div>
            </div>
            <button id="btnShowResult" class="btn btn-primary mt-3" disabled>
                <i class="fas fa-search me-2"></i>Sonuçları Göster
            </button>
        </div>
    </div>

    <!-- İleri Arama Bölümü -->
    <div class="search-section mt-4">
        <h5 class="search-title"><i class="fas fa-filter me-2"></i>Gelişmiş Arama</h5>
        
        <div class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                    <input id="ara" class="form-control" placeholder="Mahalle adını yazın...">
                    <button type="button" id="btnAra" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Ara
                    </button>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="btn-group w-100" role="group">
                    <button class="btn btn-outline-primary" data-days="7">Son 7 gün</button>
                    <button class="btn btn-outline-primary" data-days="30">Son 30 gün</button>
                    <button class="btn btn-outline-primary" data-days="365">Son 1 yıl</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama Sonuçları -->
    <div id="manualResult" class="mt-4" style="display:none;">
        <!-- Seçilen mahalle detayları buraya gelecek -->
    </div>
    
    <!-- Grafik Bölümü -->
    <div class="chart-container mt-4" id="chartContainer" style="display:none;">
        <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Puan Analizi</h5>
        <canvas id="puanGrafik"></canvas>
    </div>
</div>

<!-- Bilgi Modal'i -->
<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modal-body" class="p-2">
            <h3 class="mb-3 text-primary">Mahalle Değerlendirmesi</h3>
            
            <!-- Stats Container -->
            <div class="stats-container">
                <div class="row mb-3">
                    <div class="col">
                        <h5>Genel Ortalama: <span id="genel-ortalama" class="badge"></span> 
                        (<span id="toplam-yorum">0</span> değerlendirme)</h5>
                    </div>
                </div>
                
                <!-- Category Scores -->
                <ul id="category-scores" class="category-scores mb-4">
                    <!-- Categories will be populated here -->
                </ul>
            </div>
            
            <hr>
            
            <h5 class="mb-3"><i class="fas fa-comment-alt me-2"></i>Yorumlar:</h5>
            
            <!-- Comments Container -->
            <div id="comments-container" class="comment-container">
                <!-- Comments will be populated here -->
            </div>
            
            <!-- More Button Container -->
            <div id="more-btn-container" class="text-center mt-3">
                <!-- "Load more" button will be added here if needed -->
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__.'/../templates/footer.php'; ?>

<!-- Leaflet JS (CDN) -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Chart.js için CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<!-- Map initialization kodu -->
<script src="/assets/js/map-init.js"></script>