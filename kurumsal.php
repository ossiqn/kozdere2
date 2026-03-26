<?php
require_once 'baglan.php';

$kurumsal_icerik = [
    'tarihce_baslik' => 'Asırlık Çınarın Gölgesinde: Kozdere',
    'tarihce_metin' => 'Köyümüzün kökleri 1800\'lü yılların başlarına dayanmaktadır. Adını, içinden geçen ve bahar aylarında coşkuyla akan Koz Deresi\'nden almıştır. İlk yerleşimcileri, hayvancılık ve tarımla uğraşan Yörük boylarıdır. Yıllar içinde farklı sülalelerin de yerleşmesiyle zengin bir kültürel mozaik oluşmuştur. Kurtuluş Savaşı yıllarında köyümüz, cepheye gönderdiği kahramanlarla destan yazmıştır.',
    'koy_merkez_uzaklik' => '25 km',
    'koy_ilce_uzaklik' => '12 km',
    'rakim' => '1250 Metre',
    'nufus' => '850',
    'komsu_koyler' => 'Dereköy, Çamlıbel, Yenice'
];

$muhtar = [
    'isim' => 'Hasan Demir', 
    'unvan' => 'Köy Muhtarı', 
    'mesaj' => 'Kıymetli hemşehrilerim, köyümüze hizmet etmekten onur duyuyorum. Birlik ve beraberliğimiz en büyük gücümüzdür.', 
    'resim' => 'https://placehold.co/300x300/15803d/fff?text=Muhtar', 
    'telefon' => '0532 111 22 33'
];

$azalar = [
    ['isim' => 'Ahmet Yılmaz', 'unvan' => '1. Aza', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=A.Y'],
    ['isim' => 'Mehmet Kaya', 'unvan' => '2. Aza', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=M.K'],
    ['isim' => 'Ayşe Demirci', 'unvan' => '3. Aza', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=A.D'],
    ['isim' => 'Mustafa Çelik', 'unvan' => '4. Aza', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=M.C']
];

if (isset($site['muhtar'])) {
    $muhtar['isim'] = $site['muhtar'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kurumsal & Tarihçe - <?= htmlspecialchars($site['baslik']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #15803d; --primary-hover: #166534; --dark: #0f172a; --light: #f8fafc; --gray: #64748b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--light); color: var(--dark); overflow-x: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }
        
        .top-bar { background: var(--dark); color: #cbd5e1; font-size: 0.85rem; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .top-bar a { color: #cbd5e1; text-decoration: none; transition: 0.3s; }
        .top-bar a:hover { color: #fff; }
        
        .navbar { background: rgba(255,255,255,0.98); padding: 18px 0; transition: 0.4s; z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.03); position: relative; }
        .navbar.sticky { position: fixed; top: 0; width: 100%; padding: 12px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); animation: slideDown 0.5s ease; }
        @keyframes slideDown { from { transform: translateY(-100%); } to { transform: translateY(0); } }
        .navbar-brand { font-weight: 900; font-size: 1.7rem; letter-spacing: -0.5px; color: var(--dark); }
        .navbar-brand span { color: var(--primary); }
        .nav-link { font-weight: 700; color: #475569; padding: 10px 20px !important; border-radius: 10px; transition: 0.3s; font-size: 0.95rem; }
        .nav-link:hover, .nav-link.active { color: var(--primary); background: rgba(21,128,61,0.05); transform: translateY(-2px); }
        .dropdown-menu { border-radius: 16px; border: 1px solid rgba(0,0,0,0.05); padding: 10px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .dropdown-item { font-weight: 700; color: #475569; border-radius: 8px; padding: 10px 20px; transition: 0.3s; }
        .dropdown-item:hover { background: rgba(21,128,61,0.05); color: var(--primary); transform: translateX(5px); }
        .btn-custom { background: var(--primary); color: #fff; font-weight: 800; padding: 12px 28px; border-radius: 12px; text-decoration: none; transition: 0.4s; display: inline-flex; align-items: center; gap: 8px; border: none; }
        .btn-custom:hover { background: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(21,128,61,0.3); color: #fff; }
        
        .page-header { background: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.95)), url('https://placehold.co/1920x600/1e293b/fff?text=Kozdere+Doga') center/cover; padding: 120px 0 80px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3.5rem; letter-spacing: -1px; margin-bottom: 15px; }
        .breadcrumb-custom { display: flex; justify-content: center; gap: 10px; font-weight: 600; color: #cbd5e1; font-size: 0.95rem; }
        .breadcrumb-custom a { color: var(--primary); text-decoration: none; }
        
        .section-title { font-weight: 900; font-size: 2.2rem; color: var(--dark); letter-spacing: -1px; margin-bottom: 30px; position: relative; display: inline-block; }
        .section-title::after { content: ''; position: absolute; left: 0; bottom: -10px; width: 60px; height: 5px; background: var(--primary); border-radius: 10px; }
        
        .history-img { width: 100%; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); object-fit: cover; height: 100%; min-height: 400px; }
        .history-text { font-size: 1.1rem; color: var(--gray); line-height: 1.8; font-weight: 500; }
        
        .geo-card { background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.03); transition: 0.4s; height: 100%; display: flex; align-items: center; gap: 20px; }
        .geo-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); border-color: rgba(21,128,61,0.1); }
        .geo-icon { width: 60px; height: 60px; border-radius: 16px; background: rgba(21,128,61,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
        .geo-info h5 { font-weight: 800; color: var(--dark); margin-bottom: 5px; font-size: 1.1rem; }
        .geo-info p { margin: 0; color: var(--gray); font-weight: 600; font-size: 1rem; }
        
        .team-section { background: #fff; padding: 100px 0; border-top: 1px solid #f1f5f9; }
        .muhtar-card { background: linear-gradient(135deg, var(--dark), #1e293b); border-radius: 30px; padding: 50px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(15,23,42,0.15); }
        .muhtar-card::after { content: '\f0a1'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -30px; bottom: -50px; font-size: 15rem; color: rgba(255,255,255,0.02); }
        .m-img-wrapper { position: relative; display: inline-block; }
        .m-img-wrapper img { width: 220px; height: 220px; border-radius: 24px; object-fit: cover; border: 5px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .m-badge { position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: #fff; padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 0.9rem; white-space: nowrap; box-shadow: 0 10px 20px rgba(21,128,61,0.4); }
        
        .aza-card { background: var(--light); border-radius: 20px; padding: 25px; text-align: center; border: 1px solid #e2e8f0; transition: 0.4s; }
        .aza-card:hover { background: #fff; transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); border-color: var(--primary); }
        .aza-card img { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 20px; border: 3px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1); object-fit: cover; }
        .aza-card h5 { font-weight: 800; color: var(--dark); margin-bottom: 5px; font-size: 1.1rem; }
        .aza-card p { color: var(--primary); font-weight: 700; font-size: 0.9rem; margin: 0; }
        
        footer { background: var(--dark); color: #cbd5e1; padding: 60px 0 30px 0; }
        .f-title { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 25px; }
        .f-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 600; transition: 0.3s; }
        .f-link:hover { color: var(--primary); transform: translateX(5px); }

        @media (max-width: 991px) {
            .navbar-collapse { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); position: absolute; top: 100%; left: 15px; right: 15px; border: 1px solid rgba(0,0,0,0.05); }
            .nav-link { text-align: center; padding: 15px !important; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; }
            .nav-link:last-child { border-bottom: none; }
            .dropdown-menu { border: none; box-shadow: none; background: #f8fafc; margin-top: 10px; }
            .btn-custom { display: flex; width: 100%; justify-content: center; padding: 15px; font-size: 1.1rem; margin-top: 15px; }
            .page-title { font-size: 2.8rem; }
            .history-img { min-height: 250px; margin-bottom: 20px; }
            .muhtar-card { text-align: center; padding: 40px 20px; }
            .m-img-wrapper { margin-bottom: 40px; }
            .m-img-wrapper img { width: 180px; height: 180px; }
            .team-section { padding: 60px 0; }
            footer { padding: 40px 0 20px 0; text-align: center; }
            .f-title { margin-top: 20px; }
        }
    </style>
</head>
<body>

<div class="top-bar d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex gap-4 fw-bold">
            <span><i class="fa-solid fa-phone me-2 text-success"></i> <?= htmlspecialchars($site['telefon']) ?></span>
            <span><i class="fa-solid fa-location-dot me-2 text-success"></i> Kozdere Meydanı, Merkez</span>
        </div>
        <div class="d-flex gap-3 fw-bold">
            <a href="login.php"><i class="fa-solid fa-shield-halved me-1"></i> Admin</a>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span>.</span></a>
        <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="fa-solid fa-bars-staggered fs-1 text-dark"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="index.php">Ana Sayfa</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Köyümüz</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="kurumsal.php"><i class="fa-solid fa-landmark me-2 text-primary"></i>Tarihçe & Bilgi</a></li>
                        <li><a class="dropdown-item" href="soyagaci.php"><i class="fa-solid fa-sitemap me-2 text-success"></i>Soyağacı</a></li>
                        <li><a class="dropdown-item" href="dernek.php"><i class="fa-solid fa-handshake-angle me-2 text-warning"></i>Dernek Yönetimi</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">İlanlar</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="haberler.php"><i class="fa-solid fa-newspaper me-2 text-primary"></i>Tüm Haberler</a></li>
                        <li><a class="dropdown-item" href="vefatlar.php"><i class="fa-solid fa-book-prayers me-2 text-dark"></i>Vefat & Taziye</a></li>
                        <li><a class="dropdown-item" href="dugunler.php"><i class="fa-solid fa-ring me-2 text-danger"></i>Düğün & Cemiyet</a></li>
                    </ul>
                </li>
                
                <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="pazar.php">E-Pazar</a></li>
            </ul>
            
            <?php if (isset($_SESSION['uye_id'])): ?>
                <a href="profil.php" class="btn-custom bg-dark"><i class="fa-solid fa-user-check"></i> Profilim</a>
            <?php else: ?>
                <a href="kayit.php" class="btn-custom"><i class="fa-solid fa-user-plus"></i> Kayıt / Giriş</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1 class="page-title" data-aos="fade-down">Kurumsal Kimlik</h1>
        <div class="breadcrumb-custom" data-aos="fade-up" data-aos-delay="100">
            <a href="index.php">Ana Sayfa</a>
            <span><i class="fa-solid fa-chevron-right mx-2" style="font-size: 0.8rem;"></i></span>
            <span class="text-white">Köyümüz & Tarihçe</span>
        </div>
    </div>
</div>

<div class="container py-5 mt-5">
    <div class="row g-5 align-items-center mb-5 pb-5">
        <div class="col-lg-6" data-aos="fade-right">
            <img src="https://placehold.co/800x600/1e293b/fff?text=Kozdere+Tarih" alt="Tarihçe" class="history-img">
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <h2 class="section-title"><?= htmlspecialchars($kurumsal_icerik['tarihce_baslik']) ?></h2>
            <p class="history-text mt-4"><?= htmlspecialchars($kurumsal_icerik['tarihce_metin']) ?></p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="#" class="btn btn-outline-success fw-bold px-4 py-3 rounded-3 w-sm-100"><i class="fa-solid fa-images me-2"></i>Eski Fotoğraflar</a>
                <a href="soyagaci.php" class="btn btn-dark fw-bold px-4 py-3 rounded-3 w-sm-100"><i class="fa-solid fa-sitemap me-2"></i>Sülaleler</a>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-5 pb-5">
        <div class="col-12" data-aos="fade-up">
            <h3 class="fw-bold mb-4" style="color: var(--dark);">Coğrafya ve Konum</h3>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="geo-card">
                <div class="geo-icon"><i class="fa-solid fa-mountain-city"></i></div>
                <div class="geo-info">
                    <h5>İl Merkezine</h5>
                    <p><?= htmlspecialchars($kurumsal_icerik['koy_merkez_uzaklik']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="geo-card">
                <div class="geo-icon"><i class="fa-solid fa-building"></i></div>
                <div class="geo-info">
                    <h5>İlçe Merkezine</h5>
                    <p><?= htmlspecialchars($kurumsal_icerik['koy_ilce_uzaklik']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="geo-card">
                <div class="geo-icon"><i class="fa-solid fa-cloud-sun"></i></div>
                <div class="geo-info">
                    <h5>Köy Rakımı</h5>
                    <p><?= htmlspecialchars($kurumsal_icerik['rakim']) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="geo-card">
                <div class="geo-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <div class="geo-info">
                    <h5>Komşular</h5>
                    <p style="font-size: 0.85rem;"><?= htmlspecialchars($kurumsal_icerik['komsu_koyler']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="team-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title" style="margin-bottom: 0;">Köy Yönetimi</h2>
            <p class="text-secondary mt-3 fw-medium">Hizmet için el ele, gönül gönüle veren yönetim kadromuz.</p>
        </div>
        
        <div class="row justify-content-center mb-5 pb-4">
            <div class="col-lg-10" data-aos="zoom-in">
                <div class="muhtar-card">
                    <div class="row align-items-center">
                        <div class="col-lg-4 text-center">
                            <div class="m-img-wrapper">
                                <img src="<?= $muhtar['resim'] ?>" alt="Muhtar">
                                <div class="m-badge">Muhtar</div>
                            </div>
                        </div>
                        <div class="col-lg-8 text-center text-lg-start">
                            <h3 class="fw-bold mb-1" style="font-size: 2.5rem; letter-spacing: -1px;"><?= htmlspecialchars($muhtar['isim']) ?></h3>
                            <p class="text-success fw-bold text-uppercase letter-spacing-1 mb-4"><?= htmlspecialchars($muhtar['unvan']) ?></p>
                            <p class="fs-5 mb-4 opacity-75 fw-medium" style="line-height: 1.6; font-style: italic;">"<?= htmlspecialchars($muhtar['mesaj']) ?>"</p>
                            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                                <a href="tel:<?= htmlspecialchars($muhtar['telefon']) ?>" class="btn btn-success fw-bold px-4 py-2 rounded-3 w-sm-100"><i class="fa-solid fa-phone me-2"></i>Muhtarı Ara</a>
                                <a href="#" class="btn btn-outline-light fw-bold px-4 py-2 rounded-3 w-sm-100"><i class="fa-solid fa-envelope me-2"></i>Mesaj Bırak</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-12 text-center mb-3" data-aos="fade-up">
                <h4 class="fw-bold" style="color: var(--dark);">İhtiyar Heyeti (Azalar)</h4>
            </div>
            <?php foreach($azalar as $i => $aza): ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
                <div class="aza-card">
                    <img src="<?= htmlspecialchars($aza['resim']) ?>" alt="<?= htmlspecialchars($aza['isim']) ?>">
                    <h5><?= htmlspecialchars($aza['isim']) ?></h5>
                    <p><?= htmlspecialchars($aza['unvan']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="mb-4 text-secondary fw-medium">Kozdere Köyü Muhtarlığı ve Yardımlaşma Derneği resmi iletişim portalı.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Kısayollar</h4>
                <a href="kurumsal.php" class="f-link">Köy Tarihçesi</a>
                <a href="soyagaci.php" class="f-link">Soyağacı</a>
                <a href="pazar.php" class="f-link">Köy Pazarı</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">İletişim</h4>
                <p class="mb-2 text-secondary fw-medium"><i class="fa-solid fa-location-dot me-2 text-success"></i> Kozdere Meydanı, Merkez</p>
                <p class="mb-2 text-secondary fw-medium"><i class="fa-solid fa-phone me-2 text-success"></i> <?= htmlspecialchars($site['telefon']) ?></p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.getElementById('navbar').classList.add('sticky');
        } else {
            document.getElementById('navbar').classList.remove('sticky');
        }
    });
</script>
</body>
</html>