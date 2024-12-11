<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');

header('Content-type: image/jpeg');

$img_r = $_GET['filePathAndName'];
$degree = $_GET['degree'];

$searchTerm = 'UploadImagePhp';
$filePath = substr($img_r, strpos($img_r, $searchTerm));



$img_r = imagecreatefromjpeg($img_r);
$rotate = imagerotate($img_r, $degree, 0);


imagejpeg($rotate);
imagejpeg($rotate,$filePath);

// print_r($filePath);
// die();

imagedestroy($img_r);
imagedestroy($rotate);

// print_r($filePath);


?>