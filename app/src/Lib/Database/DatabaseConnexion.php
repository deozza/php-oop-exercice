<?php

namespace App\Lib\Database;

use App\Lib\Database\Dsn;

class DatabaseConnexion
{
    private \PDO $pdo_connexion;
    private \PDOStatement $stmt;

    public function __construct()
    {
        $this->setConnexion();
    }

    public function setConnexion(): self
    {
        $dsn = new Dsn();
        $this->pdo_connexion = new \PDO(
            $dsn->getDsn(),
            $dsn->getUser(),
            $dsn->getPassword()
        );
        return $this;
    }

    public function getConnexion(): \PDO
    {
        return $this->pdo_connexion;
    }

    public function closeConnexion(): void
    {
        $this->pdo_connexion = null;
    }

    public function query($sql)
    {
        $this->stmt = $this->pdo_connexion->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    //Return multiple records
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //Return a single record
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_OBJ);
    }

    //Get row count
    public function rowCount(){
        return $this->stmt->rowCount();
    }
}
