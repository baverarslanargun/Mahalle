/**
 * Neighborhood Rating System (Mahalle Değerlendirme Sistemi)
 * Enhanced JavaScript functionality
 */

document.addEventListener('DOMContentLoaded', () => {
  // Main elements
  const ilSelect = document.getElementById('ilSelect'),
        ilceSelect = document.getElementById('ilceSelect'),
        mahSelect = document.getElementById('mahSelect'),
        form = document.getElementById('reviewForm'),
        ratingStars = document.querySelectorAll('.rating-stars'),
        submitButton = document.getElementById('submitButton'),
        searchInput = document.getElementById('ara');
  
  // Initialize tooltips if Bootstrap is available
  if (typeof bootstrap !== 'undefined') {
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(el => new bootstrap.Tooltip(el));
  }
  
  // Initialize notification system
  initNotificationSystem();
  
  // Initialize star rating functionality
  initStarRating();
  
  // Initialize location dropdowns
  initLocationDropdowns();
  
  // Initialize search and autocomplete
  initSearch();
  
  /**
   * Initialize star rating system
   */
  function initStarRating() {
    if (!ratingStars.length) return;
    
    ratingStars.forEach(group => {
      const stars = Array.from(group.querySelectorAll('.star'));
      const hiddenInput = group.querySelector('input[type="hidden"]');
      
      stars.forEach(star => {
        // Handle hover effects
        star.addEventListener('mouseover', () => {
          const value = parseInt(star.getAttribute('data-value'));
          
          stars.forEach(s => {
            const starValue = parseInt(s.getAttribute('data-value'));
            s.classList.toggle('hover', starValue <= value);
          });
        });
        
        group.addEventListener('mouseout', () => {
          stars.forEach(s => s.classList.remove('hover'));
        });
        
        // Handle click events
        star.addEventListener('click', () => {
          const value = star.getAttribute('data-value');
          
          // Play sound effect if audio API is available
          if (typeof Audio !== 'undefined') {
            try {
              const audio = new Audio('/assets/sounds/star-click.mp3');
              audio.volume = 0.3;
              audio.play().catch(() => {}); // Ignore errors if sound can't play
            } catch (e) {
              // Silent error - sound is not critical
            }
          }
          
          // Update hidden input value
          hiddenInput.value = value;
          
          // Reset all stars
          stars.forEach(s => s.classList.remove('active'));
          
          // Activate stars up to clicked star
          for (let i = 0; i < value; i++) {
            stars[i].classList.add('active');
          }
          
          // Add animation to the clicked star
          star.style.animation = 'none';
          setTimeout(() => {
            star.style.animation = 'pop 0.3s ease';
          }, 5);
        });
      });
    });
  }
  
  /**
   * Initialize notification system
   */
  function initNotificationSystem() {
    // Create notification container if it doesn't exist
    if (!document.getElementById('notification-container')) {
      const container = document.createElement('div');
      container.id = 'notification-container';
      container.style.position = 'fixed';
      container.style.top = '20px';
      container.style.right = '20px';
      container.style.zIndex = '9999';
      document.body.appendChild(container);
    }
  }
  
  /**
   * Show a notification message
   * @param {string} message - The message to display
   * @param {string} type - The notification type (success, error, warning, info)
   * @param {number} duration - How long to show the notification in ms
   */
  function showNotification(message, type = 'info', duration = 5000) {
    const container = document.getElementById('notification-container');
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.style.marginBottom = '10px';
    notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    notification.style.minWidth = '280px';
    notification.style.maxWidth = '400px';
    notification.style.animation = 'slide-in-right 0.3s ease-out forwards';
    
    // Add notification content
    notification.innerHTML = `
      ${message}
      <button type="button" class="btn-close" aria-label="Close"></button>
    `;
    
    // Add close button functionality
    const closeBtn = notification.querySelector('.btn-close');
    closeBtn.addEventListener('click', () => {
      notification.style.animation = 'slide-out-right 0.3s ease-in forwards';
      setTimeout(() => {
        container.removeChild(notification);
      }, 300);
    });
    
    // Add to container
    container.appendChild(notification);
    
    // Auto-remove after duration
    if (duration > 0) {
      setTimeout(() => {
        if (notification.parentNode === container) {
          notification.style.animation = 'slide-out-right 0.3s ease-in forwards';
          setTimeout(() => {
            if (notification.parentNode === container) {
              container.removeChild(notification);
            }
          }, 300);
        }
      }, duration);
    }
    
    // Add CSS for animations if not already present
    if (!document.getElementById('notification-styles')) {
      const style = document.createElement('style');
      style.id = 'notification-styles';
      style.textContent = `
        @keyframes slide-in-right {
          from { transform: translateX(100%); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slide-out-right {
          from { transform: translateX(0); opacity: 1; }
          to { transform: translateX(100%); opacity: 0; }
        }
      `;
      document.head.appendChild(style);
    }
  }
  
  /**
   * Initialize location dropdowns (il, ilce, mahalle)
   */
  function initLocationDropdowns() {
    if (!ilSelect) return;
    
    // Handle il (province) selection
    ilSelect.addEventListener('change', () => {
      // Reset and disable dependent selects
      ilceSelect.innerHTML = '<option value="">Seçiniz</option>';
      ilceSelect.disabled = !ilSelect.value;
      mahSelect.innerHTML = '<option value="">Önce ilçe seçin</option>';
      mahSelect.disabled = true;
      
      if (!ilSelect.value) return;
      
      // Show loading state
      ilceSelect.innerHTML = '<option value="">Yükleniyor...</option>';
      
      // Fetch ilçe data
      fetch(`/api/get_ilceler.php?il_id=${ilSelect.value}`)
        .then(response => {
          if (!response.ok) throw new Error('Sunucu hatası');
          return response.json();
        })
        .then(data => {
          // Populate ilçe dropdown
          ilceSelect.innerHTML = '<option value="">Seçiniz</option>';
          
          if (Array.isArray(data) && data.length > 0) {
            data.forEach(ilce => {
              const option = document.createElement('option');
              option.value = ilce.id;
              option.textContent = ilce.isim;
              ilceSelect.appendChild(option);
            });
          } else {
            ilceSelect.innerHTML = '<option value="">Sonuç bulunamadı</option>';
          }
        })
        .catch(error => {
          console.error('İlçe verisi alınamadı:', error);
          ilceSelect.innerHTML = '<option value="">Hata oluştu, tekrar deneyin</option>';
        });
    });
    
    // Handle ilçe (district) selection
    ilceSelect.addEventListener('change', () => {
      // Reset and disable mahalle select
      mahSelect.innerHTML = '<option value="">Seçiniz</option>';
      mahSelect.disabled = !ilceSelect.value;
      
      if (!ilceSelect.value) return;
      
      // Show loading state
      mahSelect.innerHTML = '<option value="">Yükleniyor...</option>';
      
      // Fetch mahalle data
      fetch(`/api/get_mahalleler_by_ilce.php?ilce_id=${ilceSelect.value}`)
        .then(response => {
          if (!response.ok) throw new Error('Sunucu hatası');
          return response.json();
        })
        .then(data => {
          // Populate mahalle dropdown
          mahSelect.innerHTML = '<option value="">Seçiniz</option>';
          
          if (Array.isArray(data) && data.length > 0) {
            data.forEach(mahalle => {
              const option = document.createElement('option');
              option.value = mahalle.id;
              option.textContent = mahalle.isim;
              mahSelect.appendChild(option);
            });
          } else {
            mahSelect.innerHTML = '<option value="">Sonuç bulunamadı</option>';
          }
        })
        .catch(error => {
          console.error('Mahalle verisi alınamadı:', error);
          mahSelect.innerHTML = '<option value="">Hata oluştu, tekrar deneyin</option>';
          
        });
    });
  }
  
  /**
   * Initialize search functionality and autocomplete
   */
  function initSearch() {
    if (!searchInput) return;
    
    // Create a debounce function to limit API calls
    const debounce = (func, delay) => {
      let timeout;
      return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
      };
    };
    
    // Handle input changes for autocomplete
    searchInput.addEventListener('input', debounce(function() {
      const query = this.value.trim();
      if (query.length < 2) return; // Require at least 2 characters
      
      fetch(`/api/getir.php?autocomplete=1&q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          const datalist = document.getElementById('mahalleler');
          datalist.innerHTML = ''; // Clear previous suggestions
          
          if (Array.isArray(data)) {
            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item; // If DB returns objects, use item.name or similar
              datalist.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Otomatik tamamlama hatası:', error);
        });
    }, 300)); // 300ms delay to avoid too many requests
  }
  
  /**
   * Handle form submission
   */
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      
      // Validate form
      if (!validateForm()) {
        
        return;
      }
      
      // Prepare form data
      const formData = new FormData(form);
      const payload = {
        mahalle_id: mahSelect.value,
        yorum: formData.get('yorum'),
        puan: {}
      };
      
      // Extract rating values - sadece değer olanları gönder
      formData.forEach((value, key) => {
        if (key.startsWith('puan[')) {
          const matches = key.match(/\[(\d+)\]/);
          if (matches && matches[1] && value) { // Sadece değer varsa ekle
            payload.puan[matches[1]] = value;
          }
        }
      });
      
      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
      
      // Submit data
      fetch('/api/submit_review.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => {
        if (!response.ok) throw new Error('Sunucu hatası');
        return response.json();
      })
      .then(data => {
        // Reset form state
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Değerlendirmeyi Kaydet';
        
        if (data.success) {
          // Success! Reset form and show notification
          resetForm();
          
          // Optional: Reload neighborhood data if viewing the same neighborhood
          const searchInput = document.getElementById('ara');
          if (searchInput && searchInput.value) {
            setTimeout(() => {
              yorumGetir(); // Refresh data
            }, 1000);
          }
        } else {
          // Error occurred
        }
      })
      .catch(error => {
        console.error('Form gönderme hatası:', error);
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Değerlendirmeyi Kaydet';
      });
    });
  }
  
  /**
   * Validate the review form
   * @returns {boolean} Whether the form is valid
   */
  function validateForm() {
    let isValid = true;
    
    // Check if location is selected
    if (!ilSelect.value || !ilceSelect.value || !mahSelect.value) {
      isValid = false;
      
      // Highlight invalid selects
      if (!ilSelect.value) ilSelect.classList.add('is-invalid');
      if (!ilceSelect.value) ilceSelect.classList.add('is-invalid');
      if (!mahSelect.value) mahSelect.classList.add('is-invalid');
    }
    
    // Kategori puanlamayı zorunlu tutmuyoruz - tüm kategorilerin puanlanması opsiyonel
    // Kullanıcı hiçbir kategori puanlamadıysa hafif bir uyarı gösterelim
    const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
    let hasAnyRating = false;
    
    hiddenInputs.forEach(input => {
      if (input.value) {
        hasAnyRating = true;
      }
    });
    
    // Eğer hiçbir kategori puanlanmadıysa, kullanıcıya bilgi verelim ama formu engellemeyelim
    if (!hasAnyRating) {
      // Form gönderimini engelleme - sadece bilgilendirme yap
      // isValid = false;
    }
    
    // Check if comment is provided
    const commentField = form.querySelector('textarea[name="yorum"]');
    if (!commentField.value.trim()) {
      isValid = false;
      commentField.classList.add('is-invalid');
    } else {
      commentField.classList.remove('is-invalid');
    }
    
    return isValid;
  }
  
  /**
   * Reset the form to its initial state
   */
  function resetForm() {
    // Reset form elements
    form.reset();
    
    // Reset star ratings
    document.querySelectorAll('.star').forEach(star => {
      star.classList.remove('active');
    });
    
    // Reset select boxes
    ilceSelect.innerHTML = '<option value="">Önce il seçin</option>';
    ilceSelect.disabled = true;
    mahSelect.innerHTML = '<option value="">Önce ilçe seçin</option>';
    mahSelect.disabled = true;
    
    // Remove validation styling
    document.querySelectorAll('.is-invalid').forEach(el => {
      el.classList.remove('is-invalid');
    });
    
    // Reset category cards
    document.querySelectorAll('.category-card').forEach(card => {
      card.classList.remove('invalid');
    });
  }
  
  /**
   * Get and display comments for a neighborhood
   * @param {number|null} gun - Number of days to filter by (null for all time)
   */
  window.yorumGetir = function(gun = null) {
    const mahalleAdi = document.getElementById('ara').value.trim();
    const sonucDiv = document.getElementById('sonuc');
    const chartContainer = document.getElementById('chartContainer');
    
    if (!mahalleAdi) {
      return;
    }
    
    // Show loading state
    sonucDiv.classList.remove('d-none');
    sonucDiv.innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Yükleniyor...</span>
        </div>
        <p class="mt-3 text-muted">Mahalle bilgileri yükleniyor...</p>
      </div>
    `;
    
    // Construct API URL
    let apiUrl = `/api/getir.php?mahalle=${encodeURIComponent(mahalleAdi)}`;
    if (gun) {
      apiUrl += `&gun=${gun}`;
    }
    
    // Fetch data
    fetch(apiUrl)
      .then(response => {
        if (!response.ok) throw new Error('Sunucu hatası');
        return response.json();
      })
      .then(data => {
        // Handle error
        if (data.hata) {
          sonucDiv.innerHTML = `
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-2"></i>
              ${data.hata}
            </div>
            <div class="text-center">
              <p class="text-muted">Farklı bir mahalle adı ile yeniden arama yapabilirsiniz.</p>
            </div>
          `;
          chartContainer.classList.add('d-none');
          return;
        }
        
        // Display results
        displayResults(data, mahalleAdi);
      })
      .catch(error => {
        console.error('Veri alma hatası:', error);
        sonucDiv.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-times-circle me-2"></i>
            Veriler alınırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.
          </div>
        `;
        chartContainer.classList.add('d-none');
      });
  };
  
  /**
   * Display neighborhood results
   * @param {Object} data - The neighborhood data
   * @param {string} mahalleAdi - The neighborhood name
   */
  function displayResults(data, mahalleAdi) {
    const sonucDiv = document.getElementById('sonuc');
    const chartContainer = document.getElementById('chartContainer');
    
    // Prepare rating categories and data
    const categories = [
      { key: 'guvenlik', icon: 'shield-alt', title: 'Güvenlik' },
      { key: 'temizlik', icon: 'broom', title: 'Temizlik' },
      { key: 'ulasim', icon: 'bus', title: 'Ulaşım' },
      { key: 'komsuluk', icon: 'users', title: 'Komşuluk' }
    ];
    
    // Generate rating cards
    const ratingCards = categories.map(cat => `
      <div class="col-6 col-md-3 mb-3">
        <div class="stats-card">
          <div class="stats-icon">
            <i class="fas fa-${cat.icon}"></i>
          </div>
          <div class="stats-title">${cat.title}</div>
          <div class="stats-value">
            ${renderStars(data[cat.key] || 0)}
          </div>
        </div>
      </div>
    `).join('');
    
    // Generate comments HTML
    const yorumlar = data.yorumlar || [];
    const yorumlarHTML = yorumlar.length ? yorumlar.map(yorum => `
      <div class="comment-card">
        <div class="card-body">
          <p class="comment-text mb-2">${yorum.metin}</p>
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="comment-date">${yorum.tarih || ''}</small>
            <div class="d-flex gap-3">
              ${categories.map(cat => `
                <span title="${cat.title}" data-bs-toggle="tooltip">
                  <i class="fas fa-${cat.icon} text-muted me-1"></i>
                  <small>${yorum[cat.key] || 0}</small>
                </span>
              `).join('')}
            </div>
          </div>
        </div>
      </div>
    `).join('') : `
      <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Bu mahalle için henüz yorum bulunmamaktadır.
      </div>
    `;
    
    // Update results container
    sonucDiv.innerHTML = `
      <div class="mb-4">
        <h4 class="results-title">
          <i class="fas fa-map-marker-alt me-2"></i>
          ${mahalleAdi} Mahallesi Değerlendirmesi
        </h4>
        <div class="row">
          ${ratingCards}
        </div>
      </div>
      
      <div class="mb-4">
        <h5 class="mb-3">
          <i class="fas fa-comments me-2"></i>
          Yorumlar ${yorumlar.length ? `(${yorumlar.length})` : ''}
        </h5>
        ${yorumlarHTML}
      </div>
    `;
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
      const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      tooltips.forEach(el => new bootstrap.Tooltip(el));
    }
    
    // Display charts if we have data
    if (yorumlar.length > 0) {
      chartContainer.classList.remove('d-none');
      createCharts(data, mahalleAdi);
    } else {
      chartContainer.classList.add('d-none');
    }
    
    // Smooth scroll to results
    sonucDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  
  /**
   * Create charts for neighborhood data
   * @param {Object} data - The neighborhood data
   * @param {string} mahalleAdi - The neighborhood name
   */
  function createCharts(data, mahalleAdi) {
    // Get chart context
    const ctx = document.getElementById('puanGrafik').getContext('2d');
    
    // Prepare data
    const categories = ['Güvenlik', 'Temizlik', 'Ulaşım', 'Komşuluk'];
    const values = [
      data.guvenlik || 0,
      data.temizlik || 0,
      data.ulasim || 0,
      data.komsuluk || 0
    ];
    
    // Destroy existing chart if it exists
    if (window.puanChart) {
      window.puanChart.destroy();
    }
    
    // Create radar chart
    window.puanChart = new Chart(ctx, {
      type: 'radar',
      data: {
        labels: categories,
        datasets: [{
          label: mahalleAdi,
          data: values,
          backgroundColor: 'rgba(79, 70, 229, 0.2)',
          borderColor: 'rgba(79, 70, 229, 1)',
          pointBackgroundColor: 'rgba(79, 70, 229, 1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(79, 70, 229, 1)',
          borderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        scales: {
          r: {
            angleLines: {
              color: 'rgba(0, 0, 0, 0.1)'
            },
            grid: {
              color: 'rgba(0, 0, 0, 0.1)'
            },
            pointLabels: {
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            beginAtZero: true,
            max: 5,
            ticks: {
              stepSize: 1,
              backdropColor: 'transparent'
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 15,
              font: {
                size: 14
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.raw.toFixed(1)}/5`;
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
    
    // Optional: Add a bar chart below radar chart
    const barCanvas = document.createElement('canvas');
    barCanvas.id = 'barChart';
    barCanvas.style.marginTop = '30px';
    barCanvas.height = 180;
    document.getElementById('chartContainer').appendChild(barCanvas);
    
    new Chart(barCanvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: categories,
        datasets: [{
          label: 'Puan',
          data: values,
          backgroundColor: [
            'rgba(79, 70, 229, 0.7)',  // Indigo
            'rgba(16, 185, 129, 0.7)', // Emerald
            'rgba(245, 158, 11, 0.7)', // Amber
            'rgba(99, 102, 241, 0.7)'  // Indigo/Purple
          ],
          borderColor: [
            'rgba(79, 70, 229, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(99, 102, 241, 1)'
          ],
          borderWidth: 1,
          borderRadius: 6
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            max: 5,
            ticks: {
              stepSize: 1,
              callback: function(value) {
                return value + (value === 5 ? ' ★' : '');
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
                return `${context.dataset.label}: ${context.raw.toFixed(1)}/5`;
              }
            }
          }
        },
        animation: {
          duration: 1000,
          easing: 'easeOutQuart'
        }
      }
    });
  }
  
  /**
   * Render star rating HTML
   * @param {number} puan - The rating value (0-5)
   * @returns {string} - HTML for star rating
   */
  function renderStars(puan) {
    puan = parseFloat(puan) || 0;
    
    const tamYildiz = Math.floor(puan);
    const yarimYildiz = puan % 1 >= 0.5 ? 1 : 0;
    const bosYildiz = 5 - tamYildiz - yarimYildiz;
    
    let stars = '';
    
    // Full stars
    for (let i = 0; i < tamYildiz; i++) {
      stars += '<i class="fas fa-star text-warning"></i>';
    }
    
    // Half star
    if (yarimYildiz) {
      stars += '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < bosYildiz; i++) {
      stars += '<i class="far fa-star text-warning"></i>';
    }
    
    return `${stars} <small class="text-muted">(${puan.toFixed(1)})</small>`;
  }
});