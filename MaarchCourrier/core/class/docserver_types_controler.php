<?php

/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief  Contains the docserver_types_controler Object (herits of the BaseObject class)
*
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;

// Loads the required class
try {
    require_once("core/class/docserver_types.php");
    require_once("core/core_tables.php");
    require_once("core/class/ObjectControlerAbstract.php");
    require_once("core/class/ObjectControlerIF.php");
} catch (Exception $e) {
    echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Controler of the docserver_types_controler object
*
*<ul>
*  <li>Get an docserver_types_controler object from an id</li>
*  <li>Save in the database a docserver_types_controler</li>
*  <li>Manage the operation on the docserver_types_controler related tables in the database (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class docserver_types_controler extends ObjectControler implements ObjectControlerIF
{
    
    /**
     * Save given object in database:
     * - make an update if object already exists,
     * - make an insert if new object.
     * Return updated object.
     * @param docservers_types $docservers_types
     * @return array
     */
    public function save($docserver_type, $mode = "")
    {
    }

    public function delete($args)
    {
    }

    /**
    * Returns an docserver_types object based on a docserver_types identifier
    *
    * @param  $docserver_type_id string  docserver_types identifier
    * @param  $comp_where string  where clause arguments (must begin with and or or)
    * @param  $can_be_disabled bool  if true gets the docserver_type even if it is disabled in the database (false by default)
    * @return docserver_types object with properties from the database or null
    */
    public function get($docserver_type_id, $comp_where = '', $can_be_disabled = false)
    {
        $this->set_foolish_ids(array('docserver_type_id'));
        $this->set_specific_id('docserver_type_id');
        $docserver_type = $this->advanced_get($docserver_type_id, _DOCSERVER_TYPES_TABLE_NAME);

        if (isset($docserver_type_id)) {
            return $docserver_type;
        } else {
            return null;
        }
    }

    /**
    * Disables a given docserver_types
    *
    * @param  $docserver_type docserver_types object
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($docserver_type)
    {
        if ($docserver_type <> 'TEMPLATES') {
            $control = array();
            if (!isset($docserver_type) || empty($docserver_type)) {
                $control = array("status" => "ko", "value" => "", "error" => _DOCSERVER_TYPE_EMPTY);
                return $control;
            }
            $docserver_type = $this->isADocserverType($docserver_type);
            $this->set_foolish_ids(array('docserver_type_id'));
            $this->set_specific_id('docserver_type_id');
            if ($this->docserverLinkExists($docserver_type->docserver_type_id)) {
                $control = array("status" => "ko", "value" => "", "error" => _LINK_EXISTS);
                return $control;
            }
            if ($this->lcCycleStepsLinkExists($docserver_type->docserver_type_id)) {
                $control = array("status" => "ko", "value" => "", "error" => _LINK_EXISTS);
                return $control;
            }
            if ($this->advanced_disable($docserver_type)) {
                $control = array("status" => "ok", "value" => $docserver_type->docserver_type_id);
                if ($_SESSION['history']['docserverstypesban'] == "true") {
                    $history = new history();
                    $history->add(
                        _DOCSERVER_TYPES_TABLE_NAME,
                        $docserver_type->docserver_type_id,
                        "BAN",
                        'docserverstypesban',
                        _DOCSERVER_TYPE_DISABLED." : ".$docserver_type->docserver_type_id,
                        $_SESSION['config']['databasetype']
                    );
                }
            } else {
                $control = array("status" => "ko", "value" => "", "error" => _PB_WITH_DOCSERVER_TYPE);
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _CANNOT_DISABLE_DOCSERVER_TYPE_ID . ' '. $docserver_type->docserver_type_id,
            );
        }
        return $control;
    }

    /**
    * Enables a given docserver_types
    *
    * @param  $docserver_type docserver_types object
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($docserver_type)
    {
        $control = array();
        if (!isset($docserver_type) || empty($docserver_type)) {
            $control = array("status" => "ko", "value" => "", "error" => _DOCSERVER_TYPE_EMPTY);
            return $control;
        }
        $docserver_type = $this->isADocserverType($docserver_type);
        $this->set_foolish_ids(array('docserver_type_id'));
        $this->set_specific_id('docserver_type_id');
        if ($this->advanced_enable($docserver_type)) {
            $control = array("status" => "ok", "value" => $docserver_type->docserver_type_id);
            if ($_SESSION['history']['docserverstypesallow'] == "true") {
                $history = new history();
                $history->add(
                    _DOCSERVER_TYPES_TABLE_NAME,
                    $docserver_type->docserver_type_id,
                    "BAN",
                    'docserverstypesallow',
                    _DOCSERVER_TYPE_ENABLED." : ".$docserver_type->docserver_type_id,
                    $_SESSION['config']['databasetype']
                );
            }
        } else {
            $control = array("status" => "ko", "value" => "", "error" => _PB_WITH_DOCSERVER_TYPE);
        }
        return $control;
    }

    /**
    * Fill a docserver_types object with an object if it's not a docserver_types
    *
    * @param  $object ws docserver_types object
    * @return object docserver_types
    */
    private function isADocserverType($object)
    {
        if (get_class($object) <> "docserver_types") {
            $func = new functions();
            $docserverTypesObject = new docserver_types();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $docserverTypesObject->{$key} = $array[$key];
            }
            return $docserverTypesObject;
        } else {
            return $object;
        }
    }
    
    /**
    *  Checks if a docserver is linked
    *
    * @param $docserver_id docserver id
    * @return bool true if the docserver is linked
    */
    public function docserverLinkExists($docserver_type_id)
    {
        if (!isset($docserver_type_id) || empty($docserver_type_id)) {
            return false;
        }
        $db = new Database();
        $query = "select docserver_type_id from "
            . _DOCSERVERS_TABLE_NAME . " where docserver_type_id = ?";
        $stmt = $db->query($query, array($docserver_type_id));
        if ($stmt->rowCount()>0) {
            return true;
        }
    }
    
    /**
    *  Checks if a cycle_steps is linked
    *
    * @param $docserver_id docserver id
    * @return bool true if the cycle_steps is linked
    */
    public function lcCycleStepsLinkExists($docserver_type_id)
    {
        if (!isset($docserver_type_id) || empty($docserver_type_id)) {
            return false;
        }
        $db = new Database();
        $query = "select docserver_type_id from "
            . _LC_CYCLE_STEPS_TABLE_NAME . " where docserver_type_id = ?";
        $stmt = $db->query($query, array($docserver_type_id));
        if ($stmt->rowCount()>0) {
            return true;
        }
    }
}
