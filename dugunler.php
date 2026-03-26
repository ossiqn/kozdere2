<?php
require_once 'baglan.php';

$arama = isset($_GET['q']) ? trim($_GET['q']) : '';
$dugunler = [];

if (isset($db) && $db) {
    if ($arama != '') {
        $sorgu = $db->prepare("SELECT * FROM haberler WHERE tip = 'dugun' AND durum = 1 AND (baslik LIKE ? OR ozet LIKE ?) ORDER BY id DESC");
        $sorgu->execute(["%$arama%", "%$arama%"]);
        $dugunler = $sorgu->fetchAll();
    } else {
        $dugun_sorgu = $db->query("SELECT * FROM haberler WHERE tip = 'dugun' AND durum = 1 ORDER BY id DESC")->fetchAll();
        if ($dugun_sorgu && count($dugun_sorgu) > 0) {
            $dugunler = $dugun_sorgu;
        }
    }
}

if (empty($dugunler) && $arama == '') {
    $dugunler = [
        ['id' => 0, 'baslik' => 'Ayşe & Ali', 'ozet' => 'Genç çiftimizin düğün merasimine tüm köylülerimiz davetlidir. Düğünümüz köy meydanında yemekli olarak yapılacaktır.', 'tarih' => '28 Mart 2026', 'resim' => 'https://placehold.co/800x600/ec4899/fff?text=Ayse+%26+Ali'],
        ['id' => 0, 'baslik' => 'Fatma & Mehmet (Nişan)', 'ozet' => 'Kızımız Fatma ile oğlumuz Mehmet\'in nişan yüzükleri takılacaktır. Bu mutlu günümüzde sizleri de aramızda görmek isteriz.', 'tarih' => '05 Nisan 2026', 'resim' => 'https://placehold.co/800x600/db2777/fff?text=Fatma+%26+Mehmet']
    ];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Düğün & Cemiyet - <?= htmlspecialchars($site['baslik']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #15803d; --primary-hover: #166534; --dark: #0f172a; --light: #f8fafc; --gray: #64748b; --pink: #ec4899; --pink-light: #fdf2f8; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--light); color: var(--dark); overflow-x: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: var(--pink); border-radius: 10px; }
        
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
        .dropdown-item:hover, .dropdown-item.active { background: rgba(21,128,61,0.05); color: var(--primary); transform: translateX(5px); }
        
        .btn-custom { background: var(--primary); color: #fff; font-weight: 800; padding: 12px 28px; border-radius: 12px; text-decoration: none; transition: 0.4s; display: inline-flex; align-items: center; gap: 8px; border: none; }
        .btn-custom:hover { background: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(21,128,61,0.3); color: #fff; }
        
        .page-header { background: linear-gradient(135deg, rgba(15,23,42,0.85), rgba(236,72,153,0.85)), url('https://placehold.co/1920x400/ec4899/fff?text=Dugunler') center/cover; padding: 100px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3.2rem; letter-spacing: -1px; margin-bottom: 15px; }

        .search-wrapper { max-width: 600px; margin: -30px auto 40px auto; position: relative; z-index: 10; padding: 0 15px; }
        .search-box { background: #fff; border-radius: 50px; padding: 8px; display: flex; align-items: center; box-shadow: 0 15px 35px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        .search-input { border: none; padding: 12px 20px; width: 100%; border-radius: 50px; outline: none; font-weight: 500; font-size: 1.05rem; }
        .search-btn { background: var(--pink); color: #fff; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 800; transition: 0.3s; }
        .search-btn:hover { background: #be185d; }

        .wedding-card { background: #fff; border-radius: 24px; overflow: hidden; border: 2px solid var(--pink-light); box-shadow: 0 10px 30px rgba(236,72,153,0.05); transition: 0.4s; height: 100%; display: flex; flex-direction: column; position: relative; }
        .wedding-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(236,72,153,0.15); border-color: var(--pink); }
        
        .w-img-wrap { position: relative; height: 260px; overflow: hidden; border-bottom: 4px solid var(--pink); }
        .w-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .wedding-card:hover .w-img { transform: scale(1.05); filter: brightness(1.05); }
        
        .w-badge { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); color: var(--pink); padding: 8px 15px; border-radius: 12px; font-weight: 900; font-size: 0.9rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); backdrop-filter: blur(5px); }
        
        .w-body { padding: 30px 25px; flex-grow: 1; display: flex; flex-direction: column; text-align: center; }
        .w-icon { width: 50px; height: 50px; background: var(--pink-light); color: var(--pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: -55px auto 15px auto; position: relative; z-index: 2; border: 4px solid #fff; }
        
        .w-title { font-weight: 900; font-size: 1.8rem; color: var(--dark); margin-bottom: 15px; font-family: 'Georgia', serif; letter-spacing: 0.5px; }
        .w-desc { color: var(--gray); font-size: 1rem; line-height: 1.6; margin-bottom: 25px; flex-grow: 1; font-weight: 500; }
        
        .w-meta { background: var(--pink-light); border-radius: 16px; padding: 15px; display: flex; justify-content: center; align-items: center; gap: 10px; font-weight: 800; color: var(--pink); margin-top: auto; }
        
        footer { background: var(--dark); color: #cbd5e1; padding: 60px 0 30px 0; margin-top: 80px; }
        .f-title { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 25px; }
        .f-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 600; transition: 0.3s; }
        .f-link:hover { color: var(--primary); transform: translateX(5px); }

        @media (max-width: 991px) {
            .navbar-collapse { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); position: absolute; top: 100%; left: 15px; right: 15px; border: 1px solid rgba(0,0,0,0.05); }
            .nav-link { text-align: center; padding: 15px !important; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; }
            .dropdown-menu { border: none; box-shadow: none; background: #f8fafc; margin-top: 10px; }
            .btn-custom { display: flex; width: 100%; justify-content: center; padding: 15px; font-size: 1.1rem; margin-top: 15px; }
            .page-title { font-size: 2.5rem; }
            .search-box { flex-direction: column; border-radius: 20px; padding: 15px; }
            .search-input { margin-bottom: 10px; }
            .search-btn { width: 100%; border-radius: 12px; }
            .w-img-wrap { height: 220px; }
            footer { text-align: center; }
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
        <div class="d-flex gap-3 fw-bold"><a href="login.php"><i class="fa-solid fa-shield-halved me-1"></i> Admin</a></div>
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
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Köyümüz</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="kurumsal.php"><i class="fa-solid fa-landmark me-2 text-primary"></i>Tarihçe & Bilgi</a></li>
                        <li><a class="dropdown-item" href="soyagaci.php"><i class="fa-solid fa-sitemap me-2 text-success"></i>Soyağacı</a></li>
                        <li><a class="dropdown-item" href="dernek.php"><i class="fa-solid fa-handshake-angle me-2 text-warning"></i>Dernek Yönetimi</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">İlanlar</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="haberler.php"><i class="fa-solid fa-newspaper me-2 text-primary"></i>Tüm Haberler</a></li>
                        <li><a class="dropdown-item" href="vefatlar.php"><i class="fa-solid fa-book-prayers me-2 text-dark"></i>Vefat & Taziye</a></li>
                        <li><a class="dropdown-item active" href="dugunler.php"><i class="fa-solid fa-ring me-2 text-danger"></i>Düğün & Cemiyet</a></li>
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
        <h1 class="page-title" data-aos="zoom-in">Düğün & Cemiyet İlanları</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Mutluluklar paylaştıkça çoğalır, sevincimize ortak olun.</p>
    </div>
</div>

<div class="search-wrapper" data-aos="fade-up" data-aos-delay="200">
    <form action="dugunler.php" method="GET" class="search-box">
        <input type="text" name="q" class="search-input" placeholder="Çift ismi veya sülale ara..." value="<?= htmlspecialchars($arama) ?>">
        <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass me-2"></i>Ara</button>
    </form>
</div>

<div class="container py-4 mb-5">
    <div class="row justify-content-center g-4">
        <?php if (empty($dugunler)): ?>
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-rings-wedding fs-1 text-secondary mb-3 opacity-50"></i>
                <h3 class="fw-bold text-dark">Sonuç Bulunamadı</h3>
                <p class="text-secondary">Aradığınız kriterlere uygun bir düğün veya cemiyet ilanı bulunmuyor.</p>
                <a href="dugunler.php" class="btn btn-outline-dark mt-3 fw-bold rounded-pill px-4">Tüm İlanları Gör</a>
            </div>
        <?php else: ?>
            <?php foreach($dugunler as $i => $d): 
                $resim = isset($d['resim']) && $d['resim'] != '' ? $d['resim'] : 'https://placehold.co/800x600/ec4899/fff?text='.urlencode($d['baslik']);
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
                <div class="wedding-card">
                    <div class="w-img-wrap">
                        <div class="w-badge"><i class="fa-regular fa-calendar-heart me-1"></i> Davetiye</div>
                        <img src="<?= htmlspecialchars($resim) ?>" alt="<?= htmlspecialchars($d['baslik']) ?>" class="w-img">
                    </div>
                    <div class="w-body">
                        <div class="w-icon"><i class="fa-solid fa-ring"></i></div>
                        <h2 class="w-title"><?= htmlspecialchars($d['baslik']) ?></h2>
                        <p class="w-desc"><?= htmlspecialchars($d['ozet']) ?></p>
                        <div class="w-meta">
                            <i class="fa-regular fa-clock"></i> Tarih: <?= htmlspecialchars($d['tarih']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4 text-center text-lg-start">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="mb-4 text-secondary fw-medium">Kozdere Köyü Muhtarlığı resmi cemiyet ve etkinlik takip sayfasıdır.</p>
            </div>
            <div class="col-lg-4">
                <h4 class="f-title">Kısayollar</h4>
                <a href="kurumsal.php" class="f-link">Köy Tarihçesi</a>
                <a href="soyagaci.php" class="f-link">Soyağacı</a>
                <a href="pazar.php" class="f-link">Köy Pazarı</a>
            </div>
            <div class="col-lg-4">
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
        if (window.scrollY > 50) { document.getElementById('navbar').classList.add('sticky'); }
        else { document.getElementById('navbar').classList.remove('sticky'); }
    });
</script>
</body>
</html>