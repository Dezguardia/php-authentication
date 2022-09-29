<?php

declare(strict_types=1);

use ImageManipulation\MyGdImage;

$img= MyGdImage::createFromSize(500, 500);
$background= $img->colorallocate(0, 0, 0);
$img->filledrectangle(250, 250, 500, 500, $background);
$yellow = $img->colorallocate(250, 246, 134);
$blackValue=0;
$yVal=0;
for ($i=0;$i<40;$i++) {
    $color = $img->colorallocate($blackValue, $blackValue, $blackValue);
    $img->filledrectangle(0, $yVal, 500, $yVal+500/40, $color);
    $blackValue+=2;
    $yVal+=500/40;
}
for ($i=0;$i<30;$i++) {
    $starsize=rand(1, 4);
    $img->filledellipse(rand(0, 250), rand(0, 250), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(250, 500), rand(250, 500), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(0, 250), rand(250, 500), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(250, 500), rand(0, 250), $starsize, $starsize, $yellow);
}
$moonValue = $_GET['moonValue'] ?? 80;
$width=130-(130/100)*(200-$moonValue);

$arcColor = $background;
$moonColor = $yellow;
$angle=-90;
$end_angle=90;
if ($moonValue>100) {
    $arcColor=$yellow;
    $moonColor = $background;
    $angle=90;
    $end_angle=-90;
}
$img->filledellipse(150, 250, 125, 125, $moonColor);
$img->filledarc(150, 250, 125, 125, $angle, $end_angle, $arcColor, IMG_ARC_PIE);
$img->filledarc(150, 250, $width, 126, 120, 120, $arcColor, IMG_ARC_PIE);
//$img->filter(IMG_FILTER_NEGATE, IMG_FILTER_BRIGHTNESS);
header("Content-type: image/png");
$img->png();
