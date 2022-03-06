<?php

namespace Bobyblog\Model;

abstract class AbstractDAO {

    private \PDO $dbConnection;
    protected string $tableName;

    public function __construct(string $tableName){
        $this->tableName = $tableName;
    }

    public function getDbConnection(): \PDO{
        if(!isset($this->dbConnection))
            $this->dbConnection = new \PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        return $this->dbConnection;
    }

    public function getAll(){
        $query = $this->getDbConnection()->prepare("SELECT * FROM $this->tableName");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getById($id){
        if(is_numeric($id) && $id > 0){
            $query = $this->getDbConnection()->prepare("SELECT * FROM $this->tableName WHERE id = :id");
            $query->execute([':id' => $id]);
            return $query->fetch(\PDO::FETCH_OBJ);
        }
        return false;
    }
    
    public function getTableName(){
        return $this->tableName;
    }
}