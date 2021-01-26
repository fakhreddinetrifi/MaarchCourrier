<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief  Contains the Service Object (herits of the BaseObject class)
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
    require_once("core/class/BaseObject.php");
} catch (Exception $e) {
    echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Service Object, herits of the BaseObject class
*
* @ingroup core
*/
class Service extends BaseObject
{
    /**
    * Returns the string representing the Service object
    *
    * @return string The service label (name)
    */
    public function __toString()
    {
        return $this->name ;
    }
}
