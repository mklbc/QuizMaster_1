<?php
include "baglan.php";

/**Soru sil */

if (isset($_GET['sinav-sil'])) {
    $id = intval($_GET['sinav-sil']);

    // Veritabanında id'nin var olup olmadığını kontrol et
    $kontrol = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $kontrol->execute([$id]);

    if ($kontrol->rowCount() > 0) {
        // ID mevcut, silme işlemi yapılabilir
        $sil = $db->prepare("DELETE FROM questions WHERE id = ?");
        $sil->execute([$id]);

        // Silme işleminin başarılı olup olmadığını kontrol et
        if ($sil->rowCount() > 0) {
            header("Location: ../../yonetim/seviye-tespit-sinavlari.php?durum=basarili");
            exit;
        } else {
            header("Location: ../../yonetim/seviye-tespit-sinavlari.php?durum=hatali");
            exit;
        }
    } else {
        // ID bulunamadı
        header("Location: ../../yonetim/seviye-tespit-sinavlari.php?durum=bulunamadi");
        exit;
    }
} else {
    // 'sinav-sil' parametresi URL'de bulunamadı
    header("Location: ../../yonetim/seviye-tespit-sinavlari.php?durum=bulunamadi");
    exit;
}
?>
