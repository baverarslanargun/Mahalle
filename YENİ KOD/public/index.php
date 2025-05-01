<?php
require_once __DIR__.'/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeniden Düzenlenmiş Header Tasarımı</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Inter', 'Helvetica', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        
    </style>
</head>
<body>
    

    <div class="mahalle-container">
        <div class="mahalle-content">
            <h1 class="mahalle-title">Mahallenizi farklı bakış açılarıyla keşfedin</h1>
            <p class="mahalle-description">
                Mahalleler hakkında gerçek deneyimleri öğrenin, kendi yorumlarınızı paylaşın. 
                Türkiye'nin en büyük mahalle değerlendirme topluluğuna katılarak mahalleleri 
                güvenlik, ulaşım, sosyal imkanlar ve daha birçok açıdan değerlendirin. 
                İstediğiniz mahalle hakkında anında bilgi alın ve diğer kullanıcıların 
                deneyimlerini görün.
            </p>
            <div class="mahalle-buttons">
                <a href="#" class="mahalle-button-google">
                    <svg class="mahalle-google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google'a kaydolun
                </a>
                <a href="#" class="mahalle-button-email">E-posta ile Kayıt Ol</a>
            </div>
        </div>
        <div class="mahalle-image">
            <svg class="mahalle-illustration" viewBox="0 0 600 500" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="300" cy="250" r="200" fill="#f8f9fa" />
                
                <!-- Map Background -->
                <rect x="150" y="150" width="300" height="200" rx="10" fill="#e9ecef" stroke="#ced4da" stroke-width="2" />
                
                <!-- Map Roads -->
                <path d="M150 250 H450" stroke="#adb5bd" stroke-width="3" />
                <path d="M300 150 V350" stroke="#adb5bd" stroke-width="3" />
                <path d="M200 200 L250 180 L280 220 L320 200 L350 230 L400 190" stroke="#adb5bd" stroke-width="2" />
                
                <!-- Map Blocks -->
                <rect x="180" y="180" width="40" height="40" rx="2" fill="#dee2e6" />
                <rect x="240" y="200" width="30" height="30" rx="2" fill="#dee2e6" />
                <rect x="330" y="170" width="50" height="50" rx="2" fill="#dee2e6" />
                <rect x="200" y="270" width="50" height="50" rx="2" fill="#dee2e6" />
                <rect x="330" y="270" width="40" height="40" rx="2" fill="#dee2e6" />
                
                <!-- Comment Bubbles -->
                <g transform="translate(170, 140) scale(0.7)">
                    <rect x="0" y="0" width="120" height="60" rx="15" fill="#3498db" />
                    <text x="60" y="35" font-family="Arial" font-size="14" fill="white" text-anchor="middle">Güvenli bölge!</text>
                </g>
                
                <g transform="translate(350, 120) scale(0.6)">
                    <rect x="0" y="0" width="140" height="60" rx="15" fill="#e74c3c" />
                    <text x="70" y="35" font-family="Arial" font-size="14" fill="white" text-anchor="middle">Trafik sorunlu!</text>
                </g>
                
                <g transform="translate(380, 280) scale(0.65)">
                    <rect x="0" y="0" width="130" height="60" rx="15" fill="#2ecc71" />
                    <text x="65" y="35" font-family="Arial" font-size="14" fill="white" text-anchor="middle">Parklar harika!</text>
                </g>
                
                <g transform="translate(130, 290) scale(0.55)">
                    <rect x="0" y="0" width="160" height="60" rx="15" fill="#f39c12" />
                    <text x="80" y="35" font-family="Arial" font-size="14" fill="white" text-anchor="middle">Okullar yakın!</text>
                </g>
                
                <!-- Location Marker -->
                <g transform="translate(300, 250)">
                    <path d="M0,-30 C-20,-30 -20,10 0,30 C20,10 20,-30 0,-30 Z" fill="#e74c3c" />
                    <circle cx="0" cy="-10" r="8" fill="white" />
                </g>
                
                <!-- Stars for Ratings -->
                <g transform="translate(210, 330)">
                    <path d="M10,1.5 L12.5,6.5 L18,7.5 L14,11.5 L15,17 L10,14.5 L5,17 L6,11.5 L2,7.5 L7.5,6.5 Z" fill="#f1c40f" />
                    <path d="M30,1.5 L32.5,6.5 L38,7.5 L34,11.5 L35,17 L30,14.5 L25,17 L26,11.5 L22,7.5 L27.5,6.5 Z" fill="#f1c40f" />
                    <path d="M50,1.5 L52.5,6.5 L58,7.5 L54,11.5 L55,17 L50,14.5 L45,17 L46,11.5 L42,7.5 L47.5,6.5 Z" fill="#f1c40f" />
                    <path d="M70,1.5 L72.5,6.5 L78,7.5 L74,11.5 L75,17 L70,14.5 L65,17 L66,11.5 L62,7.5 L67.5,6.5 Z" fill="#f1c40f" />
                    <path d="M90,1.5 L92.5,6.5 L98,7.5 L94,11.5 L95,17 L90,14.5 L85,17 L86,11.5 L82,7.5 L87.5,6.5 Z" fill="#e0e0e0" />
                </g>
            </svg>
        </div>
    </div>

    <div class="m360-hedef-container">
        <h2 class="m360-hedef-baslik">Kimlere Hitap Ediyoruz?</h2>
        
        <div class="m360-hedef-kartlar">
            <div class="m360-hedef-kart">
                <img src="https://i.ibb.co/d41K3Gh7/Albedo-Base-XL-neighbourhood-themed-corporate-style-flat-cartoo-1.jpg" alt="Mahalle sakinleri" class="m360-hedef-gorsel">
                <div class="m360-hedef-icerik">
                    <h3 class="m360-hedef-kart-baslik">
                        <svg class="m360-hedef-icon" viewBox="0 0 24 24">
                            <path d="M12,3L2,12H5V20H19V12H22L12,3M12,8.75A2.25,2.25 0 0,1 14.25,11A2.25,2.25 0 0,1 12,13.25A2.25,2.25 0 0,1 9.75,11A2.25,2.25 0 0,1 12,8.75M12,15C13.5,15 16.5,15.75 16.5,17.25V18H7.5V17.25C7.5,15.75 10.5,15 12,15Z"></path>
                        </svg>
                        Mahalle Sakinleri
                    </h3>
                    <p class="m360-hedef-metin">
                        Mahallenizin kalitesini artırmak ve güçlü bir topluluk oluşturmak için deneyimlerinizi paylaşın.
                    </p>
                    <div class="m360-hedef-etiketler">
                        <span class="m360-hedef-etiket">Topluluk Geliştirme</span>
                        <span class="m360-hedef-etiket">Komşuluk Bağları</span>
                        <span class="m360-hedef-etiket">Yerel Sorunlar</span>
                    </div>
                </div>
            </div>
            
            <div class="m360-hedef-kart">
                <img src="https://i.ibb.co/Q75r2TZf/Albedo-Base-XL-A-group-of-university-students-standing-in-front-3.jpg" alt="Yeni taşınanlar" class="m360-hedef-gorsel">
                <div class="m360-hedef-icerik">
                    <h3 class="m360-hedef-kart-baslik">
                        <svg class="m360-hedef-icon" viewBox="0 0 24 24">
                            <path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z"></path>
                        </svg>
                        Yeni Taşınanlar
                    </h3>
                    <p class="m360-hedef-metin">
                        Yeni bir şehirde kendinize uygun mahalleyi bulun, gerçek deneyimlere dayalı bilgilerle karar verin.
                    </p>
                    <div class="m360-hedef-etiketler">
                        <span class="m360-hedef-etiket">Öğrenciler</span>
                        <span class="m360-hedef-etiket">Profesyoneller</span>
                        <span class="m360-hedef-etiket">Yeni Aileler</span>
                    </div>
                </div>
            </div>
            
            <div class="m360-hedef-kart">
                <img src="https://i.ibb.co/VWHQykb0/Albedo-Base-XL-A-community-leader-standing-in-the-middle-of-a-s-3.jpg" alt="Topluluk Liderleri" class="m360-hedef-gorsel">
                <div class="m360-hedef-icerik">
                    <h3 class="m360-hedef-kart-baslik">
                        <svg class="m360-hedef-icon" viewBox="0 0 24 24">
                            <path d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z"></path>
                        </svg>
                        Topluluk Liderleri
                    </h3>
                    <p class="m360-hedef-metin">
                        Mahallenizde pozitif değişim yaratın, komşularınızı birleştirin ve yaşam kalitenizi hep birlikte yükseltin.
                    </p>
                    <div class="m360-hedef-etiketler">
                        <span class="m360-hedef-etiket">Etkinlikler</span>
                        <span class="m360-hedef-etiket">İşbirliği</span>
                        <span class="m360-hedef-etiket">Mahalle Gelişimi</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="m360-daha-fazla">
            <a href="#" class="m360-daha-fazla-btn">Mahalle360'a Katılın</a>
        </div>
    </div>

    
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobil menü açma/kapama
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mainNav = document.querySelector('.main-nav');
            const searchContainer = document.querySelector('.search-container');
            const authLinks = document.querySelector('.auth-links');
            
            mobileMenuBtn.addEventListener('click', function() {
                mainNav.classList.toggle('open');
                searchContainer.classList.toggle('open');
                authLinks.classList.toggle('open');
            });
            
            // Aktif menü öğesi işlevi
            const navLinkItems = document.querySelectorAll('.nav-link');
            
            navLinkItems.forEach(link => {
                link.addEventListener('click', function() {
                    navLinkItems.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    <?php
require_once __DIR__.'/../templates/footer.php';
?>
</body>
</html>
