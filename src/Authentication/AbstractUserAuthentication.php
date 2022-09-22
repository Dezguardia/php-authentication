<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Authentication\Exception\NotLoggedInException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\StringEscaper;
use Service\Exception\SessionException;
use Service\Session;

abstract class AbstractUserAuthentication
{
    public const SESSION_KEY = "_UserAuthentication_";
    public const SESSION_USER_KEY = "user";
    public const LOGOUT_INPUT_NAME = "logout";
    private ?User $user = null;

    /**
     * Constructeur de la classe AbstractUserAuthentication
     * @throws SessionException
     */
    public function __construct()
    {
        try {
            $this->user=$this->getUserFromSession();
        } catch (NotLoggedInException) {
        }
    }

    /**
     * Crée le code html formulaire de connexion
     * @param string $action
     * @param string $submitText
     * @return string Le code html
     */
    abstract public function loginForm(string $action, string $submitText='OK'): string;

    /**
     * Cherche un utilisateur à partir des données transmises en POST
     * @return User
     */
    abstract public function getUserFromAuth(): User;

    /**
     * Affecte à la propriété user l'utilisateur passé en paramètre
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        Session::start();
        $this->user=$user;
        $_SESSION[$this::SESSION_KEY][$this::SESSION_USER_KEY]=$user;
    }

    /**
     * Vérfie si un utilisateur est connecté
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        return isset($_SESSION[$this::SESSION_KEY][$this::SESSION_USER_KEY]);
    }

    /**
     * Produit le formulaire de déconnexion à partir de l'action donnée en paramètre
     * @param string $action
     * @param string $text
     * @return string
     */
    public function logoutForm(string $action, string $text): string
    {
        $logout=self::LOGOUT_INPUT_NAME;
        $form = <<<HTML
        <form name="logoutForm" method="POST" action="$action">
            <button type="submit" name="$logout">$text</button>
        </form>

        HTML;
        return $form;
    }

    /**
     * Vérifie si une demande de déconnexion se trouve dans les données en post
     * @throws SessionException
     */
    public function logoutIfRequested(): void
    {
        if (array_key_exists('logout', $_POST)) {
            Session::start();
            session_destroy();
            $this->user=null;
        }
    }

    /**
     * Retourne un utilisateur ou non à partir des données de session
     * @throws NotLoggedInException|SessionException
     */
    public function getUserFromSession(): ?User
    {
        Session::start();
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])) {
            if ($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof User) {
                return $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
            } else {
                throw new NotLoggedInException("Utilisateur non connecté");
            }
        }
        return null;
    }

    /**
     * Retourne l'utilisateur affecté à user
     * @return User
     * @throws NotLoggedInException
     */
    public function getUser(): User
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            throw new NotLoggedInException("Utilisateur non connecté");
        }
    }
}
