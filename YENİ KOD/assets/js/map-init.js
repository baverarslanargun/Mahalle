/**
 * Mahalle Haritası ve Değerlendirme Sistemi
 * 
 * Bu dosya harita üzerinde mahallelerin görüntülenmesi ve 
 * değerlendirmelerin gösterilmesini sağlar.
 */

document.addEventListener('DOMContentLoaded', () => {
  // ===============================================
  // HARİTA İŞLEMLERİ
  // ===============================================
  
  // Harita başlangıç ayarları - Türkiye merkezli
  const map = L.map('map').setView([39.0, 35.0], 6);

  // OpenStreetMap katmanı
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Değişkenler
  let markers = [];
  let kategoriSayisi = 5; // Varsayılan değer

  loadKategoriler().then(() => {
    // Harita hazır olduğunda ilk marker yüklemesini yap
    loadMarkers();
  });

  // Kategorileri API'den yükle
  function loadKategoriler() {
    return fetch('/api/get_kategoriler.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Kategoriler yüklenirken hata oluştu');
        }
        return response.json();
      })
      .then(data => {
        kategoriSayisi = data.sayi;
        return data;
      })
      .catch(error => {
        console.error('Kategori yükleme hatası:', error);
        return { sayi: 5 }; // Hata durumunda varsayılan değer
      });
  }

  // Harita üzerindeki işaretçileri yükle
  function loadMarkers() {
    // Mevcut işaretçileri temizle
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    // Harita sınırlarını al
    const bounds = map.getBounds();
    const params = new URLSearchParams({
      southWestLat: bounds.getSouthWest().lat,
      southWestLng: bounds.getSouthWest().lng,
      northEastLat: bounds.getNorthEast().lat,
      northEastLng: bounds.getNorthEast().lng
    });

    // Görünüm alanındaki mahalleleri getir
    fetch(`/api/get_mahalleler.php?${params}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Mahalleler yüklenirken hata oluştu');
        }
        return response.json();
      })
      .then(data => {
        data.forEach(mahalle => {
          // Yorum sayısına göre boyut ayarla
          const yorumSayisi = mahalle.puan_sayisi / kategoriSayisi;
          
          // Daire şeklinde işaretçi oluştur
          const circle = L.circleMarker([mahalle.latitude, mahalle.longitude], {
            radius: Math.min(6 + yorumSayisi, 20), // Maksimum boyut sınırı
            fillColor: getColor(mahalle.ortalama),
            color: '#000',
            weight: 1,
            fillOpacity: 0.8
          }).addTo(map);

          // Tıklama olayını ekle
          circle.on('click', () => showInfo(mahalle.id));
          circle.bindTooltip(`${mahalle.isim} (${mahalle.ortalama.toFixed(1)})`);
          
          // İşaretçiyi diziye ekle
          markers.push(circle);
        });
      })
      .catch(error => {
        console.error('Harita işaretçileri yüklenirken hata:', error);
      });
  }

  // Harita hareket ettiğinde işaretçileri yenile
  map.on('moveend', loadMarkers);

  // Puan değerine göre renk döndür
  function getColor(val) {
    if (val > 4) return '#006400'; // Koyu yeşil
    if (val > 3) return '#228B22'; // Yeşil
    if (val > 2) return '#FFFF00'; // Sarı
    if (val > 1) return '#FFA500'; // Turuncu
    return '#FF0000';              // Kırmızı
  }

  // ===============================================
  // MODAL İŞLEMLERİ
  // ===============================================
  
  const modal = document.getElementById('infoModal');
    const modalBody = document.getElementById('modal-body');
    const closeBtn = modal.querySelector('.close');
    const genelOrtalamaEl = document.getElementById('genel-ortalama');
    const toplamYorumEl = document.getElementById('toplam-yorum');
    const categoryScoresEl = document.getElementById('category-scores');
    const commentsContainerEl = document.getElementById('comments-container');
    const moreBtnContainerEl = document.getElementById('more-btn-container');
    
    let currentPage = 1, currentId = null;
    
    // Show modal function
    function showInfo(id) {
        currentPage = 1;
        currentId = id;
        
        // Clear previous content
        categoryScoresEl.innerHTML = '';
        commentsContainerEl.innerHTML = '';
        moreBtnContainerEl.innerHTML = '';
        
        loadPage(true);
        modal.style.display = 'block';
    }
    
    // Load page data
    function loadPage(reset) {
        fetch(`/api/get_mahalle.php?` + new URLSearchParams({
            id: currentId, 
            page: currentPage
        }))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Mahalle bilgileri alınamadı');
                }
                return response.json();
            })
            .then(json => {
                if (reset) {
                    // Update statistics
                    genelOrtalamaEl.textContent = json.stats.genel_ortalama;
                    genelOrtalamaEl.className = `badge bg-${getBadgeClass(json.stats.genel_ortalama)}`;
                    toplamYorumEl.textContent = json.stats.toplam_yorum;
                    
                    // Update categories
                    json.categories.forEach(category => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            <span class="category-name">${category.isim}:</span> 
                            <span class="badge bg-${getBadgeClass(category.ortalama)}">${category.ortalama}</span>
                        `;
                        categoryScoresEl.appendChild(li);
                    });
                    
                    // Clear comments container
                    commentsContainerEl.innerHTML = '';
                }
                
                // Add comments
                json.comments.forEach(comment => {
                    const p = document.createElement('p');
                    p.innerHTML = `<span class="badge bg-${getBadgeClass(comment.ort)}">${comment.ort}</span> – ${comment.yorum}`;
                    commentsContainerEl.appendChild(p);
                });
                
                // Update "More" button
                updateMoreButton(json.totalPages);
            })
            .catch(error => {
                console.error('Mahalle detayları yüklenirken hata:', error);
                commentsContainerEl.innerHTML = `<div class="alert alert-danger">Hata: Veriler yüklenemedi</div>`;
            });
    }
    
    // Badge color based on score
    function getBadgeClass(score) {
        if (score > 4) return 'success';
        if (score > 3) return 'info';
        if (score > 2) return 'warning';
        return 'danger';
    }
    
    // Update the "More" button
    function updateMoreButton(totalPages) {
        moreBtnContainerEl.innerHTML = '';
        
        if (currentPage < totalPages) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-secondary mt-2';
            btn.textContent = 'Daha fazla yorum yükle';
            btn.onclick = () => { 
                currentPage++; 
                loadPage(false); 
            };
            moreBtnContainerEl.appendChild(btn);
        }
    }
    
    // Close modal events
    closeBtn.onclick = () => modal.style.display = 'none';
    
    window.onclick = (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
    
    // Initialize map markers when categories are loaded
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof loadKategoriler === 'function') {
            loadKategoriler().then(() => {
                if (typeof loadMarkers === 'function') {
                    loadMarkers();
                }
            });
        }
    });

  // ===============================================
  // MAHALLE ARAMA İŞLEMLERİ
  // ===============================================
  
  const selIl = document.getElementById('selIl');
  const selIlce = document.getElementById('selIlce');
  const selMahalle = document.getElementById('selMahalle');
  const btnShow = document.getElementById('btnShowResult');
  const manualRes = document.getElementById('manualResult');

  // İl seçildiğinde ilçeleri yükle
  selIl.addEventListener('change', () => {
    const ilId = selIl.value;
    
    // İlçe seçimini resetle
    selIlce.innerHTML = '<option value="">Yükleniyor...</option>';
    selIlce.disabled = true;
    
    // Mahalle seçimini resetle
    selMahalle.innerHTML = '<option value="">Önce ilçe seçin</option>';
    selMahalle.disabled = true;
    
    // Buton ve sonuçları resetle
    btnShow.disabled = true;
    manualRes.style.display = 'none';

    if (!ilId) {
      selIlce.innerHTML = '<option value="">Önce il seçin</option>';
      return;
    }

    // İlçeleri API'den yükle
    fetch(`/api/get_ilceler.php?il_id=${ilId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('İlçeler yüklenemedi');
        }
        return response.json();
      })
      .then(data => {
        selIlce.innerHTML = '<option value="">Seçiniz</option>';
        data.forEach(ilce => {
          selIlce.innerHTML += `<option value="${ilce.id}">${ilce.isim}</option>`;
        });
        selIlce.disabled = false;
      })
      .catch(error => {
        console.error('İlçe yükleme hatası:', error);
        selIlce.innerHTML = '<option value="">Hata oluştu</option>';
      });
  });

  // İlçe seçildiğinde mahalleleri yükle
  selIlce.addEventListener('change', () => {
    const ilceId = selIlce.value;
    
    // Mahalle seçimini resetle
    selMahalle.innerHTML = '<option value="">Yükleniyor...</option>';
    selMahalle.disabled = true;
    
    // Buton ve sonuçları resetle
    btnShow.disabled = true;
    manualRes.style.display = 'none';

    if (!ilceId) {
      selMahalle.innerHTML = '<option value="">Önce ilçe seçin</option>';
      return;
    }

    // Mahalleleri API'den yükle
    fetch(`/api/get_mahalleler_by_ilce.php?ilce_id=${ilceId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Mahalleler yüklenemedi');
        }
        return response.json();
      })
      .then(data => {
        selMahalle.innerHTML = '<option value="">Seçiniz</option>';
        data.forEach(mahalle => {
          selMahalle.innerHTML += `<option value="${mahalle.id}">${mahalle.isim}</option>`;
        });
        selMahalle.disabled = false;
      })
      .catch(error => {
        console.error('Mahalle yükleme hatası:', error);
        selMahalle.innerHTML = '<option value="">Hata oluştu</option>';
      });
  });

  // Mahalle seçildiğinde butonu aktifleştir
  selMahalle.addEventListener('change', () => {
    btnShow.disabled = !selMahalle.value;
    manualRes.style.display = 'none';
  });

  // "Sonuçları Göster" butonuna tıklandığında
  btnShow.addEventListener('click', () => {
    const mahalleId = selMahalle.value;
    if (!mahalleId) return;
  
    // Sonuç alanını temizle ve yükleme mesajı göster
    manualRes.innerHTML = `
      <div class="d-flex justify-content-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Yükleniyor...</span>
        </div>
      </div>`;
    manualRes.style.display = 'block';
    
    // Grafik konteynırını gizle (önceki sonuçlardan kalabilir)
    document.getElementById('chartContainer').style.display = 'none';
  
    // Mahalle bilgilerini API'den al
    fetch(`/api/get_mahalle.php?` + new URLSearchParams({ 
      id: mahalleId, 
      page: 1 
    }))
      .then(response => {
        if (!response.ok) {
          throw new Error('Mahalle bilgileri alınamadı');
        }
        return response.json();
      })
      .then(json => {
        // Rating Badge ve sınıfını hesapla
        const ratingClass = getBadgeClass(json.stats.genel_ortalama);
        const ratingLabel = getRatingLabel(json.stats.genel_ortalama);
  
        // Başlık ve istatistikler
let html = `
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>${selMahalle.options[selMahalle.selectedIndex].text}</h5>
      <span class="badge bg-light text-primary">${selIl.options[selIl.selectedIndex].text} / ${selIlce.options[selIlce.selectedIndex].text}</span>
    </div>
    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="text-center p-4 border rounded bg-light h-100">
            <h6 class="text-muted mb-3">Genel Değerlendirme</h6>
            <div class="display-4 fw-bold text-${ratingClass} mb-2">${json.stats.genel_ortalama}</div>
            <div class="badge bg-${ratingClass} px-3 py-2 mb-2">${ratingLabel}</div>
            <div class="text-muted"><i class="fas fa-users me-1"></i> ${json.stats.toplam_yorum} değerlendirme</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 h-100">
            <h6 class="border-bottom pb-2 mb-3">Kategori Puanları</h6>
            <div class="row">`;
// Kategorileri 2 sütunda göster
json.categories.forEach((category, index) => {
  html += `
    <div class="col-6 mb-2">
      <div class="d-flex justify-content-between">
        <span class="small">${category.isim}</span>
        <span class="badge bg-${getBadgeClass(category.ortalama)}">${category.ortalama}</span>
      </div>
      <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-${getBadgeClass(category.ortalama)}" role="progressbar" 
             style="width: ${(category.ortalama / 5) * 100}%" 
             aria-valuenow="${category.ortalama}" aria-valuemin="0" aria-valuemax="5"></div>
      </div>
    </div>`;
});
html += `
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card shadow-sm mt-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
              <h6 style="color:black;" class="mb-0">
                <i class="fas fa-comment-alt me-2 text-primary"></i>Son Yorumlar
                <span class="badge bg-primary ms-2">${json.comments.length}</span>
              </h6>
              <button id="btnShowAllComments" class="btn btn-sm btn-primary">
                <i class="fas fa-list me-1"></i>Tüm yorumları göster
              </button>
            </div>
            <div class="card-body p-0">`;
// Yorumlar
if (json.comments.length > 0) {
  html += `<div class="comment-list">`;
  json.comments.forEach((comment, index) => {
    const commentDate = new Date(comment.created_at);
    const formattedDate = commentDate.toLocaleDateString('tr-TR', {
      day: 'numeric', 
      month: 'long', 
      year: 'numeric'
    });
    
    // Puanı görsel yıldızlara dönüştür
    const stars = renderStars(comment.ort);
    
    // Her diğer yorum için hafif gri arka plan
    const bgClass = index % 2 === 0 ? '' : 'bg-light';
    
    html += `
      <div class="comment-item p-3 ${bgClass} border-bottom position-relative">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="d-flex align-items-center">
            <div class="comment-avatar me-2">
              <i class="fas fa-user-circle text-secondary" style="font-size: 2rem;"></i>
            </div>
            <div class="comment-meta">
              <div class="stars text-warning">${stars}</div>
              <small class="text-muted d-block"><i class="far fa-calendar-alt me-1"></i>${formattedDate}</small>
            </div>
          </div>
          <span class="badge bg-${getBadgeClass(comment.ort)} px-3 py-2">${comment.ort}</span>
        </div>
        <div class="comment-content mt-2 ps-5">
          <p class="mb-0">${comment.yorum}</p>
        </div>
        <div class="comment-actions text-end mt-2 ps-5">
          <button class="btn btn-sm btn-outline-secondary comment-like" data-id="${index}">
            <i class="far fa-thumbs-up me-1"></i><span class="like-count">0</span>
          </button>
        </div>
      </div>`;
  });
  html += `</div>`;
  
  // Sayfalama varsa
  if (json.totalPages > 1) {
    html += `
      <div class="text-center p-3">
        <button id="btnLoadMoreComments" class="btn btn-outline-primary">
          <i class="fas fa-chevron-down me-1"></i>Daha fazla yorum yükle
        </button>
      </div>`;
  }
} else {
  html += `
    <div class="p-4 text-center">
      <i class="fas fa-comment-slash text-muted mb-3" style="font-size: 3rem;"></i>
      <p class="text-muted">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
    </div>`;
}
html += `</div>
      </div>
    </div>
  </div>`;
        
        html += `</div>
              </div>
              
              <script>
                // Yorum beğeni fonksiyonu
                document.addEventListener('DOMContentLoaded', function() {
                  const likeButtons = document.querySelectorAll('.comment-like');
                  likeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                      const likeCount = this.querySelector('.like-count');
                      let count = parseInt(likeCount.textContent);
                      likeCount.textContent = count + 1;
                      this.classList.remove('btn-outline-secondary');
                      this.classList.add('btn-secondary');
                      this.disabled = true;
                    });
                  });
                });
              </script>`;

              // Yıldız renderleyici fonksiyon
        function renderStars(rating) {
          const fullStar = '<i class="fas fa-star"></i>';
          const halfStar = '<i class="fas fa-star-half-alt"></i>';
          const emptyStar = '<i class="far fa-star"></i>';
          
          let stars = '';
          const fullStars = Math.floor(rating);
          const hasHalfStar = rating % 1 >= 0.5;
          
          for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
              stars += fullStar;
            } else if (i === fullStars + 1 && hasHalfStar) {
              stars += halfStar;
            } else {
              stars += emptyStar;
            }
          }
          
          return stars;
        }
        
        // HTML'i ekrana yansıt
        manualRes.innerHTML = html;
        
        // Grafik konteynerini görünür yap
        const chartContainer = document.getElementById('chartContainer');
        chartContainer.style.display = 'block';
        
        // Grafik oluştur
        createRatingCharts(json.categories);
        
        // Tüm yorumları göster butonuna olay ekle
        document.getElementById('btnShowAllComments')?.addEventListener('click', () => {
          showInfo(mahalleId);
        });
        
        // Daha fazla yorum yükleme butonuna olay ekle (varsa)
        document.getElementById('btnLoadMoreComments')?.addEventListener('click', () => {
          showInfo(mahalleId);
        });
        
        // Değerlendirme yapma butonuna olay ekle
        document.getElementById('btnAddRating')?.addEventListener('click', () => {
          // Burada değerlendirme formuna yönlendirme veya modal açılabilir
          window.location.href = `/degerlendirme.php?mahalle_id=${mahalleId}`;
        });
        
        // Haritada seçilen mahalleyi göster - koordinatlar varsa
        if (json.coordinates) {
          map.setView([json.coordinates.lat, json.coordinates.lng], 14);
        }
      })
      .catch(error => {
        console.error('Mahalle detayları yüklenirken hata:', error);
        manualRes.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Hata: Veriler yüklenemedi. Lütfen daha sonra tekrar deneyin.
          </div>`;
      });
  });
  
  // Puan için etiket döndür
  function getRatingLabel(score) {
    if (score > 4.5) return 'Mükemmel';
    if (score > 4) return 'Çok İyi';
    if (score > 3) return 'İyi';
    if (score > 2) return 'Orta';
    if (score > 1) return 'Kötü';
    return 'Çok Kötü';
  }
  
  // Grafikleri oluştur
  function createRatingCharts(categories) {
    const chartContainer = document.getElementById('chartContainer');
    
    // Grafik konteyner içeriğini temizle
    chartContainer.innerHTML = `
      <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Puan Analizi</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <canvas id="radarChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    `;
  
    // Kategori verileri
    const labels = categories.map(cat => cat.isim);
    const data = categories.map(cat => parseFloat(cat.ortalama));
    const backgroundColors = categories.map(cat => {
      const score = parseFloat(cat.ortalama);
      if (score > 4) return 'rgba(16, 185, 129, 0.7)'; // success - yeşil
      if (score > 3) return 'rgba(6, 182, 212, 0.7)';  // info - mavi
      if (score > 2) return 'rgba(245, 158, 11, 0.7)'; // warning - turuncu
      return 'rgba(239, 68, 68, 0.7)';                 // danger - kırmızı
    });
    
    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    new Chart(radarCtx, {
      type: 'radar',
      data: {
        labels,
        datasets: [{
          label: 'Kategori Puanları',
          data,
          backgroundColor: 'rgba(59, 130, 246, 0.2)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          pointBackgroundColor: 'rgba(59, 130, 246, 1)',
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        scales: {
          r: {
            angleLines: {
              display: true,
              color: 'rgba(0, 0, 0, 0.1)'
            },
            suggestedMin: 0,
            suggestedMax: 5,
            ticks: {
              stepSize: 1
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Kategori Bazlı Değerlendirme',
            font: {
              size: 16
            }
          }
        }
      }
    });
    
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Puan',
          data,
          backgroundColor: backgroundColors,
          borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        indexAxis: 'y',
        scales: {
          x: {
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
          },
          title: {
            display: true,
            text: 'Kategori Puanları',
            font: {
              size: 16
            }
          }
        }
      }
    });
  }
});