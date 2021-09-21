<?php

class DbConnectionManager
{
    private static array $dbConfig;
    private static PDO $handler;

    public static function setConfig(array $config)
    {
        self::$dbConfig = $config;
        self::$handler = DbConnectionManager::createConnection();
    }

    public static function getConnection(): PDO
    {
        return self::$handler;
    }

    private static function createConnection(): PDO
    {
        try {
            self::$handler = new PDO(
                'mysql:host=' . self::$dbConfig['host'] . ';dbname=' . self::$dbConfig['database'],
                self::$dbConfig['user'],
                self::$dbConfig['password']
            );
            self::$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
            die();
        }
        return self::$handler;
    }
}
