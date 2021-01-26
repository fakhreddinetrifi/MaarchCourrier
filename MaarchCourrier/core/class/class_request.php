<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief   Contains all the function to build a SQL query
*
* @file
* @author  Loïc Vinet  <dev@maarch.org>
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

require_once 'core/class/class_db_pdo.php';
require_once 'core/class/class_db.php';
/**
* @brief   Contains all the function to build a SQL query (select, insert and update)
*
* @ingroup core
*/
class request extends dbquery
{
    /**
    * Builds the insert query and sends it to the database
    *
    * @param string $table table to insert
    * @param array $data data to insert
    * @param array $database_type type of the database
    * @return bool True if the query was sent ok and processed by the database without error, False otherwise
    */
    public function insert($table, $data, $database_type)
    {
        $db = new Database();
        $field_string = "( ";
        $value_string = "( ";
        $parameters = array();
        foreach ($data as $value) {
            if (trim(strtoupper($value['value'])) == "SYSDATE" || trim(strtoupper($value['value'])) == "CURRENT_TIMESTAMP") {
                $value_string .= $value['value'] . ',';
            } else {
                $value_string .= "?,";
                $parameters[] = $value['value'];
            }
            $field_string .= $value['column'].",";
        }
        $value_string = substr($value_string, 0, -1);
        $field_string = substr($field_string, 0, -1);

        $value_string .= ")";
        $field_string .= ")";

        $query = "INSERT INTO " . $table . " " . $field_string . " VALUES " . $value_string;
        $stmt = $db->query($query, $parameters);

        return true;
    }
}
