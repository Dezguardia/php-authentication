<?php

use Html\WebPage;

$p = new WebPage('Images');
$p->appendContent(
    <<<HTML
    <h2>Cible</h2>
    <img src="imageScripts/target.php" alt="target image">
    <h2>Cercles</h2>
    <img src="imageScripts/circles.php" alt="circles image">
    <h2>Ciel</h2>
    <img src="imageScripts/sky.php" alt="sky image">
HTML

);
echo $p->toHTML();
