<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use PDO;

class User
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $login;
    private string $phone;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Cherche un utilisateur dans la base de données à partir des informations de connexion en paramètre.
     * @throws EntityNotFoundException
     */
    public static function findByCredentials(string $login, string $password): self
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            select id, firstName, lastName, login, phone
            from user 
            where login = :login
            and sha512pass = SHA2(:password,512)
        SQL
        );
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        } else {
            throw new EntityNotFoundException('Utilisateur introuvable');
        }
    }
}
