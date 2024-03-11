<?php
$servername = "localhost"; 
$username = "root";
$password = " ";
$dbname = "kayitform"; 

// MySQLi nesnesi oluşturarak veritabanına bağlanma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
} else {
    echo "Veritabanı bağlantısı başarılı!";
}

?>
