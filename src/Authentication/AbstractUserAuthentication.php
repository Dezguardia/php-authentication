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


    public function __construct()
    {
        try {
            $this->user=$this->getUserFromSession();
        } catch (NotLoggedInException) {

        }
    }


    abstract public function loginForm(string $action, string $submitText='OK'): string;

    abstract public function getUserFromAuth(): User;

    /**
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        Session::start();
        $this->user=$user;
        $_SESSION[$this::SESSION_KEY][$this::SESSION_USER_KEY]=$user;
    }

    /**
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        return isset($_SESSION[$this::SESSION_KEY][$this::SESSION_USER_KEY]);
    }

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

    public function getUser(): User
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            throw new NotLoggedInException("Utilisateur non connecté");
        }
    }
}
