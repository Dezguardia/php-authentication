<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\StringEscaper;
use Service\Exception\SessionException;

class UserAuthentication extends AbstractUserAuthentication
{
    public const LOGIN_INPUT_NAME = "login";
    public const PASSWORD_INPUT_NAME = "password";

    /**
     * Implémente loginForm de la classe mère.
     * Crée un formulaire de connexion et retourne le code html
     * @param string $action
     * @param string $submitText
     * @return string
     */
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
     * Implémente getUserFromAuth de la classe mère
     * Retourne un utilisateur s'il se trouve dans le tableau des données POST
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
}
