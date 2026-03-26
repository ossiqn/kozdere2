<?php
require_once 'baglan.php';

$sulaleler = [
    ['id' => 1, 'isim' => 'Karahasanoğulları', 'kisi_sayisi' => 145, 'renk' => 'primary'],
    ['id' => 2, 'isim' => 'Demirciler', 'kisi_sayisi' => 98, 'renk' => 'success'],
    ['id' => 3, 'isim' => 'Sarılar', 'kisi_sayisi' => 76, 'renk' => 'warning'],
    ['id' => 4, 'isim' => 'Pehlivanlar', 'kisi_sayisi' => 112, 'renk' => 'danger']
];

$arama = isset($_GET['q']) ? $_GET['q'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Köy Soyağacı - <?= htmlspecialchars($site['baslik']) ?></title>
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
        .dropdown-item:hover { background: rgba(21,128,61,0.05); color: var(--primary); transform: translateX(5px); }
        .btn-custom { background: var(--primary); color: #fff; font-weight: 800; padding: 12px 28px; border-radius: 12px; text-decoration: none; transition: 0.4s; display: inline-flex; align-items: center; gap: 8px; border: none; }
        .btn-custom:hover { background: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(21,128,61,0.3); color: #fff; }
        
        .page-header { background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(15,23,42,0.95)), url('https://placehold.co/1920x400/1e293b/fff?text=Soyağacı') center/cover; padding: 80px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3rem; letter-spacing: -1px; margin-bottom: 15px; }
        
        .search-container { max-width: 700px; margin: -35px auto 40px auto; position: relative; z-index: 10; padding: 0 15px; }
        .search-box { background: #fff; border-radius: 20px; padding: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); display: flex; align-items: center; border: 1px solid rgba(0,0,0,0.05); }
        .search-input { border: none; padding: 15px 25px; width: 100%; font-size: 1.1rem; font-weight: 500; color: var(--dark); outline: none; background: transparent; }
        .search-btn { background: var(--primary); color: #fff; border: none; padding: 15px 35px; border-radius: 14px; font-weight: 800; font-size: 1rem; transition: 0.3s; }
        .search-btn:hover { background: var(--dark); }
        
        .family-card { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s; text-align: center; text-decoration: none; display: block; height: 100%; }
        .family-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
        .f-icon-box { width: 70px; height: 70px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 20px; }
        .family-card h4 { font-weight: 800; color: var(--dark); margin-bottom: 10px; font-size: 1.25rem; }
        .family-card p { margin: 0; color: var(--gray); font-weight: 600; font-size: 0.95rem; }
        
        .tree-container { background: #fff; border-radius: 30px; padding: 50px 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.03); overflow-x: auto; margin-top: 50px; min-height: 500px; }
        .tree-title { font-weight: 900; font-size: 2rem; color: var(--dark); text-align: center; margin-bottom: 40px; }
        .tree-title span { color: var(--primary); }
        
        .tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; padding-left: 0; min-width: max-content; }
        .tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
        .tree li::before, .tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #cbd5e1; width: 50%; height: 20px; }
        .tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
        .tree li:only-child::after, .tree li:only-child::before { display: none; }
        .tree li:only-child { padding-top: 0; }
        .tree li:first-child::before, .tree li:last-child::after { border: 0 none; }
        .tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 5px 0 0; }
        .tree li:first-child::after { border-radius: 5px 0 0 0; }
        .tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #cbd5e1; width: 0; height: 20px; }
        
        .tree li a { border: 1px solid #e2e8f0; padding: 15px 25px; text-decoration: none; color: var(--dark); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.95rem; display: inline-block; border-radius: 16px; transition: all 0.4s; background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.02); font-weight: 700; min-width: 140px; position: relative; }
        .tree li a .gender-icon { position: absolute; top: -10px; right: -10px; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #fff; box-shadow: 0 5px 10px rgba(0,0,0,0.1); }
        .gender-m { background: #3b82f6; }
        .gender-f { background: #ec4899; }
        .tree li a span { display: block; font-size: 0.8rem; color: var(--gray); font-weight: 600; margin-top: 5px; }
        
        .tree li a:hover, .tree li a:hover+ul li a { background: var(--dark); color: #fff; border: 1px solid var(--dark); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(15,23,42,0.2); }
        .tree li a:hover span { color: #cbd5e1; }
        .tree li a:hover+ul li::after, .tree li a:hover+ul li::before, .tree li a:hover+ul::before, .tree li a:hover+ul ul::before { border-color: var(--dark); }
        
        .cta-section { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); border-radius: 30px; padding: 60px 40px; text-align: center; color: #fff; margin-top: 80px; position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(21,128,61,0.2); }
        .cta-section::after { content: '\f2b9'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: -20px; top: -20px; font-size: 15rem; color: rgba(255,255,255,0.05); }
        
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
            
            .page-title { font-size: 2.2rem; }
            .search-container { margin-top: -30px; }
            .search-box { flex-direction: column; padding: 15px; }
            .search-btn { width: 100%; margin-top: 10px; }
            .tree-container { padding: 30px 15px; border-radius: 20px; }
            .cta-section { padding: 40px 20px; border-radius: 20px; }
            .cta-section h2 { font-size: 2rem !important; }
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
        <h1 class="page-title" data-aos="zoom-in">Köy Soyağacı Arşivi</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Köklerimizi koruyor, gelecek nesillere aktarıyoruz.</p>
    </div>
</div>

<div class="search-container" data-aos="fade-up" data-aos-delay="200">
    <form class="search-box" method="GET" action="soyagaci.php">
        <i class="fa-solid fa-magnifying-glass ms-3 text-secondary fs-4 d-none d-md-block"></i>
        <input type="text" name="q" class="search-input" placeholder="İsim, soyisim veya sülale adı arayın..." value="<?= htmlspecialchars($arama) ?>">
        <button type="submit" class="search-btn">Soyağacında Ara</button>
    </form>
</div>

<div class="container mb-5 pb-4">
    <div class="row g-4 justify-content-center">
        <?php foreach($sulaleler as $i => $s): ?>
        <div class="col-lg-3 col-md-6 col-6" data-aos="fade-up" data-aos-delay="<?= ($i+1)*100 ?>">
            <a href="?sulale=<?= $s['id'] ?>" class="family-card">
                <div class="f-icon-box bg-<?= $s['renk'] ?> bg-opacity-10 text-<?= $s['renk'] ?>" style="background-color: var(--bs-<?= $s['renk'] ?>)!important; color:#fff!important;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h4><?= htmlspecialchars($s['isim']) ?></h4>
                <p><i class="fa-solid fa-id-card-clip me-2 text-<?= $s['renk'] ?>"></i><?= $s['kisi_sayisi'] ?> Kişi</p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="tree-container" data-aos="fade-up">
        <h2 class="tree-title">Karahasanoğulları <span>Soyağacı</span></h2>
        
        <div class="tree d-flex justify-content-center">
            <ul>
                <li>
                    <a href="#">Büyük Hasan Ağa <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1890 - 1965)</span></a>
                    <ul>
                        <li>
                            <a href="#">Mustafa <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1915 - 1980)</span></a>
                            <ul>
                                <li>
                                    <a href="#">Hasan <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1940 - )</span></a>
                                    <ul>
                                        <li><a href="#">Ali <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1968 - )</span></a></li>
                                        <li><a href="#">Ayşe <span class="gender-icon gender-f"><i class="fa-solid fa-venus"></i></span><span>(1972 - )</span></a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Fatma <span class="gender-icon gender-f"><i class="fa-solid fa-venus"></i></span><span>(1945 - )</span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Mehmet <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1920 - 1995)</span></a>
                            <ul>
                                <li><a href="#">Kemal <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1950 - )</span></a></li>
                                <li>
                                    <a href="#">Zeynep <span class="gender-icon gender-f"><i class="fa-solid fa-venus"></i></span><span>(1955 - )</span></a>
                                    <ul>
                                        <li><a href="#">Burak <span class="gender-icon gender-m"><i class="fa-solid fa-mars"></i></span><span>(1980 - )</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <div class="cta-section" data-aos="zoom-in">
        <h2 class="fw-bold mb-3" style="font-size: 2.5rem; letter-spacing: -1px;">Bilgileriniz Eksik veya Hatalı mı?</h2>
        <p class="fs-5 mb-4 opacity-75">Soyağacında kendi ailenizi göremiyorsanız veya güncellenmesi gereken bir durum varsa hemen bize bildirin, dijital arşivimizi birlikte büyütelim.</p>
        <a href="#" class="btn btn-light fw-bold px-5 py-3 rounded-pill text-success" style="font-size: 1.1rem;"><i class="fa-solid fa-pen-to-square me-2"></i>Kayıt Düzenleme Talebi</a>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="text-secondary fw-medium">Soyağacı modülü Kozdere Köyü Muhtarlığı arşivleri kullanılarak dijital ortama aktarılmıştır.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Kısayollar</h4>
                <a href="kurumsal.php" class="f-link">Köy Tarihçesi</a>
                <a href="pazar.php" class="f-link">Üretici Pazarı</a>
                <a href="dernek.php" class="f-link">Köy Derneği</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="f-title">Destek</h4>
                <p class="text-secondary fw-medium mb-2"><i class="fa-solid fa-envelope me-2 text-success"></i> soyagaci@kozdere.com.tr</p>
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