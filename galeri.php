<?php
require_once 'baglan.php';

$fotograflar = [];
$videolar = [];

if (isset($db) && $db) {
    $foto_sorgu = $db->query("SELECT * FROM fotograflar WHERE durum = 1 ORDER BY id DESC")->fetchAll();
    if ($foto_sorgu && count($foto_sorgu) > 0) {
        $fotograflar = $foto_sorgu;
    }
    
    $video_sorgu = $db->query("SELECT * FROM videolar WHERE durum = 1 ORDER BY id DESC")->fetchAll();
    if ($video_sorgu && count($video_sorgu) > 0) {
        $videolar = $video_sorgu;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Köy Galerisi - <?= htmlspecialchars($site['baslik']) ?></title>
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
        
        .page-header { background: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.95)), url('https://placehold.co/1920x400/0f172a/fff?text=Galeri') center/cover; padding: 100px 0; color: #fff; text-align: center; }
        .page-title { font-weight: 900; font-size: 3rem; letter-spacing: -1px; margin-bottom: 15px; }

        .gallery-tabs { display: flex; justify-content: center; gap: 15px; margin-top: -30px; position: relative; z-index: 10; padding: 0 15px; }
        .tab-btn { background: #fff; border: 1px solid rgba(0,0,0,0.05); color: var(--dark); font-weight: 800; padding: 15px 40px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: 0.3s; cursor: pointer; display: flex; align-items: center; gap: 10px; }
        .tab-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }

        .album-filters { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin: 40px 0; }
        .filter-btn { background: transparent; border: 2px solid #e2e8f0; color: var(--gray); font-weight: 700; padding: 8px 25px; border-radius: 50px; transition: 0.3s; }
        .filter-btn.active, .filter-btn:hover { background: var(--dark); border-color: var(--dark); color: #fff; }

        .gallery-item { position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); cursor: pointer; transition: 0.4s; }
        .gallery-item:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .gallery-img { width: 100%; height: 250px; object-fit: cover; transition: 0.5s; }
        .gallery-item:hover .gallery-img { transform: scale(1.1); }
        .gallery-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.9), transparent); opacity: 0; transition: 0.4s; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px; }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-title { color: #fff; font-weight: 800; margin: 0; font-size: 1.1rem; transform: translateY(20px); transition: 0.4s; }
        .gallery-item:hover .gallery-title { transform: translateY(0); }

        .video-item { position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); cursor: pointer; transition: 0.4s; }
        .video-item:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .video-thumb { width: 100%; height: 250px; object-fit: cover; transition: 0.5s; }
        .video-item:hover .video-thumb { transform: scale(1.05); filter: brightness(0.7); }
        .play-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1); width: 60px; height: 60px; background: rgba(220,38,38,0.9); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: 0.4s; box-shadow: 0 10px 20px rgba(220,38,38,0.4); }
        .video-item:hover .play-icon { transform: translate(-50%, -50%) scale(1.2); }
        .video-title { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 30px 20px 20px 20px; color: #fff; font-weight: 800; margin: 0; }

        .lightbox { position: fixed; inset: 0; background: rgba(15,23,42,0.95); z-index: 9999; display: none; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
        .lightbox.show { display: flex; opacity: 1; }
        .lightbox-img { max-width: 90%; max-height: 85vh; border-radius: 10px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        .lightbox-close { position: absolute; top: 30px; right: 40px; color: #fff; font-size: 2.5rem; cursor: pointer; transition: 0.3s; }
        .lightbox-close:hover { color: #ef4444; transform: scale(1.1); }

        .video-modal-content { background: transparent; border: none; }
        .video-iframe { width: 100%; height: 60vh; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }

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
            .gallery-tabs { flex-direction: column; padding: 0 20px; }
            .tab-btn { justify-content: center; width: 100%; }
            .gallery-img, .video-thumb { height: 220px; }
            .lightbox-close { top: 15px; right: 20px; font-size: 2rem; }
            .video-iframe { height: 40vh; }
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
                
                <li class="nav-item"><a class="nav-link active" href="galeri.php">Galeri</a></li>
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
        <h1 class="page-title" data-aos="zoom-in">Medya Arşivi</h1>
        <p class="fs-5 text-light opacity-75 fw-medium" data-aos="fade-up" data-aos-delay="100">Köyümüzün anılarını yaşatıyoruz.</p>
    </div>
</div>

<div class="gallery-tabs" data-aos="fade-up" data-aos-delay="200">
    <div class="tab-btn active" onclick="switchTab('foto')"><i class="fa-solid fa-camera-retro"></i> Fotoğraflar</div>
    <div class="tab-btn" onclick="switchTab('video')"><i class="fa-solid fa-circle-play"></i> Videolar</div>
</div>

<div class="container py-5" id="fotoTab">
    <div class="album-filters" data-aos="fade-in">
        <button class="filter-btn active" onclick="filterAlbum('all')">Tümü</button>
        <button class="filter-btn" onclick="filterAlbum('piknik')">Piknikler</button>
        <button class="filter-btn" onclick="filterAlbum('dugun')">Düğünler</button>
        <button class="filter-btn" onclick="filterAlbum('etkinlik')">Etkinlikler</button>
        <button class="filter-btn" onclick="filterAlbum('manzara')">Manzaralar</button>
    </div>

    <div class="row g-4">
        <?php if (empty($fotograflar)): ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-image text-secondary fs-1 mb-3 opacity-50"></i>
                <h4 class="fw-bold text-dark">Henüz fotoğraf eklenmemiş</h4>
            </div>
        <?php else: ?>
            <?php foreach($fotograflar as $i => $foto): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 foto-box <?= htmlspecialchars($foto['album']) ?>" data-aos="zoom-in" data-aos-delay="<?= ($i+1)*50 ?>">
                <div class="gallery-item" onclick="openLightbox('<?= htmlspecialchars($foto['url']) ?>')">
                    <img src="<?= htmlspecialchars($foto['url']) ?>" class="gallery-img" alt="Galeri">
                    <div class="gallery-overlay">
                        <h4 class="gallery-title"><?= htmlspecialchars($foto['baslik']) ?></h4>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="container py-5" id="videoTab" style="display: none;">
    <div class="row g-4 mt-2">
        <?php if (empty($videolar)): ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-video text-secondary fs-1 mb-3 opacity-50"></i>
                <h4 class="fw-bold text-dark">Henüz video eklenmemiş</h4>
            </div>
        <?php else: ?>
            <?php foreach($videolar as $i => $video): ?>
            <div class="col-lg-4 col-md-6">
                <div class="video-item" data-bs-toggle="modal" data-bs-target="#videoModal" onclick="playVideo('<?= htmlspecialchars($video['embed']) ?>')">
                    <img src="<?= htmlspecialchars($video['thumb']) ?>" class="video-thumb" alt="Video">
                    <div class="play-icon"><i class="fa-solid fa-play"></i></div>
                    <h4 class="video-title"><?= htmlspecialchars($video['baslik']) ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <i class="fa-solid fa-xmark lightbox-close"></i>
    <img src="" class="lightbox-img" id="lightboxImg" onclick="event.stopPropagation()">
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content video-modal-content">
            <div class="modal-body p-0">
                <iframe src="" id="videoIframe" class="video-iframe" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4 text-center text-lg-start">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-3" href="index.php" style="font-size: 2rem;"><i class="fa-brands fa-envira text-success me-2"></i>Kozdere<span style="color: var(--primary);">.</span></a>
                <p class="text-secondary fw-medium">Kozdere Dijital Medya Arşivi.</p>
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

    function switchTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        if (tab === 'foto') {
            document.getElementById('fotoTab').style.display = 'block';
            document.getElementById('videoTab').style.display = 'none';
        } else {
            document.getElementById('fotoTab').style.display = 'none';
            document.getElementById('videoTab').style.display = 'block';
        }
    }

    function filterAlbum(kategori) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.querySelectorAll('.foto-box').forEach(box => {
            if (kategori === 'all' || box.classList.contains(kategori)) {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        });
    }

    function openLightbox(url) {
        document.getElementById('lightboxImg').src = url;
        document.getElementById('lightbox').classList.add('show');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('show');
        setTimeout(() => { document.getElementById('lightboxImg').src = ''; }, 300);
    }

    function playVideo(embedUrl) {
        document.getElementById('videoIframe').src = embedUrl + "?autoplay=1";
    }

    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('videoIframe').src = '';
    });
</script>
</body>
</html>