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
        
        /* Header Stilleri */
        .header {
            background: white;
            color: #333;
            padding: 20px 24px;
            position: relative;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }
        
        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Logo Stilleri */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-icon svg {
            width: 100%;
            height: 100%;
        }
        
        .logo-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 22px;
            letter-spacing: 0.5px;
            color: #0D0C22;
            background: linear-gradient(90deg, #0D0C22, #524f68);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Ana Navigasyon */
        .main-nav {
            display: flex;
            justify-content: center;
        }
        
        .nav-links {
            display: flex;
            list-style-type: none;
        }
        
        .nav-link {
            position: relative;
            padding: 8px 16px;
            text-decoration: none;
            color: #6e6d7a;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav-link:hover {
            color: #0D0C22;
        }
        
        .nav-link.active {
            color: #0D0C22;
            font-weight: 600;
        }
        
        .nav-link:after {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #0D0C22;
            transition: all 0.3s;
        }
        
        .nav-link:hover:after,
        .nav-link.active:after {
            width: 30px;
            left: calc(50% - 15px);
        }
        
        .nav-icon {
            font-size: 16px;
        }
        
        /* Arama Kutusu */
        .search-container {
            display: flex;
            align-items: center;
            margin-left: auto;
            margin-right: 20px;
        }
        
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-input {
            border: 1px solid #e7e7e9;
            background-color: #f3f3f4;
            padding: 10px 15px 10px 36px;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            width: 180px;
            transition: all 0.2s;
            color: #6e6d7a;
        }
        
        .search-input::placeholder {
            color: #9e9ea7;
        }
        
        .search-input:focus {
            background-color: #fff;
            border-color: #dbdbde;
            box-shadow: 0 0 0 3px rgba(13, 12, 34, 0.05);
            width: 220px;
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            color: #333;
            font-size: 14px;
        }
        
        /* Giriş/Kayıt Linkleri */
        .auth-links {
            display: flex;
            gap: 12px;
        }
        
        .auth-link {
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .login {
            color: #6e6d7a;
        }
        
        .signup {
            background-color: #0D0C22;
            color: white;
        }
        
        .login:hover {
            color: #0D0C22;
        }
        
        .signup:hover {
            background-color: #000;
        }
        
        /* Mobil Menü Butonu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Responsive Tasarım */
        @media (max-width: 992px) {
            .header-container {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .main-nav {
                order: 3;
                width: 100%;
                margin-top: 10px;
            }
            
            .search-container {
                order: 2;
                margin-left: auto;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .logo {
                width: 100%;
                justify-content: space-between;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .main-nav, .search-container, .auth-links {
                display: none;
                width: 100%;
                margin-top: 15px;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
            }
            
            .nav-link {
                padding: 12px 0;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-input {
                width: 100%;
            }
            
            .auth-links {
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
            }
            
            .main-nav.open, .search-container.open, .auth-links.open {
                display: flex;
            }
        }

        .mahalle-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
        
        .mahalle-content {
            flex: 1;
            min-width: 300px;
            padding-right: 40px;
        }
        
        .mahalle-image {
            flex: 1;
            min-width: 300px;
            display: flex;
            justify-content: center;
        }
        
        .mahalle-title {
            font-size: 42px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .mahalle-description {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .mahalle-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }
        
        .mahalle-button-google {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }
        
        .mahalle-button-email {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .mahalle-button-google:hover {
            background-color: #f8f8f8;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .mahalle-button-email:hover {
            background-color: #2980b9;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .mahalle-google-icon {
            margin-right: 10px;
            width: 20px;
            height: 20px;
        }
        
        .mahalle-illustration {
            width: 100%;
            max-width: 500px;
            height: auto;
        }
        
        @media (max-width: 768px) {
            .mahalle-container {
                flex-direction: column;
            }
            
            .mahalle-content {
                padding-right: 0;
                margin-bottom: 30px;
            }
        }
        .m360-hedef-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .m360-hedef-baslik {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .m360-hedef-kartlar {
            display: flex;
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .m360-hedef-kart {
            flex: 1;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .m360-hedef-kart:hover {
            transform: translateY(-5px);
        }
        
        .m360-hedef-gorsel {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }
        
        .m360-hedef-icerik {
            padding: 1.5rem;
        }
        
        .m360-hedef-kart-baslik {
            color: #101828;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .m360-hedef-icon {
            width: 24px;
            height: 24px;
            fill: #FF5730;
        }
        
        .m360-hedef-metin {
            color: #475467;
            line-height: 1.5;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .m360-hedef-etiketler {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .m360-hedef-etiket {
            background-color: #F5F5F5;
            color: #344054;
            padding: 0.35rem 0.75rem;
            border-radius: 16px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .m360-daha-fazla {
            text-align: center;
            margin-top: 2rem;
        }
        
        .m360-daha-fazla-btn {
            background-color: #2E90FA;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .m360-daha-fazla-btn:hover {
            background-color: #1570CD;
        }
        
        @media (max-width: 768px) {
            .m360-hedef-kartlar {
                flex-direction: column;
            }
        }
        .mh360-footer-container {
            font-family: Arial, sans-serif;
            padding: 40px 20px;
            background-color: #ffffff;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .mh360-footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 30px;
        }
        
        .mh360-footer-logo-section {
            flex: 1;
            min-width: 200px;
            margin-right: 40px;
            margin-bottom: 20px;
        }
        
        .mh360-footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .mh360-footer-logo img {
            height: 24px;
            margin-right: 10px;
        }
        
        .mh360-footer-logo span {
            font-size: 22px;
            font-weight: bold;
            color: #222;
        }
        
        .mh360-footer-social {
            display: flex;
            gap: 15px;
        }
        
        .mh360-footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #f5f5f5;
            transition: background-color 0.3s;
        }
        
        .mh360-footer-social a:hover {
            background-color: #e5e5e5;
        }
        
        .mh360-footer-social svg {
            width: 16px;
            height: 16px;
            fill: #333;
        }
        
        .mh360-footer-section {
            flex: 1;
            min-width: 160px;
            margin-bottom: 20px;
        }
        
        .mh360-footer-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        
        .mh360-footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .mh360-footer-section li {
            margin-bottom: 10px;
        }
        
        .mh360-footer-section a {
            text-decoration: none;
            color: #666;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .mh360-footer-section a:hover {
            color: #0099ff;
        }
        
        .mh360-footer-bottom {
            padding-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Yıldızlar -->
                        <path d="M54 30L56.5 35L62 35.75L58 39.5L59 45L54 42.5L49 45L50 39.5L46 35.75L51.5 35L54 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M76 30L78.5 35L84 35.75L80 39.5L81 45L76 42.5L71 45L72 39.5L68 35.75L73.5 35L76 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M97 30L99.5 35L105 35.75L101 39.5L102 45L97 42.5L92 45L93 39.5L89 35.75L94.5 35L97 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        
                        <!-- İnsan figürleri -->
                        <circle cx="54" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <circle cx="76" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <circle cx="97" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        
                        <path d="M40 80C40 72 46 68 54 68C62 68 68 72 68 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M62 80C62 72 68 68 76 68C84 68 90 72 90 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M83 80C83 72 89 68 97 68C105 68 111 72 111 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                    </svg>
                </div>
                <span class="logo-text">Mahalle360</span>
                <button class="mobile-menu-btn">☰</button>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="#" class="nav-link active">Ana Sayfa</a></li>
                    <li><a href="#" class="nav-link">Harita</a></li>
                    <li><a href="#" class="nav-link">Değerlendirme</a></li>
                    <li><a href="#" class="nav-link">Rapor</a></li>
                </ul>
            </nav>
            
            <div class="search-container">
                <div class="search-box">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" class="search-input" placeholder="Mahalle veya şehir ara...">
                </div>
            </div>
            
            <div class="auth-links">
                <a href="#" class="auth-link login">Giriş Yap</a>
                <a href="#" class="auth-link signup">Kayıt Ol</a>
            </div>
        </div>
    </header>

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

    <footer class="mh360-footer-container">
        <div class="mh360-footer-content">
            <div class="mh360-footer-logo-section">
                <div class="mh360-footer-logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Yıldızlar -->
                        <path d="M54 30L56.5 35L62 35.75L58 39.5L59 45L54 42.5L49 45L50 39.5L46 35.75L51.5 35L54 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M76 30L78.5 35L84 35.75L80 39.5L81 45L76 42.5L71 45L72 39.5L68 35.75L73.5 35L76 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M97 30L99.5 35L105 35.75L101 39.5L102 45L97 42.5L92 45L93 39.5L89 35.75L94.5 35L97 30Z" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        
                        <!-- İnsan figürleri -->
                        <circle cx="54" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <circle cx="76" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <circle cx="97" cy="60" r="8" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        
                        <path d="M40 80C40 72 46 68 54 68C62 68 68 72 68 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M62 80C62 72 68 68 76 68C84 68 90 72 90 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                        <path d="M83 80C83 72 89 68 97 68C105 68 111 72 111 80" stroke="#0D0C22" stroke-width="2" fill="none"/>
                    </svg>
                </div>
                    <span>Mahalle360</span>
                </div>
                <div class="mh360-footer-social">
                    <a href="#" aria-label="Twitter">
                        <svg viewBox="0 0 24 24">
                            <path d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24">
                            <path d="M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3H19M18.5,18.5V13.2A3.26,3.26 0 0,0 15.24,9.94C14.39,9.94 13.4,10.46 12.92,11.24V10.13H10.13V18.5H12.92V13.57C12.92,12.8 13.54,12.17 14.31,12.17A1.4,1.4 0 0,1 15.71,13.57V18.5H18.5M6.88,8.56A1.68,1.68 0 0,0 8.56,6.88C8.56,5.95 7.81,5.19 6.88,5.19A1.69,1.69 0 0,0 5.19,6.88C5.19,7.81 5.95,8.56 6.88,8.56M8.27,18.5V10.13H5.5V18.5H8.27Z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="mh360-footer-section">
                <h3>Özellikler</h3>
                <ul>
                    <li><a href="#">Mahalleler</a></li>
                    <li><a href="#">Emlak İlanları</a></li>
                    <li><a href="#">Mahalle Haritası</a></li>
                    <li><a href="#">İstatistikler</a></li>
                    <li><a href="#">Karşılaştır</a></li>
                </ul>
            </div>
            
            <div class="mh360-footer-section">
                <h3>Dökümanlar</h3>
                <ul>
                    <li><a href="#">Yardım Merkezi</a></li>
                    <li><a href="#">Kullanım Kılavuzu</a></li>
                    <li><a href="#">API Dokümanları</a></li>
                    <li><a href="#">Veri Kaynakları</a></li>
                </ul>
            </div>
            
            <div class="mh360-footer-section">
                <h3>Şirket</h3>
                <ul>
                    <li><a href="#">Hakkımızda</a></li>
                    <li><a href="#">İletişim</a></li>
                    <li><a href="#">Kariyer</a></li>
                    <li><a href="#">Basın</a></li>
                </ul>
            </div>
            
            <div class="mh360-footer-section">
                <h3>Yasal</h3>
                <ul>
                    <li><a href="#">Kullanım Şartları</a></li>
                    <li><a href="#">Gizlilik Politikası</a></li>
                    <li><a href="#">Çerezler</a></li>
                    <li><a href="#">KVKK</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mh360-footer-bottom">
            <p>© 2025 Mahalle360. Tüm hakları saklıdır.</p>
        </div>
    </footer>
    
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
</body>
</html>