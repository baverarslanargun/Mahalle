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
  let currentPage = 1, currentId = null;

  // Modal penceresini göster
  function showInfo(id) {
    currentPage = 1;
    currentId = id;
    loadPage(true);
    modal.style.display = 'block';
  }

  // Sayfalı içerik yükleme
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
          // İstatistikleri başta göster
          loadKategoriler().then(kategoriler => {
            const yorumSayisi = Math.round(json.stats.toplam_puan / kategoriSayisi);

            let html = `
              <h3>Mahalle Değerlendirmesi</h3>
              <div class="stats-container">
                <h4>Genel Ortalama: <span class="badge bg-${getBadgeClass(json.stats.genel_ortalama)}">${json.stats.genel_ortalama}</span> 
                (${json.stats.toplam_yorum} değerlendirme)</h4>
                <ul class="category-scores">`;
            
            json.categories.forEach(category => {
              html += `
                <li>
                  <span class="category-name">${category.isim}:</span> 
                  <span class="badge bg-${getBadgeClass(category.ortalama)}">${category.ortalama}</span>
                </li>`;
            });
            
            html += `</ul><hr><h4>Yorumlar:</h4>`;
            modalBody.innerHTML = html;

            // Yorumları ekle
            json.comments.forEach(comment => {
              const p = document.createElement('p');
              p.innerHTML = `<span class="badge bg-${getBadgeClass(comment.ort)}">${comment.ort}</span> – ${comment.yorum}`;
              modalBody.appendChild(p);
            });

            // "Daha fazla" butonu
            addMoreButton(json.totalPages);
          });
        } else {
          // Sadece yeni yorumları ekle
          json.comments.forEach(comment => {
            const p = document.createElement('p');
            p.innerHTML = `<span class="badge bg-${getBadgeClass(comment.ort)}">${comment.ort}</span> – ${comment.yorum}`;
            modalBody.appendChild(p);
          });

          // "Daha fazla" butonu güncelle
          addMoreButton(json.totalPages);
        }
      })
      .catch(error => {
        console.error('Mahalle detayları yüklenirken hata:', error);
        modalBody.innerHTML = `<div class="alert alert-danger">Hata: Veriler yüklenemedi</div>`;
      });
  }

  // Renk sınıfını puanlara göre belirle
  function getBadgeClass(score) {
    if (score > 4) return 'success';
    if (score > 3) return 'info';
    if (score > 2) return 'warning';
    return 'danger';
  }

  // "Daha fazla" butonu ekle
  function addMoreButton(totalPages) {
    modalBody.querySelector('#moreBtn')?.remove();
    
    if (currentPage < totalPages) {
      const btn = document.createElement('button');
      btn.id = 'moreBtn';
      btn.className = 'btn btn-secondary mt-2';
      btn.textContent = 'Daha fazla yorum yükle';
      btn.onclick = () => { 
        currentPage++; 
        loadPage(false); 
      };
      modalBody.appendChild(btn);
    }
  }

  // Modal kapatma
  closeBtn.onclick = () => modal.style.display = 'none';

  // Modal dışına tıklamada kapatma
  window.onclick = (event) => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  };

  // Başlangıçta kategorileri yükle ve işaretçileri göster
  loadKategoriler().then(() => loadMarkers());

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
      <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Yükleniyor...</span>
        </div>
      </div>`;
    manualRes.style.display = 'block';

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
        // Başlık ve istatistikler
        let html = `
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">${selMahalle.options[selMahalle.selectedIndex].text} Değerlendirmesi</h5>
            </div>
            <div class="card-body">
              <h5 class="card-title">
                Genel Ortalama: 
                <span class="badge bg-${getBadgeClass(json.stats.genel_ortalama)}">${json.stats.genel_ortalama}</span>
                <small class="text-muted">(${json.stats.toplam_yorum} değerlendirme)</small>
              </h5>
              
              <ul class="list-group list-group-flush mb-3">`;
        
        // Kategoriler
        json.categories.forEach(category => {
          html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>${category.isim}</span>
              <span class="badge bg-${getBadgeClass(category.ortalama)}">${category.ortalama}</span>
            </li>`;
        });
        
        html += `</ul>
                <h6 class="mt-4 mb-3 border-bottom pb-2">Son Yorumlar</h6>`;
        
        // Yorumlar
        if (json.comments.length > 0) {
          json.comments.forEach(comment => {
            html += `
              <div class="mb-3 pb-2 border-bottom">
                <div class="d-flex justify-content-between">
                  <span class="badge bg-${getBadgeClass(comment.ort)}">${comment.ort}</span>
                  <small class="text-muted">${comment.tarih || 'Tarih yok'}</small>
                </div>
                <p class="mt-1 mb-0">${comment.yorum}</p>
              </div>`;
          });
        } else {
          html += `<p class="text-muted">Henüz yorum yapılmamış.</p>`;
        }
        
        html += `</div></div>`;
        manualRes.innerHTML = html;
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
});