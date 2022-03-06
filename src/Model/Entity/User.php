<?php

namespace Bobyblog\Model\Entity;

class User extends UserAbstract {

    public function __construct(
        private int $id,
        private string $username,
        private string $password,
        private \Datetime $creationDate,
        private \Datetime $lastLogin
    ){}

    public function getId(): int{
        return $this->id;
    }

    public function getUsername(): string{
        return $this->username;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getCreationDate(): \Datetime{
        return $this->creationDate;
    }

    public function getLastLogin(): \Datetime{
        return $this->lastLogin;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setUsername(string $username): void{
        $this->username = $username;
    }

    public function setPassword(string $password): void{
        $this->password = $password;
    }

    public function setCreationDate(\Datetime $creationDate): void{
        $this->creationDate = $creationDate;
    }

    public function setLastLogin(\Datetime $lastLogin): void{
        $this->lastLogin = $lastLogin;
    }
}