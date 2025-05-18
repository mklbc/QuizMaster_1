<!-- HTML5 doctype ve dil ayarı -->
<!doctype html>
<html class="no-js" lang="zxx"> <!-- "zxx" dili tanımsız (genelde varsayılan) -->
<head>
   <!-- Site kök yolu tanımlaması -->
   <base href="http://localhost/quiz/">

   <!-- Meta bilgiler -->
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>QuizMaster</title>
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- Favicon ve stil dosyaları -->
   <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <link rel="stylesheet" href="assets/css/animate.css">
   <link rel="stylesheet" href="assets/css/swiper-bundle.css">
   <link rel="stylesheet" href="assets/css/slick.css">
   <link rel="stylesheet" href="assets/css/magnific-popup.css">
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

   <!-- Sayfa yukarı dönme butonu -->
   <div class="back-to-top-wrapper">
      <button id="back_to_top" class="back-to-top-btn">
         <svg width="12" height="7" viewBox="0 0 12 7">
            <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" />
         </svg>
      </button>
   </div>

   <main>
      <!-- Giriş paneli alanı -->
      <section class="tp-login-area">
         <div class="tp-login-register-box d-flex align-items-center">

            <!-- Sol tarafta açıklama ve görseller -->
            <div class="tp-login-register-banner-box p-relative">
               <div class="tp-login-register-heading">
                  <h3 class="tp-login-register-title">QuizMaster<br> Administration Panel</h3>
               </div>

               <!-- Şekil animasyonları -->
               <div class="tp-login-register-shape">
                  <div class="shape-1"><img src="assets/img/login/login-register-shape-2.png" alt=""></div>
                  <div class="shape-2"><img src="assets/img/login/login-register-shape-1.png" alt=""></div>
                  <div class="shape-3"><img src="assets/img/login/login-register-shape-3.png" alt=""></div>
               </div>
            </div>

            <!--  Giriş formu -->
            <form method="POST" action="yonetim/inc/giris.php">
               <div class="tp-login-register-wrapper d-flex justify-content-center align-items-center">
                  <div class="tp-login-from-box">

                     <!-- Form başlığı ve bilgi -->
                     <div class="tp-login-from-heading text-center">
                        <h4 class="tp-login-from-title">Login</h4>
                        <p>Don't You Remember Your Information? Contact Us! 
                           <a href="mailto:memoklbc@icloud.com">Developer</a>
                        </p>
                     </div>

                     <!-- Giriş alanları -->
                     <div class="tp-login-input-form">
                        <div class="row">
                           <div class="col-12">
                              <div class="tp-login-input p-relative">
                                 <label>User Name</label>
                                 <!-- Kullanıcı adı girişi -->
                                 <input type="text" name="kullanici_ad" placeholder="Mail veya Kullanıcı Adı">
                              </div>
                           </div>

                           <div class="col-12">
                              <div class="tp-login-input p-relative">
                                 <label>Password</label>
                                 <div class="password-input p-relative">
                                    <!-- Şifre girişi -->
                                    <input type="password" name="kullanici_password" placeholder="Şifreniz">
                                    
                                    <!-- Göster/Gizle butonu -->
                                    <div class="tp-login-input-eye password-show-toggle">
                                       <span class="open-eye open-eye-icon">
                                          <!-- Açık göz ikonu -->
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                          <circle cx="12" cy="12" r="3"/>
                                       </svg>
                                    </span>
                                    <span class="open-close close-eye-icon">
                                       <!-- Kapalı göz ikonu -->
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                                          <path d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a21.77 21.77 0 015.17-6.15"/>
                                          <path d="M1 1l22 22"/>
                                          <path d="M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-.88"/>
                                          <path d="M21 21l-6.88-6.88"/>
                                       </svg>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <!-- Giriş butonu -->
                           <div class="col-12">
                              <div class="tp-login-from-btn">
                                 <button type="submit" class="tp-btn-inner w-100 text-center" name="loggin">Sign In</button>
                              </div>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
            </form>
         </div>
      </section>
   </main>

   <!-- JavaScript dosyaları -->
   <script src="assets/js/vendor/jquery.js"></script>
   <script src="assets/js/vendor/waypoints.js"></script>
   <script src="assets/js/bootstrap-bundle.js"></script>
   <script src="assets/js/swiper-bundle.js"></script>
   <script src="assets/js/slick.js"></script>
   <script src="assets/js/range-slider.js"></script>
   <script src="assets/js/magnific-popup.js"></script>
   <script src="assets/js/nice-select.js"></script>
   <script src="assets/js/purecounter.js"></script>
   <script src="assets/js/countdown.js"></script>
   <script src="assets/js/wow.js"></script>
   <script src="assets/js/isotope-pkgd.js"></script>
   <script src="assets/js/imagesloaded-pkgd.js"></script>
   <script src="assets/js/flatpickr.js"></script>      
   <script src="assets/js/ajax-form.js"></script>
   <script src="assets/js/main.js"></script>

</body>
</html>
