<?php 
// public/map.php
require_once __DIR__.'/../templates/header.php';
?>

<!-- Leaflet CSS (CDN) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
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
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover {
        color: black;
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
    }
</style>

<!-- Map Tab -->
<div class="container my-4">
    <div class="tab-pane fade show active" id="map-pane" role="tabpanel">
        <div class="map-container">
            <div id="map"></div>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Haritada mahalleleri görüntüleyebilir ve değerlendirmeleri inceleyebilirsiniz. Yakınlaştırmak için haritaya tıklayın.
        </div>
    </div>

    <!-- Mahalle Arama Formu -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Mahalleye Göre Ara</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="selIl" class="form-label">İl</label>
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
                <div class="col-md-4">
                    <label for="selIlce" class="form-label">İlçe</label>
                    <select id="selIlce" class="form-select" disabled>
                        <option value="">Önce il seçin</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="selMahalle" class="form-label">Mahalle</label>
                    <select id="selMahalle" class="form-select" disabled>
                        <option value="">Önce ilçe seçin</option>
                    </select>
                </div>
            </div>
            <button id="btnShowResult" class="btn btn-primary mt-3" disabled>
                <i class="fas fa-search me-2"></i>Sonuçları Göster
            </button>
        </div>
    </div>

    <!-- Arama Sonuçları -->
    <div id="manualResult" class="mt-4" style="display:none;">
        <!-- Seçilen mahalle detayları buraya gelecek -->
    </div>
</div>

<!-- Bilgi Modal'i -->
<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modal-body" class="p-2"></div>
    </div>
</div>

<?php require_once __DIR__.'/../templates/footer.php'; ?>

<!-- Leaflet JS (CDN) -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Map initialization kodu -->
<script src="/assets/js/map-init.js"></script>