<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\Helper\Dumper;
use Html\WebPage;

// Création de l'authentification
$authentication = new UserAuthentication();
$authentication->logoutIfRequested();
$p = new WebPage('Authentification');
$p->appendCssUrl("css/style.css");


// Production du formulaire de connexion
$p->appendCSS(
    <<<CSS
    form input {
        width : 4em ;
    }
CSS
);
if ($authentication->isUserConnected()) {
    $form = $authentication->logoutForm('form.php', 'Se déconnecter');
    $userprofile = new \Html\UserProfile($authentication->getUser());
    $p->appendContent(
        $userprofile->toHtml()
    );
} else {
    $form = $authentication->loginForm('auth.php');
}

$p->appendContent(
    <<<HTML
    {$form}
HTML
);

echo $p->toHTML();
