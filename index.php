<?php
require_once 'baglan.php';

$slider = [
    ['resim' => 'https://placehold.co/1920x800/020617/ffffff?text=Kozdere+Genel+Bakis', 'baslik' => 'Kozdere\'nin Dijital Meydanı', 'alt_baslik' => 'Gurbetten sılaya uzanan, gelenekle teknolojinin buluştuğu nokta.', 'buton_text' => 'Köyü Keşfet', 'buton_link' => 'kurumsal.php'],
    ['resim' => 'https://placehold.co/1920x800/166534/ffffff?text=Yayla+Yolu', 'baslik' => 'Doğanın Kalbinde Yaşam', 'alt_baslik' => 'Köyümüzün eşsiz doğası ve temiz havası ile huzuru hissedin.', 'buton_text' => 'Soyağacı', 'buton_link' => 'soyagaci.php']
];

if (isset($db) && $db) {
    $slider_sorgu = $db->query("SELECT * FROM slider WHERE durum = 1 ORDER BY sira ASC")->fetchAll();
    if ($slider_sorgu && count($slider_sorgu) > 0) {
        $slider = $slider_sorgu;
    }
}

$haberler = [
    ['id' => 1, 'tip' => 'vefat', 'baslik' => 'Acı Kaybımız: Mehmet Yılmaz', 'ozet' => 'Köyümüz halkından Mehmet Yılmaz hakkın rahmetine kavuşmuştur.', 'tarih' => '26 Mart 2026', 'ikon' => 'fa-book-prayers', 'renk' => 'dark'],
    ['id' => 2, 'tip' => 'dugun', 'baslik' => 'Ayşe ve Ali Evleniyor', 'ozet' => 'Genç çiftimizin düğün merasimine tüm köylülerimiz davetlidir.', 'tarih' => '28 Mart 2026', 'ikon' => 'fa-ring', 'renk' => 'danger'],
    ['id' => 3, 'tip' => 'duyuru', 'baslik' => 'Köy Yolları Asfaltlanıyor', 'ozet' => 'Köy içi asfaltlama çalışmaları hızla devam ediyor.', 'tarih' => '20 Mart 2026', 'ikon' => 'fa-road', 'renk' => 'primary'],
    ['id' => 4, 'tip' => 'etkinlik', 'baslik' => '14. Geleneksel Yayla Şenliği', 'ozet' => 'Yayla şenliğimiz bu hafta sonu gerçekleştirilecektir.', 'tarih' => '18 Mart 2026', 'ikon' => 'fa-tents', 'renk' => 'success']
];

$kayan_duyuru = "ÖNEMLİ DUYURU: Köyümüz içme suyu hatlarında bakım çalışması yapılacaktır. Lütfen tedbirli olunuz.";
$popup_duyuru = ['baslik' => 'Dernek Toplantısı', 'icerik' => 'Bu pazar günü saat 14:00\'te köy konağında genel kurul toplantısı yapılacaktır. Tüm üyelerimizin katılımı rica olunur.'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($site['baslik']) ?> - Kurumsal Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
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
        .kayan-bant { background: #dc2626; color: #fff; padding: 8px 0; font-weight: 700; font-size: 0.9rem; letter-spacing: 0.5px; }
        .swiper-hero { width: 100%; height: 85vh; min-height: 600px; }
        .swiper-slide { background-size: cover; background-position: center; display: flex; align-items: center; position: relative; }
        .swiper-slide::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.5) 50%, rgba(15,23,42,0.1) 100%); }
        .slide-content { position: relative; z-index: 10; padding: 0 5%; }
        .slide-title { font-weight: 900; font-size: 4.5rem; color: #fff; line-height: 1.1; margin-bottom: 20px; letter-spacing: -1.5px; }
        .slide-desc { font-size: 1.25rem; color: #cbd5e1; font-weight: 400; max-width: 600px; margin-bottom: 40px; line-height: 1.6; }
        .quick-actions { margin-top: -60px; position: relative; z-index: 20; padding: 0 15px; }
        .action-card { background: #fff; border-radius: 20px; padding: 30px 20px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.06); text-decoration: none; color: var(--dark); transition: 0.4s; border: 1px solid rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; gap: 15px; height: 100%; }
        .a-icon { width: 70px; height: 70px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; transition: 0.4s; }
        .bg-i-1 { background: #eff6ff; color: #2563eb; }
        .bg-i-2 { background: #fef2f2; color: #dc2626; }
        .bg-i-3 { background: #fdf2f8; color: #ec4899; }
        .bg-i-4 { background: #fffbeb; color: #ca8a04; }
        .action-card:hover { transform: translateY(-10px); }
        .action-card:hover .a-icon { transform: scale(1.1); }
        .action-card span { font-weight: 800; font-size: 1.1rem; }
        .section-title { font-weight: 900; font-size: 2.2rem; margin: 0; color: var(--dark); letter-spacing: -1px; display: inline-block; position: relative; margin-bottom: 30px; }
        .section-title::after { content: ''; position: absolute; left: 0; bottom: -10px; width: 50px; height: 4px; background: var(--primary); border-radius: 10px; }
        .news-box { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s; height: 100%; display: flex; flex-direction: column; text-decoration: none; }
        .news-box:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .card-vefat { border: 2px solid #1e293b; background: #f8fafc; }
        .card-dugun { border: 2px solid #ec4899; background: #fdf2f8; }
        .card-genel { border: 1px solid #e2e8f0; }
        .n-badge { padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; width: fit-content; }
        .n-title { font-weight: 800; font-size: 1.2rem; margin-bottom: 12px; color: var(--dark); line-height: 1.4; }
        .n-desc { color: var(--gray); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; flex-grow: 1; }
        .widget-card { background: #fff; border-radius: 20px; padding: 25px; text-align: center; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 25px; }
        .w-title { font-weight: 800; font-size: 1.1rem; color: var(--dark); margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        .weather-day { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #f1f5f9; font-weight: 600; font-size: 0.9rem; }
        .weather-day:last-child { border-bottom: none; }
        .counter-box { display: flex; justify-content: space-between; padding: 12px 15px; background: #f8fafc; border-radius: 12px; margin-bottom: 10px; font-weight: 700; font-size: 0.95rem; color: var(--dark); }
        .poll-option { display: block; text-align: left; padding: 12px 15px; background: #f8fafc; border-radius: 12px; margin-bottom: 10px; cursor: pointer; font-weight: 600; border: 1px solid #e2e8f0; transition: 0.3s; }
        .poll-option:hover { border-color: var(--primary); background: rgba(21,128,61,0.05); }
        .whatsapp-float { position: fixed; bottom: 30px; right: 30px; background: #25D366; color: #fff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 10px 25px rgba(37,211,102,0.4); z-index: 9999; text-decoration: none; transition: 0.3s; }
        .whatsapp-float:hover { transform: scale(1.1); color: #fff; }
        footer { background: var(--dark); color: #cbd5e1; padding: 60px 0 30px 0; margin-top: 80px; }
        .f-title { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 25px; }
        .f-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 600; transition: 0.3s; }
        .f-link:hover { color: var(--primary); transform: translateX(5px); }
        @media (max-width: 991px) {
            .navbar-collapse { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); position: absolute; top: 100%; left: 15px; right: 15px; border: 1px solid rgba(0,0,0,0.05); }
            .nav-link { text-align: center; padding: 15px !important; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; }
            .nav-link:last-child { border-bottom: none; }
            .dropdown-menu { border: none; box-shadow: none; background: #f8fafc; margin-top: 10px; }
            .btn-custom { display: flex; width: 100%; justify-content: center; padding: 15px; font-size: 1.1rem; margin-top: 15px; }
            .swiper-hero { height: 75vh; min-height: 500px; }
            .slide-title { font-size: 2.5rem; letter-spacing: -0.5px; }
            .quick-actions { margin-top: 30px; padding: 0; }
            .action-card { padding: 20px; }
            footer { padding: 40px 0 20px 0; text-align: center; }
            .whatsapp-float { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 1.8rem; }
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
                <li class="nav-item"><a class="nav-link active" href="index.php">Ana Sayfa</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Köyümüz</a>
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

<div class="kayan-bant">
    <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"><?= htmlspecialchars($kayan_duyuru) ?></marquee>
</div>

<div class="swiper swiper-hero">
    <div class="swiper-wrapper">
        <?php foreach($slider as $s): ?>
        <div class="swiper-slide" style="background-image: url('<?= htmlspecialchars($s['resim']) ?>');">
            <div class="slide-content container">
                <h1 class="slide-title"><?= htmlspecialchars($s['baslik']) ?></h1>
                <p class="slide-desc"><?= htmlspecialchars($s['alt_baslik']) ?></p>
                <a href="<?= htmlspecialchars($s['buton_link']) ?>" class="btn-custom py-3 px-4 fs-5"><?= htmlspecialchars($s['buton_text']) ?></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
</div>

<div class="container quick-actions">
    <div class="row g-3 justify-content-center">
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <a href="soyagaci.php" class="action-card">
                <div class="a-icon bg-i-1"><i class="fa-solid fa-sitemap"></i></div>
                <span>Soyağacı</span>
            </a>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
            <a href="vefatlar.php" class="action-card">
                <div class="a-icon bg-i-2"><i class="fa-solid fa-book-prayers"></i></div>
                <span>Vefatlar</span>
            </a>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
            <a href="dugunler.php" class="action-card">
                <div class="a-icon bg-i-3"><i class="fa-solid fa-ring"></i></div>
                <span>Düğünler</span>
            </a>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
            <a href="dernek.php" class="action-card">
                <div class="a-icon bg-i-4"><i class="fa-solid fa-graduation-cap"></i></div>
                <span>Dernek</span>
            </a>
        </div>
    </div>
</div>

<div class="container mt-5 pt-4">
    <div class="row g-5">
        <div class="col-lg-8">
            <h2 class="section-title" data-aos="fade-right">Son İlanlar & Duyurular</h2>
            <div class="row g-4 mt-1">
                <?php foreach($haberler as $i => $h): 
                    $card_class = 'card-genel';
                    if($h['tip'] == 'vefat') $card_class = 'card-vefat';
                    if($h['tip'] == 'dugun') $card_class = 'card-dugun';
                ?>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
                    <a href="haber_detay.php?id=<?= $h['id'] ?>" class="news-box <?= $card_class ?>">
                        <div class="n-badge bg-<?= $h['renk'] ?> text-white bg-opacity-10 text-<?= $h['renk'] ?>" style="background-color: var(--bs-<?= $h['renk'] ?>)!important; color: <?= $h['tip'] == 'vefat' ? '#fff' : '' ?>!important;">
                            <i class="fa-solid <?= $h['ikon'] ?> me-1"></i> <?= $h['tip'] ?>
                        </div>
                        <h3 class="n-title"><?= htmlspecialchars($h['baslik']) ?></h3>
                        <p class="n-desc"><?= htmlspecialchars($h['ozet']) ?></p>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                            <span class="text-<?= $h['renk'] ?> fw-bold small"><i class="fa-regular fa-clock me-1"></i><?= $h['tarih'] ?></span>
                            <span class="text-dark fw-bold small">İncele <i class="fa-solid fa-chevron-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left">
            <div class="widget-card">
                <h4 class="w-title"><i class="fa-solid fa-cloud-sun me-2 text-warning"></i>Hava Durumu</h4>
                <div class="weather-day"><span>Bugün</span><span><i class="fa-solid fa-sun text-warning me-2"></i> 18°C</span></div>
                <div class="weather-day"><span>Yarın</span><span><i class="fa-solid fa-cloud text-secondary me-2"></i> 16°C</span></div>
                <div class="weather-day"><span>Çarşamba</span><span><i class="fa-solid fa-cloud-rain text-primary me-2"></i> 14°C</span></div>
                <div class="weather-day"><span>Perşembe</span><span><i class="fa-solid fa-sun text-warning me-2"></i> 19°C</span></div>
                <div class="weather-day"><span>Cuma</span><span><i class="fa-solid fa-cloud-sun text-warning me-2"></i> 20°C</span></div>
            </div>

            <div class="widget-card">
                <h4 class="w-title"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Köy Anketi</h4>
                <p class="small fw-bold text-secondary mb-3">Köy meydanına ne yapılmalı?</p>
                <form action="#" method="POST">
                    <label class="poll-option"><input type="radio" name="anket" value="1" class="me-2"> Çocuk Parkı</label>
                    <label class="poll-option"><input type="radio" name="anket" value="2" class="me-2"> Çay Bahçesi</label>
                    <label class="poll-option"><input type="radio" name="anket" value="3" class="me-2"> Kapalı Çardak</label>
                    <button type="submit" class="btn btn-primary w-100 fw-bold mt-2 rounded-3">Oy Ver</button>
                </form>
            </div>

            <div class="widget-card">
                <h4 class="w-title"><i class="fa-solid fa-chart-line me-2 text-success"></i>Ziyaretçi Sayacı</h4>
                <div class="counter-box"><span>Bugün</span><span class="text-success">145</span></div>
                <div class="counter-box"><span>Bu Ay</span><span class="text-primary">3.250</span></div>
                <div class="counter-box"><span>Toplam</span><span class="text-dark">45.890</span></div>
            </div>
        </div>
    </div>
</div>

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

<div class="modal fade" id="mansetPopup" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="popupKapat()"></button>
            </div>
            <div class="modal-body text-center p-4 pt-0">
                <i class="fa-solid fa-bell text-warning mb-3" style="font-size: 3rem;"></i>
                <h3 class="fw-bold mb-3"><?= htmlspecialchars($popup_duyuru['baslik']) ?></h3>
                <p class="text-secondary fw-medium fs-5 mb-4"><?= htmlspecialchars($popup_duyuru['icerik']) ?></p>
                <button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill" data-bs-dismiss="modal" onclick="popupKapat()">Anladım, Kapat</button>
            </div>
        </div>
    </div>
</div>

<a href="https://wa.me/<?= htmlspecialchars($site['whatsapp']) ?>" class="whatsapp-float" target="_blank">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 50, duration: 800 });
    
    new Swiper('.swiper-hero', {
        loop: true,
        effect: 'fade',
        speed: 1000,
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true }
    });
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.getElementById('navbar').classList.add('sticky');
        } else {
            document.getElementById('navbar').classList.remove('sticky');
        }
    });

    let popup = document.getElementById('mansetPopup');
    let sonGosterim = localStorage.getItem('popupZamani');
    let simdi = new Date().getTime();
    if (!sonGosterim || simdi - sonGosterim > 86400000) {
        let popupModal = new bootstrap.Modal(popup);
        popupModal.show();
    }
    function popupKapat() {
        localStorage.setItem('popupZamani', new Date().getTime());
    }
</script>
</body>
</html>