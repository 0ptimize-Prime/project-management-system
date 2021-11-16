<?php

abstract class AbstractManager
{
    protected PDO $db;

    protected function __construct()
    {
        $db = DbConnectionManager::getConnection();
        if ($db instanceof PDO) {
            $this->db = $db;
        } else {
            throw new Exception("Connection should be a PDO object");
        }
    }

    public static final function getInstance()
    {
        static $instances = array();

        $called_class = get_called_class();

        if (!isset($instances[$called_class])) {
            $instances[$called_class] = new $called_class();
        }

        return $instances[$called_class];
    }
}
