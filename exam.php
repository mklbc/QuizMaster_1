<?php
ob_start(); // Çıktı tamponlamayı başlatır
session_start(); // Oturumu başlatır

date_default_timezone_set('Europe/Istanbul'); 

// Kullanıcı daha önce isim ve e-posta girip girdiyse oturumu başlatıyoruz (form gönderildiyse)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email'])) {
    $_SESSION['user_name'] = $_POST['name']; // Kullanıcı adını oturuma kaydet
    $_SESSION['user_email'] = $_POST['email']; // E-posta oturuma kaydet

    // start_time sadece ilk kez atanır (birden fazla tekrar başlatılmasın diye kontrol)
    if (!isset($_SESSION['start_time'])) {
        $_SESSION['start_time'] = date('Y-m-d H:i:s'); // Sınavın başladığı zaman
    }

    // exam_session_id de sadece sınav başlarken atanmalı
    if (!isset($_SESSION['exam_session_id'])) {
        $_SESSION['exam_session_id'] = uniqid('session_', true); // benzersiz ID üret
    }
}

// Kullanıcı zaten sınav başlamışsa, soru yükleme kısmına geç
if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
    require 'assets/baglan.php'; // Veritabanı bağlantısı

    // Sınav sorularını rastgele sırayla çek, maksimum 10 tane
    $stmt = $db->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz</title>
    <link rel="stylesheet" href="assets/css/Bootstrap/bootstrap.min.css" />
    <link rel="stylesheet"href="assets/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/animation.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/thankyou.css" />
    <script>
        let remainingTime = 3600; // 1 saat süren var  
        function updateTimer() {
            let minutes = Math.floor(remainingTime / 60);
            let seconds = remainingTime % 60;
            document.getElementById("timer").innerText = minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);
            if (remainingTime <= 0) {
                document.getElementById("examForm").submit();
            } else {
                remainingTime--;
                setTimeout(updateTimer, 1000);
            }
        }
        window.onload = function() {
            updateTimer();
        };
    </script>
  </head>
  <body>
    <!-- SORULARIN OLDUĞU KISIM -->
    
    <main class="overflow-hidden">
      <div class="container">
  
      <form id="examForm" novalidate class="show-section" id="stepForm" action="submit_exam.php" method="POST"><!-- yeni -->
        <div class="text-end mb-3">
        <strong>Time:</strong> <span id="timer">60:00</span>
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="sideArea">
                
                <img class="QMark tab-none" src="assets/images/QMark.png" alt="Question Mark" />
                <?php foreach ($questions as $index => $q): ?>
                    <!-- Step N Next Prev -->
                    <div class="nextPrev">
                        <!-- Önceki Buton (ilk sorudan sonra gösterilir) -->
                        <?php if ($index > 0): ?>
                            <button type="button" class="prev" id="prev<?=($index + 1)?>btn">
                                <i class="fa-solid fa-arrow-left"></i> Previous Question
                            </button>
                        <?php endif; ?>

                        <!-- Sonraki Buton (son sorudan önce gösterilir) -->
                        <?php if ($index < count($questions) - 1): ?>
                            <button type="button" class="next" id="step<?=($index + 1)?>btn">
                            Next Question <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        <?php endif; ?>

                        <!-- Son Soru İçin Gönder Butonunu Göster (son soru için sadece Gönder butonu) -->
                        <?php if ($index == count($questions) - 1): ?>
                            <button type="submit" class="next" id="sub">
                            Send <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-6 tab-100">
            <!-- Steps Start -->
            <section class="steps">
            
                <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
                <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>">

                <?php foreach ($questions as $index => $q): ?>
                    <fieldset id="step<?php echo ($index + 1); ?>">
                        <div class="question">
                            <h1><?php echo ($index + 1) . ". " . htmlspecialchars($q['question_text']); ?></h1>
                            <img src="assets/images/QBG.png" alt="Question Background"/>
                        </div>

                        <?php if ($q['type'] == 'listening' && $q['audio_file']): ?>
                            <audio controls>
                                <source src="<?php echo htmlspecialchars($q['audio_file']); ?>" type="audio/mpeg">
                                Your browser does not support the audio file.
                            </audio>
                        <?php endif; ?>

                        <div class="options">
                            <div class="option">
                                <span>a</span>
                                <div class="optionInner animate">
                                    <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="A">
                                    <label><?php echo htmlspecialchars($q['option_a']); ?></label>
                                </div>
                            </div>
                            <div class="option">
                                <span>b</span>
                                <div class="optionInner animate">
                                    <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="B">
                                    <label><?php echo htmlspecialchars($q['option_b']); ?></label>
                                </div>
                            </div>
                            <div class="option">
                                <span>c</span>
                                <div class="optionInner animate">
                                    <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="C">
                                    <label><?php echo htmlspecialchars($q['option_c']); ?></label>
                                </div>
                            </div>
                            <div class="option">
                                <span>d</span>
                                <div class="optionInner animate">
                                    <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="D">
                                    <label><?php echo htmlspecialchars($q['option_d']); ?></label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php endforeach; ?>

              <!-- Step BAr -->
              <div class="stepBar">
                <div class="fill"></div>
              </div>
              </form>
            </section>
       
          </div>
        </div>
      </div>
    </main>

    <!-- result -->
    <div class="loadingresult">
      <img src="assets/images/loading.gif" alt="loading" />
    </div>

    <div class="main thankyou-page">
      <div class="main-inner">
        <div class="logo">
          <div class="logo-icon">
            <img src="assets/images/logo.png" alt="" />
          </div>
          <div class="logo-text">uiza.</div>
        </div>
        <article>
          <h1><span>Thank You</span> For Your Time!</h1>
          <span>Your submission has been received</span>

        </article>
      </div>
    </div>

    <div id="error"></div>
    <!-- Bootstrap JS -->
    <script src="assets/js/Bootstrap/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="assets/js/jQuery/jquery-3.7.1.min.js"></script>

    <!-- THankyou JS -->
    <script src="assets/js/thankyou.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
    <script>
        
    </script>
 <!----------------------- 1 SAATLİK SAYACIN GERİ SAYIMI BURADA GÖSTERİLİYOR -->
<?php
    $safeTime = date('c', strtotime($_SESSION['start_time']));
?>
<script>
const examStart = new Date("<?= $safeTime ?>").getTime(); // PHP'den alınan sınav başlama zamanı
const examDurationSeconds = 3600; // Süre: 1 saat
const examEnd = examStart + examDurationSeconds * 1000; // Bitiş zamanı

function updateTimer() {
    const now = new Date().getTime(); // Anlık zaman
    const remaining = Math.floor((examEnd - now) / 1000); // Kalan süre

    if (remaining <= 0) {
        document.getElementById("timer").innerText = "00:00";
        alert("Sınav süresi doldu! Cevaplar gönderiliyor.");
        document.getElementById("examForm").submit(); // Süre bitince otomatik gönder
        return;
    }
    const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
    const seconds = String(remaining % 60).padStart(2, '0');
    document.getElementById("timer").innerText = `${minutes}:${seconds}`; // Sayacı göster
    setTimeout(updateTimer, 1000); // 1 saniye sonra tekrar çağır
}

window.onload = updateTimer;
</script>

  </body>
</html>


<?php
} else {
    // Eğer kullanıcı ismi ve e-posta girmezse bu sayfayı gösterelim
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level Determination Exam</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
    
    <!-- Bootstrap CSS bağlantısı -->
    <link rel="stylesheet" href="assets/css/Bootstrap/bootstrap.min.css" />
    <link rel="stylesheet"href="assets/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/animation.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/thankyou.css" />
    <style>
        /* Şeffaflık için CSS */
        .card {
            background-color: rgba(255, 255, 255, 0); /* Card arka planını şeffaf yap */
            border: 1px solid rgba(0, 0, 0, 0); /* Hafif border ekleyebilirsiniz */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.5); /* Input arka planını şeffaf yap */
            border: 1px solid rgba(0, 0, 0, 0.1); /* Hafif border ekleyebilirsiniz */
        }

        .btn-warning {
            background-color: rgb(235 111 2)
            border-color: rgba(255, 193, 7, 0.5);
        }
       
    </style>
</head>
<body style="background-color:rgb(255, 255, 255);">

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
    <div class="container mt-5">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 text-center">
            <img class="img-fluid" src="slider.jpg" alt=""> 
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="exam.php" method="POST">
                    <div class="mb-3">
                        <!-- bilgilerin alınıyor -->
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning">Start Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS ve bağımlılıkları (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6Qlmh/m7rWgIH/jr59hQnOR6pbF4EXxCZb6gqV6gfo9lI2k" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fqfu7zL8Kc4IynfyyHYXdcZ4+q4RfZZIuA0VxkE/p6bto" crossorigin="anonymous"></script>
</body>
</html>


<?php
}
?>