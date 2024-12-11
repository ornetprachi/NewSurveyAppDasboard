<?php

session_start();

// unset($_SESSION['CHCZ_Mobile']);
session_unset();
session_destroy();
header('Location:login.php');

?>