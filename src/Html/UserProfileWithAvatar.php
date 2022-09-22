<?php

namespace Html;

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Entity\User;
use Entity\UserAvatar;
use Html\Helper\Dumper;
use ServerConfiguration\Directive;
use Service\Exception\SessionException;

class UserProfileWithAvatar extends UserProfile
{
    public const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;

    /**
     * Produit l'affichage du profil de la classe mère en rajoutant le formulaire de mise à jour de l'avatar
     * de l'utilisateur.
     * @return string
     */
    public function toHtml(): string
    {
        $url="avatar.php?userId="."{$this->user->getId()}";
        $profile= parent::toHtml();
        $avatarname=self::AVATAR_INPUT_NAME;
        $maxfilesize=min(Directive::getUploadMaxFileSize(), UserAvatar::maxFileSize());
        $profile.= <<<HTML
            <div class="img">
                <form method="POST" action="$this->formAction" enctype="multipart/form-data" name="imgform">
                    <label>
                        Changer:
                        <input type="file" name="$avatarname">
                    </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="$maxfilesize">
                    <button type="submit">Mettre à jour</button>
                </form>
                <img src="$url" alt="avatar">
            </div>
            
        HTML;
        return $profile;
    }

    /**
     * Constructeur de la classe.
     * @param User $user
     * @param string $formAction
     */
    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction=$formAction;
    }

    /**
     *
     * @return bool
     * @throws \Entity\Exception\EntityNotFoundException
     */
    public function updateAvatar(): bool
    {
        //echo Dumper::dump($_FILES);
        if (isset($_FILES[self::AVATAR_INPUT_NAME])
            and $_FILES[self::AVATAR_INPUT_NAME]['error'] == UPLOAD_ERR_OK
            and $_FILES[self::AVATAR_INPUT_NAME]['size'] > 0
            and is_uploaded_file($_FILES[self::AVATAR_INPUT_NAME]['tmp_name'])) {
            $auth=new UserAuthentication();
            try {
                $user = $auth->getUserFromSession();
            } catch (NotLoggedInException|SessionException $e) {
            }
            $userAvatar=UserAvatar::findByID($user->getId());
            $userAvatar->setAvatar(file_get_contents($_FILES[self::AVATAR_INPUT_NAME]['tmp_name']));
            $userAvatar->save();
            return true;
        } else {
            return false;
        }
    }
}
