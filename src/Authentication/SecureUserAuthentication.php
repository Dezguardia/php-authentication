<?php

namespace Authentication;

use Entity\User;
use Helper\Random;
use Service\Exception\SessionException;
use Service\Session;

class SecureUserAuthentication extends AbstractUserAuthentication
{
    public const CODE_INPUT_NAME = 'code';
    public const SESSION_CHALLENGE_KEY = 'challenge';
    public const RANDOM_STRING_SIZE = 16;

    /**
     * @throws SessionException
     */
    public function loginForm(string $action, string $submitText = 'OK'): string
    {
        Session::start();
        $tirage=Random::string(self::RANDOM_STRING_SIZE);
        $_SESSION[self::SESSION_CHALLENGE_KEY]=$tirage;
        $script = file_get_contents("../../../js/sha512.js");
        $form = <<<HTML
        <form name="loginform" method="POST" action="{$action}" onsubmit="codage()">
            <label>
                <input id="login" type="text" placeholder="login">
            </label>
            <label>
                <input type="password" id="password" placeholder="password">
            </label>
            <label>
                <input type="hidden" id="challenge" value="$tirage">
            </label>
            <label>
                <input type="hidden" id="code" name="code">
            </label>
            <button type="submit">{$submitText}</button>
        </form>
        <script>$script</script>  
        <script>
            function codage() {
                var data = SHA2(SHA2(password)+challenge+SHA2(login));
                
            }
        </script>
        HTML;

        return $form;
    }

    public function getUserFromAuth(): User
    {
        // TODO: Implement getUserFromAuth() method.
    }
}
