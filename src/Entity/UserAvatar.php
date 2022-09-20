<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use PDO;

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
     * @param string|null $avatar
     * @return UserAvatar
     */
    public function setAvatar(?string $avatar): UserAvatar
    {
        $this->avatar = $avatar;
        return $this;
    }

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
}
