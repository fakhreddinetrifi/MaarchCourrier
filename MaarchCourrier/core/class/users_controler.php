<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief  Contains the controler of the user object (create, save, modify)
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
    require_once 'core/core_tables.php' ;
    require_once 'core/class/users.php' ;
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_history.php';
    require_once('core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'class_security.php');
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  controler of the user object
*
*<ul>
*  <li>Get an user object from an id</li>
*  <li>Save in the database a user</li>
*  <li>Manage the operation on the users related tables in the database
*   (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class users_controler extends ObjectControler implements ObjectControlerIF
{
    /**
    * Returns an user object based on a user identifier
    *
    * @param  $userId string  User identifier
    * @param  $compWhere string  where clause arguments
    *               (must begin with and or or)
    * @param  $canBeDisabled bool if true gets the user even if it is
    *               disabled in the database (false by default)
    * @return user object with properties from the database or null
    */
    public function get($userId)
    {
        self::set_foolish_ids(array('user_id', 'docserver_location_id'));
        self::set_specific_id('user_id');
        $user = self::advanced_get($userId, USERS_TABLE);

        if (isset($user)
        ) {
            return $user;
        } else {
            return null;
        }
    }

    /**
    * Returns an user object based on a user identifier with PDO
    *
    * @param  $userId string  User identifier
    * @param  $compWhere string  where clause arguments
    *               (must begin with and or or)
    * @return user object with properties from the database or null
    */
    public function getWithComp($userId, $compWhere='', $params=array())
    {
        self::set_foolish_ids(array('user_id', 'docserver_location_id'));
        self::set_specific_id('user_id');
        $user = self::advanced_getWithComp($userId, USERS_TABLE, $compWhere, $params);

        if (isset($user)
            && ($user->__get('status') == 'OK'
            || $user->__get('status') == 'ABS')
        ) {
            return $user;
        } else {
            return null;
        }
    }

    /**
    * Returns in an array all the groups associated with a user (user_id,
    * group_id and role)
    *
    * @param  $userId string  User identifier
    * @return Array or null
    */
    public function getGroups($userId)
    {
        $groups = array();
        if (empty($userId)) {
            return null;
        }
        self::$db = new Database();
        $userUse = \User\models\UserModel::getByLogin(['login' => $userId, 'select' => ['id']]);

        $query = 'select u.group_id, uc.role from usergroup_content uc, usergroups u where uc.user_id = ? and uc.group_id = u.id ';
        try {
            $stmt = self::$db->query($query, array($userUse['id']));
        } catch (Exception $e) {
            echo _NO_USER_WITH_ID.' '.functions::xssafe($userId).' // ';
        }
        while ($res = $stmt->fetchObject()) {
            array_push(
                $groups,
                array(
                    'USER_ID' => $userId,
                    'GROUP_ID' => $res->group_id,
                    'ROLE' => $res->role,
                )
            );
        }
        
        return $groups;
    }


    /**
    * Saves in the database a user object
    *
    * @param  $user user object to be saved
    * @param  $groups Groups data,
    *            array( 'USER_ID'    => User Identifier,
    *                   'GROUP_ID'   => Group identifier,
    *                   'LABEL'      => Group label,
    *                   'PRIMARY'    => Y / N (Is the group,
    *                               the primary group for the user),
    *                   'ROLE'       => User role in the group (string)
    *                  )
    * @param  $mode Mode (add or up)
    * @param  $params More parameters,
    *           array('modules_services' => $_SESSION['modules_services']
    *                                       type array,
    *                 'log_user_up'      => 'true' / 'false':
    *                                       log user modification ,
    *                 'log_user_add'     => 'true' / 'false': log user addition,
    *                 'databasetype'     => Type of the database,
    *                 'userdefaultpassword' => Default password for user,
    *                 'manageGroups'     => If true manage groups for the user
    *                                       )
    * @return array (   'status' => 'ok' / 'ko',
    *                   'value'  => User identifier or empty in case of error,
    *                   'error'  => Error message, defined only in case of error
    */
    public function save($user, $groups=array(), $mode='', $params=array())
    {
        $control = array();
        // If user not defined or empty, return an error
        if (! isset($user) || empty($user)) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _USER_EMPTY,
            );
            return $control;
        }
        // If mode not up or add, return an error
        if (! isset($mode) || empty($mode)
            || ($mode <> 'add' && $mode <> 'up')
        ) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _MODE . ' ' ._UNKNOWN,
            );
            return $control;
        }
        $user = self::_isAUser($user);
        self::set_foolish_ids(array('user_id', 'docserver_location_id'));
        self::set_specific_id('user_id');

        // Data checks
        $control = self::_control($user, $groups, $mode, $params);

        if ($control['status'] == 'ok') {
            $core = new core_tools();

            $_SESSION['service_tag'] = 'user_' . $mode;
            if (isset($params['modules_services'])) {
                $core->execute_modules_services(
                    $params['modules_services'],
                    'users_add_db',
                    'include'
                );
            }
            if ($mode == 'up') {
                //Update existing user
                if (self::_update($user)) {
                    $control = array(
                        'status' => 'ok',
                        'value'  => $user->user_id,
                    );
                    //log
                    if ($params['log_user_up'] == 'true') {
                        $history = new history();
                        $history->add(
                            USERS_TABLE,
                            $user->user_id,
                            'UP',
                            'usersup',
                            _USER_UPDATE . ' : ' . $user->user_id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value'  => '',
                        'error'  => _PB_WITH_USER_UPDATE,
                    );
                }
            } else { //mode == add
                if (self::_insert($user)) {
                    $control = array(
                        'status' => 'ok',
                        'value'  => $user->user_id,
                    );
                    //log
                    if ($params['log_user_add'] == 'true') {
                        $history = new history();
                        $history->add(
                            USERS_TABLE,
                            $user->user_id,
                            'ADD',
                            'usersadd',
                            _USER_ADDED . ' : ' . $user->user_id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value'  => '',
                        'error'  => _PB_WITH_USER,
                    );
                }
            }
        }
        unset($_SESSION['service_tag']);
        return $control;
    }

    /**
    * Fill a user object with an object if it's not a user
    *
    * @param  $object ws users object
    * @return object users
    */
    private function _isAUser($object)
    {
        if (get_class($object) <> 'users') {
            $func = new functions();
            $userObject = new users();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $userObject->{$key} = $array[$key];
            }
            return $userObject;
        } else {
            return $object;
        }
    }

    /**
    * _control the data of user object
    *
    * @param  $user user object
    * @param  $groups Groups data,
    *               array( 'USER_ID'       => User Identifier,
    *                      'GROUP_ID'      => Group identifier,
    *                      'LABEL'         => Group label,
    *                      'PRIMARY'       => Y / N (Is the group, the primary
    *                                         group for the user),
    *                      'ROLE'          => User role in the group (string)
    *                )
    * @param  $mode Mode (add or up)
    * @param  $params More parameters,
    *               array('modules_services'   => $_SESSION['modules_services']
    *                                             type array,
    *                     'log_user_up'        => 'true' / 'false': log user
    *                                             modification ,
    *                     'log_user_add'       => 'true' / 'false': log user
    *                                             addition ,
    *                     'databasetype'       => Type of the database
    *                     'userdefaultpassword' => Default password for user,
    *                     'manageGroups'     => If true manage groups for the user
    *                )
    * @return array (  'status' => 'ok' / 'ko',
    *                  'value'  => User identifier or empty in case of error,
    *                  'error'  => Error message, defined only in case of error
    *               )
    */
    private function _control($user, $groups, $mode, $params=array())
    {
        $error = "";
        $f = new functions();

        if (strpos($user->user_id, "'") !== false) {
            $error .= _USER_ID . ' '._WRONG_FORMAT . '#';
        }

        $user->user_id = $f->wash($user->user_id, 'no', _THE_ID, 'yes', 0, 128);

        if ($mode == 'add') {
            $sec = new security();
            $user->password =  $sec->getPasswordHash($params['userdefaultpassword']);

            if (self::userExists($user->user_id)) {
                $error .= _USER . ' ' . _ALREADY_EXISTS;
            }

            if (self::userDeleted($user->user_id)) {
                $url = "'".$_SESSION['config']['businessappurl']."index.php?admin=users&page=users_management_controler&mode=up&reactivate=true'";
                $error .= _ALREADY_CREATED_AND_DELETED . '. ';
                $_SESSION['reactivateUser'] = '<input class="button" style="cursor:pointer;text-align: center" onclick="document.getElementById(\'frmuser\').action ='.$url.';document.getElementById(\'user_submit\').click();" value="' . _REACTIVATE .' ?">';
            }
        }

        $user->firstname = $f->wash($user->firstname, 'no', _THE_FIRSTNAME, 'yes', 0, 255);
        $user->lastname = $f->wash($user->lastname, 'no', _THE_LASTNAME, 'yes', 0, 255);

        if (isset($user->department) && ! empty($user->department)) {
            $user->department = $f->wash($user->department, 'no', _DEPARTMENT, 'yes', 0, 50);
        }

        if (isset($user->phone) && ! empty($user->phone)) {
            $user->phone = $f->wash($user->phone, 'no', _PHONE, 'yes', 0, 32);
        }

        if (isset($user->loginmode) && ! empty($user->loginmode)) {
            $user->loginmode  = $f->wash($user->loginmode, 'no', _LOGIN_MODE, 'yes', 0, 50);
        }

        $user->mail = $f->wash($user->mail, 'mail', _MAIL, 'yes', 0, 255);


        $_SESSION['service_tag'] = 'user_check';
        $core = new core_tools();
        if (isset($params['modules_services'])) {
            $core->execute_modules_services(
                $params['modules_services'],
                'user_check',
                'include'
            );
        }
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html and not in the session
        $error = str_replace("<br />", "#", $error);
        $return = array();
        if (! empty($error)) {
            $return = array(
                'status' => 'ko',
                'value'  => $user->user_id,
                'error'  => $error,
            );
        } else {
            $return = array(
                'status' => 'ok',
                'value'  => $user->user_id,
            );
        }
        unset($_SESSION['service_tag']);
        return $return;
    }

    /**
    * inserts in the database (users table) a user object
    *
    * @param  $user user object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function _insert($user)
    {
        return self::advanced_insert($user);
    }

    /**
    * Updates a user in the database (users table) with a user object
    *
    * @param  $user user object
    * @return bool true if the update is complete, false otherwise
    */
    private function _update($user)
    {
        return self::advanced_update($user);
    }

    public function delete($user, $params = array())
    {
    }

    /**
    * Asserts if a given user (user_id) exists in the database
    *
    * @param  $userId String User identifier
    * @return bool true if the user exists, false otherwise
    */
    public function userExists($userId)
    {
        if (! isset($userId) || empty($userId)) {
            return false;
        }
        self::$db = new Database();
        $func = new functions();
        $query = 'select user_id from ' . USERS_TABLE . " where user_id = ? and status<>'DEL'";
        try {
            $stmt = self::$db->query($query, array($userId));
        } catch (Exception $e) {
            echo _UNKNOWN . ' ' . _USER . ' ' . functions::xssafe($userId) . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }


    /**
    * Disables a given user
    *
    * @param  $user user object
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($user, $params=array())
    {
    }

    /**
    * Enables a given user
    *
    * @param  $user user object
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($user, $params=array())
    {
    }

    /**
    * Asserts if a given user (user_id) is deleted in the database
    *
    * @param  $userId String User identifier
    * @return bool true if the user is deleted, false otherwise
    */
    public function userDeleted($userId)
    {
        if (! isset($userId) || empty($userId)) {
            return false;
        }
        self::$db = new Database();
        $func = new functions();
        $query = 'select user_id from ' . USERS_TABLE . " where lower(user_id) = lower(?) and status = 'DEL'";
        try {
            $stmt = self::$db->query($query, array($userId));
        } catch (Exception $e) {
            echo _UNKNOWN . ' ' . _USER . ' ' . functions::xssafe($userId) . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }

    /**
    * Reactivate a given user
    *
    * @param  $user user object
    * @return bool true if activate is complete, false otherwise
    */
    public function reactivate($user)
    {
        $user = self::_isAUser($user);
        self::set_foolish_ids(array('user_id', 'docserver_location_id'));
        self::set_specific_id('user_id');
        if (self::advanced_reactivate($user)) {
            self::$db = new Database();
            $query = "update users set user_id = ? where lower(user_id)=lower(?)";
            $stmt = self::$db->query($query, array($user->user_id, $user->user_id));
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns in an array all the entities associated with a user (user_id,
    * entity_id, primary and role)
    *
    * @param  $userId string  User identifier
    * @return Array or null
    */
    public function getEntities($userId)
    {
        $entities = array();
        if (empty($userId)) {
            return null;
        }
        self::$db = new Database();
        $func = new functions();
        $query = "SELECT ue.entity_id, ue.user_role, ue.primary_entity 
                    FROM users_entities ue, entities e 
                    WHERE ue.user_id = ? and e.enabled = 'Y' and e.entity_id = ue.entity_id
                    ORDER BY primary_entity desc";
        // set primary entity to the first row
        try {
            $stmt = self::$db->query($query, array($userId));
        } catch (Exception $e) {
            echo _NO_USER_WITH_ID.' '.functions::xssafe($userId).' // ';
        }
        while ($res = $stmt->fetchObject()) {
            array_push(
                $entities,
                array(
                    'USER_ID' => $userId,
                    'ENTITY_ID' => $res->entity_id,
                    'PRIMARY' => $res->primary_entity,
                    'ROLE' => $res->user_role,
                )
            );
        }

        return $entities;
    }
}
