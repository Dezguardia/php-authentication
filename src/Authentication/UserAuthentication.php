<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Authentication\Exception\NotLoggedInException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\StringEscaper;
use Service\Exception\SessionException;
use Service\Session;

class UserAuthentication
{
    public const LOGIN_INPUT_NAME = "login";
    public const PASSWORD_INPUT_NAME = "password";
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


    public function loginForm(string $action, string $submitText='OK'): string
    {
        $logInput=$this::LOGIN_INPUT_NAME;
        $passInput=$this::PASSWORD_INPUT_NAME;
        $form = <<<HTML
        <form name="loginform" method="POST" action="{$action}">
            <label>
                <input name="login" type="text" placeholder="$logInput">
            </label>
            <label>
                <input name="pass" type="password" placeholder="$passInput">
            </label>
        
            <button type="submit">{$submitText}</button>
        </form>
        HTML;
        return $form;
    }

    /**
     * @throws AuthenticationException
     * @throws SessionException
     */
    public function getUserFromAuth(): User
    {
        $login=$_POST['login'];
        $pass=$_POST['pass'];
        try {
            $user = User::findByCredentials($login, $pass);
        } catch (EntityNotFoundException) {
            throw new AuthenticationException("Utilisateur introuvable.");
        }
        $this->setUser($user);
        return $user;
    }

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
     * @throws NotLoggedInException
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
