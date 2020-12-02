<?php

namespace Aqua;

use \PDO, \PDOException;

class Database {
    protected $pdo;

    private $options  = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    function connect(string $host, string $name, string $username, string $password, string $driver = 'mysql', string $charset = 'utf8mb4') {
        try {
            $dsn = "$driver:host=$host;dbname=$name;charset=$charset";
            $this->pdo = new PDO($dsn, $username, $password, $this->options);

            return $this->pdo;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    function disconnect() {
        $this->pdo = null;
    }
}
