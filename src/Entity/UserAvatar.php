<?php

namespace Entity;

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use Html\UserProfileWithAvatar;
use PDO;
use Service\Exception\SessionException;

class UserAvatar
{
    private int $id;
    private ?string $avatar;


    protected function __construct()
    {
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Cherche l'avatar d'un utilisateur dans la base de données à partir de son ID.
     * @throws EntityNotFoundException
     */
    public static function findByID(int $userId): self
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            select id, avatar
            from user 
            where id = :id
        SQL
        );
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, UserAvatar::class);
        $userAvatar = $stmt->fetch();
        if ($userAvatar) {
            return $userAvatar;
        } else {
            throw new EntityNotFoundException('Utilisateur introuvable');
        }
    }

    /**
     * Affecte l'avatar passé en paramètre à avatar.
     * Retourne l'UserAvatar.
     * @param string|null $avatar
     * @return UserAvatar
     */
    public function setAvatar(?string $avatar): UserAvatar
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Met à jour dans la base de données l'avatar d'un utilisateur à partir des valeurs de l'instance d'UserAvatar.
     * Retourne l'instance d'UserAvatar.
     * @return $this
     */
    public function save(): self
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<SQL
            UPDATE user 
            SET avatar = :avatar
            WHERE id = :id
        SQL
        );
        $avatar=$this->getAvatar();
        $id=$this->getId();
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $this;
    }

    /**
     * Retourne la taille maximale de fichier possible pour l'avatar.
     * @return int
     */
    public static function maxFileSize(): int
    {
        return 65535;
    }

    /**
     * Vérifie si un fichier est valide.
     * Si oui, met à jour la valeur de l'attribut UserAvatar et appelle save() pour le mettre à jour
     * dans la base de données.
     * @throws SessionException
     * @throws NotLoggedInException
     * @throws EntityNotFoundException
     */
    public static function isValidFile(string $filename): bool
    {
        if (mime_content_type($filename)
            and getimagesize($filename)) {
            $auth=new UserAuthentication();
            $user=$auth->getUserFromSession();
            $userAvatar=self::findByID($user->getId());
            $userAvatar->setAvatar(file_get_contents($filename));
            $userAvatar->save();
            return true;
        } else {
            return false;
        }
    }
}
