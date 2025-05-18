<?php
include "inc/baglan.php";

?>

<!doctype html>
<html class="no-js" lang="tr">
<head>
   <base href="http://localhost/quiz/"> <!-- Tüm bağlantılar için temel URL -->
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>Exam Results</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
   <!-- CSS dosyaları -->
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <link rel="stylesheet" href="assets/css/animate.css">
   <link rel="stylesheet" href="assets/css/swiper-bundle.css">
   <link rel="stylesheet" href="assets/css/slick.css">
   <link rel="stylesheet" href="assets/css/magnific-popup.css">
   <link rel="stylesheet" href="assets/css/flatpickr.min.css">
   <link rel="stylesheet" href="assets/css/font-awesome-pro.css">
   <link rel="stylesheet" href="assets/css/spacing.css">
   <link rel="stylesheet" href="assets/css/main.css">
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


   <main class="tp-dashboard-body-bg">
      <?php include 'banner.php'; ?> <!-- Üst bilgi kısmı (banner) -->
      <section class="tpd-main pb-75">
         <div class="container">
            <div class="row">
               <?php include 'menu.php'; ?> <!-- Sol menü -->

               <div class="col-lg-9">
                  <div class="tpd-content-layout">
                     <!-- Başlık kısmı -->
                     <section class="tpd-order-area">
                        <div class="row">
                           <div class="col-lg-9">
                              <div class="tp-dashboard-section">
                                 <h2 class="tp-dashboard-title">Level Determination Exam Results</h2>
                              </div>
                           </div>
                        </div>

                        <!-- Tablo yapısı başlangıcı -->
                        <div class="tpd-table mb-45">
                           <ul>
                              <li class="tpd-table-head">
                                 <div class="tpd-table-row">
                                    <div class="tpd-instructor-qa-student">
                                       <h4 class="tpd-table-title">Name</h4>
                                    </div>
                                    <div class="tpd-instructor-qa-reply">
                                       <h4 class="tpd-table-title" style="margin-left: 55px;">Mail</h4>
                                    </div>
                                    <div class="tpd-instructor-qa-status">
                                       <h4 class="tpd-table-title"></h4>
                                    </div>
                                    <div class="tpd-instructor-qa-status">
                                       <h4 class="tpd-table-title" style="margin-left: 65px;">Score</h4>
                                    </div>
                                    <div class="tpd-instructor-qa-status">
                                       <h4 class="tpd-table-title"></h4>
                                    </div>
                                    <div class="tpd-instructor-qa-action" style="margin-left:40px">Actions</div>
                                 </div>
                              </li>

                              <?php
                              // Her kullanıcı için en yüksek sınav skorunu getiriyoruz
                              $query = $db->query("
                                 SELECT s1.user_name, s1.user_email, s1.score, s1.exam_duration
                                 FROM scores s1
                                 INNER JOIN (
                                    SELECT user_email, MAX(id) as max_id
                                    FROM scores
                                    GROUP BY user_email
                                 ) s2 ON s1.id = s2.max_id
                              ", PDO::FETCH_ASSOC);
                          

                              if ($query->rowCount()) {
                                 foreach ($query as $row) {
                              ?>
                                 <!-- Tek tek her kullanıcıyı listele -->
                                 <li>
                                    <div class="tpd-table-row">
                                       <div class="tpd-instructor-qa-student">
                                          <div class="tpd-reviews-profile d-flex align-items-center">
                                             <div class="tpd-reviews-text">
                                                <h4 class="tpd-reviews-thumb-title"><?= $row['user_name'] ?></h4>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="tpd-instructor-qa-student">
                                          <div class="tpd-reviews-profile d-flex align-items-center">
                                             <div class="tpd-reviews-text">
                                                <h4 class="tpd-reviews-thumb-title"><?= $row['user_email'] ?></h4>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="tpd-instructor-qa-btn">
                                          <h4 class="tpd-reviews-thumb-title"><?= $row['score'] ?></h4>
                                       </div>

                                       <div class="tpd-instructor-qa-question">
                                          <!-- Detay sayfasına bağlantı -->
                                          <a class="tpd-border-btn" href="yonetim/sinav-detay.php?id=<?= $row['user_email'] ?>">Details</a>
                                       </div>
                                    </div>
                                 </li>
                              <?php } } ?>
                           </ul>
                        </div>
                     </section>
                  </div> <!-- tpd-content-layout -->
               </div> <!-- col-lg-9 -->
            </div>
         </div>
      </section>
   </main>

   <!-- JavaScript dosyaları -->
   <script src="assets/js/vendor/jquery.js"></script>
   <script src="assets/js/vendor/waypoints.js"></script>
   <script src="assets/js/bootstrap-bundle.js"></script>
   <script src="assets/js/swiper-bundle.js"></script>
   <script src="assets/js/slick.js"></script>
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

   <script>
   // İlgili kullanıcıyı düzenleme sayfasına yönlendirir
   function editJobSection(jobId) {
      window.location.href = 'yonetim/sinav-duzenle.php?id=' + jobId;
   }
   </script>
</body>
</html>
