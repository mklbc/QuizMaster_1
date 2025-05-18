<?php
include "inc/baglan.php";
$id = $_GET["id"];

// Kullanıcının adı için bir kayıt al
$duzenle = $db->prepare("SELECT * FROM exam_results WHERE user_email = :id LIMIT 1");
$duzenle->execute(['id' => $id]);
$duzenle = $duzenle->fetch(PDO::FETCH_ASSOC);

// Sınav sonuçlarını ve soruları getir
// Tüm oturumları sırayla getiriyoruz (en yeni en üstte)
$queryab = $db->prepare("
    SELECT * FROM questions 
    INNER JOIN exam_results ON questions.id = exam_results.question_id
    WHERE exam_results.user_email = :id 
    ORDER BY exam_results.exam_session_id DESC, exam_results.date ASC
");
$queryab->execute(['id' => $id]);
$results = $queryab->fetchAll(PDO::FETCH_ASSOC);


$queryab = $db->prepare("
    SELECT * FROM questions 
    INNER JOIN exam_results ON questions.id = exam_results.question_id
    WHERE exam_results.user_email = :id 
    ORDER BY exam_results.exam_session_id DESC, exam_results.date ASC
");
$queryab->execute(['id' => $id]);
$results = $queryab->fetchAll(PDO::FETCH_ASSOC);










//  Gerçek sınav süresi artık scores tablosundan geliyor!
$gercekSure = "Hesaplanamadı";
$gercekSureEtiket = "";

$scoreQuery = $db->prepare("SELECT exam_duration FROM scores WHERE user_email = :id ORDER BY id DESC LIMIT 1");
$scoreQuery->execute(['id' => $id]);
$scoreRow = $scoreQuery->fetch(PDO::FETCH_ASSOC);

if ($scoreRow && $scoreRow['exam_duration']) {
    $gercekSure = $scoreRow['exam_duration'];

    // Etiketleme yapalım
    if (preg_match('/(\d+)\s*Hour\s*(\d+)\s*Minute\s*(\d+)\s*Seconds/', $gercekSure, $match)) {
        $totalSeconds = ($match[1] * 3600) + ($match[2] * 60) + $match[3];

        // çok hızlı orta normal yazıları burada

        if ($totalSeconds <= 300) {
            $gercekSureEtiket = '<span class="badge badge-danger">So Fast</span>';
        } elseif ($totalSeconds <= 1200) {
            $gercekSureEtiket = '<span class="badge badge-warning">Medium Duration</span>';
        } else {
            $gercekSureEtiket = '<span class="badge badge-success">Normal</span>';
        }
    }
 
}



?>





<!doctype html>
<html class="no-js" lang="tr">

<head>

    <base href="http://localhost/quiz/">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>QuizMaster - Task Distribution</title>
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
        <?php include 'banner.php';?>
        <!-- dashboad-content-box-area-start -->
        <section class="tpd-main pb-75">
            <div class="container">
                <div class="row">
                    <?php include 'menu.php';?>
                    <div class="col-lg-9">
                        <!-- dashboard-content-area-start -->
                        <div class="tpd-content-layout">

                            <!-- section-area-start -->
                            <div class="section-area">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="tp-dashboard-section tpd-quiz-attempts">
                                            <div class="tpd-course-wrap">
                                                <a href="yonetim/sinav-sonuclari.php"><span><svg width="12" height="12"
                                                            viewBox="0 0 12 12" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 6H1" stroke="#6B7194" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M6 11L1 6L6 1" stroke="#6B7194" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg></span> Back</a>
                                                <span class="tpd-course-title">
                                                    <?= isset($duzenle['user_name']) ? htmlspecialchars($duzenle['user_name']) : 'Kullanıcı bulunamadı'; ?></span>

                                            </div>
                                            <h2 class="tp-dashboard-title">Level Assessment Exam</h2>
                                            <div class="tpd-quiz-time">
                                                <ul>
                                                    <li>
                                                        <div class="tpd-quiz-time-item">
                                                            <p><span>Quiz Time:</span> 1 Hour</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="tpd-quiz-time-item">
                                                            <p><span>Exam Duration:</span> <?= $gercekSure; ?>
                                                                <?= $gercekSureEtiket; ?></p>
                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- section-area-end -->
                            <!-- course-area-start -->
                            <div class="tpd-quiz-attempts-area">
                                <div class="row">
                                    <div class="col-12">
                                        <h2 class="tp-dashboard-title">Test Review</h2>
                                        <div class="tpd-table mb-25">
                                            <ul>
                                                <li class="tpd-table-head">
                                                    <div class="tpd-table-row">
                                                        <div class="tpd-quiz-no">
                                                            <h4 class="tpd-table-title">No</h4>
                                                        </div>
                                                        <div class="tpd-quiz-type">
                                                            <h4 class="tpd-table-title">Type</h4>
                                                        </div>
                                                        <div class="tpd-quiz-date-2">
                                                            <h4 class="tpd-table-title">Question</h4>
                                                        </div>
                                                        <div class="tpd-quiz-tm-3">
                                                            <h4 class="tpd-table-title">Answer Given</h4>
                                                        </div>
                                                        <div class="tpd-quiz-pm-2">
                                                            <h4 class="tpd-table-title">Correct Answer</h4>
                                                        </div>
                                                        <div class="tpd-quiz-result-2">
                                                            <h4 class="tpd-table-title">Result</h4>
                                                        </div>

                                                    </div>
                                                </li>
                                                <?php
                                                   
                                                    $lastSessionId = null;
                                                    $i = 1;
                                                    
                                                    foreach ($results as $row):
                                                        $currentSessionId = $row['exam_session_id'];
                                                        $currentDate = date('Y-m-d H:i', strtotime($row['date']));
                                                    
                                                        if ($lastSessionId !== $currentSessionId):
                                                            $lastSessionId = $currentSessionId;
                                                            $i = 1;
                                                    ?>
                                                        <li>
                                                            <div class="tpd-table-row">
                                                                <div style="width:100%; padding:10px; background:#f0f0f0; font-weight:bold;">
                                                                    Exam Date: <?= $currentDate ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    
                                                        <?php
                                                        // Skoru ve süreyi scores tablosundan getir
                                                        $scoreQuery = $db->prepare("SELECT score, exam_duration FROM scores WHERE exam_session_id = :session_id LIMIT 1");
                                                        $scoreQuery->execute(['session_id' => $currentSessionId]);
                                                        $scoreRow = $scoreQuery->fetch(PDO::FETCH_ASSOC);
                                                        ?>
                                                    
                                                        <?php if ($scoreRow): ?>
                                                        <li>
                                                            <div class="tpd-table-row">
                                                                <div style="width:100%; padding:5px 10px; background:#f9f9f9;">
                                                                    Score: <?= $scoreRow['score'] ?> <br>
                                                                    Duration: <?= $scoreRow['exam_duration'] ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    
                                                    



                                                <!-- Normal soru bilgileri -->
                                                <li>
                                                    <div class="tpd-table-row">
                                                        <div class="tpd-quiz-no">
                                                            <h4 class="tpd-quiz-title-2-color"><?= $i++; ?></h4>
                                                        </div>
                                                        <div class="tpd-quiz-type">
                                                            <div class="tpd-action-btn">
                                                            <?php
                                                                $type = $row['type'];
                                                                $iconPath = "assets/icons/default.png";

                                                                if ($type === 'reading') {
                                                                    $iconPath = "assets/icons/reading.png";
                                                                } elseif ($type === 'listening') {
                                                                    $iconPath = "assets/icons/listening.png";
                                                                }
                                                            ?>

                                                            <button class="nohover position-relative"
                                                            title="<?= htmlspecialchars($type); ?>" 
                                                            style="width: 40px; height: 40px; border-radius: 50%; padding: 0; overflow: hidden;">
                                                                <?php
                                                                    $type = $row['type'];
                                                                    $iconPath = "assets/icons/default.png"; // varsayılan ikon

                                                                    if ($type === 'reading') {
                                                                        $iconPath = "assets/icons/reading.png";
                                                                    } elseif ($type === 'listening') {
                                                                        $iconPath = "assets/icons/listening.png";
                                                                    }
                                                                ?>
    
                                                                <!-- Yuvarlak içi ikon -->
                                                                <img src="<?= $iconPath ?>" alt="<?= $type ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                                <!-- Tooltip etiketi -->


                                                                <!-- Mevcut dış etiket korunuyor -->
                                                                <span class="tpd-action-tooltip d-flex align-items-center gap-1" style="position: absolute; left: 45px; top: 50%; transform: translateY(-50%); background-color: #000; color: #fff; padding: 3px 6px; border-radius: 5px;">
                                                                    <img src="<?= $iconPath ?>" alt="<?= $type ?>" style="width: 20px; height: 20px; margin-right: 5px;">
                                                                    <?= htmlspecialchars($type); ?>
                                                                </span>
                                                            </button>


                                                            </div>
                                                        </div>
                                                        <div class="tpd-quiz-date-2">
                                                            <h4 class="tpd-quiz-title-2-color">
                                                                <?= htmlspecialchars($row['question_text']); ?></h4>
                                                        </div>
                                                        <div class="tpd-quiz-tm-3">
                                                            <h4 class="tpd-quiz-title-2-color"style="
                                                            margin-left: 45px;">
                                                                <?= htmlspecialchars($row['user_answer']); ?></h4>
                                                        </div>
                                                        <div class="tpd-quiz-pm-2">
                                                            <h4 class="tpd-quiz-title-2-color">
                                                                <?= htmlspecialchars($row['correct_option']); ?></h4>
                                                        </div>
                                                        <div class="tpd-quiz-result-2">
                                                            <div class="tpd-badge-item">
                                                                <?php if ($row['is_correct'] == 1): ?>
                                                                <span class="tpd-badge sucess">Correct</span>
                                                                <?php else: ?>
                                                                <span class="tpd-badge danger">Wrong</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- course-area-start-end -->

                        </div>
                        <!-- dashboard-content-area-end -->

                    </div>
                </div>
            </div>
        </section>
        <!-- dashboad-content-box-area-end -->

    </main>


    <!-- JS here -->
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
    function editJobSection(jobId) {
        window.location.href = 'yonetim/is-bolumu-duzenle.php?id=' + jobId;
    }
    </script>
</body>


<!-- Mirrored from html.hixstudio.net/acadia-prev/acadia/student-q&a.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 01 Dec 2024 18:53:41 GMT -->

</html>