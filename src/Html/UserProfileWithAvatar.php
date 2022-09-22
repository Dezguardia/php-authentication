<?php

namespace Html;

use Entity\User;
use Html\Helper\Dumper;

class UserProfileWithAvatar extends UserProfile
{

    public const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;
    public function toHtml(): string
    {
        $url="avatar.php?userId="."{$this->user->getId()}";
        $profile= parent::toHtml();
        $avatarname=self::AVATAR_INPUT_NAME;
        $profile.= <<<HTML
            <div class="img">
                <form method="POST" action="$this->formAction" enctype="multipart/form-data" name="imgform">
                    <label>
                        Changer:
                        <input type="file" name="$avatarname">
                    </label>
                    <button type="submit">Mettre à jour</button>
                </form>
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

    public function updateAvatar(): bool
    {
        echo Dumper::dump($_FILES);
        if (isset($_FILES[self::AVATAR_INPUT_NAME])
            and $_FILES[self::AVATAR_INPUT_NAME]['error'] == UPLOAD_ERR_OK
            and $_FILES[self::AVATAR_INPUT_NAME]['size'] > 0
            and is_uploaded_file($_FILES[self::AVATAR_INPUT_NAME]['tmp_name'])) {
            return true;
        } else {
            return false;
        }
    }
}
