<?php
session_start(); // Oturumu başlat

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı bağlantısı için bilgiler
    $servername = "localhost"; 
    $username = "root";
    $password = ""; // Veritabanı şifresi
    $dbname = "kayitform"; // Kullanılacak veritabanı adı

    try {
        // PDO nesnesi oluşturma
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Hata modunu ayarlama
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // POST isteği ile gelen kullanıcı adı ve şifre bilgilerini alınması
        $kullanici_adi = $_POST['username'];
        $sifre = $_POST['password'];

        // Kullanıcı bilgilerini veritabanından kontrol etme
        $sql = "SELECT * FROM kayit WHERE kullanici_adi = :kullanici_adi";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $stmt->execute();

        // Eğer kullanıcı bulunursa, şifresini kontrol et
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user['sifre'] == $sifre) {
                // Eğer şifre doğruysa, oturumu başlat ve kullanıcıyı home.php sayfasına yönlendir
                $_SESSION['username'] = $kullanici_adi;
                header("Location: home.php");
                exit();
            } else {
                // Şifre yanlışsa, hata mesajı göster
                $hata_mesaji = "Şifre hatalı.";
            }
        } else {
            // Kullanıcı bulunamazsa, hata mesajı göster
            $hata_mesaji = "Kullanıcı bulunamadı.";
        }
    } catch(PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }

    // Bağlantıyı kapatma
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Giriş Formu</title>
</head>
<body>
    <div class="formum">
        <h2>Giriş Formu</h2>
        <?php if(isset($hata_mesaji)) { ?>
            <p style="color: red;"><?php echo $hata_mesaji; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Kullanıcı Adı:</label><br>
            <input type="text" id="username" name="username"><br><br>

            <label for="password">Şifre:</label><br>
            <input type="password" id="password" name="password"><br><br>

            <input type="submit" value="Giriş Yap">
        </form>
        <p>Hesabınız yoksa <a href="kayit.php">kayıt olun</a>.</p>
    </div>
</body>
</html>
