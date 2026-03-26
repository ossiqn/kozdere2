<?php
require_once 'baglan.php';

if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

$hata = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kadi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = trim($_POST['sifre'] ?? '');

    if ($kadi != '' && $sifre != '') {
        $sorgu = $db->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = ?");
        $sorgu->execute([$kadi]);
        $yonetici = $sorgu->fetch();

        if ($yonetici && password_verify($sifre, $yonetici['sifre'])) {
            $_SESSION['admin'] = $yonetici['kullanici_adi'];
            $_SESSION['admin_isim'] = $yonetici['isim'];
            header("Location: admin.php");
            exit;
        } else {
            $hata = 'Hatalı kullanıcı adı veya şifre girdiniz.';
        }
    } else {
        $hata = 'Lütfen tüm alanları eksiksiz doldurun.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Yönetim Paneli Girişi - <?= htmlspecialchars($site['baslik']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #15803d; --primary-hover: #166534; --dark: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(21,128,61,0.9)), url('https://placehold.co/1920x1080/0f172a/fff?text=Kozdere+Koyu') center/cover; height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; margin: 0; }
        .login-box { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; padding: 50px 40px; width: 100%; max-width: 450px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); position: relative; }
        .brand-logo { width: 80px; height: 80px; background: var(--primary); color: #fff; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px auto; box-shadow: 0 10px 25px rgba(21,128,61,0.4); }
        .login-title { font-weight: 900; font-size: 1.8rem; color: var(--dark); text-align: center; margin-bottom: 5px; }
        .login-subtitle { color: #64748b; text-align: center; font-weight: 500; margin-bottom: 30px; font-size: 0.95rem; }
        .form-control { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px 20px; border-radius: 12px; font-weight: 500; font-size: 1rem; color: var(--dark); transition: 0.3s; }
        .form-control:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(21,128,61,0.1); outline: none; }
        .input-group-text { background: #f8fafc; border: 1px solid #e2e8f0; border-right: none; color: #94a3b8; border-radius: 12px 0 0 12px; padding-left: 20px; }
        .form-control.with-icon { border-left: none; padding-left: 10px; }
        .btn-login { background: var(--primary); color: #fff; font-weight: 800; font-size: 1.1rem; padding: 15px; border-radius: 12px; width: 100%; border: none; transition: 0.3s; margin-top: 10px; }
        .btn-login:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(21,128,61,0.3); }
        .alert-custom { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; border-radius: 12px; padding: 15px; font-weight: 600; font-size: 0.9rem; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .back-link { display: block; text-align: center; margin-top: 25px; color: #64748b; text-decoration: none; font-weight: 600; transition: 0.3s; font-size: 0.95rem; }
        .back-link:hover { color: var(--dark); }
        @media (max-width: 576px) {
            body { padding: 20px; }
            .login-box { padding: 40px 25px; }
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="brand-logo"><i class="fa-brands fa-envira"></i></div>
    <h1 class="login-title">Yönetici Girişi</h1>
    <p class="login-subtitle">Kozdere Dijital Portalı Yönetim Merkezi</p>

    <?php if ($hata != ''): ?>
    <div class="alert-custom">
        <i class="fa-solid fa-circle-exclamation fs-5"></i>
        <span><?= htmlspecialchars($hata) ?></span>
    </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="kullanici_adi" class="form-control with-icon" placeholder="Kullanıcı Adı" required autocomplete="off">
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="sifre" class="form-control with-icon" placeholder="Şifre" required>
            </div>
        </div>
        <button type="submit" class="btn-login">Giriş Yap <i class="fa-solid fa-right-to-bracket ms-2"></i></button>
    </form>
    
    <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left me-2"></i>Siteye Geri Dön</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>