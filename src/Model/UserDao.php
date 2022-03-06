<?php

namespace Bobyblog\Model;

use Bobyblog\Model\Entity\User;

class UserDAO extends AbstractDAO {

    protected string $tableName = 'user';

    public function __construct(){
        parent::__construct($this->tableName);
    }

    public function insert(User $user): \PDOStatement{
        $query = $this->getDbConnection()->prepare(''
                . 'INSERT INTO `' . $this->tableName . '` (username, creationDate, password) '
                . 'VALUES (:username, :creationDate, :password)');

        $query->execute([
            ':username' => $user->getUsername(),
            ':creationDate' => $user->getCreationDate()->format('c'),
            ':password' => $user->getPassword(),
        ]);

        return $query;
    }

    public function update(User $user): \PDOStatement{
        $query = $this->getDbConnection()->prepare('UPDATE `' . $this->tableName . '` SET lastLogin = :lastLogin WHERE id = :id');

        $query->execute([
            ':lastLogin' => $user?->getLastLogin()->format('c') ?? new \DateTime(),
            ':id' => $user->getId()
        ]);

        return $query;
    }

    public function delete(User $user){
        $query = $this->getDbConnection()->prepare('DELETE FROM`' . $this->tableName . '` WHERE id = :id');

        $query->execute([
            ':id' => $user->getId()
        ]);

        return $query;
    }

    public function getById($id): ?User{
        $obj = parent::getById($id);

        if($obj !== false){
            return $this->fromObjToUser($obj);
        }
        return null;
    }

    public function getByUsername($username): ?User{
        if(is_string($username) && $username !== ''){
            $query = $this->getDbConnection()->prepare('SELECT * FROM `' . $this->tableName . '` WHERE username = :username');
            $query->execute([':username' => $username]);

            $result = $query->fetch(\PDO::FETCH_OBJ);

            if($result !== false){
                return $this->fromObjToUser($result);
            }
        }
        return null;
    }

    private function fromObjToUser($obj): User{
        $user = new User(
            $obj->id,
            $obj->username,
            $obj->password,
            new \DateTime($obj->creationDate),
            new \DateTime($obj->lastLogin)
        );

        return $user;
    }
}