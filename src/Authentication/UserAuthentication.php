<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
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
        $logout=$this::LOGOUT_INPUT_NAME;
        $form = <<<HTML
        <form name="logoutForm" method="POST" action="$action">
            <label>
                <input name="$logout">
            </label>
            <button type="submit">$text</button>
        </form>

        HTML;
        return $form;
    }

    public function logoutIfRequested(): void
    {
        if ($_POST['logout']) {
            $_SESSION[$this::SESSION_KEY][$this::SESSION_USER_KEY]=null;
        }
    }
}
