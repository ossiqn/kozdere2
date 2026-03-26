<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_name = 'kozderedb2';
$db_user = 'root';
$db_pass = '';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $mevcut_tablolar = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('ayarlar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE ayarlar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            baslik VARCHAR(255) NOT NULL,
            muhtar VARCHAR(255) NOT NULL,
            nufus VARCHAR(50) NOT NULL,
            hane VARCHAR(50) NOT NULL,
            rakim VARCHAR(50) NOT NULL,
            telefon VARCHAR(50) NOT NULL,
            whatsapp VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->exec("INSERT INTO ayarlar (baslik, muhtar, nufus, hane, rakim, telefon, whatsapp) VALUES ('Kozdere Köyü', 'Hasan Demir', '850', '210', '1250', '0555 555 55 55', '905555555555')");
    }

    if (!in_array('slider', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE slider (
            id INT AUTO_INCREMENT PRIMARY KEY,
            resim VARCHAR(500) NOT NULL,
            baslik VARCHAR(255) NOT NULL,
            alt_baslik VARCHAR(500) NOT NULL,
            buton_text VARCHAR(100) NOT NULL,
            buton_link VARCHAR(255) NOT NULL,
            sira INT DEFAULT 0,
            durum TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->exec("INSERT INTO slider (resim, baslik, alt_baslik, buton_text, buton_link, sira, durum) VALUES 
        ('https://placehold.co/1920x800/020617/ffffff?text=Kozdere+Genel+Bakis', 'Kozdere\'nin Dijital Meydanı', 'Gurbetten sılaya uzanan, gelenekle teknolojinin buluştuğu nokta.', 'Köyü Keşfet', 'kurumsal.php', 1, 1),
        ('https://placehold.co/1920x800/166534/ffffff?text=Yayla+Yolu', 'Doğanın Kalbinde Yaşam', 'Köyümüzün eşsiz doğası ve temiz havası ile huzuru hissedin.', 'Soyağacı', 'soyagaci.php', 2, 1)");
    }

    if (!in_array('haberler', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE haberler (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tip VARCHAR(50) NOT NULL,
            baslik VARCHAR(255) NOT NULL,
            ozet TEXT NOT NULL,
            tarih VARCHAR(50) NOT NULL,
            ikon VARCHAR(50) NOT NULL,
            renk VARCHAR(50) NOT NULL,
            durum TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    } else {
        $kolon_kontrol = $db->query("SHOW COLUMNS FROM haberler LIKE 'yazar_id'")->fetchAll();
        if (count($kolon_kontrol) == 0) {
            $db->exec("ALTER TABLE haberler ADD yazar_id INT DEFAULT 0");
        }
    }

    if (!in_array('pazar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE pazar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            isim VARCHAR(255) NOT NULL,
            fiyat VARCHAR(50) NOT NULL,
            satici VARCHAR(255) NOT NULL,
            telefon VARCHAR(50) NOT NULL,
            resim VARCHAR(500) NOT NULL,
            birim VARCHAR(50) NOT NULL,
            durum TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    if (!in_array('kullanicilar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE kullanicilar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ad VARCHAR(100) NOT NULL,
            soyad VARCHAR(100) NOT NULL,
            eposta VARCHAR(255) NOT NULL,
            sifre VARCHAR(255) NOT NULL,
            baglilik VARCHAR(255) NOT NULL,
            rol VARCHAR(50) DEFAULT 'uye',
            durum TINYINT(1) DEFAULT 0,
            kayit_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    if (!in_array('yorumlar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE yorumlar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            haber_id INT NOT NULL,
            ad_soyad VARCHAR(100) NOT NULL,
            yorum TEXT NOT NULL,
            durum TINYINT(1) DEFAULT 0,
            tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    if (!in_array('yoneticiler', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE yoneticiler (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kullanici_adi VARCHAR(50) NOT NULL,
            sifre VARCHAR(255) NOT NULL,
            isim VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $varsayilan_sifre = password_hash('admin123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO yoneticiler (kullanici_adi, sifre, isim) VALUES ('admin', '$varsayilan_sifre', 'Sistem Yöneticisi')");
    }

    if (!in_array('fotograflar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE fotograflar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            album VARCHAR(50) NOT NULL,
            baslik VARCHAR(255) NOT NULL,
            url VARCHAR(500) NOT NULL,
            durum TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->exec("INSERT INTO fotograflar (album, baslik, url, durum) VALUES 
        ('manzara', 'Yayla Manzarası', 'https://placehold.co/800x600/15803d/fff?text=Kozdere+Yayla', 1),
        ('dugun', 'Ahmet ve Ayşe Düğün', 'https://placehold.co/800x600/ec4899/fff?text=Ahmet+Ayse+Dugun', 1),
        ('piknik', '2025 Bahar Pikniği', 'https://placehold.co/800x600/ca8a04/fff?text=Bahar+Piknigi', 1)");
    }

    if (!in_array('videolar', $mevcut_tablolar)) {
        $db->exec("CREATE TABLE videolar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            baslik VARCHAR(255) NOT NULL,
            embed VARCHAR(500) NOT NULL,
            thumb VARCHAR(500) NOT NULL,
            durum TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $db->exec("INSERT INTO videolar (baslik, embed, thumb, durum) VALUES 
        ('2025 Yayla Şenlikleri Özeti', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'https://placehold.co/800x450/0f172a/fff?text=Video+1', 1)");
    }

} catch (PDOException $e) {
    die("Sistem Hatası: " . $e->getMessage());
}

$site = [
    'baslik' => 'Kozdere Köyü',
    'muhtar' => 'Hasan Demir',
    'nufus' => '850',
    'hane' => '210',
    'rakim' => '1250',
    'telefon' => '0555 555 55 55',
    'whatsapp' => '905555555555'
];

if (in_array('ayarlar', $mevcut_tablolar)) {
    $ayar_sorgu = $db->query("SELECT * FROM ayarlar LIMIT 1")->fetch();
    if ($ayar_sorgu) {
        $site = array_merge($site, $ayar_sorgu);
    }
}
?>