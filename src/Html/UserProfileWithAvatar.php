<?php

namespace Html;

use Entity\User;

class UserProfileWithAvatar extends UserProfile
{

    public const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;
    public function toHtml(): string
    {
        $url="avatar.php?userId="."{$this->user->getId()}";
        $profile= parent::toHtml();
        $profile.= <<<HTML
            <div class="img">
                <img src="$url" alt="avatar">
            </div>
        HTML;
        return $profile;

    }

    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction=$formAction;
    }
}
