<?php

declare(strict_types=1);

use Authentication\SecureUserAuthentication;
use Authentication\UserAuthentication;
use Html\Helper\Dumper;
use Html\WebPage;

// Création de l'authentification
$authentication = new SecureUserAuthentication();
try {
    $authentication->logoutIfRequested();
} catch (\Service\Exception\SessionException $e) {
}
$p = new WebPage('Authentification');



// Production du formulaire de connexion
$p->appendCSS(
    <<<CSS
    form input {
        width : 4em ;
    }
CSS
);
try {
    if ($authentication->isUserConnected()) {
        $form = $authentication->logoutForm('form.php', 'Se déconnecter');
        $userprofile = new \Html\UserProfile($authentication->getUser());
        $p->appendContent(
            $userprofile->toHtml()
        );
    } else {
        $form = $authentication->loginForm('secure_auth.php');
    }
} catch (\Authentication\Exception\NotLoggedInException|\Service\Exception\SessionException $e) {
}

$p->appendContent(
    <<<HTML
    {$form}
HTML
);

echo $p->toHTML();
