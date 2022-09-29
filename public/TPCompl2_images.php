<?php

use Html\WebPage;

$p = new WebPage('Images');
$p->appendCssUrl("css/style.css");
$moonValue = $_POST['moonValue'] ?? 80;
$p->appendContent(
    <<<HTML
    <h2>Cible</h2>
    <img src="imageScripts/target.php" alt="target image">
    <h2>Cercles</h2>
    <img src="imageScripts/circles.php" alt="circles image">
    <h2>Ciel</h2>
    <form name="moonValue" action="TPCompl2_images.php" method="POST">
    <label>
    Phase de la lune :
        <input name="moonValue" type="range" min="0" max="200" step="20" value="$moonValue" required>
    </label>
    <button type="submit">Changer</button>
    </form>
    <img src="imageScripts/sky.php?moonValue=$moonValue" alt="sky image">
    <h2>Filtres</h2>
HTML
);
echo $p->toHTML();
