<?php
require_once 'baglan.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: kayit.php");
    exit;
}

$uye_id = $_SESSION['uye_id'];
$sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$sorgu->execute([$uye_id]);
$kullanici = $sorgu->fetch();

if (!$kullanici || $kullanici['durum'] == 0) {
    session_destroy();
    header("Location: kayit.php");
    exit;
}

$kolon_kontrol = $db->query("SHOW COLUMNS FROM haberler LIKE 'yazar_id'")->fetchAll();
if (count($kolon_kontrol) == 0) {
    $db->exec("ALTER TABLE haberler ADD yazar_id INT DEFAULT 0");
}

$mesaj = '';
$mesaj_tip = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['icerik_ekle']) && $kullanici['rol'] == 'editor') {
        $tip = $_POST['tip'];
        $baslik = trim($_POST['baslik']);
        $ozet = trim($_POST['ozet']);
        $tarih = date('d.m.Y'); 
        
        $ikon = 'fa-newspaper';
        $renk = 'primary';
        if ($tip == 'vefat') { $ikon = 'fa-book-prayers'; $renk = 'danger'; }
        if ($tip == 'dugun') { $ikon = 'fa-ring'; $renk = 'danger'; }
        if ($tip == 'etkinlik') { $ikon = 'fa-tents'; $renk = 'success'; }

        $ekle = $db->prepare("INSERT INTO haberler (tip, baslik, ozet, tarih, ikon, renk, durum, yazar_id) VALUES (?, ?, ?, ?, ?, ?, 1, ?)");
        if ($ekle->execute([$tip, $baslik, $ozet, $tarih, $ikon, $renk, $uye_id])) {
            $mesaj = 'İçerik başarıyla yayınlandı!';
            $mesaj_tip = 'success';
        } else {
            $mesaj = 'İçerik eklenirken hata oluştu.';
            $mesaj_tip = 'danger';
        }
    } elseif (isset($_POST['sifre_guncelle'])) {
        $yeni_sifre = trim($_POST['yeni_sifre']);
        if ($yeni_sifre != '') {
            $hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $guncelle = $db->prepare("UPDATE kullanicilar SET sifre = ? WHERE id = ?");
            if ($guncelle->execute([$hash, $uye_id])) {
                $mesaj = 'Şifreniz başarıyla güncellendi.';
                $mesaj_tip = 'success';
            }
        }
    }
}

if (isset($_GET['cikis'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$yazdiklarim = $db->prepare("SELECT * FROM haberler WHERE yazar_id = ? ORDER BY id DESC");
$yazdiklarim->execute([$uye_id]);
$haber_listesi = $yazdiklarim->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profilim - <?= htmlspecialchars($site['baslik']) ?></title>
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
        
        .page-header { background: linear-gradient(rgba(15,23,42,0.9), rgba(15,23,42,0.95)), url('https://placehold.co/1920x300/0f172a/fff?text=Profil') center/cover; padding: 60px 0; color: #fff; text-align: center; margin-bottom: 40px; }
        
        .profile-sidebar { background: #fff; border-radius: 24px; padding: 40px 30px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.02); }
        .avatar-circle { width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 900; margin-bottom: 20px; box-shadow: 0 15px 30px rgba(21,128,61,0.3); }
        .profile-name { font-weight: 900; font-size: 1.5rem; color: var(--dark); margin-bottom: 5px; }
        .role-badge { display: inline-block; background: #f1f5f9; color: var(--dark); font-weight: 800; padding: 6px 15px; border-radius: 50px; font-size: 0.85rem; margin-bottom: 20px; letter-spacing: 0.5px; }
        .role-badge.editor { background: #fee2e2; color: #dc2626; }
        .profile-info-box { background: #f8fafc; border-radius: 16px; padding: 20px; text-align: left; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .info-label { font-size: 0.8rem; font-weight: 800; color: var(--gray); text-transform: uppercase; margin-bottom: 5px; display: block; }
        .info-value { font-weight: 700; color: var(--dark); margin-bottom: 15px; font-size: 0.95rem; line-height: 1.5; }
        .info-value:last-child { margin-bottom: 0; }
        .btn-logout { background: #fef2f2; color: #dc2626; font-weight: 800; padding: 12px; border-radius: 12px; width: 100%; border: none; transition: 0.3s; text-decoration: none; display: block; }
        .btn-logout:hover { background: #dc2626; color: #fff; }

        .profile-content { background: #fff; border-radius: 24px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.02); min-height: 100%; }
        .nav-pills { border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 30px; gap: 10px; }
        .nav-pills .nav-link { border-radius: 12px; font-weight: 800; color: var(--gray); padding: 10px 20px; transition: 0.3s; }
        .nav-pills .nav-link.active { background: var(--dark); color: #fff; }
        .nav-pills .nav-link.editor-tab { background: #fef2f2; color: #dc2626; }
        .nav-pills .nav-link.editor-tab.active { background: #dc2626; color: #fff; }
        
        .form-label { font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 12px; padding: 14px 20px; border: 1px solid #e2e8f0; font-weight: 500; background: #f8fafc; transition: 0.3s; }
        .form-control:focus, .form-select:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(21,128,61,0.1); outline: none; }
        .btn-submit { background: var(--primary); color: #fff; font-weight: 800; padding: 14px 30px; border-radius: 12px; border: none; transition: 0.3s; }
        .btn-submit:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(21,128,61,0.3); }

        .news-item { padding: 20px; border: 1px solid #f1f5f9; border-radius: 16px; margin-bottom: 15px; transition: 0.3s; display: flex; justify-content: space-between; align-items: center; }
        .news-item:hover { border-color: var(--primary); background: #f8fafc; }
        .n-title { font-weight: 800; color: var(--dark); margin-bottom: 5px; font-size: 1.1rem; }
        .n-meta { font-size: 0.85rem; color: var(--gray); font-weight: 600; }
        
        footer { background: var(--dark); color: #cbd5e1; padding: 60px 0 30px 0; margin-top: 80px; }
        .f-title { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 25px; }
        .f-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 600; transition: 0.3s; }
        .f-link:hover { color: var(--primary); transform: translateX(5px); }

        @media (max-width: 991px) {
            .navbar-collapse { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); position: absolute; top: 100%; left: 15px; right: 15px; border: 1px solid rgba(0,0,0,0.05); }
            .nav-link { text-align: center; padding: 15px !important; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; }
            .dropdown-menu { border: none; box-shadow: none; background: #f8fafc; margin-top: 10px; }
            .btn-custom { display: flex; width: 100%; justify-content: center; padding: 15px; font-size: 1.1rem; margin-top: 15px; }
            .profile-sidebar { margin-bottom: 30px; }
            .profile-content { padding: 25px 20px; }
            .nav-pills { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 10px; white-space: nowrap; }
            .news-item { flex-direction: column; align-items: flex-start; gap: 10px; }
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
        <h1 class="page-title fw-bold m-0" data-aos="zoom-in">Üye Kontrol Paneli</h1>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-4" data-aos="fade-right">
            <div class="profile-sidebar">
                <div class="avatar-circle"><?= mb_strtoupper(mb_substr($kullanici['ad'], 0, 1, 'UTF-8') . mb_substr($kullanici['soyad'], 0, 1, 'UTF-8'), 'UTF-8') ?></div>
                <h3 class="profile-name"><?= htmlspecialchars($kullanici['ad'] . ' ' . $kullanici['soyad']) ?></h3>
                
                <?php if ($kullanici['rol'] == 'editor'): ?>
                    <span class="role-badge editor"><i class="fa-solid fa-pen-nib me-1"></i> Köy Editörü</span>
                <?php else: ?>
                    <span class="role-badge"><i class="fa-solid fa-user me-1"></i> Onaylı Üye</span>
                <?php endif; ?>

                <div class="profile-info-box">
                    <span class="info-label">E-Posta Adresi</span>
                    <div class="info-value"><?= htmlspecialchars($kullanici['eposta']) ?></div>
                    <span class="info-label mt-3">Köye Bağlılık Bilgisi</span>
                    <div class="info-value text-success"><?= htmlspecialchars($kullanici['baglilik']) ?></div>
                </div>
                
                <a href="profil.php?cikis=1" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Hesaptan Çıkış Yap</a>
            </div>
        </div>

        <div class="col-lg-8" data-aos="fade-left">
            <div class="profile-content">
                <?php if ($mesaj != ''): ?>
                <div class="alert alert-<?= $mesaj_tip ?> fw-bold rounded-3 border-0">
                    <i class="fa-solid <?= $mesaj_tip == 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation' ?> me-2"></i> <?= htmlspecialchars($mesaj) ?>
                </div>
                <?php endif; ?>

                <ul class="nav nav-pills" id="profileTabs">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#yazilar">Faaliyetlerim</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#ayarlar">Şifre Değiştir</button></li>
                    <?php if ($kullanici['rol'] == 'editor'): ?>
                    <li class="nav-item"><button class="nav-link editor-tab" data-bs-toggle="pill" data-bs-target="#icerik_ekle"><i class="fa-solid fa-plus me-1"></i> Hızlı İlan Ekle</button></li>
                    <?php endif; ?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="yazilar">
                        <h4 class="fw-bold mb-4">Eklediğim İçerikler</h4>
                        <?php if (empty($haber_listesi)): ?>
                            <p class="text-secondary fw-medium">Henüz eklediğiniz bir içerik bulunmuyor.</p>
                        <?php else: ?>
                            <?php foreach($haber_listesi as $h): ?>
                            <div class="news-item">
                                <div>
                                    <div class="n-title"><?= htmlspecialchars($h['baslik']) ?></div>
                                    <div class="n-meta"><span class="badge bg-secondary me-2"><?= strtoupper($h['tip']) ?></span> <i class="fa-regular fa-calendar me-1"></i> <?= htmlspecialchars($h['tarih']) ?></div>
                                </div>
                                <a href="haber_detay.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-dark fw-bold rounded-pill px-3">Görüntüle</a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="ayarlar">
                        <h4 class="fw-bold mb-4">Güvenlik Ayarları</h4>
                        <form action="profil.php" method="POST">
                            <div class="mb-4">
                                <label class="form-label">Yeni Şifreniz</label>
                                <input type="password" name="yeni_sifre" class="form-control" required>
                            </div>
                            <button type="submit" name="sifre_guncelle" class="btn-submit">Şifreyi Güncelle</button>
                        </form>
                    </div>

                    <?php if ($kullanici['rol'] == 'editor'): ?>
                    <div class="tab-pane fade" id="icerik_ekle">
                        <h4 class="fw-bold text-danger mb-4"><i class="fa-solid fa-bolt me-2"></i>Editör Paneli</h4>
                        <p class="text-secondary mb-4 fw-medium">Eklediğiniz içerikler yönetici onayı beklemeden doğrudan ana sayfada yayınlanacaktır. Lütfen içerik kurallarına dikkat ediniz.</p>
                        <form action="profil.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Kategori / İlan Tipi</label>
                                    <select name="tip" class="form-select" required>
                                        <option value="haber">Köy Haberi</option>
                                        <option value="vefat">Vefat / Taziye İlanı</option>
                                        <option value="dugun">Düğün / Nişan İlanı</option>
                                        <option value="etkinlik">Köy Etkinliği</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">İlan Başlığı</label>
                                    <input type="text" name="baslik" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">İlan Detayı / Metni</label>
                                    <textarea name="ozet" class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" name="icerik_ekle" class="btn-submit bg-danger"><i class="fa-solid fa-paper-plane me-2"></i>Hemen Yayınla</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4 text-center text-lg-start">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="text-secondary fw-medium">Kozdere Köyü Muhtarlığı ve Yardımlaşma Derneği resmi iletişim portalı.</p>
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