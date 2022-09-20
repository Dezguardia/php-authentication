<?php

namespace Html;

use Html\StringEscaper;
use Entity\User;

class UserProfile
{
    protected User $user;

    public function __construct(User $fUser)
    {
        $this->user = $fUser;
    }

    public function toHtml(): string
    {
        $lastName=StringEscaper::escapeString($this->user->getLastName());
        $firstName=StringEscaper::escapeString($this->user->getFirstName());
        $login=StringEscaper::escapeString($this->user->getLogin());
        $phone=StringEscaper::escapeString($this->user->getPhone());
        $profile =
            <<<HTML
        <div class="profile">
            <h2>Nom</h2>
            <p>{$lastName}</p>
            <h2>Prénom</h2>
            <p>{$firstName}</p>
            <h2>Login</h2>
            <p>{$login}</p>
            <h2>Téléphone</h2>
            <p>{$phone}</p>
        </div>
        HTML;
        return $profile;
    }
}
