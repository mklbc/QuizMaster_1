<?php
try {
    // Veritabanına PDO ile bağlanılıyor (host: localhost, veritabanı: quiz, charset: utf8)
   $db = new PDO("mysql:host=localhost;dbname=quiz;charset=utf8", "root", "");
} catch ( PDOException $e ){
       // Bağlantı hatası durumunda ekrana hata mesajı yazdırılır
     print $e->getMessage();
}
// veritabanı bağlantısı

session_start();
?>
