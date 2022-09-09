<?php

namespace Html;

use Html\StringEscaper;
use Entity\User;

class UserProfile
{
    private User $user;

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
            <p>Nom</p>
            <p>{$lastName}</p>
            <p>Prénom</p>
            <p>{$firstName}</p>
            <p>Login</p>
            <p>{$login}</p>
            <p>Téléphone</p>
            <p>{$phone}</p>
        </div>
        HTML;
        return $profile;
    }
}
