<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\StringEscaper;

class UserAuthentication
{
    private const LOGIN_INPUT_NAME = "login";
    private const PASSWORD_INPUT_NAME = "password";


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
        return $user;
    }
}
