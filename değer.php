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