<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Authentication\Exception\AuthenticationException;
use Html\WebPage;

$authentication = new UserAuthentication();

$p = new WebPage('Utilisateur');
$p->appendCssUrl("css/style.css");

try {
    // Tentative de connexion
    $user = $authentication->getUserFromSession();
    $userprofile = new \Html\UserProfileWithAvatar($authentication->getUser(), $_SERVER['PHP_SELF']);
    $userprofile->updateAvatar();
    echo \ServerConfiguration\Directive::getUploadMaxFileSize();
    $p->appendContent(<<<HTML
        <h1>Profil de {$user->getFirstName()}</h1>
    HTML);
    $p->appendContent(
        $userprofile->toHtml()
    );
} catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}");
}

if (!$authentication->isUserConnected()) {
    // Rediriger vers le formulaire de connexion
    header("Location: /form.php");
    die(); // Fin du programme
}
// Envoi du code HTML au navigateur du client
echo $p->toHTML();
