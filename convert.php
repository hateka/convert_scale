<?php

require_once('./ImageResize.php');

$imgsdir = 'imgs/';
$scale = 300;

$im = new ImageResize($imgsdir,$scale);
$filelist = $im->get_file($im->dirname);
$path = $im->filetype($filelist);
$target = $im->image_info($path);
$im->resize($target);
$im->reduction($im->resized);
