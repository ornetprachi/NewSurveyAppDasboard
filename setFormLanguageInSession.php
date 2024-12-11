<?php 
session_start();

if(isset($_GET['formLanguage']) && !empty($_GET['formLanguage']))
{
    $formLanguage = $_GET['formLanguage'];

    $_SESSION['Form_Language'] = $formLanguage;

    echo "<script type='text/javascript'>alert('$formLanguage');</script>";
}
?>