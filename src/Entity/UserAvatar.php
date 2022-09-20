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
}
