<?php
require_once 'baglan.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 'dashboard';
$mesaj = '';
$mesaj_tip = '';

if (isset($_GET['cikis'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ayarlar_guncelle'])) {
        $guncelle = $db->prepare("UPDATE ayarlar SET baslik=?, muhtar=?, nufus=?, hane=?, rakim=?, telefon=?, whatsapp=? WHERE id=1");
        if ($guncelle->execute([$_POST['baslik'], $_POST['muhtar'], $_POST['nufus'], $_POST['hane'], $_POST['rakim'], $_POST['telefon'], $_POST['whatsapp']])) {
            $mesaj = 'Ayarlar başarıyla güncellendi!'; $mesaj_tip = 'success';
            $site = array_merge($site, $_POST);
        } else {
            $mesaj = 'Hata oluştu.'; $mesaj_tip = 'danger';
        }
    } elseif (isset($_POST['haber_ekle'])) {
        $ekle = $db->prepare("INSERT INTO haberler (tip, baslik, ozet, tarih, ikon, renk, durum) VALUES (?, ?, ?, ?, ?, ?, 1)");
        if ($ekle->execute([$_POST['tip'], $_POST['baslik'], $_POST['ozet'], $_POST['tarih'], $_POST['ikon'], $_POST['renk']])) {
            $mesaj = 'İçerik başarıyla eklendi!'; $mesaj_tip = 'success';
        }
    } elseif (isset($_POST['pazar_ekle'])) {
        $ekle = $db->prepare("INSERT INTO pazar (isim, fiyat, satici, telefon, resim, birim, durum) VALUES (?, ?, ?, ?, ?, ?, 1)");
        if ($ekle->execute([$_POST['isim'], $_POST['fiyat'], $_POST['satici'], $_POST['telefon'], $_POST['resim'], $_POST['birim']])) {
            $mesaj = 'Ürün başarıyla pazara eklendi!'; $mesaj_tip = 'success';
        }
    } elseif (isset($_POST['slider_ekle'])) {
        $ekle = $db->prepare("INSERT INTO slider (resim, baslik, alt_baslik, buton_text, buton_link, sira, durum) VALUES (?, ?, ?, ?, ?, ?, 1)");
        if ($ekle->execute([$_POST['resim'], $_POST['baslik'], $_POST['alt_baslik'], $_POST['buton_text'], $_POST['buton_link'], $_POST['sira']])) {
            $mesaj = 'Slider başarıyla eklendi!'; $mesaj_tip = 'success';
        }
    } elseif (isset($_POST['foto_ekle'])) {
        $ekle = $db->prepare("INSERT INTO fotograflar (album, baslik, url, durum) VALUES (?, ?, ?, 1)");
        if ($ekle->execute([$_POST['album'], $_POST['baslik'], $_POST['url']])) {
            $mesaj = 'Fotoğraf galeriye eklendi!'; $mesaj_tip = 'success';
        }
    } elseif (isset($_POST['video_ekle'])) {
        $ekle = $db->prepare("INSERT INTO videolar (baslik, embed, thumb, durum) VALUES (?, ?, ?, 1)");
        if ($ekle->execute([$_POST['baslik'], $_POST['embed'], $_POST['thumb']])) {
            $mesaj = 'Video galeriye eklendi!'; $mesaj_tip = 'success';
        }
    } elseif (isset($_POST['rol_degistir'])) {
        $guncelle = $db->prepare("UPDATE kullanicilar SET rol=? WHERE id=?");
        if ($guncelle->execute([$_POST['yeni_rol'], $_POST['uye_id']])) {
            $mesaj = 'Kullanıcı yetkisi güncellendi!'; $mesaj_tip = 'success';
        }
    }
}

if (isset($_GET['sil']) && isset($_GET['tablo'])) {
    $sil_id = (int)$_GET['sil'];
    $tablo = $_GET['tablo'];
    $izinli_tablolar = ['haberler', 'pazar', 'slider', 'kullanicilar', 'yorumlar', 'fotograflar', 'videolar'];
    
    if (in_array($tablo, $izinli_tablolar)) {
        $sil = $db->prepare("DELETE FROM $tablo WHERE id = ?");
        if ($sil->execute([$sil_id])) {
            $mesaj = 'Kayıt başarıyla silindi!'; $mesaj_tip = 'warning';
        }
    }
}

if (isset($_GET['onayla']) && isset($_GET['tablo'])) {
    $onay_id = (int)$_GET['onayla'];
    $tablo = $_GET['tablo'];
    $izinli_tablolar = ['kullanicilar', 'yorumlar'];
    
    if (in_array($tablo, $izinli_tablolar)) {
        $onayla = $db->prepare("UPDATE $tablo SET durum = 1 WHERE id = ?");
        if ($onayla->execute([$onay_id])) {
            $mesaj = 'Kayıt başarıyla onaylandı ve yayına alındı!'; $mesaj_tip = 'success';
        }
    }
}

$toplam_haber = $db->query("SELECT COUNT(*) FROM haberler")->fetchColumn();
$toplam_urun = $db->query("SELECT COUNT(*) FROM pazar")->fetchColumn();
$toplam_uye = $db->query("SELECT COUNT(*) FROM kullanicilar")->fetchColumn();
$bekleyen_uye = $db->query("SELECT COUNT(*) FROM kullanicilar WHERE durum = 0")->fetchColumn();
$bekleyen_yorum = $db->query("SELECT COUNT(*) FROM yorumlar WHERE durum = 0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Yönetim Paneli - <?= htmlspecialchars($site['baslik']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #15803d; --dark: #0f172a; --light: #f8fafc; --sidebar: #020617; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--light); color: var(--dark); margin: 0; overflow-x: hidden; }
        .sidebar { background: var(--sidebar); color: #cbd5e1; min-height: 100vh; width: 280px; position: fixed; top: 0; left: 0; z-index: 1040; transition: 0.3s; padding-top: 20px; overflow-y: auto; }
        .sidebar-brand { font-size: 1.8rem; font-weight: 900; color: #fff; text-align: center; margin-bottom: 30px; display: block; text-decoration: none; }
        .sidebar-brand span { color: var(--primary); }
        .nav-sidebar { list-style: none; padding: 0; margin: 0; }
        .nav-sidebar li { padding: 5px 20px; }
        .nav-sidebar a { display: flex; align-items: center; justify-content: space-between; color: #94a3b8; text-decoration: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .nav-sidebar a .menu-text { display: flex; align-items: center; gap: 15px; }
        .nav-sidebar a:hover, .nav-sidebar a.active { background: rgba(21,128,61,0.15); color: #22c55e; }
        .nav-sidebar a i { font-size: 1.2rem; width: 25px; text-align: center; }
        .main-content { margin-left: 280px; padding: 30px; transition: 0.3s; min-height: 100vh; }
        .top-navbar { background: #fff; padding: 15px 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border: 1px solid rgba(0,0,0,0.03); }
        .admin-profile { display: flex; align-items: center; gap: 15px; font-weight: 700; color: var(--dark); }
        .admin-avatar { width: 45px; height: 45px; background: var(--primary); color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .page-title { font-weight: 900; font-size: 1.8rem; margin-bottom: 30px; color: var(--dark); letter-spacing: -1px; display: flex; justify-content: space-between; align-items: center; }
        .stat-card { background: #fff; border-radius: 20px; padding: 30px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px; transition: 0.3s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .stat-icon { width: 70px; height: 70px; border-radius: 16px; background: rgba(21,128,61,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .stat-info h3 { font-size: 2.5rem; font-weight: 900; margin: 0; color: var(--dark); line-height: 1; }
        .stat-info p { margin: 5px 0 0 0; color: #64748b; font-weight: 600; }
        .content-card { background: #fff; border-radius: 24px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.03); overflow-x: auto; }
        .form-label { font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px 18px; border: 1px solid #e2e8f0; font-weight: 500; background: #f8fafc; transition: 0.3s; }
        .form-control:focus, .form-select:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(21,128,61,0.1); outline: none; }
        
        .btn-custom { background: var(--primary); color: #fff; font-weight: 800; padding: 14px 30px; border-radius: 12px; border: none; transition: 0.3s; }
        .btn-custom:hover { background: #166534; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(21,128,61,0.2); color: #fff; }
        
        .btn-action { background: #16a34a; color: #fff; font-weight: 800; font-size: 0.85rem; padding: 8px 20px; border-radius: 8px; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(22,163,74,0.2); text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-action:hover { background: #15803d; transform: translateY(-2px); color: #fff; }

        .table { vertical-align: middle; }
        .table thead th { border-bottom: 2px solid #f1f5f9; color: #64748b; font-weight: 700; padding-bottom: 15px; }
        .table tbody td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; font-weight: 600; }
        .img-thumbnail-custom { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }
        .mobile-toggle { display: none; background: transparent; border: none; font-size: 1.8rem; color: var(--dark); cursor: pointer; }
        .badge-notification { background: #ef4444; color: #fff; font-size: 0.75rem; padding: 3px 8px; border-radius: 50px; font-weight: 800; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-toggle { display: block; }
            .top-navbar { padding: 15px 20px; border-radius: 16px; margin-bottom: 25px; }
            .stat-card { margin-bottom: 20px; }
            .content-card { padding: 20px; }
            .page-title { flex-direction: column; align-items: flex-start; gap: 15px; font-size: 1.6rem; }
            .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1030; }
            .overlay.show { display: block; }
        }
    </style>
</head>
<body>

<div class="overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">
    <a href="index.php" class="sidebar-brand" target="_blank"><i class="fa-brands fa-envira me-2 text-success"></i>Kozdere<span>.</span></a>
    <ul class="nav-sidebar">
        <li><a href="admin.php?sayfa=dashboard" class="<?= $sayfa == 'dashboard' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-gauge-high"></i> Dashboard</div></a></li>
        <li><a href="admin.php?sayfa=ayarlar" class="<?= $sayfa == 'ayarlar' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-gear"></i> Site Ayarları</div></a></li>
        <li><a href="admin.php?sayfa=haberler" class="<?= $sayfa == 'haberler' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-bullhorn"></i> İlanlar & İçerik</div></a></li>
        <li><a href="admin.php?sayfa=galeri" class="<?= $sayfa == 'galeri' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-photo-film"></i> Medya Arşivi</div></a></li>
        <li><a href="admin.php?sayfa=pazar" class="<?= $sayfa == 'pazar' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-shop"></i> Köy Pazarı</div></a></li>
        <li><a href="admin.php?sayfa=slider" class="<?= $sayfa == 'slider' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-images"></i> Slider Yönetimi</div></a></li>
        
        <li class="mt-3 mb-2 px-4 text-secondary small fw-bold text-uppercase">Kullanıcı & Etkileşim</li>
        <li><a href="admin.php?sayfa=uyeler" class="<?= $sayfa == 'uyeler' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-users"></i> Üye Yönetimi</div> <?= $bekleyen_uye > 0 ? '<span class="badge-notification">'.$bekleyen_uye.'</span>' : '' ?></a></li>
        <li><a href="admin.php?sayfa=yorumlar" class="<?= $sayfa == 'yorumlar' ? 'active' : '' ?>"><div class="menu-text"><i class="fa-solid fa-comments"></i> Yorum Yönetimi</div> <?= $bekleyen_yorum > 0 ? '<span class="badge-notification">'.$bekleyen_yorum.'</span>' : '' ?></a></li>
        
        <li class="mt-4"><a href="admin.php?cikis=1" class="text-danger"><div class="menu-text"><i class="fa-solid fa-right-from-bracket"></i> Güvenli Çıkış</div></a></li>
    </ul>
</div>

<div class="main-content">
    <div class="top-navbar">
        <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fa-solid fa-bars-staggered"></i></button>
        <div class="d-none d-md-block fw-bold text-secondary">Kozdere Yönetim Merkezi</div>
        <div class="admin-profile">
            <div class="text-end d-none d-sm-block">
                <div class="mb-0 lh-1"><?= htmlspecialchars($_SESSION['admin_isim'] ?? 'Yönetici') ?></div>
                <small class="text-success">Yetkili Yönetici</small>
            </div>
            <div class="admin-avatar"><i class="fa-solid fa-user-shield"></i></div>
        </div>
    </div>

    <?php if ($mesaj != ''): ?>
    <div class="alert alert-<?= $mesaj_tip ?> alert-dismissible fade show fw-bold rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid <?= $mesaj_tip == 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation' ?> me-2"></i> <?= $mesaj ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if ($sayfa == 'dashboard'): ?>
    <h2 class="page-title">Sistem Özeti</h2>
    <div class="row g-4">
        <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-bullhorn"></i></div><div class="stat-info"><h3><?= $toplam_haber ?></h3><p>Aktif İlan</p></div></div></div>
        <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;"><i class="fa-solid fa-shop"></i></div><div class="stat-info"><h3><?= $toplam_urun ?></h3><p>Ürün</p></div></div></div>
        <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon" style="background: rgba(202,138,4,0.1); color: #ca8a04;"><i class="fa-solid fa-users"></i></div><div class="stat-info"><h3><?= $toplam_uye ?></h3><p>Kayıtlı Üye</p></div></div></div>
        <div class="col-lg-3 col-md-6"><div class="stat-card"><div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;"><i class="fa-solid fa-bell"></i></div><div class="stat-info"><h3><?= $bekleyen_uye + $bekleyen_yorum ?></h3><p>Bekleyen İşlem</p></div></div></div>
    </div>
    
    <?php elseif ($sayfa == 'galeri'): ?>
    <div class="page-title">
        <span><i class="fa-solid fa-camera-retro me-2 text-success"></i>Medya Arşivi</span>
        <div>
            <button class="btn-action bg-dark" data-bs-toggle="modal" data-bs-target="#videoEkleModal"><i class="fa-solid fa-video"></i> Video Ekle</button>
            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#fotoEkleModal"><i class="fa-solid fa-image"></i> Fotoğraf Ekle</button>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Fotoğraflar</h5>
                <table class="table table-hover">
                    <thead><tr><th>Görsel</th><th>Başlık / Albüm</th><th>İşlem</th></tr></thead>
                    <tbody>
                        <?php $fotolar = $db->query("SELECT * FROM fotograflar ORDER BY id DESC")->fetchAll(); foreach($fotolar as $f): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($f['url']) ?>" class="img-thumbnail-custom"></td>
                            <td><?= htmlspecialchars($f['baslik']) ?><br><span class="badge bg-secondary"><?= strtoupper($f['album']) ?></span></td>
                            <td><a href="?sayfa=galeri&sil=<?= $f['id'] ?>&tablo=fotograflar" class="btn btn-sm btn-danger fw-bold rounded-3" onclick="return confirm('Emin misiniz?');"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Videolar</h5>
                <table class="table table-hover">
                    <thead><tr><th>Kapak</th><th>Başlık</th><th>İşlem</th></tr></thead>
                    <tbody>
                        <?php $videolar = $db->query("SELECT * FROM videolar ORDER BY id DESC")->fetchAll(); foreach($videolar as $v): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($v['thumb']) ?>" class="img-thumbnail-custom"></td>
                            <td><?= htmlspecialchars($v['baslik']) ?></td>
                            <td><a href="?sayfa=galeri&sil=<?= $v['id'] ?>&tablo=videolar" class="btn btn-sm btn-danger fw-bold rounded-3" onclick="return confirm('Emin misiniz?');"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoEkleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-4">Yeni Fotoğraf Ekle</h5>
                    <form action="admin.php?sayfa=galeri" method="POST">
                        <div class="mb-3"><label class="form-label">Albüm</label><select name="album" class="form-select"><option value="manzara">Manzara</option><option value="dugun">Düğün</option><option value="piknik">Piknik</option><option value="etkinlik">Etkinlik</option></select></div>
                        <div class="mb-3"><label class="form-label">Fotoğraf Başlığı</label><input type="text" name="baslik" class="form-control" required></div>
                        <div class="mb-4"><label class="form-label">Resim URL (Link)</label><input type="text" name="url" class="form-control" placeholder="https://..." required></div>
                        <button type="submit" name="foto_ekle" class="btn-custom w-100">Fotoğrafı Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoEkleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-4">Yeni Video Ekle</h5>
                    <form action="admin.php?sayfa=galeri" method="POST">
                        <div class="mb-3"><label class="form-label">Video Başlığı</label><input type="text" name="baslik" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">YouTube Embed URL</label><input type="text" name="embed" class="form-control" placeholder="https://www.youtube.com/embed/..." required></div>
                        <div class="mb-4"><label class="form-label">Kapak Resmi URL</label><input type="text" name="thumb" class="form-control" placeholder="https://..." required></div>
                        <button type="submit" name="video_ekle" class="btn-custom bg-dark w-100">Videoyu Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php elseif ($sayfa == 'ayarlar'): ?>
    <h2 class="page-title">Site Ayarları</h2>
    <div class="content-card">
        <form action="admin.php?sayfa=ayarlar" method="POST">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label">Ana Başlık</label><input type="text" name="baslik" class="form-control" value="<?= htmlspecialchars($site['baslik']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Muhtar Adı</label><input type="text" name="muhtar" class="form-control" value="<?= htmlspecialchars($site['muhtar']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Nüfus</label><input type="text" name="nufus" class="form-control" value="<?= htmlspecialchars($site['nufus']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Hane</label><input type="text" name="hane" class="form-control" value="<?= htmlspecialchars($site['hane']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Rakım</label><input type="text" name="rakim" class="form-control" value="<?= htmlspecialchars($site['rakim']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Telefon</label><input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($site['telefon']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">WhatsApp (905...)</label><input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($site['whatsapp']) ?>" required></div>
                <div class="col-12 mt-4 text-end"><button type="submit" name="ayarlar_guncelle" class="btn-custom"><i class="fa-solid fa-save me-2"></i>Kaydet</button></div>
            </div>
        </form>
    </div>

    <?php elseif ($sayfa == 'uyeler'): ?>
    <h2 class="page-title">Kullanıcı Yönetimi</h2>
    <div class="content-card">
        <table class="table table-hover">
            <thead><tr><th>Durum</th><th>İsim Soyisim</th><th>E-Posta / Bağlılık</th><th>Rol</th><th>İşlemler</th></tr></thead>
            <tbody>
                <?php $kullanici_liste = $db->query("SELECT * FROM kullanicilar ORDER BY id DESC")->fetchAll(); foreach($kullanici_liste as $k): ?>
                <tr>
                    <td>
                        <?php if($k['durum'] == 1): ?>
                            <span class="badge bg-success rounded-pill"><i class="fa-solid fa-check"></i> Onaylı</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill"><i class="fa-solid fa-clock"></i> Bekliyor</span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold"><?= htmlspecialchars($k['ad'] . ' ' . $k['soyad']) ?></td>
                    <td>
                        <div><?= htmlspecialchars($k['eposta']) ?></div>
                        <small class="text-success fw-bold"><?= htmlspecialchars($k['baglilik']) ?></small>
                    </td>
                    <td><span class="badge bg-<?= $k['rol'] == 'editor' ? 'danger' : 'secondary' ?>"><?= strtoupper($k['rol']) ?></span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <?php if($k['durum'] == 0): ?>
                                <a href="?sayfa=uyeler&onayla=<?= $k['id'] ?>&tablo=kullanicilar" class="btn btn-sm btn-success fw-bold"><i class="fa-solid fa-check"></i></a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#rolModal<?= $k['id'] ?>"><i class="fa-solid fa-user-gear"></i></button>
                            <a href="?sayfa=uyeler&sil=<?= $k['id'] ?>&tablo=kullanicilar" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Üyeyi silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i></a>
                        </div>

                        <div class="modal fade" id="rolModal<?= $k['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4">
                                    <div class="modal-body p-4 text-center">
                                        <h5 class="fw-bold mb-3">Kullanıcı Yetkisini Değiştir</h5>
                                        <p class="text-secondary small mb-4"><strong><?= htmlspecialchars($k['ad'] . ' ' . $k['soyad']) ?></strong> kullanıcısına Editör yetkisi verirseniz, onay beklemeden sisteme içerik ekleyebilir.</p>
                                        <form action="admin.php?sayfa=uyeler" method="POST">
                                            <input type="hidden" name="uye_id" value="<?= $k['id'] ?>">
                                            <select name="yeni_rol" class="form-select mb-4">
                                                <option value="uye" <?= $k['rol'] == 'uye' ? 'selected' : '' ?>>Standart Üye</option>
                                                <option value="editor" <?= $k['rol'] == 'editor' ? 'selected' : '' ?>>İçerik Editörü</option>
                                            </select>
                                            <button type="submit" name="rol_degistir" class="btn-custom w-100">Yetkiyi Güncelle</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($sayfa == 'yorumlar'): ?>
    <h2 class="page-title">Yorum Yönetimi</h2>
    <div class="content-card">
        <table class="table table-hover">
            <thead><tr><th>Durum</th><th>Gönderen</th><th>Yorum İçeriği</th><th>Tarih</th><th>İşlemler</th></tr></thead>
            <tbody>
                <?php $yorum_liste = $db->query("SELECT * FROM yorumlar ORDER BY id DESC")->fetchAll(); foreach($yorum_liste as $y): ?>
                <tr>
                    <td>
                        <?php if($y['durum'] == 1): ?>
                            <span class="badge bg-success rounded-pill"><i class="fa-solid fa-check"></i> Yayında</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill"><i class="fa-solid fa-clock"></i> Bekliyor</span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold"><?= htmlspecialchars($y['ad_soyad']) ?></td>
                    <td>
                        <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($y['yorum']) ?></div>
                        <a href="haber_detay.php?id=<?= $y['haber_id'] ?>" target="_blank" class="small text-primary fw-bold text-decoration-none"><i class="fa-solid fa-link"></i> Habere Git</a>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($y['tarih'])) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <?php if($y['durum'] == 0): ?>
                                <a href="?sayfa=yorumlar&onayla=<?= $y['id'] ?>&tablo=yorumlar" class="btn btn-sm btn-success fw-bold"><i class="fa-solid fa-check"></i> Onayla</a>
                            <?php endif; ?>
                            <a href="?sayfa=yorumlar&sil=<?= $y['id'] ?>&tablo=yorumlar" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Yorumu silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($sayfa == 'haberler'): ?>
    <div class="page-title">
        <span>Haber & İlan Yönetimi</span>
        <button class="btn-action" data-bs-toggle="modal" data-bs-target="#haberEkleModal"><i class="fa-solid fa-plus"></i> Yeni Ekle</button>
    </div>
    <div class="content-card">
        <table class="table table-hover">
            <thead><tr><th>Tip</th><th>Başlık</th><th>Tarih</th><th>Durum/Ekleyen</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php $haberler_liste = $db->query("SELECT * FROM haberler ORDER BY id DESC")->fetchAll(); foreach($haberler_liste as $h): ?>
                <tr>
                    <td><span class="badge bg-<?= $h['renk'] ?>"><?= strtoupper($h['tip']) ?></span></td>
                    <td><?= htmlspecialchars($h['baslik']) ?></td>
                    <td><?= htmlspecialchars($h['tarih']) ?></td>
                    <td>
                        <?php if($h['durum'] == 1): ?>
                            <span class="badge bg-success rounded-pill"><i class="fa-solid fa-check"></i> Yayında</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill"><i class="fa-solid fa-clock"></i> Bekliyor</span>
                        <?php endif; ?>
                        <br>
                        <small class="text-secondary">
                            <?php 
                            if(isset($h['yazar_id']) && $h['yazar_id'] > 0) {
                                $yazar = $db->query("SELECT ad, soyad FROM kullanicilar WHERE id=".$h['yazar_id'])->fetch();
                                echo $yazar ? $yazar['ad'].' '.$yazar['soyad'] : 'Editör';
                            } else {
                                echo 'Admin';
                            }
                            ?>
                        </small>
                    </td>
                    <td><a href="?sayfa=haberler&sil=<?= $h['id'] ?>&tablo=haberler" class="btn btn-sm btn-danger fw-bold rounded-3" onclick="return confirm('Silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="haberEkleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Yeni İçerik Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <form action="admin.php?sayfa=haberler" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Kategori Tipi</label><select name="tip" class="form-select"><option value="haber">Haber</option><option value="vefat">Vefat / Taziye</option><option value="dugun">Düğün / Cemiyet</option><option value="duyuru">Duyuru</option><option value="etkinlik">Etkinlik</option></select></div>
                            <div class="col-md-6"><label class="form-label">Renk</label><select name="renk" class="form-select"><option value="primary">Mavi (Haber)</option><option value="danger">Kırmızı (Vefat/Düğün)</option><option value="warning">Sarı (Duyuru)</option><option value="success">Yeşil (Etkinlik)</option></select></div>
                            <div class="col-12"><label class="form-label">Başlık</label><input type="text" name="baslik" class="form-control" required></div>
                            <div class="col-12"><label class="form-label">Özet / İçerik</label><textarea name="ozet" class="form-control" rows="4" required></textarea></div>
                            <div class="col-md-6"><label class="form-label">Tarih</label><input type="text" name="tarih" class="form-control" placeholder="Örn: 26 Mart 2026" required></div>
                            <div class="col-md-6"><label class="form-label">İkon Sınıfı (FontAwesome)</label><input type="text" name="ikon" class="form-control" value="fa-newspaper" required></div>
                            <div class="col-12 text-end mt-4"><button type="submit" name="haber_ekle" class="btn-custom">Ekle</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php elseif ($sayfa == 'pazar'): ?>
    <div class="page-title">
        <span>Köy Pazarı Ürünleri</span>
        <button class="btn-action" data-bs-toggle="modal" data-bs-target="#pazarEkleModal"><i class="fa-solid fa-plus"></i> Ürün Ekle</button>
    </div>
    <div class="content-card">
        <table class="table table-hover">
            <thead><tr><th>Görsel</th><th>Ürün Adı</th><th>Satıcı</th><th>Fiyat</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php $pazar_liste = $db->query("SELECT * FROM pazar ORDER BY id DESC")->fetchAll(); foreach($pazar_liste as $p): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($p['resim']) ?>" class="img-thumbnail-custom"></td>
                    <td><?= htmlspecialchars($p['isim']) ?><br><small class="text-secondary"><?= htmlspecialchars($p['birim']) ?></small></td>
                    <td><?= htmlspecialchars($p['satici']) ?></td>
                    <td class="text-success fw-bold"><?= htmlspecialchars($p['fiyat']) ?></td>
                    <td><a href="?sayfa=pazar&sil=<?= $p['id'] ?>&tablo=pazar" class="btn btn-sm btn-danger fw-bold rounded-3" onclick="return confirm('Silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="pazarEkleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Yeni Ürün Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <form action="admin.php?sayfa=pazar" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Ürün Adı</label><input type="text" name="isim" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Fiyat (Örn: 150 ₺)</label><input type="text" name="fiyat" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Satıcı Adı</label><input type="text" name="satici" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Satıcı Telefon (905...)</label><input type="text" name="telefon" class="form-control" required></div>
                            <div class="col-md-8"><label class="form-label">Resim URL (Link)</label><input type="text" name="resim" class="form-control" value="https://placehold.co/600x400/15803d/fff?text=Urun" required></div>
                            <div class="col-md-4"><label class="form-label">Birim (Örn: 1 Kg)</label><input type="text" name="birim" class="form-control" required></div>
                            <div class="col-12 text-end mt-4"><button type="submit" name="pazar_ekle" class="btn-custom">Ekle</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php elseif ($sayfa == 'slider'): ?>
    <div class="page-title">
        <span>Ana Sayfa Slider</span>
        <button class="btn-action" data-bs-toggle="modal" data-bs-target="#sliderEkleModal"><i class="fa-solid fa-plus"></i> Slayt Ekle</button>
    </div>
    <div class="content-card">
        <table class="table table-hover">
            <thead><tr><th>Görsel</th><th>Başlık</th><th>Buton</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php $slider_liste = $db->query("SELECT * FROM slider ORDER BY sira ASC")->fetchAll(); foreach($slider_liste as $s): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($s['resim']) ?>" class="img-thumbnail-custom" style="width: 100px;"></td>
                    <td><?= htmlspecialchars($s['baslik']) ?></td>
                    <td><span class="badge bg-dark"><?= htmlspecialchars($s['buton_text']) ?></span></td>
                    <td><a href="?sayfa=slider&sil=<?= $s['id'] ?>&tablo=slider" class="btn btn-sm btn-danger fw-bold rounded-3" onclick="return confirm('Silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="sliderEkleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Yeni Slayt Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <form action="admin.php?sayfa=slider" method="POST">
                        <div class="row g-3">
                            <div class="col-12"><label class="form-label">Ana Başlık</label><input type="text" name="baslik" class="form-control" required></div>
                            <div class="col-12"><label class="form-label">Alt Başlık (Açıklama)</label><input type="text" name="alt_baslik" class="form-control" required></div>
                            <div class="col-12"><label class="form-label">Resim URL (Link)</label><input type="text" name="resim" class="form-control" value="https://placehold.co/1920x800/020617/ffffff?text=Kozdere" required></div>
                            <div class="col-md-4"><label class="form-label">Buton Yazısı</label><input type="text" name="buton_text" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Buton Linki</label><input type="text" name="buton_link" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Sıra No</label><input type="number" name="sira" class="form-control" value="1" required></div>
                            <div class="col-12 text-end mt-4"><button type="submit" name="slider_ekle" class="btn-custom">Ekle</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('mobileOverlay').classList.toggle('show');
    }
</script>
</body>
</html>