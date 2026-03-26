<?php
require_once 'baglan.php';

$dernek_yonetimi = [
    ['isim' => 'Kemal Aydın', 'gorev' => 'Dernek Başkanı', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=K.A'],
    ['isim' => 'Ali Rıza Çelik', 'gorev' => 'Başkan Yrd.', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=A.C'],
    ['isim' => 'Yasin Turan', 'gorev' => 'Muhasip', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=Y.T'],
    ['isim' => 'Osman Korkmaz', 'gorev' => 'Genel Sekreter', 'resim' => 'https://placehold.co/150x150/0f172a/fff?text=O.K']
];

$projeler = [
    ['baslik' => 'Köy Meydanı Düzenlemesi', 'durum' => '%80 Tamamlandı', 'ilerleme' => 80, 'renk' => 'success', 'detay' => 'Meydana yeni parke taşları döşenip, oturma alanları ve modern bir çeşme inşa ediliyor.'],
    ['baslik' => 'Mezarlık Çevre Duvarı', 'durum' => '%45 Tamamlandı', 'ilerleme' => 45, 'renk' => 'primary', 'detay' => 'Köy mezarlığımızın güvenliği ve temizliği için etrafı taş duvar ve ferforje ile çevriliyor.'],
    ['baslik' => 'Cami Çatı Tadilatı', 'durum' => 'Planlama Aşamasında', 'ilerleme' => 15, 'renk' => 'warning', 'detay' => 'Merkez camimizin kış aylarında su alan çatısının tamamen yenilenmesi projesi.']
];

$burs_istatistik = [
    'hedef' => 250000,
    'toplanan' => 185000,
    'ogrenci_sayisi' => 12
];
$burs_yuzde = round(($burs_istatistik['toplanan'] / $burs_istatistik['hedef']) * 100);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Köy Derneği & Yardımlaşma - <?= htmlspecialchars($site['baslik']) ?></title>
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
        
        .page-header { background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(15,23,42,0.95)), url('https://placehold.co/1920x500/1e293b/fff?text=Dernek+Toplantisi') center/cover; padding: 100px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3.5rem; letter-spacing: -1px; margin-bottom: 15px; }
        
        .burs-card { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border-radius: 30px; padding: 50px; position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(21,128,61,0.2); margin-top: -60px; z-index: 10; border: 4px solid #fff; }
        .burs-card::after { content: '\f19d'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -20px; bottom: -40px; font-size: 15rem; color: rgba(255,255,255,0.05); transform: rotate(-15deg); }
        .progress-wrapper { background: rgba(255,255,255,0.2); border-radius: 50px; height: 15px; margin: 30px 0 15px 0; overflow: hidden; position: relative; }
        .progress-bar-custom { background: #fde047; height: 100%; border-radius: 50px; position: relative; }
        
        .iban-box { background: rgba(0,0,0,0.15); border-radius: 16px; padding: 25px; margin-top: 30px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); }
        .iban-text { font-family: monospace; font-size: 1.4rem; font-weight: 700; letter-spacing: 2px; margin: 10px 0; color: #fde047; word-break: break-all; }
        .btn-copy { background: rgba(255,255,255,0.2); border: none; color: #fff; padding: 8px 20px; border-radius: 8px; font-weight: 700; transition: 0.3s; font-size: 0.9rem; }
        .btn-copy:hover { background: #fff; color: var(--primary); }

        .team-card { background: #fff; border-radius: 20px; padding: 30px 20px; text-align: center; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s; height: 100%; }
        .team-card img { width: 110px; height: 110px; border-radius: 50%; margin-bottom: 20px; border: 4px solid var(--light); object-fit: cover; }
        
        .project-card { background: #fff; border-radius: 20px; padding: 30px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s; height: 100%; display: flex; flex-direction: column; }
        .proj-progress { height: 8px; background: var(--light); border-radius: 10px; margin-top: 20px; overflow: hidden; }
        .proj-bar { height: 100%; border-radius: 10px; }
        
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
            .burs-card { padding: 30px 20px; text-align: center; }
            .iban-text { font-size: 1.1rem; }
            .team-card { padding: 20px; }
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
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Köyümüz</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="kurumsal.php"><i class="fa-solid fa-landmark me-2 text-primary"></i>Tarihçe & Bilgi</a></li>
                        <li><a class="dropdown-item" href="soyagaci.php"><i class="fa-solid fa-sitemap me-2 text-success"></i>Soyağacı</a></li>
                        <li><a class="dropdown-item active" href="dernek.php"><i class="fa-solid fa-handshake-angle me-2 text-warning"></i>Dernek Yönetimi</a></li>
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
        <h1 class="page-title" data-aos="zoom-in">Yardımlaşma & Dernek</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Birlikte daha güçlüyüz, geleceği birlikte kuruyoruz.</p>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="burs-card" data-aos="fade-up" data-aos-delay="200">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h2 class="fw-bold mb-3"><i class="fa-solid fa-graduation-cap me-3"></i>Eğitim & Burs Havuzu</h2>
                        <p class="fs-5 opacity-75 mb-0"><strong><?= $burs_istatistik['ogrenci_sayisi'] ?></strong> üniversite öğrencimize destek oluyoruz.</p>
                        <div class="progress-wrapper"><div class="progress-bar-custom" style="width: <?= $burs_yuzde ?>%;"></div></div>
                        <div class="d-flex justify-content-between fw-bold fs-5"><span><?= number_format($burs_istatistik['toplanan'], 0, ',', '.') ?> ₺</span><span class="text-warning"><?= $burs_yuzde ?>%</span></div>
                    </div>
                    <div class="col-lg-5">
                        <div class="iban-box text-center">
                            <div class="iban-text" id="ibanText">TR12 0001 0002 0003 0004 0005 06</div>
                            <button class="btn-copy mt-2" onclick="kopyalaIBAN()"><i class="fa-regular fa-copy me-2"></i>Kopyala</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-5">
    <div class="row g-4 justify-content-center mb-5 pb-4">
        <?php foreach($dernek_yonetimi as $i => $kisi): ?>
        <div class="col-lg-3 col-md-6 col-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
            <div class="team-card">
                <img src="<?= htmlspecialchars($kisi['resim']) ?>" alt="<?= htmlspecialchars($kisi['isim']) ?>">
                <h4><?= htmlspecialchars($kisi['isim']) ?></h4>
                <p><?= htmlspecialchars($kisi['gorev']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-4 mt-4">
        <?php foreach($projeler as $i => $proje): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
            <div class="project-card">
                <h4 class="fw-bold mb-3"><?= htmlspecialchars($proje['baslik']) ?></h4>
                <p class="text-secondary small mb-3"><?= htmlspecialchars($proje['detay']) ?></p>
                <div class="mt-auto pt-3 border-top">
                    <div class="d-flex justify-content-between small fw-bold mb-1"><span>Durum:</span><span class="text-<?= $proje['renk'] ?>"><?= $proje['durum'] ?></span></div>
                    <div class="proj-progress"><div class="proj-bar bg-<?= $proje['renk'] ?>" style="width: <?= $proje['ilerleme'] ?>%; background-color: var(--bs-<?= $proje['renk'] ?>)!important;"></div></div>
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
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span>.</span></a>
                <p class="text-secondary fw-medium">Kozdere Yardımlaşma Derneği resmi iletişim sayfasıdır.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Kısayollar</h4>
                <a href="kurumsal.php" class="f-link">Hakkımızda</a>
                <a href="soyagaci.php" class="f-link">Soyağacı</a>
                <a href="pazar.php" class="f-link">Köy Pazarı</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">İletişim</h4>
                <p class="text-secondary fw-medium mb-2"><i class="fa-solid fa-envelope me-2 text-success"></i> dernek@kozdere.com.tr</p>
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
        if (window.scrollY > 50) { document.getElementById('navbar').classList.add('sticky'); }
        else { document.getElementById('navbar').classList.remove('sticky'); }
    });
    function kopyalaIBAN() {
        var ibanText = document.getElementById("ibanText").innerText;
        navigator.clipboard.writeText(ibanText).then(function() {
            var btn = document.querySelector('.btn-copy');
            btn.innerHTML = '<i class="fa-solid fa-check me-2"></i>Tamam!';
            setTimeout(function() { btn.innerHTML = '<i class="fa-regular fa-copy me-2"></i>Kopyala'; }, 2000);
        });
    }
</script>
</body>
</html>