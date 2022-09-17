<?php
session_start();
$en=41;
$boy=12;
$sayi = mt_rand(1000,9999);
$ss = $_GET["s"];
$_SESSION['captaha_'.$ss] = $sayi;
$tuval = imagecreatetruecolor($en,$boy);
$color = imagecolorallocate($tuval,rand(0,255),rand(0,255),rand(0,255));
imageline($tuval, rand(0,100), rand(0,10), rand(0,100), rand(0,20), $color);
$color = imagecolorallocate($tuval,rand(0,255),rand(0,255),rand(0,255));
imageline($tuval, rand(0,100), rand(0,20), rand(0,100), rand(0,20), $color);
$color = imagecolorallocate($tuval,rand(0,255),rand(0,255),rand(0,255));
imageline($tuval, rand(0,100), rand(0,20), rand(0,100), rand(0,20), $color);
$color = imagecolorallocate($tuval,rand(0,255),rand(0,255),rand(0,255));
imageline($tuval, rand(0,100), rand(0,20), rand(0,100), rand(0,20), $color);
$color = imagecolorallocate($tuval,rand(0,255),rand(0,255),rand(0,255));
imageline($tuval, rand(0,100), rand(0,20), rand(0,100), rand(0,20), $color);
$b = imagecolorallocate($tuval,175,238,238);
$s = imagecolorallocate($tuval,0,0,0);
imagefill($tuval,0,0,$s);
imagestring($tuval,3,7,-1,$sayi,$b);
Header("content-type:image/gif");
imagegif($tuval);
imagedestroy($tuval);
?>