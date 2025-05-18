<?php
require 'inc/baglan.php'; // Veritabanı bağlantısı dahil edilir

// Form gönderildiyse çalışacak blok
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = $_GET['id']; // Güncellenecek sorunun ID'si alınır
    $type = $_POST['type']; // Soru tipi
    $question_text = $_POST['question_text']; // Soru metni
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $audio_file = NULL; // Başta null

    // Dinleme sorusuysa ve dosya geldiyse işlem yapılır
    if ($type == 'listening' && isset($_FILES['audio_file'])) {
        $target_dir = "uploads/audio/";
        $audio_file = $target_dir . basename($_FILES["audio_file"]["name"]);
        move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file); // Dosya yüklenir
    }

    // Eğer yeni ses dosyası varsa bu da güncellenir
    if ($audio_file) {
        $sql = "UPDATE questions 
                SET type = :type, question_text = :question_text, option_a = :option_a, option_b = :option_b, option_c = :option_c, option_d = :option_d, correct_option = :correct_option, audio_file = :audio_file 
                WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':type' => $type,
            ':question_text' => $question_text,
            ':option_a' => $option_a,
            ':option_b' => $option_b,
            ':option_c' => $option_c,
            ':option_d' => $option_d,
            ':correct_option' => $correct_option,
            ':audio_file' => $audio_file,
            ':id' => $question_id
        ]);
    } else {
        // Dosya yoksa sadece metinler güncellenir
        $sql = "UPDATE questions 
                SET type = :type, question_text = :question_text, option_a = :option_a, option_b = :option_b, option_c = :option_c, option_d = :option_d, correct_option = :correct_option 
                WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':type' => $type,
            ':question_text' => $question_text,
            ':option_a' => $option_a,
            ':option_b' => $option_b,
            ':option_c' => $option_c,
            ':option_d' => $option_d,
            ':correct_option' => $correct_option,
            ':id' => $question_id
        ]);
    }

    echo "Question updated successfully!";
}

// Soru verilerini formda göstermek için çekiyoruz
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];
    $sql = "SELECT * FROM questions WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $question_id]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC); // Mevcut soru bilgisi
}
?>



<!doctype html>
<html class="no-js" lang="tr">
<head>

<base href="http://localhost/quiz/">
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>QuizMaster - Level Determination Exams</title>
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- Place favicon.ico in the root directory -->
   <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">

   <!-- CSS here -->
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <link rel="stylesheet" href="assets/css/animate.css">
   <link rel="stylesheet" href="assets/css/swiper-bundle.css">
   <link rel="stylesheet" href="assets/css/slick.css">
   <link rel="stylesheet" href="assets/css/magnific-popup.css">
   <link rel="stylesheet" href="assets/css/flatpickr.min.css">
   <link rel="stylesheet" href="assets/css/font-awesome-pro.css">
   <link rel="stylesheet" href="assets/css/spacing.css">
   <link rel="stylesheet" href="assets/css/main.css">
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

            <!-- create new course area start -->
            <section class="tpd-new-course-area pt-80 pb-120">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="tpd-new-course-wrap">
                           <div class="tpd-new-course-box">
                           <form action="" method="POST" enctype="multipart/form-data">
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button tpd-new-course-heading-title" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                        Update Question
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                        <div class="tpd-new-course-box-1">
                                             <!-- Soru tipi seçimi -->
                                            <div class="tpd-input">
                                            <label>Question Type:</label><br>
                                            <select name="type">
                                                <option value="reading" <?php echo ($question['type'] == 'reading') ? 'selected' : ''; ?>>Reading</option>
                                                <option value="listening" <?php echo ($question['type'] == 'listening') ? 'selected' : ''; ?>>Listening</option>
                                            </select>
                                            </div>
                                             <!-- Soru metni -->
                                            <div class="tpd-input">
                                            <br>
                                            <label>Question Text:</label>
                                            <textarea name="question_text"><?php echo htmlspecialchars($question['question_text']); ?></textarea><br>
                                            </div>
                                             <!-- Şıklar -->
                                            <div class="tpd-input">
                                            <label>Options:</label><br>
                                            A: <input type="text" name="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>"><br>
                                            B: <input type="text" name="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>"><br>
                                            C: <input type="text" name="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>"><br>
                                            D: <input type="text" name="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>"><br>
                                            </div>
                                             <!-- Doğru seçenek seçimi -->
                                            <div class="tpd-input about-height">
                                            <label>Correct Option:</label><br>
                                            <select name="correct_option">
                                                <option value="A" <?php echo ($question['correct_option'] == 'A') ? 'selected' : ''; ?>>A</option>
                                                <option value="B" <?php echo ($question['correct_option'] == 'B') ? 'selected' : ''; ?>>B</option>
                                                <option value="C" <?php echo ($question['correct_option'] == 'C') ? 'selected' : ''; ?>>C</option>
                                                <option value="D" <?php echo ($question['correct_option'] == 'D') ? 'selected' : ''; ?>>D</option>
                                            </select>
                                            </div>
                                             <!-- Ses dosyası yükleme alanı (dinleme sorusu için) -->
                                            <br>
                                            <label>Audio File for Listening Question:</label><br>
                                            <input type="file" name="audio_file"><br>
                                             <!-- Daha önce yüklü ses varsa oynatıcı göster -->
                                            <?php if ($question['audio_file']) { ?>
                                            <audio controls>
                                                <source src="<?php echo $question['audio_file']; ?>" type="audio/mpeg">
                                            </audio><br>
                                            <?php } ?>
                                            
                                            
                                            
                                        </div>
                                        <div class="tpd-new-course-box-3">
                                            <div class="tpd-new-course-categories">
                                                 <!-- Güncelleme butonu -->
                                            <input class="btn btn-success" type="submit" value="Update Question"> 
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
            <!-- create new course area end -->

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