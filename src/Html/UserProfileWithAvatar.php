<?php

namespace Html;

class UserProfileWithAvatar extends UserProfile
{
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
}
