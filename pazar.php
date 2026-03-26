<?php
require_once 'baglan.php';

$urunler = [
    ['isim' => 'Hakiki Çiçek Balı', 'fiyat' => '450 ₺', 'satici' => 'Ahmet Usta (Arıcı)', 'telefon' => '905551112233', 'resim' => 'https://placehold.co/600x400/d97706/fff?text=Cicek+Bali', 'birim' => '1 Kg Kavanoz'],
    ['isim' => 'Organik Köy Peyniri', 'fiyat' => '200 ₺', 'satici' => 'Fatma Teyze', 'telefon' => '905552223344', 'resim' => 'https://placehold.co/600x400/15803d/fff?text=Koy+Peyniri', 'birim' => 'Tekerlek'],
    ['isim' => 'Ev Yapımı Tarhana', 'fiyat' => '180 ₺', 'satici' => 'Ayşe Hanım', 'telefon' => '905553334455', 'resim' => 'https://placehold.co/600x400/be123c/fff?text=Tarhana', 'birim' => '1 Kg Paket'],
    ['isim' => 'Cevizli Sucuk', 'fiyat' => '250 ₺', 'satici' => 'Mehmet Amca', 'telefon' => '905554445566', 'resim' => 'https://placehold.co/600x400/78350f/fff?text=Cevizli+Sucuk', 'birim' => 'Kg']
];

if (isset($db) && $db) {
    $pazar_sorgu = $db->query("SELECT * FROM pazar WHERE durum = 1 ORDER BY id DESC")->fetchAll();
    if ($pazar_sorgu && count($pazar_sorgu) > 0) {
        $urunler = $pazar_sorgu;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Köy Pazarı - <?= htmlspecialchars($site['baslik']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #15803d; --primary-hover: #166534; --dark: #0f172a; --light: #f8fafc; --gray: #64748b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--light); color: var(--dark); overflow-x: hidden; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
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
        
        .page-header { background: linear-gradient(135deg, rgba(15,23,42,0.85), rgba(21,128,61,0.85)), url('https://placehold.co/1920x500/1e293b/fff?text=Koy+Pazari') center/cover; padding: 100px 0 120px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3.5rem; letter-spacing: -1px; margin-bottom: 15px; }
        
        .market-info-box { background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); margin-top: -60px; position: relative; z-index: 10; border: 1px solid rgba(0,0,0,0.03); }
        .info-item { display: flex; align-items: center; gap: 15px; justify-content: center; }
        .info-icon { width: 60px; height: 60px; background: rgba(21,128,61,0.1); color: var(--primary); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
        .info-text h5 { margin: 0 0 5px 0; font-weight: 800; font-size: 1.15rem; color: var(--dark); }
        .info-text p { margin: 0; color: var(--gray); font-size: 0.95rem; font-weight: 600; }

        .product-card { background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.03); transition: 0.4s; height: 100%; display: flex; flex-direction: column; position: relative; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(0,0,0,0.08); border-color: rgba(21,128,61,0.2); }
        .product-img-wrap { position: relative; overflow: hidden; padding-top: 70%; }
        .product-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .product-card:hover .product-img { transform: scale(1.05); }
        .product-price-badge { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); backdrop-filter: blur(5px); color: var(--primary); font-weight: 900; padding: 8px 15px; border-radius: 12px; font-size: 1.2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-body { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .product-category { font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; }
        .product-title { font-weight: 800; font-size: 1.3rem; margin-bottom: 15px; color: var(--dark); line-height: 1.3; }
        .seller-info { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
        .seller-avatar { width: 45px; height: 45px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--gray); font-size: 1.2rem; }
        .seller-name { font-weight: 800; color: var(--dark); margin: 0; font-size: 1rem; }
        .seller-unit { color: var(--gray); font-size: 0.85rem; margin: 0; font-weight: 600; }
        .btn-whatsapp { background: #25D366; color: #fff; font-weight: 800; padding: 14px; border-radius: 14px; text-align: center; text-decoration: none; transition: 0.3s; display: block; width: 100%; border: none; margin-top: auto; font-size: 1.05rem; }
        .btn-whatsapp:hover { background: #128C7E; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(37,211,102,0.3); color: #fff; }

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
            
            .page-title { font-size: 2.5rem; }
            .market-info-box { padding: 30px 20px; text-align: center; }
            .info-item { flex-direction: column; text-align: center; margin-bottom: 25px; gap: 10px; }
            .info-item:last-child { margin-bottom: 0; }
            .info-icon { margin: 0 auto; }
            
            footer { padding: 40px 0 20px 0; text-align: center; }
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
                <li class="nav-item"><a class="nav-link active" href="pazar.php">E-Pazar</a></li>
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
        <h1 class="page-title" data-aos="zoom-in">Kozdere Üretici Pazarı</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Köyümüzün bereketli topraklarından, doğrudan sofranıza.</p>
    </div>
</div>

<div class="container">
    <div class="market-info-box" data-aos="fade-up" data-aos-delay="200">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-leaf"></i></div>
                    <div class="info-text">
                        <h5>%100 Doğal</h5>
                        <p>Katkısız köy ürünleri</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-handshake-angle"></i></div>
                    <div class="info-text">
                        <h5>Aracısız Satış</h5>
                        <p>Direkt üreticiden alın</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="info-item">
                    <div class="info-icon"><i class="fa-brands fa-whatsapp"></i></div>
                    <div class="info-text">
                        <h5>Kolay İletişim</h5>
                        <p>WhatsApp ile hızlı sipariş</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-4 mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3" data-aos="fade-right">
        <h2 class="fw-bold m-0" style="color: var(--dark); font-size: 2rem; letter-spacing: -0.5px;">Tüm Ürünler</h2>
        <span class="badge bg-success bg-opacity-10 text-success fs-6 px-4 py-2 rounded-pill border border-success border-opacity-25" style="width: fit-content;"><?= count($urunler) ?> Ürün Bulundu</span>
    </div>

    <div class="row g-4">
        <?php foreach($urunler as $i => $u): ?>
        <div class="col-lg-3 col-md-6 col-sm-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
            <div class="product-card">
                <div class="product-img-wrap">
                    <img src="<?= htmlspecialchars($u['resim']) ?>" alt="<?= htmlspecialchars($u['isim']) ?>" class="product-img">
                    <div class="product-price-badge"><?= htmlspecialchars($u['fiyat']) ?></div>
                </div>
                <div class="product-body">
                    <span class="product-category"><?= htmlspecialchars($u['birim']) ?></span>
                    <h3 class="product-title"><?= htmlspecialchars($u['isim']) ?></h3>
                    
                    <div class="seller-info">
                        <div class="seller-avatar"><i class="fa-solid fa-user"></i></div>
                        <div>
                            <p class="seller-name"><?= htmlspecialchars($u['satici']) ?></p>
                            <p class="seller-unit"><i class="fa-solid fa-location-dot me-1 text-success"></i>Kozdere Köyü</p>
                        </div>
                    </div>
                    
                    <?php 
                    $mesaj = urlencode("Merhaba " . $u['satici'] . ", Kozdere portalında '" . $u['isim'] . "' ürününüzü gördüm. Sipariş vermek istiyorum.");
                    $wp_link = "https://wa.me/" . $u['telefon'] . "?text=" . $mesaj;
                    ?>
                    <a href="<?= $wp_link ?>" target="_blank" class="btn-whatsapp">
                        <i class="fa-brands fa-whatsapp me-2 fs-4 align-middle"></i>Sipariş Ver
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="text-secondary fw-medium">Üretici Pazarı modülü, köylümüzün emeğini aracısız olarak sizlere ulaştırmak için kurulmuştur. Sorumluluk alıcı ve satıcıya aittir.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Kısayollar</h4>
                <a href="kurumsal.php" class="f-link">Hakkımızda</a>
                <a href="soyagaci.php" class="f-link">Köy Soyağacı</a>
                <a href="dernek.php" class="f-link">Köy Derneği</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Destek</h4>
                <p class="text-secondary fw-medium mb-2"><i class="fa-solid fa-envelope me-2 text-success"></i> pazar@kozdere.com.tr</p>
                <p class="text-secondary fw-medium"><i class="fa-solid fa-phone me-2 text-success"></i> <?= htmlspecialchars($site['telefon']) ?></p>
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