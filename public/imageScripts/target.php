<?php

declare(strict_types=1);

use ImageManipulation\MyGdImage;

// Création d'une nouvelle image de 100 par 100
$im1 = MyGdImage::createFromSize(100, 100) ;
// Allocation d'une couleur pour $im1
$im1Black = $im1->colorAllocate(0, 0, 0) ;
$yellow = $im1->colorallocate(250, 247, 54);
$red = $im1->colorallocate(250,0,0);
// Remplissage de l'image $im1 avec la couleur
$im1->filledRectangle(0, 0, 99, 99, $im1Black) ;
$switch=true;
for ($i=0;$i<100;$i+=9) {
    if ($switch) {
        $color=$yellow;
    } else {
        $color=$im1Black;
    }
    $im1->filledellipse(50, 50, 100-$i, 100-$i, $color);
    $switch = !$switch;
}
$im1->filledellipse(50, 50, 10, 10, $red);
// Entête HTTP
header('Content-Type: image/png') ;
// Envoi des données PNG vers le navigateur
$im1->png() ;
