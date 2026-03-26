<?php
require_once 'baglan.php';

if (isset($db)) {
    $tablo_kontrol = $db->query("SHOW TABLES LIKE 'yorumlar'")->rowCount();
    if ($tablo_kontrol == 0) {
        $db->exec("CREATE TABLE yorumlar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            haber_id INT NOT NULL,
            ad_soyad VARCHAR(100) NOT NULL,
            yorum TEXT NOT NULL,
            durum TINYINT(1) DEFAULT 0,
            tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$haber = null;

if ($id > 0 && isset($db)) {
    $sorgu = $db->prepare("SELECT * FROM haberler WHERE id = ? AND durum = 1");
    $sorgu->execute([$id]);
    $haber = $sorgu->fetch();
}

if (!$haber) {
    header("Location: index.php");
    exit;
}

$mesaj = '';
$mesaj_tip = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['yorum_yap'])) {
    $ad_soyad = trim($_POST['ad_soyad']);
    $yorum = trim($_POST['yorum']);
    
    if ($ad_soyad != '' && $yorum != '') {
        $ekle = $db->prepare("INSERT INTO yorumlar (haber_id, ad_soyad, yorum) VALUES (?, ?, ?)");
        if ($ekle->execute([$id, $ad_soyad, $yorum])) {
            $mesaj = 'Yorumunuz başarıyla gönderildi. Yönetici onayından sonra yayınlanacaktır.';
            $mesaj_tip = 'success';
        } else {
            $mesaj = 'Yorum gönderilirken bir hata oluştu.';
            $mesaj_tip = 'danger';
        }
    }
}

$onayli_yorumlar = [];
if (isset($db)) {
    $y_sorgu = $db->prepare("SELECT * FROM yorumlar WHERE haber_id = ? AND durum = 1 ORDER BY id DESC");
    $y_sorgu->execute([$id]);
    $onayli_yorumlar = $y_sorgu->fetchAll();
    
    $ilgili_sorgu = $db->prepare("SELECT * FROM haberler WHERE tip = ? AND id != ? AND durum = 1 ORDER BY id DESC LIMIT 3");
    $ilgili_sorgu->execute([$haber['tip'], $id]);
    $ilgili_haberler = $ilgili_sorgu->fetchAll();
}

$badge_class = 'bg-primary';
if ($haber['tip'] == 'vefat') $badge_class = 'bg-dark';
if ($haber['tip'] == 'dugun') $badge_class = 'bg-danger';
if ($haber['tip'] == 'duyuru') $badge_class = 'bg-warning text-dark';
if ($haber['tip'] == 'etkinlik') $badge_class = 'bg-success';

$sayfa_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($haber['baslik']) ?> - <?= htmlspecialchars($site['baslik']) ?></title>
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
        
        .article-header { padding: 60px 0 30px 0; }
        .article-badge { display: inline-block; padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 20px; color: #fff; }
        .article-title { font-weight: 900; font-size: 3rem; color: var(--dark); line-height: 1.2; margin-bottom: 20px; letter-spacing: -1px; }
        .article-meta { display: flex; flex-wrap: wrap; gap: 25px; color: var(--gray); font-weight: 600; margin-bottom: 40px; font-size: 0.95rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        .article-meta i { color: var(--primary); }
        
        .article-cover { width: 100%; border-radius: 24px; max-height: 500px; object-fit: cover; box-shadow: 0 20px 40px rgba(0,0,0,0.08); margin-bottom: 40px; }
        .article-content { font-size: 1.15rem; line-height: 1.8; color: #334155; font-weight: 500; }
        .article-content p { margin-bottom: 20px; }
        
        .share-box { background: #fff; border-radius: 16px; padding: 25px; text-align: center; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-top: 40px; }
        .share-title { font-weight: 800; color: var(--dark); margin-bottom: 15px; font-size: 1.1rem; }
        .share-btn { display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; color: #fff; font-size: 1.2rem; transition: 0.3s; margin: 0 5px; text-decoration: none; }
        .share-btn:hover { transform: translateY(-3px); color: #fff; }
        .share-wp { background: #25D366; } .share-fb { background: #1877F2; } .share-x { background: #000000; }
        
        .sidebar-widget { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 30px; }
        .w-title { font-weight: 800; font-size: 1.2rem; color: var(--dark); margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        
        .related-item { display: flex; gap: 15px; margin-bottom: 20px; text-decoration: none; color: inherit; transition: 0.3s; }
        .related-item:hover { transform: translateX(5px); }
        .related-item:last-child { margin-bottom: 0; }
        .related-img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; }
        .related-info { flex-grow: 1; }
        .related-title { font-weight: 800; font-size: 0.95rem; color: var(--dark); margin-bottom: 5px; line-height: 1.3; }
        .related-date { font-size: 0.8rem; color: var(--gray); font-weight: 600; }

        .comments-section { background: #fff; border-radius: 24px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.03); margin-top: 50px; }
        .comment-item { display: flex; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 25px; }
        .comment-item:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0; }
        .comment-avatar { width: 50px; height: 50px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--gray); font-weight: 900; }
        .comment-body h5 { font-weight: 800; font-size: 1rem; margin-bottom: 5px; color: var(--dark); }
        .comment-date { font-size: 0.8rem; color: var(--gray); font-weight: 600; display: block; margin-bottom: 10px; }
        .comment-text { margin: 0; font-size: 0.95rem; color: #475569; line-height: 1.6; }
        
        .form-control { border-radius: 12px; padding: 14px 20px; border: 1px solid #e2e8f0; font-weight: 500; background: #f8fafc; transition: 0.3s; }
        .form-control:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(21,128,61,0.1); outline: none; }
        .btn-submit { background: var(--dark); color: #fff; font-weight: 800; padding: 14px 30px; border-radius: 12px; border: none; transition: 0.3s; }
        .btn-submit:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        footer { background: var(--dark); color: #cbd5e1; padding: 60px 0 30px 0; margin-top: 80px; }
        .f-title { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 25px; }
        .f-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: 600; transition: 0.3s; }
        .f-link:hover { color: var(--primary); transform: translateX(5px); }

        @media (max-width: 991px) {
            .navbar-collapse { background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); position: absolute; top: 100%; left: 15px; right: 15px; border: 1px solid rgba(0,0,0,0.05); }
            .nav-link { text-align: center; padding: 15px !important; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; }
            .dropdown-menu { border: none; box-shadow: none; background: #f8fafc; margin-top: 10px; }
            .btn-custom { display: flex; width: 100%; justify-content: center; padding: 15px; font-size: 1.1rem; margin-top: 15px; }
            .article-title { font-size: 2.2rem; }
            .article-meta { flex-direction: column; gap: 10px; }
            .comments-section { padding: 25px 20px; }
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

<div class="container pb-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="article-header" data-aos="fade-up">
                <span class="article-badge <?= $badge_class ?>"><?= mb_strtoupper($haber['tip'], 'UTF-8') ?></span>
                <h1 class="article-title"><?= htmlspecialchars($haber['baslik']) ?></h1>
                <div class="article-meta">
                    <span><i class="fa-regular fa-calendar me-2"></i> <?= htmlspecialchars($haber['tarih']) ?></span>
                    <span><i class="fa-regular fa-clock me-2"></i> 2 Dk Okuma</span>
                    <span><i class="fa-solid fa-user-pen me-2"></i> Sistem / Editör</span>
                </div>
                
                <?php 
                $resim = isset($haber['resim']) && $haber['resim'] != '' ? $haber['resim'] : 'https://placehold.co/1200x600/1e293b/fff?text='.urlencode($haber['baslik']);
                ?>
                <img src="<?= htmlspecialchars($resim) ?>" alt="<?= htmlspecialchars($haber['baslik']) ?>" class="article-cover">
                
                <div class="article-content">
                    <p><?= nl2br(htmlspecialchars($haber['ozet'])) ?></p>
                </div>
                
                <div class="share-box">
                    <h4 class="share-title">Bu Haberi Paylaş</h4>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($haber['baslik'] . " - " . $sayfa_url) ?>" target="_blank" class="share-btn share-wp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($sayfa_url) ?>" target="_blank" class="share-btn share-fb"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($haber['baslik']) ?>&url=<?= urlencode($sayfa_url) ?>" target="_blank" class="share-btn share-x"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>

            <div class="comments-section" data-aos="fade-up" id="yorumlar">
                <h3 class="fw-bold mb-4" style="color: var(--dark);"><i class="fa-regular fa-comments me-2 text-primary"></i>Yorumlar & Düşünceler</h3>
                
                <?php if ($mesaj != ''): ?>
                <div class="alert alert-<?= $mesaj_tip ?> fw-bold rounded-3 border-0">
                    <i class="fa-solid <?= $mesaj_tip == 'success' ? 'fa-check-circle' : 'fa-triangle-exclamation' ?> me-2"></i> <?= htmlspecialchars($mesaj) ?>
                </div>
                <?php endif; ?>

                <?php if (empty($onayli_yorumlar)): ?>
                    <p class="text-secondary fw-medium mb-5 pb-3 border-bottom">Bu içeriğe henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                <?php else: ?>
                    <div class="mb-5">
                        <?php foreach($onayli_yorumlar as $y): ?>
                        <div class="comment-item">
                            <div class="comment-avatar"><?= mb_strtoupper(mb_substr($y['ad_soyad'], 0, 1, 'UTF-8'), 'UTF-8') ?></div>
                            <div class="comment-body">
                                <h5><?= htmlspecialchars($y['ad_soyad']) ?></h5>
                                <span class="comment-date"><?= date('d.m.Y H:i', strtotime($y['tarih'])) ?></span>
                                <p class="comment-text"><?= nl2br(htmlspecialchars($y['yorum'])) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="comment-form-wrap bg-light p-4 rounded-4 border">
                    <h4 class="fw-bold mb-3">Yorum Bırakın</h4>
                    <form action="haber_detay.php?id=<?= $id ?>#yorumlar" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Adınız Soyadınız</label>
                            <input type="text" name="ad_soyad" class="form-control" value="<?= isset($_SESSION['uye_ad']) ? htmlspecialchars($_SESSION['uye_ad'].' '.$_SESSION['uye_soyad']) : '' ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Yorumunuz</label>
                            <textarea name="yorum" class="form-control" rows="4" placeholder="Düşüncelerinizi buraya yazabilirsiniz..." required></textarea>
                        </div>
                        <button type="submit" name="yorum_yap" class="btn-submit"><i class="fa-solid fa-paper-plane me-2"></i>Yorumu Gönder</button>
                        <p class="small text-secondary mt-3 fw-bold mb-0"><i class="fa-solid fa-shield-check me-1 text-success"></i> Yorumunuz yönetici onayından sonra listelenecektir.</p>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; padding-top: 60px;">
                <div class="sidebar-widget" data-aos="fade-left">
                    <h4 class="w-title"><i class="fa-solid fa-list me-2 text-primary"></i>İlgili <?= ucfirst($haber['tip']) ?>lar</h4>
                    
                    <?php if (empty($ilgili_haberler)): ?>
                        <p class="text-secondary small fw-medium mb-0">Bu kategoride başka içerik bulunmuyor.</p>
                    <?php else: ?>
                        <?php foreach($ilgili_haberler as $ih): 
                            $i_resim = isset($ih['resim']) && $ih['resim'] != '' ? $ih['resim'] : 'https://placehold.co/150x150/1e293b/fff?text=Haber';
                        ?>
                        <a href="haber_detay.php?id=<?= $ih['id'] ?>" class="related-item">
                            <img src="<?= htmlspecialchars($i_resim) ?>" class="related-img" alt="<?= htmlspecialchars($ih['baslik']) ?>">
                            <div class="related-info">
                                <h5 class="related-title"><?= htmlspecialchars($ih['baslik']) ?></h5>
                                <span class="related-date"><i class="fa-regular fa-clock me-1"></i> <?= htmlspecialchars($ih['tarih']) ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                    <h4 class="w-title"><i class="fa-solid fa-envelope-open-text me-2 text-warning"></i>Bültene Katıl</h4>
                    <p class="small text-secondary fw-medium mb-3">Köyümüzle ilgili önemli duyuruları mailinize gönderelim.</p>
                    <form action="#" method="POST" class="d-flex flex-column gap-2">
                        <input type="email" class="form-control" placeholder="E-Posta Adresiniz" required>
                        <button type="button" class="btn btn-dark fw-bold w-100 rounded-3 py-2">Kayıt Ol</button>
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
</script>
</body>
</html>