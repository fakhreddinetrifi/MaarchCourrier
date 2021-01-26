<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief  Contains the controler of the Security Object
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

// Loads the required class
try {
    require_once 'core/core_tables.php';
    require_once 'core/class/class_db.php';
    require_once 'core/class/users_controler.php';
    require_once 'core/class/session_security_controler.php';
    require_once 'core/class/Security.php';
    if (! defined('_CLASSIFICATION_SCHEME_VIEW')) {
        define('_CLASSIFICATION_SCHEME_VIEW', 'mr_classification_scheme_view');
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the Security Object
*
*<ul>
*  <li>Get an security object from an id</li>
*  <li>Save in the database a security</li>
*  <li>Manage the operation on the security table in the database
*   (_insert, select, _update, delete)</li>
*</ul>
* @ingroup core
*/


class SecurityControler
{
    /**
    * Returns an Security Object based on a security identifier
    *
    * @param  $securityId string  Security identifier
    * @return Security object with properties from the database or null
    */
    public function get($securityId)
    {
        if (empty($securityId)) {
            return null;
        }
        $db = new Database();

        $query = "select * from " . SECURITY_TABLE . " where security_id = ?";

        $stmt = $db->query($query, array($securityId));

        if ($stmt->rowCount() > 0) {
            $access = new SecurityObj();
            $queryResult = $stmt->fetchObject();
            foreach ($queryResult as $key => $value) {
                $access->{$key} = $value;
            }
            return $access;
        } else {
            return null;
        }
    }

    /**
    * Saves in the database a security object
    *
    * @param  $security Security object to be saved
    * @param  $mode string  Saving mode : add or up (add by default)
    * @return bool true if the save is complete, false otherwise
    */
    public function save($security, $mode="add")
    {
        if (!isset($security)) {
            return false;
        }

        if ($mode == "up") {
            return $this->_update($security);
        } elseif ($mode == "add") {
            return $this->_insert($security);
        }

        return false;
    }

    /**
    * Inserts in the database (security table) a Security object
    *
    * @param  $security Security object
    * @return bool true if the _insertion is complete, false otherwise
    */
    private function _insert($security)
    {
        if (!isset($security)) {
            return false;
        }
        $db = new Database();
        
        $prepQuery = $this->_insertPrepare($security);
        
        $query = "insert into " . SECURITY_TABLE . " (" . $prepQuery['COLUMNS']
               . ") values (" . $prepQuery['VALUES'] . ")";
        
        $stmt = $db->query($query, $prepQuery['ARRAY_VALUES']);
        $ok = true;
        
        return $ok;
    }

    /**
    * Updates a security in the database (security table) with a Security object
    *
    * @param  $security Security object
    * @return bool true if the _update is complete, false otherwise
    */
    private function _update($security)
    {
        if (!isset($security)) {
            return false;
        }
        $db = new Database();

        $prep_query = $this->_updatePrepare($security);

        $query = "update " . SECURITY_TABLE . " set "
               . $prep_query['QUERY'] . " where security_id=?";

        $prep_query['VALUES'][] = $security->security_id;
    
        $stmt = self::$db->query($query, $prep_query['VALUES']);
        $ok = true;

        return $ok;
    }

    /**
    * Deletes in the database (security table) a given security
    *
    * @param  $securityId string  Security identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($securityId)
    {
        if (!isset($securityId) || empty($securityId)) {
            return false;
        }
        $db = new Database();

        $query = "delete from " . SECURITY_TABLE . " where security_id=?";

        $db->query($query, array($securityId));
        $ok = true;

        return $ok;
    }

    /**
    * Deletes in the database (security table) all security of a given usergroup
    *
    * @param  $groupId string  Usergroup identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function deleteForGroup($groupId)
    {
        if (!isset($groupId) || empty($groupId)) {
            return false;
        }
        $db = new Database();

        $query = "delete from " . SECURITY_TABLE . " where group_id=?";

        $db->query($query, array($groupId));
        $ok = true;

        return $ok;
    }

    /**
    * Prepares the _update query for a given Security object
    *
    * @param  $security Security object
    * @return String containing the fields and the values
    */
    private function _updatePrepare($security)
    {
        $result = array();
        $arrayValues=array();
        foreach ($security->getArray() as $key => $value) {
            // For now all fields in the usergroups table are strings or date
            // excepts the security_id
            if (! empty($value)) {
                if ($key <> 'security_id') {
                    $result[]=$key."=?";
                    $arrayValues[]=$value;
                }
            }
        }
        return array(
            'QUERY' => implode(",", $result),
            'VALUES' => $arrayValues,
        );
    }

    /**
    * Prepares the _insert query for a given Security object
    *
    * @param  $security Security object
    * @return Array containing the fields and the values
    */
    private function _insertPrepare($security)
    {
        $columns = array();
        $values = array();
        $arrayValues = array();
        foreach ($security->getArray() as $key => $value) {
            // For now all fields in the security table are strings
            // or date excepts the security_id
            if (! empty($value)) {
                if ($key <> 'security_id') {
                    $columns[] = $key;
                    $values[] = "?";
                    $arrayValues[]=$value;
                }
            }
        }
        return array(
            'COLUMNS' => implode(",", $columns),
            'VALUES'  => implode(",", $values),
            'ARRAY_VALUES' => $arrayValues
        );
    }

    public function check_where_clause(
        $collId,
        $whereClause,
        $view,
        $userId
    ) {
        $res = array(
            'RESULT' => false,
            'TXT' => '',
        );

        if (empty($collId) || empty($whereClause)) {
            $res['TXT'] = _ERROR_PARAMETERS_FUNCTION;
            return $res;
        }
        $where = ' ' . $whereClause;
        $where = str_replace('\\', '', $where);
        $where = $this->process_security_where_clause($where, $userId);
        if (str_replace(' ', '', $where) == '') {
            $where = '';
        }
        $where = str_replace('where', ' ', $where);
        $db = new Database();

        $query = 'select res_id from ' . $view . ' where ' . $where;

        $ok = $db->query($query, array(), true);
        if (!$ok) {
            $res['TXT'] = _SYNTAX_ERROR_WHERE_CLAUSE;
            return $res;
        } else {
            $res['TXT'] = _SYNTAX_OK;
            $res['RESULT'] = true;
        }
        return $res;
    }

    /**
    * Process a where clause, using the process_where_clause methods of the
    * modules, the core and the apps
    *
    * @param  $whereClause string Where clause to process
    * @param  $userId string User identifier
    * @return string Proper where clause
    */
    public function process_security_where_clause($whereClause, $userId, $addWhere = true)
    {
        if (!empty($whereClause)) {
            $whereClause = str_replace("&#039;", "'", $whereClause);
            if ($addWhere) {
                $where = ' where ' . $whereClause;
            } else {
                $where = $whereClause;
            }
            // Process with the core vars
            $where = $this->process_where_clause($where, $userId);
            // Process with the modules vars
            foreach (array_keys($_SESSION['modules_loaded']) as $key) {
                if (file_exists(
                    $_SESSION['config']['corepath'] . 'custom'
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                        . $_SESSION['modules_loaded'][$key]['name'] . DIRECTORY_SEPARATOR . "class"
                        . DIRECTORY_SEPARATOR . "class_modules_tools.php"
                )
                ) {
                    $pathModuleTools = $_SESSION['config']['corepath'] . 'custom'
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                        . $_SESSION['modules_loaded'][$key]['name'] . DIRECTORY_SEPARATOR . "class"
                        . DIRECTORY_SEPARATOR . "class_modules_tools.php";
                } else {
                    $pathModuleTools = 'modules' . DIRECTORY_SEPARATOR
                        . $_SESSION['modules_loaded'][$key]['name'] . DIRECTORY_SEPARATOR . "class"
                        . DIRECTORY_SEPARATOR . "class_modules_tools.php";
                }

                if (file_exists($pathModuleTools)) {
                    require_once($pathModuleTools);
                    if (class_exists($key)) {
                        $object = new $key;
                        if (method_exists(
                            $object,
                            'process_where_clause'
                        ) == true
                        ) {
                            $where = $object->process_where_clause(
                                $where,
                                $userId
                            );
                        }
                    }
                }
            }

            $where = preg_replace('/, ,/', ',', $where);
            $where = preg_replace('/\( ?,/', '(', $where);
            $where = preg_replace('/, ?\)/', ')', $where);

            // Process with the apps vars
            require_once 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'class_business_app_tools.php';
            $object = new business_app_tools();
            if (method_exists($object, 'process_where_clause')) {
                $where = $object->process_where_clause($where, $userId);
            }
            return $where;
        } else {
            return '';
        }
    }

    /**
    * Process a where clause with the core specific vars
    *
    * @param  $whereClause string Where clause to process
    * @param  $userId string User identifier
    * @return string Proper where clause
    */
    public function process_where_clause($whereClause, $userId)
    {
        if (preg_match('/@user_id/', $whereClause)) {
            $user = \User\models\UserModel::getByLogin(['login' => $userId, 'select' => ['id']]);
            $whereClause = str_replace('@user_id', "{$user['id']}", $whereClause);
        }
        if (preg_match('/@user/', $whereClause)) {
            $whereClause = str_replace(
                "@user",
                "'" . trim($userId) . "'",
                $whereClause
            );
        }
        if (preg_match('/@email/', $whereClause)) {
            $user = \User\models\UserModel::getByLogin(['login' => $userId, 'select' => ['mail']]);
            $whereClause = str_replace(
                "@email",
                "'" . trim($user['mail']) . "'",
                $whereClause
            );
        }
        return $whereClause;
    }

    /**
    * Gets the indice of the collection in the  $_SESSION['collections'] array
    *
    * @param  $collId string  Collection identifier
    * @return integer Indice of the collection in the $_SESSION['collections']
    *           or -1 if not found
    */
    public function get_ind_collection($collId)
    {
        for ($i = 0; $i < count($_SESSION['collections']); $i ++) {
            if (trim($_SESSION['collections'][$i]['id']) == trim($collId)) {
                return $i;
            }
        }
        return -1;
    }

    /**
    * Check the where clause syntax
    *
    * @param  $whereClause string The where clause to check
    * @return bool true if the request is not secure, false otherwise
    */
    public function isUnsecureRequest($whereClause)
    {
        $whereClause = str_replace("&#039;", "'", $whereClause);
        $search1 = '#\b(?:abort|alter|copy|create|delete|disgard|drop|'
                . 'execute|grant|insert|load|lock|move|reset|truncate|update)\b#i';
        preg_match($search1, $whereClause, $out);
        if (isset($out[0])) {
            $count = count($out[0]);
            if ($count == 1) {
                $find1 = true;
            } else {
                $find1 = false;
            }
        } else {
            $find1 = false;
        }
        return $find1;
    }
}
