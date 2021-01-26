<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief class db pdo statement
* @author dev@maarch.org
* @ingroup core
*/


require_once 'class_db_pdo.php';

class MyPDOStatement
{
    private $pdoStatement;
    public $queryArgs;

    public function __construct($pdoStatement)
    {
        $this->pdoStatement = $pdoStatement;
    }

    public function __call($method, array $args=array())
    {
        if ($method == 'rowCount') {
            return $this->nbResult();
        } elseif ($method == 'fetchObject') {
            return $this->fetchMyObject();
        } else {
            return call_user_func_array(array($this->pdoStatement, $method), $args);
        }
    }

    protected function nbResult()
    {
        $db = new Database();
        switch ($db->driver) {
            case 'pgsql'   : 
                return $this->pdoStatement->rowCount();
            default :
                $query = "select count(1) as rc from (" . $this->pdoStatement->queryString . ")";
                $stmtRC = $db->query($query, $this->queryArgs); 
                $fetch = $stmtRC->fetchObject();
                return $fetch->rc;
        }
    }

    protected function fetchMyObject()
    {
        $db = new Database();
        switch ($db->driver) {
            case 'pgsql'   :
                //see later if special cases
                return $this->pdoStatement->fetchObject();
            default :
                $result = $this->pdoStatement->fetchObject();
                if ($result) {
                    foreach ($result as $name => $value) {
                        if (gettype($value) == 'resource') {
                            $result->$name = stream_get_contents($value);
                        }
                    }
                }
                return $result;
        }
    }
}
