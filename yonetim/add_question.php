<?php
require 'inc/baglan.php'; // Veritabanı bağlantısı

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $audio_file = NULL;

    // Eğer soru listening ise dosya yükleme işlemi
    if ($type == 'listening' && isset($_FILES['audio_file'])) {
        $target_dir = "uploads/audio/";
        $audio_file = $target_dir . basename($_FILES["audio_file"]["name"]);
        move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file);
    }

    // Veritabanına ekleme
    $sql = "INSERT INTO questions (type, question_text, option_a, option_b, option_c, option_d, correct_option, audio_file)
            VALUES (:type, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option, :audio_file)";
    $stmt = $db->prepare($sql);
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

    echo "Question added successfully!";
    //başarıla eklendi kodu
}
?>
<!-- form tablosu-->
 
<form action="" method="POST" enctype="multipart/form-data">
    <label>Question Type:</label>
    <select name="type">
        <option value="reading">Reading</option>
        <option value="listening">Listening</option>
    </select>
    <br>
    <label>Question Text:</label>
    <textarea name="question_text"></textarea><br>
    <label>Option:</label><br>
    A: <input type="text" name="option_a"><br>
    B: <input type="text" name="option_b"><br>
    C: <input type="text" name="option_c"><br>
    D: <input type="text" name="option_d"><br>
    <label>Correct Option:</label>
    <select name="correct_option">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select>
    <br>
    <label>Audio File for Listening Question:</label>
    <input type="file" name="audio_file"><br>
    <button type="submit">Add Question</button>
</form>
