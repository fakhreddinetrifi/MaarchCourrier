<?php


/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief Contains the docservers_controler Object
* (herits of the BaseObject class)
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

//Loads the required class
try {
    require_once 'core/class/class_request.php';
    require_once 'core/class/docservers.php';
    require_once 'core/docservers_tools.php';
    require_once 'core/core_tables.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_security.php';
    require_once 'core/class/class_resource.php';
    require_once 'core/class/class_history.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
 * Class for controling docservers objects from database
 */
class docservers_controler extends ObjectControler implements ObjectControlerIF
{

    /**
     * Save given object in database:
     * - make an update if object already exists,
     * - make an insert if new object.
     * Return updated object.
     * @param docservers $docservers
     * @return array
     */
    public function save($docserver, $mode='')
    {
    }


    /**
     * Get docservers with given id.
     * Can return null if no corresponding object.
     * @param $id Id of docservers to get
     * @return docservers
     */
    public function get($docserver_id)
    {
        //var_dump($docserver_id);
        $this->set_foolish_ids(array('docserver_id'));
        $this->set_specific_id('docserver_id');
        $docserver = $this->advanced_get($docserver_id, _DOCSERVERS_TABLE_NAME);
        //var_dump($docserver);
        if (get_class($docserver) <> 'docservers') {
            return null;
        } else {
            //var_dump($docserver);
            return $docserver;
        }
    }

    public function delete($args)
    {
    }

    /**
    * Disables a given docservers
    *
    * @param  $docserver docservers object
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($docserver)
    {
        if ($docserver->docserver_id <> 'TEMPLATES') {
            $control = array();
            if (!isset($docserver) || empty($docserver)) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _DOCSERVER_EMPTY,
                );
                return $control;
            }
            $docserver = $this->isADocserver($docserver);
            $this->set_foolish_ids(array('docserver_id'));
            $this->set_specific_id('docserver_id');
            if ($this->advanced_disable($docserver)) {
                $control = array(
                    'status' => 'ok',
                    'value' => $docserver->docserver_id,
                );
                if ($_SESSION['history']['docserversban'] == 'true') {
                    $history = new history();
                    $history->add(
                        _DOCSERVERS_TABLE_NAME,
                        $docserver->docserver_id,
                        'BAN',
                        'docserversban',
                        _DOCSERVER_DISABLED . ' : ' . $docserver->docserver_id,
                        $_SESSION['config']['databasetype']
                    );
                }
            } else {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _PB_WITH_DOCSERVER,
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _CANNOT_SUSPEND_DOCSERVER . ' ' . $docserver->docserver_id,
            );
        }
        return $control;
    }

    /**
    * Enables a given docserver
    *
    * @param  $docserver docservers object
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($docserver)
    {
    }

    /**
    * Fill a docserver object with an object if it's not a docserver
    *
    * @param  $object ws docserver object
    * @return object docservers
    */
    private function isADocserver($object)
    {
        if (get_class($object) <> 'docservers') {
            $func = new functions();
            $docserverObject = new docservers();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $docserverObject->{$key} = $array[$key];
            }
            return $docserverObject;
        } else {
            return $object;
        }
    }

    /**
    * Calculates the next file name in the docserver
    * @param $pathOnDocserver docservers path
    * @return array Contains 3 items :
    * subdirectory path and new filename and error
    */
    public function getNextFileNameInDocserver($pathOnDocserver)
    {
        umask(0022);
        //Scans the docserver path
        $fileTab = scandir($pathOnDocserver);
        //Removes . and .. lines
        array_shift($fileTab);
        array_shift($fileTab);

        if (file_exists(
            $pathOnDocserver . DIRECTORY_SEPARATOR . 'package_information'
        )
        ) {
            unset($fileTab[array_search('package_information', $fileTab)]);
        }
        if (is_dir($pathOnDocserver . DIRECTORY_SEPARATOR . 'BATCH')) {
            unset($fileTab[array_search('BATCH', $fileTab)]);
        }
        $nbFiles = count($fileTab);
        //Docserver is empty
        if ($nbFiles == 0) {
            //Creates the directory
            if (!mkdir($pathOnDocserver . '0001', 0770)) {
                return array(
                    'destinationDir' => '',
                    'fileDestinationName' => '',
                    'error' => 'Pb to create directory on the docserver:'
                    . $pathOnDocserver,
                );
            } else {
                Ds_setRights($pathOnDocserver . '0001' . DIRECTORY_SEPARATOR);
                $destinationDir = $pathOnDocserver . '0001'
                                . DIRECTORY_SEPARATOR;
                $fileDestinationName = '0001';
                $fileDestinationName = $fileDestinationName . '_' . mt_rand();
                return array(
                    'destinationDir' => $destinationDir,
                    'fileDestinationName' => $fileDestinationName,
                    'error' => '',
                );
            }
        } else {
            //Gets next usable subdirectory in the docserver
            $destinationDir = $pathOnDocserver
                . str_pad(
                    count($fileTab),
                    4,
                    '0',
                    STR_PAD_LEFT
                )
                . DIRECTORY_SEPARATOR;
            $fileTabBis = scandir(
                $pathOnDocserver
                . strval(str_pad(count($fileTab), 4, '0', STR_PAD_LEFT))
            );
            //Removes . and .. lines
            array_shift($fileTabBis);
            array_shift($fileTabBis);
            $nbFilesBis = count($fileTabBis);
            //If number of files => 1000 then creates a new subdirectory
            if ($nbFilesBis >= 1000) {
                $newDir = ($nbFiles) + 1;
                if (!mkdir(
                    $pathOnDocserver
                    . str_pad($newDir, 4, '0', STR_PAD_LEFT),
                    0770
                )
                ) {
                    return array(
                        'destinationDir' => '',
                        'fileDestinationName' => '',
                        'error' => 'Pb to create directory on the docserver:'
                        . $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT),
                    );
                } else {
                    Ds_setRights(
                        $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT)
                        . DIRECTORY_SEPARATOR
                    );
                    $destinationDir = $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT)
                        . DIRECTORY_SEPARATOR;
                    $fileDestinationName = '0001';
                    $fileDestinationName = $fileDestinationName . '_' . mt_rand();
                    return array(
                        'destinationDir' => $destinationDir,
                        'fileDestinationName' => $fileDestinationName,
                        'error' => '',
                    );
                }
            } else {
                //Docserver contains less than 1000 files
                $newFileName = $nbFilesBis + 1;
                $greater = $newFileName;
                for ($n = 0;$n < count($fileTabBis);$n++) {
                    $currentFileName = array();
                    $currentFileName = explode('.', $fileTabBis[$n]);
                    if ((int) $greater <= (int) $currentFileName[0]) {
                        if ((int) $greater == (int) $currentFileName[0]) {
                            $greater ++;
                        } else {
                            //$greater < current
                            $greater = (int) $currentFileName[0] + 1;
                        }
                    }
                }
                $fileDestinationName = str_pad($greater, 4, '0', STR_PAD_LEFT);
                $fileDestinationName = $fileDestinationName . '_' . mt_rand();
                return array(
                    'destinationDir' => $destinationDir,
                    'fileDestinationName' => $fileDestinationName,
                    'error' => '',
                );
            }
        }
    }

    /**
    * Sets the size of the docserver
    * @param $docserver docservers object
    * @param $newSize integer New size of the docserver
    */
    public function setSize($docserver, $newSize)
    {
        $db = new Database();
        $stmt = $db->query(
            "update " . _DOCSERVERS_TABLE_NAME
            . " set actual_size_number = ? where docserver_id = ?",
            array(
                $newSize,
                $docserver->docserver_id
            )
        );
        
        return $newSize;
    }
}
