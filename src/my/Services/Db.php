<?php

namespace my\Services;

use my\Exceptions\DbException;

class Db{
    /** @var \PDO */
    private $pdo;
    private static $count;
    private static $instance;

    private function __construct(){
        $dbOptions = (require __DIR__ . '/../../settings.php')['db'];
        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF8');

        } catch (\PDOException $e){
            throw new DbException('Error connect to DB:'.$e->getMessage());
        }
    }

    public static function getInstance(){
        if (!self::$instance) self::$instance = new self;
    return self::$instance;
    }
    public function query(string $sql, $params = [],string $className = 'stdClass'): ?array{
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }
    public function getLastInsertId(){
       return $this->pdo->lastInsertId();
    }
}