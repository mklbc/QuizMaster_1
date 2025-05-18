<?php
ini_set('display_errors', 1); // Hataları ekranda göstermek için (geliştirme aşamasında kullanılır)
error_reporting(E_ALL); // Tüm hata seviyelerini bildir

date_default_timezone_set('Europe/Istanbul'); // Saat dilimini İstanbul olarak ayarla

require 'assets/baglan.php'; // Veritabanı bağlantı dosyası
session_start(); // Oturumu başlat
ob_start(); // Output buffer başlat

// Kullanıcı oturumu kontrol
if (isset($_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['start_time'])) {
    $user_name = $_SESSION['user_name']; // Oturumdan kullanıcı adını al
    $user_email = $_SESSION['user_email']; // Oturumdan e-posta al
    $start_time = $_SESSION['start_time']; // Oturumdan sınav başlangıç zamanını al
} else {
    header("Location: exam.php"); // Eğer oturum bilgileri yoksa sınav giriş sayfasına yönlendir
    exit();
}

$answers = $_POST['answer'] ?? []; // Kullanıcıdan gelen cevaplar
$score = 0; // Başlangıç puanı

$end_time = date('Y-m-d H:i:s'); // Sınav bitiş zamanı (şu anki zaman)

try {
    $start = new DateTime($start_time); // Başlangıç zamanını DateTime nesnesine çevir
    $end = new DateTime($end_time); // Bitiş zamanını DateTime nesnesine çevir

    // ++ Sınav süresi kontrolü (sunucu tarafında)
    $maxDurationSeconds = 3600; // 1 saat sınırı (3600 saniye)
    $elapsedSeconds = $end->getTimestamp() - $start->getTimestamp(); // Geçen süreyi hesapla

    if ($elapsedSeconds > $maxDurationSeconds) {
        die("⚠️ Exam time exceeded! Answers not saved."); // Süre aşıldıysa işlem sonlandırılır
    }

    //  Sınav süresini hesapla ve formatla
    $interval = $start->diff($end); // Başlangıç ve bitiş arasındaki fark
    $totalHours = ($interval->days * 24) + $interval->h; // Toplam saat
    $exam_duration = sprintf("%d Hour %d Minute %d Seconds", $totalHours, $interval->i, $interval->s); // Biçimlendirilmiş sınav süresi

    $baseTime = new DateTime($start_time); // Cevap zamanları için başlangıç zamanı
    $i = 0; // Her cevap için farklı zaman simülasyonu (test amaçlı)

    foreach ($answers as $question_id => $user_answer) {
        $answerTime = clone $baseTime; // Zamanı klonla
        $answerTime->modify("+$i seconds"); // Her cevap arası 1 saniye ekle (simülasyon)
        $formattedDate = $answerTime->format('Y-m-d H:i:s'); // Zamanı biçimlendir
        $i++;

        $stmt = $db->prepare("SELECT correct_option FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);
        $correct_answer = $stmt->fetchColumn(); // Doğru cevabı al

        if ($correct_answer === $user_answer) {
            $score += 10; // Doğruysa 10 puan ekle puan burada hesaplanıyor 
        }

        // exam_results tablosuna cevabı kaydet
        $stmt = $db->prepare("INSERT INTO exam_results (
             user_name, user_email, question_id, user_answer, is_correct, date, exam_session_id
        ) VALUES (
            :user_name, :user_email, :question_id, :user_answer, :is_correct, :date, :exam_session_id
        )");        
        $stmt->execute([
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':question_id' => $question_id,
            ':user_answer' => $user_answer,
            ':is_correct' => ($correct_answer === $user_answer) ? 1 : 0,
            ':date' => $formattedDate,
            ':exam_session_id' => $_SESSION['exam_session_id']
        ]);
    }

    // scores tablosuna toplam puan ve süreyi kaydet
    $stmt = $db->prepare("INSERT INTO scores 
        (user_name, user_email, score, exam_duration, exam_session_id) 
        VALUES (:user_name, :user_email, :score, :exam_duration, :exam_session_id)");
    $stmt->execute([
        ':user_name' => $user_name,
        ':user_email' => $user_email,
        ':score' => $score,
        ':exam_duration' => $exam_duration,
        ':exam_session_id' => $_SESSION['exam_session_id']
]);


    unset($_SESSION['start_time']); // Sınav başlangıç süresini oturumdan sil

} catch (Exception $e) {
    echo "Hata: " . $e->getMessage(); // Hata varsa yazdır
}
?>









<!-- HTML kısmı: sınav tamamlandı mesajı -->


<!DOCTYPE html>
<html lang="en">
<head>
    <base href="http://localhost/quiz/">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
    
    <link rel="stylesheet" href="assets/css/Bootstrap/bootstrap.min.css" />
    <link rel="stylesheet"href="assets/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/animation.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/thankyou.css" />
  </head>
  <body>


  <!-- Yükleniyor animasyonu -->
  <div id="loading">
      <div id="loading-center">
         <div id="loading-center-absolute">
            <div class="tp-preloader-content">
               <div class="tp-preloader-logo">
                  <div class="tp-preloader-circle">
                     <!-- SVG dairesel yükleme animasyonu -->
                     <svg width="190" height="190" viewBox="0 0 380 380">
                        <circle stroke="#D9D9D9" cx="190" cy="190" r="180" stroke-width="6" />
                        <circle stroke="red" cx="190" cy="190" r="180" stroke-width="6" />
                     </svg>
                  </div>
                  <!-- Yüklenme simgesi -->
                  <img src="assets/img/logo/preloader-icon.png" alt="">
               </div>
               <p class="tp-preloader-subtitle">Loading...</p>
            </div>
         </div>
      </div>
   </div>

   
  <main class="overflow-hidden">
      <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img class="QMark tab-none" src="assets/images/QMark.png" alt="Question Mark" />
            </div>
            
            <div class="col-md-6 tab-100">
                <h1><span>Thank You</span> For Your Time!</h1>
                <span><?php   echo "Exam completed! Your score: $score";?></span> <!-- Kullanıcının puanı -->
                <h4><span>Your submission has been received</span></h4>
                <div class="mb-5 back-home">
          <!--<a href="/quiz/exam.php">Back to Home</a>  -->
        </div>
            </div>
        </div>
      </div>
    </main>
    <!-- Bootstrap JS -->
    <script src="assets/js/Bootstrap/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="assets/js/jQuery/jquery-3.7.1.min.js"></script>
 
  </body>
</html>