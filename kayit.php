<?php
require_once 'baglan.php';

if (isset($_SESSION['uye_id'])) {
    header("Location: profil.php");
    exit;
}

$mesaj = '';
$mesaj_tip = '';
$aktif_tab = 'giris';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['kayit_ol'])) {
        $aktif_tab = 'kayit';
        $ad = trim($_POST['ad']);
        $soyad = trim($_POST['soyad']);
        $eposta = trim($_POST['eposta']);
        $sifre = trim($_POST['sifre']);
        $baglilik = trim($_POST['baglilik']);

        if ($ad != '' && $soyad != '' && $eposta != '' && $sifre != '' && $baglilik != '') {
            $kontrol = $db->prepare("SELECT id FROM kullanicilar WHERE eposta = ?");
            $kontrol->execute([$eposta]);
            if ($kontrol->rowCount() > 0) {
                $mesaj = 'Bu e-posta adresi zaten sistemde kayıtlı.';
                $mesaj_tip = 'danger';
            } else {
                $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
                $ekle = $db->prepare("INSERT INTO kullanicilar (ad, soyad, eposta, sifre, baglilik) VALUES (?, ?, ?, ?, ?)");
                if ($ekle->execute([$ad, $soyad, $eposta, $sifre_hash, $baglilik])) {
                    $mesaj = 'Kayıt başarılı! Hesabınız yönetici onayından sonra aktif edilecektir.';
                    $mesaj_tip = 'success';
                    $aktif_tab = 'giris';
                } else {
                    $mesaj = 'Kayıt sırasında bir hata oluştu.';
                    $mesaj_tip = 'danger';
                }
            }
        } else {
            $mesaj = 'Lütfen tüm alanları doldurun.';
            $mesaj_tip = 'warning';
        }
    } elseif (isset($_POST['giris_yap'])) {
        $aktif_tab = 'giris';
        $eposta = trim($_POST['eposta']);
        $sifre = trim($_POST['sifre']);

        if ($eposta != '' && $sifre != '') {
            $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE eposta = ?");
            $sorgu->execute([$eposta]);
            $kullanici = $sorgu->fetch();

            if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
                if ($kullanici['durum'] == 1) {
                    $_SESSION['uye_id'] = $kullanici['id'];
                    $_SESSION['uye_ad'] = $kullanici['ad'];
                    $_SESSION['uye_soyad'] = $kullanici['soyad'];
                    $_SESSION['uye_rol'] = $kullanici['rol'];
                    header("Location: profil.php");
                    exit;
                } else {
                    $mesaj = 'Hesabınız henüz yönetici tarafından onaylanmamıştır.';
                    $mesaj_tip = 'warning';
                }
            } else {
                $mesaj = 'Hatalı e-posta veya şifre girdiniz.';
                $mesaj_tip = 'danger';
            }
        } else {
            $mesaj = 'Lütfen e-posta ve şifrenizi girin.';
            $mesaj_tip = 'warning';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Giriş / Kayıt - <?= htmlspecialchars($site['baslik']) ?></title>
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
        
        .page-header { background: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.95)), url('https://placehold.co/1920x400/0f172a/fff?text=Giris') center/cover; padding: 80px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3rem; letter-spacing: -1px; margin-bottom: 15px; }

        .auth-container { max-width: 900px; margin: -50px auto 50px auto; position: relative; z-index: 10; padding: 0 15px; }
        .auth-card { background: #fff; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.03); overflow: hidden; display: flex; }
        
        .auth-sidebar { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 50px 40px; width: 40%; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden; }
        .auth-sidebar::after { content: '\f0c0'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -20px; bottom: -20px; font-size: 15rem; color: rgba(255,255,255,0.05); }
        .auth-sidebar h3 { font-weight: 900; font-size: 2rem; margin-bottom: 20px; }
        .auth-sidebar p { font-size: 1.05rem; opacity: 0.9; line-height: 1.6; }
        
        .auth-content { padding: 50px 40px; width: 60%; background: #fff; }
        .nav-pills { border: none; margin-bottom: 30px; background: #f1f5f9; border-radius: 14px; padding: 5px; display: inline-flex; }
        .nav-pills .nav-link { border-radius: 10px; font-weight: 800; color: var(--gray); padding: 10px 25px; transition: 0.3s; }
        .nav-pills .nav-link.active { background: #fff; color: var(--primary); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        .form-label { font-weight: 700; color: var(--dark); margin-bottom: 8px; font-size: 0.95rem; }
        .form-control { border-radius: 12px; padding: 14px 20px; border: 1px solid #e2e8f0; font-weight: 500; background: #f8fafc; transition: 0.3s; }
        .form-control:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(21,128,61,0.1); outline: none; }
        .input-group-text { background: #f8fafc; border: 1px solid #e2e8f0; border-right: none; color: #94a3b8; border-radius: 12px 0 0 12px; padding-left: 20px; }
        .form-control.with-icon { border-left: none; padding-left: 10px; }
        
        .btn-submit { background: var(--dark); color: #fff; font-weight: 800; padding: 15px; border-radius: 12px; width: 100%; border: none; transition: 0.3s; margin-top: 10px; font-size: 1.05rem; }
        .btn-submit:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); color: #fff; }
        
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
            .auth-card { flex-direction: column; }
            .auth-sidebar { width: 100%; padding: 40px 25px; text-align: center; }
            .auth-content { width: 100%; padding: 40px 25px; }
            .nav-pills { width: 100%; display: flex; }
            .nav-pills .nav-item { flex: 1; text-align: center; }
            .nav-pills .nav-link { width: 100%; padding: 12px 10px; }
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
        <h1 class="page-title" data-aos="zoom-in">Köy Portalı Üyeliği</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Köyümüzün dijital kimliğine sahip olun.</p>
    </div>
</div>

<div class="auth-container" data-aos="fade-up" data-aos-delay="200">
    <div class="auth-card">
        <div class="auth-sidebar">
            <h3>Neden Üye Olmalıyım?</h3>
            <p><i class="fa-solid fa-check-circle me-2 text-warning"></i>Köyümüzle ilgili anlık bildirimleri almak için.</p>
            <p><i class="fa-solid fa-check-circle me-2 text-warning"></i>Özel vefat ve etkinlik ilanlarını görebilmek için.</p>
            <p><i class="fa-solid fa-check-circle me-2 text-warning"></i>Taziye defterine mesaj bırakabilmek için.</p>
            <p><i class="fa-solid fa-check-circle me-2 text-warning"></i>Editör yetkisiyle köye haber ve fotoğraf ekleyebilmek için.</p>
        </div>
        
        <div class="auth-content">
            <?php if ($mesaj != ''): ?>
            <div class="alert alert-<?= $mesaj_tip ?> alert-dismissible fade show fw-bold rounded-4 border-0" style="background: <?= $mesaj_tip == 'success' ? '#dcfce7' : ($mesaj_tip == 'danger' ? '#fee2e2' : '#fef3c7') ?>; color: <?= $mesaj_tip == 'success' ? '#16a34a' : ($mesaj_tip == 'danger' ? '#dc2626' : '#d97706') ?>;" role="alert">
                <i class="fa-solid <?= $mesaj_tip == 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?> me-2"></i> <?= htmlspecialchars($mesaj) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <ul class="nav nav-pills" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $aktif_tab == 'giris' ? 'active' : '' ?>" id="giris-tab" data-bs-toggle="pill" data-bs-target="#giris" type="button" role="tab">Giriş Yap</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $aktif_tab == 'kayit' ? 'active' : '' ?>" id="kayit-tab" data-bs-toggle="pill" data-bs-target="#kayit" type="button" role="tab">Yeni Kayıt Oluştur</button>
                </li>
            </ul>
            
            <div class="tab-content" id="authTabsContent">
                <div class="tab-pane fade <?= $aktif_tab == 'giris' ? 'show active' : '' ?>" id="giris" role="tabpanel">
                    <form action="kayit.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label">E-Posta Adresi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="eposta" class="form-control with-icon" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Şifre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="sifre" class="form-control with-icon" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hatirla">
                                <label class="form-check-label fw-bold text-secondary small" for="hatirla">Beni Hatırla</label>
                            </div>
                            <a href="#" class="text-success fw-bold small text-decoration-none">Şifremi Unuttum</a>
                        </div>
                        <button type="submit" name="giris_yap" class="btn-submit">Sisteme Giriş Yap</button>
                    </form>
                </div>
                
                <div class="tab-pane fade <?= $aktif_tab == 'kayit' ? 'show active' : '' ?>" id="kayit" role="tabpanel">
                    <form action="kayit.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Adınız</label>
                                <input type="text" name="ad" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Soyadınız</label>
                                <input type="text" name="soyad" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">E-Posta Adresi</label>
                                <input type="email" name="eposta" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Şifre Belirleyin</label>
                                <input type="password" name="sifre" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Köye Bağlılık Bilgisi</label>
                                <input type="text" name="baglilik" class="form-control" placeholder="Örn: Karahasanoğulları sülalesindenim, İstanbul'da yaşıyorum." required>
                                <div class="form-text mt-2 fw-bold text-success"><i class="fa-solid fa-shield-check me-1"></i> Bu bilgi üyeliğinizin muhtarlıkça onaylanması için gereklidir.</div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" name="kayit_ol" class="btn-submit">Kayıt Talebini Gönder</button>
                            </div>
                        </div>
                    </form>
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
                <p class="text-secondary fw-medium">Kozdere Dijital Portal Üyelik Sistemi.</p>
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