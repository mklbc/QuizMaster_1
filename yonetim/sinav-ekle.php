<?php
require 'inc/baglan.php'; // Veritabanı bağlantısı dahil edilir

// Form POST ile gönderildiyse çalışır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen tüm veriler alınır
    $type = $_POST['type']; // Soru tipi (reading/listening)
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $audio_file = NULL; // Başlangıçta null, sadece listening'de dosya alınır

    // Eğer soru tipi "listening" ise dosya yüklemesi yapılır
    if ($type == 'listening') {
        if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/audio/"; // Hedef klasör

            // Klasör yoksa oluşturulur
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Dosyanın hedef yolu belirlenir
            $audio_file = $target_dir . basename($_FILES["audio_file"]["name"]);

            // Dosya sunucuya yüklenir
            if (!move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file)) {
                echo "Error: File could not be loaded."; // Hata mesajı
                exit;
            }
        } else {
            echo "The audio file is not valid or was not loaded."; // Dosya yoksa hata
            exit;
        }
    }

    // Veritabanına soru eklenir
    $sql = "INSERT INTO questions (type, question_text, option_a, option_b, option_c, option_d, correct_option, audio_file)
            VALUES (:type, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option, :audio_file)";
    
    $stmt = $db->prepare($sql); // Sorgu hazırlanır
    $stmt->execute([
        ':type' => $type,
        ':question_text' => $question_text,
        ':option_a' => $option_a,
        ':option_b' => $option_b,
        ':option_c' => $option_c,
        ':option_d' => $option_d,
        ':correct_option' => $correct_option,
        ':audio_file' => $audio_file
    ]);

    echo "Question added successfully!"; // Başarılı mesajı
}
?>



<!doctype html>
<html class="no-js" lang="tr">
<head>
   <base href="http://localhost/quiz/"> <!-- Tüm linkler için baz URL -->
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>Exam System - Level Determination Exams</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- CSS dosyaları -->
   <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <link rel="stylesheet" href="assets/css/animate.css">
   <link rel="stylesheet" href="assets/css/swiper-bundle.css">
   <link rel="stylesheet" href="assets/css/slick.css">
   <link rel="stylesheet" href="assets/css/magnific-popup.css">
   <link rel="stylesheet" href="assets/css/flatpickr.min.css">
   <link rel="stylesheet" href="assets/css/font-awesome-pro.css">
   <link rel="stylesheet" href="assets/css/spacing.css">
   <link rel="stylesheet" href="assets/css/main.css">

   <!-- Soru ekleme butonu için özel stil -->
   <style>
    .pulish {
        font-size: 16px;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 6px;
        margin-right: 18px;
        color: var(--tp-common-white);
        background: var(--tp-dashboard-primary);
        box-shadow: 0 0 1px 0 #1438b5, 0 1px 2px 0 rgba(20, 56, 181, 0.25);
    }
   </style>
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

<main class="tp-dashboard-body-bg p-relative">
   <div class="tpd-dashboard-wrap-bg" data-background="assets/img/dashboard/bg/dashboard-bg-shape-1.jpg">
      <!-- Soru ekleme alanı başlar -->
      <section class="tpd-new-course-area pt-80 pb-120">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="tpd-new-course-wrap">
                     <div class="tpd-new-course-box">
                        <form action="" method="POST" enctype="multipart/form-data">
                           <!-- Akordiyon yapısı -->
                           <div class="accordion" id="accordionPanelsStayOpenExample">
                              <div class="accordion-item">
                                 <h2 class="accordion-header">
                                    <button class="accordion-button tpd-new-course-heading-title" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne"
                                            aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                       Add Question
                                    </button>
                                 </h2>
                                 <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                       <div class="tpd-new-course-box-1">
                                          <!-- Soru tipi seçimi -->
                                          <div class="tpd-input">
                                             <label>Question Type:</label><br>
                                             <select name="type">
                                                <option value="reading">Reading</option>
                                                <option value="listening">Listening</option>
                                             </select>
                                          </div>

                                          <!-- Soru metni -->
                                          <div class="tpd-input"><br>
                                             <label>Question Text:</label>
                                             <textarea name="question_text"></textarea><br>
                                          </div>

                                          <!-- Seçenekler -->
                                          <div class="tpd-input">
                                             <label>Options:</label><br>
                                             A: <input type="text" name="option_a"><br>
                                             B: <input type="text" name="option_b"><br>
                                             C: <input type="text" name="option_c"><br>
                                             D: <input type="text" name="option_d"><br>
                                          </div>

                                          <!-- Doğru seçenek -->
                                          <div class="tpd-input about-height">
                                             <label>Correct Option:</label><br>
                                             <select name="correct_option">
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                             </select>
                                          </div>

                                          <!-- Dinleme sorusu için ses dosyası -->
                                          <br>
                                          <label>Audio File for Listening Question:</label><br>
                                          <input type="file" name="audio_file"><br>

                                         
                                          
                                       </div>

                                       <!-- Butonlar -->
                                       <div class="tpd-new-course-box-3">
                                          <div class="tpd-new-course-categories">
                                              <!-- Ekleme butonu -->
                                             <input class="btn btn-success" type="submit" value="Add Question">
                                             <a class="btn btn-warning text-light" href="yonetim/seviye-tespit-sinavlari.php">Back</a>
                                          </div>
                                       </div>

                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Soru ekleme alanı biter -->
   </div>
</main>


      <!-- JS here -->
      <script src="assets/js/vendor/jquery.js"></script>
      <script src="assets/js/vendor/waypoints.js"></script>
      <script src="assets/js/bootstrap-bundle.js"></script>
      <script src="assets/js/swiper-bundle.js"></script>
      <script src="assets/js/slick.js"></script>
      <script src="assets/js/range-slider.js"></script>
      <script src="assets/js/magnific-popup.js"></script>
      <script src="assets/js/nice-select.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/js/purecounter.js"></script>
      <script src="assets/js/wow.js"></script>
      <script src="assets/js/isotope-pkgd.js"></script>
      <script src="assets/js/imagesloaded-pkgd.js"></script>
      <script src="assets/js/flatpickr.js"></script>      
      <script src="assets/js/ajax-form.js"></script>
      <script src="assets/js/main.js"></script>
<!-- Görsel Önizleme Scripti -->
<script>
    
</script>
   </body>


</html>