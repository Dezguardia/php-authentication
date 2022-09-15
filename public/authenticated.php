<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\WebPage;

$authentication = new UserAuthentication();

// Un utilisateur est-il connecte ?
if (!$authentication->isUserConnected()) {
    // Rediriger vers le formulaire de connexion
    header("Location: /form.php");
    die(); // Fin du programme
}

$title = 'Utilisateur';
$p = new WebPage($title);
$user=$authentication->getUser();

$p->appendContent(
    <<<HTML
        <h1>{$user->getFirstName()}</h1>
HTML
);

echo $p->toHTML();
