<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kayıt Ol</title>
</head>
<body>
    <div class="formum">
        <h2>Kayıt Formu</h2>
        <form action="kayit.php" method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="kullanici_adi" name="kullanici_adi"><br><br>


            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre"><br><br>


            <label for="email">E-posta:</label>
            <input type="email" id="email" name="email"><br><br>

            <label for="telefon">Telefon:</label>
            <input type="tel" id="telefon" name="telefon"><br><br>

            <input type="submit" value="Kayıt Ol">
        </form>
        <p>Zaten hesabınız varsa <a href="index.php">giriş yapın</a>.</p>
    </div>
    

</body>
</html>


<?php
// Veritabanı bağlantısı için bilgiler
$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "kayitform"; 

try {
    // PDO nesnesi oluşturma
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Hata modunu ayarlama
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     // Form gönderildi mi diye kontrol etme
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // POST isteği ile gelen verileri alınması
        $kullanici_adi = $_POST['kullanici_adi'] ?? '';
        $sifre = $_POST['sifre'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefon = $_POST['telefon'] ?? '';

        // E-posta adresinin veritabanında bulunup bulunmadığını kontrol etme
        $sql_check_email = "SELECT COUNT(*) AS count FROM kayit WHERE email = :email";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->bindParam(':email', $email);
        $stmt_check_email->execute();
        $email_count = $stmt_check_email->fetch(PDO::FETCH_ASSOC)['count'];

        if ($email_count > 0) {
            // Eğer e-posta adresi zaten kayıtlıysa, kullanıcıya bir uyarı mesajı gösterin
            echo '<script type="text/javascript">
                // JavaScript kodları buraya yazılır
                alert("Bu e-posta adresi zaten kayıtlı");
            </script>';
        } else {
            // Eğer e-posta adresi veritabanında bulunmuyorsa, yeni kayıt işlemi yapılabilir
            // Kullanıcı bilgilerini veritabanına ekleme
            $sql = "INSERT INTO kayit (kullanici_adi, sifre, email, telefon) VALUES (:kullanici_adi, :sifre, :email, :telefon)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':kullanici_adi', $kullanici_adi);
            $stmt->bindParam(':sifre', $sifre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefon', $telefon);
            $stmt->execute();

            // Kayıt başarıyla eklendiyse mesajı göster ve JavaScript ile bekleme süresini ayarla
            if ($stmt->rowCount() > 0) {
                echo '<script type="text/javascript">
                // JavaScript kodları buraya yazılır
                alert("Kayıt başarılı");
                </script>';
            }
        }
    }
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

// Bağlantıyı kapatma
$conn = null;
?>

