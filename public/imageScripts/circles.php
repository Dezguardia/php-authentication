<?php

declare(strict_types=1);

use ImageManipulation\MyGdImage;

$im1 = MyGdImage::createFromSize(100, 100) ;
$background = $im1->colorallocate(190, 216, 222);
$im1->filledRectangle(0, 0, 99, 99, $background) ;
header('Content-Type: image/png') ;

for ($i=0;$i<30;$i++) {
    $color=$im1->colorallocate(rand(0, 255), rand(0, 255), rand(0, 255));
    $im1->colortransparent($color);
    $im1->filledellipse(rand(0, 100), rand(0, 100), rand(0, 100), rand(0, 100), $color);
}

$im1->png() ;
