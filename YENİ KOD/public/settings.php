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
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
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
        
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .page-title {
            color: var(--primary-dark);
            font-weight: 700;
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card-shadow:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
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
        
        /* Rating stars styling */
        .rating-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        
        .rating-stars {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.5rem;
            justify-content: center;
            flex-direction: row-reverse;
        }
        
        .star {
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #d1d5db;
        }
        
        .star:hover,
        .star.active {
            color: var(--accent-color);
        }

        
        /* Star hover effect */
        .rating-stars:hover .star {
            color: #d1d5db;
        }
        
        .rating-stars .star:hover ~ .star {
            color: #d1d5db;
        }
        
        .rating-stars .star:hover,
        .rating-stars .star:hover ~ .star {
            color: var(--accent-color);
        }
        
        /* Animation for stars */
        @keyframes pop {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .star.active {
            animation: pop 0.3s ease;
        }
        
        /* Category cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .category-card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem 1rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid transparent;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .category-card.invalid {
            border-color: var(--danger);
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% {transform: translateX(0);}
            10%, 30%, 50%, 70%, 90% {transform: translateX(-5px);}
            20%, 40%, 60%, 80% {transform: translateX(5px);}
        }
        
        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            height: 4rem;
            width: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
        }
        
        .category-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
            text-align: center;
            color: var(--primary-dark);
        }

        .number-line-container {
    width: 100%;
    margin-top: 1rem;
    padding: 0 0.5rem;
}

.number-line {
    position: relative;
    height: 30px;
    width: 100%;
}

.number-line-track {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    height: 4px;
    background-color: #e0e0e0;
    border-radius: 2px;
    transform: translateY(-50%);
}

.number-line-thumb {
    position: absolute;
    top: 100%;
    left: 0;
    width: 16px;
    height: 16px;
    background-color: var(--primary-color, #3b82f6);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
    transition: background-color 0.2s;
    z-index: 2;
}

.number-line-thumb:hover, .number-line-thumb:focus {
    background-color: var(--primary-dark, #2563eb);
    outline: none;
}

.number-line-thumb:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.number-line-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    font-size: 0.8rem;
    color: #666;
}

.number-line-value {
    text-align: center;
    margin-top: 0.5rem;
    font-weight: 600;
    color: var(--primary-color, #3b82f6);
    font-size: 1rem;
}

        
        /* Form styling */
        .form-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .form-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-color);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        
        /* Button styling */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        .btn-outline-primary.active {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        /* Search and filter section */
        .search-section {
            background-color: var(--white);
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
        
        /* Results styling */
        .results-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .results-title {
            color: var(--primary-dark);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .stats-card {
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
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
        
        /* Comment card */
        .comment-card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .comment-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        
        .comment-text {
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .comment-date {
            font-size: 0.8rem;
            color: #6b7280;
        }
        
        /* Chart container */
        .chart-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Rating display in result cards */
        .rating-display {
            display: flex;
            gap: 2px;
        }
        
        .star-filled {
            color: var(--accent-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            #map {
                height: 350px;
            }
            
            .page-title {
                font-size: 1.5rem;
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
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<!--<form id="reviewForm">
  
  <hr>
  <?php foreach($kategoriler as $kat): ?>
    <label><?= htmlspecialchars($kat['isim']) ?>:
      <select name="puan[<?= $kat['id'] ?>]">
        <option value="1">★☆☆☆☆</option>
        <option value="2">★★☆☆☆</option>
        <option value="3">★★★☆☆</option>
        <option value="4">★★★★☆</option>
        <option value="5">★★★★★</option>
      </select>
    </label><br>
  <?php endforeach; ?>
  <label>Yorum (opsiyonel):<br>
    <textarea name="yorum" rows="3"></textarea>
  </label><br>
  <button type="submit">Gönder</button>
</form>-->

<!-- Rating Form -->
<div class="main-container">
    <div class="page-header">
        <h1 class="page-title">Kullanıcı Tercihleri</h1>
        <p class="page-subtitle">Değerlendirmeleri tercihlerinizle göre şekillendirin</p>
    </div>

<div class="form-container">
                    
                    <form id="reviewForm" action="..\api\submit_review.php" method="post" class="row g-3">
                        <!-- Location Selection -->
                        

                        <!-- Rating Categories (Dinamik) -->
                    <div class="col-12">
                        <h5 class="mt-3 mb-3 text-center">Kategorilere Göre Önem Oranı</h5>
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
                                
                                    <div class="number-line-container">
                                        <div class="number-line">
                                            <div class="number-line-track"></div>
                                            <div class="number-line-thumb" tabindex="0" data-value="0"></div>
                                            <div class="number-line-labels">
                                                <span>0</span>
                                                <span>1</span>
                                                <span>2</span>
                                                <span>3</span>
                                                <span>4</span>
                                                <span>5</span>
                                            </div>
                                        </div>
                                        <div class="number-line-value">x0</div>
                                    </div>

                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Comment -->
                        
                    </form>
                </div>
                            </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script src="/assets/js/review.js"></script>
<?php require_once __DIR__.'/../templates/footer.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {

  const numberLines = document.querySelectorAll('.number-line');
    
    // Map values to display text - consistent across all number lines
    const valueMap = {
        0: 'x0',
        1: 'x0.50',
        2: 'x0.75',
        3: 'x1',
        4: 'x1.25',
        5: 'x1.5'
    };
    
    numberLines.forEach(line => {
        const track = line.querySelector('.number-line-track');
        const thumb = line.querySelector('.number-line-thumb');
        const valueDisplay = line.parentElement.querySelector('.number-line-value');
        
        updatePosition(0);
        
        // Handle click on track
        track.addEventListener('click', handleTrackClick);
        
        // Handle drag on thumb
        thumb.addEventListener('mousedown', startDrag);
        
        // Handle keyboard navigation
        thumb.addEventListener('keydown', function(e) {
            let currentValue = parseInt(thumb.getAttribute('data-value') || 0);
            
            if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
                currentValue = Math.min(currentValue + 1, 5);
                updatePosition(currentValue);
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
                currentValue = Math.max(currentValue - 1, 0);
                updatePosition(currentValue);
            }
        });
        
        function handleTrackClick(e) {
            const rect = track.getBoundingClientRect();
            const position = (e.clientX - rect.left) / rect.width;
            const value = Math.round(position * 5);
            updatePosition(value);
        }
        
        function startDrag(e) {
            e.preventDefault();
            
            function onMove(moveEvent) {
                const rect = track.getBoundingClientRect();
                let position = (moveEvent.clientX - rect.left) / rect.width;
                position = Math.max(0, Math.min(1, position));
                const value = Math.round(position * 5);
                updatePosition(value);
            }
            
            function onUp() {
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
            }
            
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        }
        
        function updatePosition(value) {
            value = Math.max(0, Math.min(5, value));
            
            // Store the current value for this specific number line
            thumb.setAttribute('data-value', value);
            
            // Update thumb position for this number line (percentage of track width)
            const percentage = (value / 5) * 100;
            thumb.style.left = `${percentage}%`;
            
            // Update displayed value text for this number line
            valueDisplay.textContent = valueMap[value];
            

        }
    });
    
    // Star rating functionality
    const ratingGroups = document.querySelectorAll('.rating-stars');
    
    ratingGroups.forEach(group => {
        const stars = Array.from(group.querySelectorAll('.star')).reverse();
        const hiddenInput = group.querySelector('input[type="hidden"]');
        
        stars.forEach((star,index)=> {
            star.addEventListener('click', () => {
                const value = index + 1;
                
                // Update hidden input value
                hiddenInput.value = value;
                
                // Reset all stars
                stars.forEach(s => s.classList.remove('active'));
                
                // Set active stars up to the clicked one
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('active');
                }
            });
        });
    });
    
    // Form validation before submit
    const form = document.getElementById('ratingForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
            let valid = true;
            
            hiddenInputs.forEach(input => {
                if (!input.value) {
                    valid = false;
                    // Find the parent category card to highlight
                    const categoryCard = input.closest('.category-card');
                    if (categoryCard) {
                        categoryCard.classList.add('invalid');
                        
                        // Remove invalid class after animation
                        setTimeout(() => {
                            categoryCard.classList.remove('invalid');
                        }, 2000);
                    }
                }
            });
            
            if (!valid) {
                event.preventDefault();
                showAlert('Lütfen tüm kategorileri değerlendirin!', 'danger');
            }
        });
    }

    // Load location data (il-ilce-mahalle)
    loadLocationData();
    
    // Add event listener for filter buttons
    const filterButtons = document.querySelectorAll('.btn-outline-primary');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Show alert messages
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Find the results div to insert the alert before it
    const resultsDiv = document.querySelector('#sonuc');
    resultsDiv.parentNode.insertBefore(alertDiv, resultsDiv);
    
    // Auto-close after 5 seconds
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

// Otomatik tamamlama / autocomplete
document.getElementById("ara").addEventListener("input", function() {
    const q = this.value.trim();
    if (q.length < 2) return; // 2 karakterin altındaysa sorgulama yapma

    fetch(`getir.php?autocomplete=1&q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(list => {
            const dataList = document.getElementById("mahalleler");
            dataList.innerHTML = ""; // önceki önerileri temizle
            list.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item;       // eğer DB'den obje geliyorsa item.ad gibi ayarlayın
                dataList.appendChild(opt);
            });
        })
        .catch(err => console.error("Autocomplete hatası:", err));
});

// Load location data from JSON
function yorumGetir(gun = 0) {
    const mahalleAdi = document.getElementById("ara").value.trim();

    if (mahalleAdi === "") {
        alert("Lütfen mahalle adı girin.");
        return;
    }

    fetch(`getir.php?mahalle=${encodeURIComponent(mahalleAdi)}&gun=${gun}`)
        .then(response => response.json())
        .then(data => {
            if (data.hata) {
                document.getElementById("sonuc").innerHTML = `<p class="text-danger">${data.hata}</p>`;
                return;
            }

            // Verileri doldur
            document.getElementById("sonuc").classList.remove("d-none");
            document.getElementById("chartContainer").classList.remove("d-none");

            // Ortalama puanları yaz
            document.getElementById("sonuc").innerHTML = `
                <h4 class="mt-4 mb-3">${mahalleAdi} Mahallesi Değerlendirmesi</h4>
                <div class="row">
                    ${kategoriPuan("Güvenlik", data.guvenlik)}
                    ${kategoriPuan("Temizlik", data.temizlik)}
                    ${kategoriPuan("Ulaşım", data.ulasim)}
                    ${kategoriPuan("Komşuluk", data.komsuluk)}
                </div>
                <h5 class="mt-4"><i class="fas fa-comments me-2"></i>Yorumlar</h5>
                ${data.yorumlar.map(yorum => yorumKart(yorum)).join("")}
            `;

            // (İsteğe bağlı) Chart.js ile grafik çizimi yapılabilir burada
        });
}

function kategoriPuan(ad, puan) {
    return `
    <div class="col-6 col-md-3 text-center mb-3">
        <i class="fas fa-star text-warning"></i>
        <span class="fs-5 fw-bold">(${puan.toFixed(1)})</span><br>
        <span>${ad}</span>
    </div>`;
}

function yorumKart(yorum) {
    return `
        <div class="card my-2">
            <div class="card-body">
                <p>${yorum.metin}</p>
                <small class="text-muted">${yorum.tarih}</small>
                <div class="mt-2 text-end small text-secondary">
                    <i class="fas fa-shield-alt me-1"></i>${yorum.guvenlik}
                    <i class="fas fa-broom me-1 ms-3"></i>${yorum.temizlik}
                    <i class="fas fa-bus me-1 ms-3"></i>${yorum.ulasim}
                    <i class="fas fa-users me-1 ms-3"></i>${yorum.komsuluk}
                </div>
            </div>
        </div>`;
}


// Populate location dropdowns
function populateLocationDropdowns(data) {
    const ilSelect = document.getElementById("il-select");
    const ilceSelect = document.getElementById("ilce-select");
    const mahalleSelect = document.getElementById("mahalle-select");
    
    // Clear existing options
    ilSelect.innerHTML = '<option value="" selected disabled>İl Seçiniz</option>';
    ilceSelect.innerHTML = '<option value="" selected disabled>İlçe Seçiniz</option>';
    mahalleSelect.innerHTML = '<option value="" selected disabled>Mahalle Seçiniz</option>';
    
    // Populate il dropdown
    data.iller.forEach(il => {
        const option = document.createElement("option");
        option.value = il.il;
        option.textContent = il.il;
        ilSelect.appendChild(option);
    });
    
    // Handle il change event
    ilSelect.addEventListener("change", () => {
        const secilenIl = ilSelect.value;
        const ilObj = data.iller.find(i => i.il === secilenIl);
        
        ilceSelect.innerHTML = '<option value="" selected disabled>İlçe Seçiniz</option>';
        mahalleSelect.innerHTML = '<option value="" selected disabled>Mahalle Seçiniz</option>';
        
        if (!ilObj) return;
        
        ilObj.ilceler.forEach(ilce => {
            const option = document.createElement("option");
            option.value = ilce.ilce;
            option.textContent = ilce.ilce;
            ilceSelect.appendChild(option);
        });
    });
    
    // Handle ilce change event
    ilceSelect.addEventListener("change", () => {
        const secilenIl = ilSelect.value;
        const secilenIlce = ilceSelect.value;
        const ilObj = data.iller.find(i => i.il === secilenIl);
        
        if (!ilObj) return;
        
        const ilceObj = ilObj.ilceler.find(i => i.ilce === secilenIlce);
        
        mahalleSelect.innerHTML = '<option value="" selected disabled>Mahalle Seçiniz</option>';
        
        if (!ilceObj) return;
        
        ilceObj.mahalleler.forEach(mahalle => {
            const option = document.createElement("option");
            option.value = mahalle;
            option.textContent = mahalle;
            mahalleSelect.appendChild(option);
        });
    });
}

// Add sample markers to the map
function addSampleMarkersToMap() {
    const map = L.map ? L.map : null;
    if (!map) return;
    
    // Sample neighborhood data with coordinates
    const sampleNeighborhoods = [
        {
            name: "Caferağa",
            location: [40.9896, 29.0305],
            ratings: { guvenlik: 4.5, temizlik: 4.2, ulasim: 4.8, komsuluk: 4.1 }
        },
        {
            name: "Levent",
            location: [41.0825, 29.0114],
            ratings: { guvenlik: 4.7, temizlik: 4.6, ulasim: 4.5, komsuluk: 3.9 }
        },
        {
            name: "Bahçelievler",
            location: [39.9202, 32.8206],
            ratings: { guvenlik: 3.9, temizlik: 3.7, ulasim: 4.4, komsuluk: 4.2 }
        },
        {
            name: "Alsancak",
            location: [38.4350, 27.1438],
            ratings: { guvenlik: 4.1, temizlik: 3.9, ulasim: 4.9, komsuluk: 4.3 }
        }
    ];
    
    // Add markers to the map
    sampleNeighborhoods.forEach(neighborhood => {
        const marker = L.marker(neighborhood.location)
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <h5>${neighborhood.name}</h5>
                    <div class="mt-2">
                        <div><b>Güvenlik:</b> ${renderStars(neighborhood.ratings.guvenlik)}</div>
                        <div><b>Temizlik:</b> ${renderStars(neighborhood.ratings.temizlik)}</div>
                        <div><b>Ulaşım:</b> ${renderStars(neighborhood.ratings.ulasim)}</div>
                        <div><b>Komşuluk:</b> ${renderStars(neighborhood.ratings.komsuluk)}</div>
                    </div>
                    <hr>
                    <button 
                        class="btn btn-sm btn-primary" 
                        onclick="document.getElementById('ara').value = '${neighborhood.name}'; document.getElementById('report-tab').click(); yorumGetir();"
                    >
                        Detaylar
                    </button>
                </div>
            `);
    });
}

// Helper function to render star ratings
function renderStars(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star text-warning"></i>';
    }
    
    if (halfStar) {
        stars += '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star text-warning"></i>';
    }
    
    return stars + ` (${rating})`;
}

// Get and display comments
function yorumGetir(gun = null) {
    const mahalle = document.getElementById("ara").value.trim();  // Get neighborhood name
    const sonucDiv = document.getElementById("sonuc");  // Results div
    const chartContainer = document.getElementById("chartContainer");
    
    if (!mahalle) {
        showAlert('Lütfen bir mahalle adı girin.', 'warning');
        return;
    }
    
    // Show loading state
    sonucDiv.classList.remove('d-none');
    sonucDiv.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Yükleniyor...</span>
            </div>
            <p class="mt-2">Bilgiler yükleniyor...</p>
        </div>
    `;
    
    // Construct API URL with parameters
    let apiUrl = `getir.php?mahalle=${encodeURIComponent(mahalle)}`;
    if (gun) {
        apiUrl += `&gun=${gun}`;
    }

    // Fetch real data from the server
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Sunucudan veri alınamadı');
            }
            return response.json();
        })
        .then(data => {
            // Handle error response from PHP
            if (data.hata) {
                showAlert(data.hata, 'danger');
                sonucDiv.innerHTML = '';
                chartContainer.classList.add('d-none');
                return;
            }
            
            // Process data and update UI
            const puanlar = {
                guvenlik: data.guvenlik || 0,
                temizlik: data.temizlik || 0,
                ulasim: data.ulasim || 0,
                komsuluk: data.komsuluk || 0
            };
            
            const yorumlar = data.yorumlar || [];
            
            // Generate category cards
            const kategoriler = [
                { key: 'guvenlik', icon: 'shield-alt', title: 'Güvenlik' },
                { key: 'temizlik', icon: 'broom', title: 'Temizlik' },
                { key: 'ulasim', icon: 'bus', title: 'Ulaşım' },
                { key: 'komsuluk', icon: 'users', title: 'Komşuluk' }
            ];
            
            const puanKartlari = kategoriler.map(kategori => `
                <div class="col-6 col-md-3 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-${kategori.icon} text-primary"></i>
                        </div>
                        <div class="stats-title">${kategori.title}</div>
                        <div class="stats-value">
                            <div class="rating-display mb-1">
                                ${renderStars(puanlar[kategori.key])}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Generate comments HTML
            const yorumlarHTML = yorumlar.length
                ? yorumlar.map(yorum => {
                    const tarih = yorum.tarih || '';
                    return `
                        <div class="comment-card card mb-3">
                            <div class="card-body">
                                <p class="comment-text mb-2">${yorum.metin}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="comment-date">${tarih}</small>
                                    <div class="d-flex gap-2">
                                        ${kategoriler.map(k => `
                                            <span title="${k.title}" data-bs-toggle="tooltip">
                                                <i class="fas fa-${k.icon} text-muted"></i>
                                                <small>${yorum[k.key]}</small>
                                            </span>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('')
                : '<div class="alert alert-info">Bu mahalle için yorum bulunmamaktadır.</div>';
            
            // Update results container
            sonucDiv.innerHTML = `
                <div class="mb-4">
                    <h4 class="results-title">
                        <i class="fas fa-chart-line me-2"></i>
                        ${mahalle} Mahallesi Değerlendirmesi
                    </h4>
                    <div class="row">${puanKartlari}</div>
                </div>
                
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-comments me-2"></i>
                        Yorumlar ${yorumlar.length ? `(${yorumlar.length})` : ''}
                    </h5>
                    ${yorumlarHTML}
                </div>
            `;
            
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
            
            // Create chart if we have data
            if (yorumlar.length > 0) {
                chartContainer.classList.remove('d-none');
                const ctx = document.getElementById('puanGrafik').getContext('2d');
                
                // Destroy existing chart if it exists
                if (window.puanChart) {
                    window.puanChart.destroy();
                }
                
                // Create new chart
                window.puanChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Güvenlik', 'Temizlik', 'Ulaşım', 'Komşuluk'],
                        datasets: [{
                            label: mahalle,
                            data: [
                                puanlar.guvenlik,
                                puanlar.temizlik,
                                puanlar.ulasim,
                                puanlar.komsuluk
                            ],
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw.toFixed(1)}/5`;
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Create bar chart as additional visualization
                // Remove old bar chart if it exists
                const oldBarChart = document.getElementById('barChart');
                if (oldBarChart) {
                    oldBarChart.remove();
                }
                
                const barCtx = document.createElement('canvas');
                barCtx.id = 'barChart';
                barCtx.width = 400;
                barCtx.height = 200;
                chartContainer.appendChild(barCtx);
                
                new Chart(barCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Güvenlik', 'Temizlik', 'Ulaşım', 'Komşuluk'],
                        datasets: [{
                            label: 'Puan',
                            data: [
                                puanlar.guvenlik,
                                puanlar.temizlik,
                                puanlar.ulasim,
                                puanlar.komsuluk
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(255, 159, 64, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            } else {
                chartContainer.classList.add('d-none');
            }
            
            // Scroll to results
            sonucDiv.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Hata:', error);
            showAlert('Veriler yüklenirken bir hata oluştu.', 'danger');
            sonucDiv.innerHTML = '<div class="alert alert-danger">Veriler alınamadı. Lütfen daha sonra tekrar deneyin.</div>';
        });
}

// Yardımcı fonksiyon: Yıldız puanı gösterimi
function renderStars(puan) {
    const tamYildiz = Math.floor(puan);
    const yarimYildiz = puan % 1 >= 0.5 ? 1 : 0;
    const bosYildiz = 5 - tamYildiz - yarimYildiz;
    
    let stars = '';
    
    // Tam yıldızlar
    for (let i = 0; i < tamYildiz; i++) {
        stars += '<i class="fas fa-star text-warning"></i>';
    }
    
    // Yarım yıldız
    if (yarimYildiz) {
        stars += '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    // Boş yıldızlar
    for (let i = 0; i < bosYildiz; i++) {
        stars += '<i class="far fa-star text-warning"></i>';
    }
    
    return `${stars} <small class="text-muted">(${puan.toFixed(1)})</small>`;
}

// Yardımcı fonksiyon: Uyarı mesajı gösterimi
function showAlert(message, type = 'info') {
    const alertsContainer = document.getElementById('alerts') || document.createElement('div');
    
    if (!document.getElementById('alerts')) {
        alertsContainer.id = 'alerts';
        alertsContainer.className = 'alerts-container';
        document.body.appendChild(alertsContainer);
    }
    
    const alertElement = document.createElement('div');
    alertElement.className = `alert alert-${type} alert-dismissible fade show`;
    alertElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertsContainer.appendChild(alertElement);
    
    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertElement);
        bsAlert.close();
    }, 5000);
}
    </script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const ilSelect = document.getElementById("il-select");
    const ilceSelect = document.getElementById("ilce-select");
    const mahalleSelect = document.getElementById("mahalle-select");

    let veri = null;

    // JSON dosyasını yükle
    fetch("il-ilce-mahalle-temiz.json")
        .then(response => response.json())
        .then(data => {
            veri = data.iller;
            // İlleri doldur
            veri.forEach(il => {
                const option = document.createElement("option");
                option.value = il.il;
                option.textContent = il.il;
                ilSelect.appendChild(option);
            });
        });

    // İl seçilince ilçeleri güncelle
    ilSelect.addEventListener("change", () => {
        const secilenIl = ilSelect.value;
        ilceSelect.innerHTML = '<option value="" disabled selected>İlçe Seçiniz</option>';
        mahalleSelect.innerHTML = '<option value="" disabled selected>Mahalle Seçiniz</option>';

        const ilData = veri.find(il => il.il === secilenIl);
        if (ilData) {
            ilData.ilceler.forEach(ilce => {
                const option = document.createElement("option");
                option.value = ilce.ilce;
                option.textContent = ilce.ilce;
                ilceSelect.appendChild(option);
            });
        }
    });

    // İlçe seçilince mahalleleri güncelle
    ilceSelect.addEventListener("change", () => {
        const secilenIl = ilSelect.value;
        const secilenIlce = ilceSelect.value;
        mahalleSelect.innerHTML = '<option value="" disabled selected>Mahalle Seçiniz</option>';

        const ilData = veri.find(il => il.il === secilenIl);
        const ilceData = ilData?.ilceler.find(ilce => ilce.ilce === secilenIlce);
        if (ilceData) {
            ilceData.mahalleler.forEach(mahalle => {
                const option = document.createElement("option");
                option.value = mahalle;
                option.textContent = mahalle;
                mahalleSelect.appendChild(option);
            });
        }
    });
});
</script>

</body>