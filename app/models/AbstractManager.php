<?php

abstract class AbstractManager
{
    protected PDO $db;

    public function __construct($db)
    {
        if ($db instanceof PDO) {
            $this->db = $db;
        } else {
            throw new Exception("Connection should be a PDO object");
        }
    }
}
