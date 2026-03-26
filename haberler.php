<?php
require_once 'baglan.php';

$haber_listesi = [
    ['id' => 1, 'tip' => 'duyuru', 'baslik' => 'Su Kesintisi Uyarısı', 'ozet' => 'Ana su deposundaki planlı bakım ve temizlik çalışmaları nedeniyle yarın sabahtan itibaren su kesintisi yaşanacaktır. Lütfen tedbirli olunuz.', 'tarih' => '25 Mart 2026', 'ikon' => 'fa-bullhorn', 'renk' => 'warning', 'resim' => 'https://placehold.co/800x500/ca8a04/fff?text=Duyuru'],
    ['id' => 2, 'tip' => 'haber', 'baslik' => 'Köy Yolları Asfaltlanıyor', 'ozet' => 'İl Özel İdaresi işbirliği ile köyümüzün ana giriş yolları ve meydan bağlantılarında asfaltlama çalışmaları hızla devam ediyor.', 'tarih' => '20 Mart 2026', 'ikon' => 'fa-road', 'renk' => 'primary', 'resim' => 'https://placehold.co/800x500/2563eb/fff?text=Haber'],
    ['id' => 3, 'tip' => 'etkinlik', 'baslik' => '14. Geleneksel Yayla Şenliği', 'ozet' => 'Her yıl coşkuyla kutladığımız yayla şenliğimiz bu hafta sonu gerçekleştirilecektir. Tüm hemşehrilerimiz davetlidir.', 'tarih' => '18 Mart 2026', 'ikon' => 'fa-tents', 'renk' => 'success', 'resim' => 'https://placehold.co/800x500/16a34a/fff?text=Etkinlik'],
    ['id' => 4, 'tip' => 'haber', 'baslik' => 'Yeni Trafo Merkezi Kuruldu', 'ozet' => 'Kış aylarında yaşanan elektrik kesintilerinin önüne geçmek için köye yeni nesil yüksek kapasiteli trafo merkezi kuruldu.', 'tarih' => '10 Mart 2026', 'ikon' => 'fa-bolt', 'renk' => 'primary', 'resim' => 'https://placehold.co/800x500/2563eb/fff?text=Haber']
];

if (isset($db) && $db) {
    $sorgu = $db->query("SELECT * FROM haberler WHERE tip IN ('haber', 'duyuru', 'etkinlik') AND durum = 1 ORDER BY id DESC")->fetchAll();
    if ($sorgu && count($sorgu) > 0) {
        $haber_listesi = $sorgu;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Haberler & Duyurular - <?= htmlspecialchars($site['baslik']) ?></title>
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
        .dropdown-item:hover, .dropdown-item.active { background: rgba(21,128,61,0.05); color: var(--primary); transform: translateX(5px); }
        .btn-custom { background: var(--primary); color: #fff; font-weight: 800; padding: 12px 28px; border-radius: 12px; text-decoration: none; transition: 0.4s; display: inline-flex; align-items: center; gap: 8px; border: none; }
        .btn-custom:hover { background: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(21,128,61,0.3); color: #fff; }
        
        .page-header { background: linear-gradient(rgba(15,23,42,0.9), rgba(15,23,42,0.95)), url('https://placehold.co/1920x400/0f172a/fff?text=Haberler') center/cover; padding: 100px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3rem; letter-spacing: -1px; margin-bottom: 15px; }

        .filter-container { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin: -30px auto 40px auto; position: relative; z-index: 10; padding: 0 15px; }
        .filter-btn { background: #fff; border: 1px solid rgba(0,0,0,0.05); color: var(--gray); font-weight: 800; padding: 15px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: 0.3s; cursor: pointer; display: flex; align-items: center; gap: 10px; }
        .filter-btn.active, .filter-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); transform: translateY(-3px); }

        .news-card { background: #fff; border-radius: 24px; overflow: hidden; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s; height: 100%; display: flex; flex-direction: column; text-decoration: none; color: inherit; }
        .news-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .n-img-wrap { position: relative; height: 220px; overflow: hidden; }
        .n-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .news-card:hover .n-img { transform: scale(1.05); }
        .n-badge { position: absolute; top: 15px; left: 15px; padding: 6px 15px; border-radius: 10px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .bg-haber { background: #2563eb; }
        .bg-duyuru { background: #ca8a04; }
        .bg-etkinlik { background: #16a34a; }
        .n-body { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .n-meta { color: var(--gray); font-size: 0.9rem; font-weight: 600; margin-bottom: 12px; display: flex; gap: 15px; }
        .n-meta i { color: var(--primary); }
        .n-title { font-weight: 800; font-size: 1.3rem; color: var(--dark); margin-bottom: 15px; line-height: 1.4; }
        .n-desc { color: var(--gray); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; flex-grow: 1; }
        .n-readmore { font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 5px; transition: 0.3s; margin-top: auto; }
        .news-card:hover .n-readmore { color: var(--dark); gap: 10px; }

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
            .filter-container { flex-direction: column; padding: 0 20px; }
            .filter-btn { justify-content: center; width: 100%; }
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
                        <li><a class="dropdown-item active" href="haberler.php"><i class="fa-solid fa-newspaper me-2 text-primary"></i>Tüm Haberler</a></li>
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
        <h1 class="page-title" data-aos="zoom-in">Köyden Haberler</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Köyümüzdeki son gelişmeleri ve etkinlikleri takip edin.</p>
    </div>
</div>

<div class="filter-container" data-aos="fade-up" data-aos-delay="200">
    <button class="filter-btn active" onclick="filterNews('all')"><i class="fa-solid fa-layer-group"></i> Tümü</button>
    <button class="filter-btn" onclick="filterNews('haber')"><i class="fa-solid fa-newspaper text-primary"></i> Haberler</button>
    <button class="filter-btn" onclick="filterNews('duyuru')"><i class="fa-solid fa-bullhorn text-warning"></i> Duyurular</button>
    <button class="filter-btn" onclick="filterNews('etkinlik')"><i class="fa-solid fa-tents text-success"></i> Etkinlikler</button>
</div>

<div class="container py-4 mb-5">
    <div class="row g-4" id="newsGrid">
        <?php foreach($haber_listesi as $i => $h): 
            $badge_class = 'bg-haber';
            if ($h['tip'] == 'duyuru') $badge_class = 'bg-duyuru';
            if ($h['tip'] == 'etkinlik') $badge_class = 'bg-etkinlik';
            $resim = isset($h['resim']) ? $h['resim'] : 'https://placehold.co/800x500/1e293b/fff?text='.ucfirst($h['tip']);
        ?>
        <div class="col-lg-4 col-md-6 news-item <?= htmlspecialchars($h['tip']) ?>" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
            <a href="haber_detay.php?id=<?= $h['id'] ?>" class="news-card">
                <div class="n-img-wrap">
                    <div class="n-badge <?= $badge_class ?>"><i class="fa-solid <?= htmlspecialchars($h['ikon'] ?? 'fa-newspaper') ?> me-1"></i> <?= htmlspecialchars($h['tip']) ?></div>
                    <img src="<?= htmlspecialchars($resim) ?>" alt="<?= htmlspecialchars($h['baslik']) ?>" class="n-img">
                </div>
                <div class="n-body">
                    <div class="n-meta">
                        <span><i class="fa-regular fa-calendar-check me-1"></i> <?= htmlspecialchars($h['tarih']) ?></span>
                    </div>
                    <h3 class="n-title"><?= htmlspecialchars($h['baslik']) ?></h3>
                    <p class="n-desc"><?= mb_substr(htmlspecialchars($h['ozet']), 0, 120, 'UTF-8') ?>...</p>
                    <div class="n-readmore">Devamını Oku <i class="fa-solid fa-arrow-right"></i></div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4 text-center text-lg-start">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="mb-4 text-secondary fw-medium">Kozdere Köyü Muhtarlığı ve Yardımlaşma Derneği resmi iletişim portalı.</p>
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

    function filterNews(tip) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        
        let items = document.querySelectorAll('.news-item');
        items.forEach(item => {
            if (tip === 'all') {
                item.style.display = 'block';
            } else {
                if (item.classList.contains(tip)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }
</script>
</body>
</html>