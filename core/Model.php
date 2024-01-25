<?php

namespace Core;

use PDOException;

abstract class Model {

    private $pdo;

    public function __construct()
    {
        extract(DB_CONFIG);

        $dsn = "{$driver}:host={$host};dbname={$dbname};charset=utf8mb4";
        
        try {

            $this->pdo = new \PDO(
                $dsn,
                $username,
                $password,
                $options
            );

        } catch(PDOException $e) {

            die( $e->getMessage() );

        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function execute(string $query, array $values = [], $list = true)
    {
        try {

            $result = $this->pdo->prepare($query);

            empty($values) ? $result->execute() : $result->execute($values);

            return [
                "data"=> !$list ? $result->fetch() : $result->fetchAll(),
                "error"=> null,
                "affected"=> $result->rowCount(),
            ];

        } catch(PDOException $e) {

            return [
                "data"=> null,
                "error"=> $e->getMessage(),
                "affected"=> 0,
            ];

        }
    }

}