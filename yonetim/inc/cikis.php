<?php
session_start();
unset($_SESSION['loggin']);

header('Location:../login.php?Logout=ok')
// oturumu kapatmaya yarar 

?>