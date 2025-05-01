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
            padding: 30px 10% 10px 10%;
            background-color: #ffffff;
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

  <!-- Custom Styles -->
  <link rel="stylesheet" href="/assets/css/style.css">
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
  <main class="container-fluid p-0">
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
  


    