<?php

namespace Api\Repository;

use PDO;
use Exception;

class ManagerRepository
{
    public $connection;

    public function buildObject($row)
    {
        $entityNameSpace = $this->getEntityNameSpace();
        $entity = new $entityNameSpace;

        foreach ($row as $key => $value) {
            $method = 'set' . ucfirst($key);

            if ($key !== "connection") {
                $entity->$method($value);
            }
        }
        return $entity;
    }

    public function getConnection(){
        try {
            $database = new PDO(DB_HOST, DB_USER, DB_PASS);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $database;
            
            return $this->connection;

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function checkConnection(){
        if($this->connection === null){
            return $this->getConnection();
        }
        return $this->connection;
    }

    public function createQuery($sql, $parameters = null){
        $result = $this->checkConnection()->prepare($sql);
        $result->setFetchMode(PDO::FETCH_CLASS, static::class);

        if($parameters){
            $result->execute($parameters);
        }else{
            $result->execute();
        }
        return $result;
    }

    public function getRepositoryClassName()
    {
        $staticClass = static::class;
        $explode = explode('\\', $staticClass);
        $className = end($explode);

        return $className;
    }

    public function getTableName()
    {
        $repositoryClassName = $this->getRepositoryClassName();
        $tableName = strToLower(str_replace("Repository", "", $repositoryClassName));
        return $tableName;
    }

    public function getEntityNameSpace()
    {
        $repositoryClassName = $this->getRepositoryClassName();
        $entityName = str_replace("Repository", "", $repositoryClassName);
        $entityNameSpace = "Api\\Entity\\$entityName";

        return $entityNameSpace;
    }

    public function findOne($id)
    {
        $tableName = $this->getTableName();
        $idName = $tableName."Id";
        $sql = "SELECT * FROM $tableName WHERE $idName = ?";
        $result = $this->createQuery($sql, [$id]); //on envoie le numéro d'id
        if($row = $result->fetch()) {
            $entity = $this->buildObject($row);
            return $entity;
        } else{
            header('Location: ?404');
        }
    }

    public function findAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM $tableName";
        $result = $this->createQuery($sql);
        $entities = [];

        foreach ($result as $row) {
            $entity = $this->buildObject($row);
            array_push($entities, $entity);
        }

        return $entities;
    }

    public function delete($id)
    {
        $tableName = $this->getTableName();
        $idName = $tableName."Id";
        $sql = "DELETE FROM $tableName WHERE $idName = ?";
        $this->createQuery($sql, [$id]);
    }
}

