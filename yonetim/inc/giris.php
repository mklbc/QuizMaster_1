<?php
include "baglan.php"; // Veritabanı bağlantısını dahil ediyoruz





// Giriş butonuna basılmışsa çalışır
if(isset($_POST['loggin'])){
        
        $kullanici_ad=$_POST['kullanici_ad']; // Kullanıcı adı (ya da mail)

        //burada şifre oluşturuyoruz otomatik şifre db için 
        $kullanici_password=md5($_POST['kullanici_password']);
    
    
        if($kullanici_ad && $kullanici_password){  // Kullanıcı adı ve şifre doluysa
            
            // Kullanıcıyı veritabanında arıyoruz
            $kullanicisor = $db ->prepare("SELECT * FROM users WHERE user_name=:ad and user_password=:password");
            $kullanicisor->execute(array(
                'ad' => $kullanici_ad,
                'password' => $kullanici_password
    
            ));
             // Eşleşen kullanıcıyı al
            $kullanici_cek = $kullanicisor->fetch(PDO::FETCH_ASSOC);
            // Kullanıcının yetki seviyesi (varsa)
            $kullanici_yetki = $kullanici_cek['user_authority'];
            // Kullanıcı bulundu mu kontrolü (rowCount > 0)
            $say =$kullanicisor->rowCount();
    
            if($say >0 ){ 

                 // Giriş başarılıysa oturum başlatılır
                $_SESSION['kullanici_Ad'] = $kullanici_ad; 
                $_SESSION['kullanici_yetki'] = $kullanici_yetki; 
                $_SESSION['loggin'] = TRUE;
                header('Location:../seviye-tespit-sinavlari.php'); // sign in seviyetespit sınavlarına yönlendirir
            }else{
                // Giriş başarısızsa login sayfasına hata parametresiyle dön
                header('Location:../login.php?login=nox');
            }
    
            
        }
    }

    ?>
