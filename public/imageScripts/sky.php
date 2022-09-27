<?php

declare(strict_types=1);

use ImageManipulation\MyGdImage;

$img= MyGdImage::createFromSize(500, 500);
$background= $img->colorallocate(0, 0, 0);
$img->filledrectangle(250, 250, 500, 500, $background);
$yellow = $img->colorallocate(250, 246, 134);
for ($i=0;$i<30;$i++) {
    $starsize=rand(1, 4);
    $img->filledellipse(rand(0, 250), rand(0, 250), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(250, 500), rand(250, 500), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(0, 250), rand(250, 500), $starsize, $starsize, $yellow);
    $img->filledellipse(rand(250, 500), rand(0, 250), $starsize, $starsize, $yellow);
}

$img->filledellipse(150, 250, 125, 125, $yellow);
$img->filledarc(150, 250, 25, 125, 90, 90, $background, IMG_ARC_PIE);
$img->filledellipse(350, 250, 125, 125, $yellow);
$img->filledarc(350, 250, 25, 125, 90, 90, $background, IMG_ARC_PIE);
header("Content-type: image/png");
$img->png();
