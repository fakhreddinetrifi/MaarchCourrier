<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief   Contains all the function to manage the resources
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Contains all the function to manage the resources
*
* <ul>
*<li>Standardized methods to insert, update and delete a resource</li>
*</ul>
* @ingroup core
*/
 class resource extends request
 {

    /**
    * Resource identifier
    * Integer
    */
     private $res_id;


     /**
      * Type identifier of the resource
      * String
      */
     private $type_id;

     /**
      * Person who inserts the resource in the application
      * String
      */
     private $typist;

     /**
      * File format of the resource
      * String
      */
     private $format;

     /**
      * Docserver identifier of the resource
      * String
      */
     private $docserver_id;

     /**
      * Path of the resource in the docserver
      * String
      */
     private $path;

     /**
      * Fingerprint of the resource
      * String
      */
     private $fingerprint;

     /**
      * File name of the resource
      * String
      */
     private $filename;

     /**
      * File Size of the resource
      * Integer
      */
     private $filesize;

     /**
      * Offset
      * Integer
      */
     private $offset;

     /**
      * Logical address
      * Integer
      */
     private $log_adr;

     /**
      * Status of the resource
      * String
      */
     private $status;

     /**
      * Error message
      * String
      */
     private $error;

     /**
     * get the adr of the document
     *
     * @param $view resource view
     * @param $resId resource ID
     * @param $whereClause security clause
     * @return array of adr fields if is ok
     */
     public function getResourceAdr($view, $resId, $whereClause, $adrTable)
     {
         $control = array();
         if (!isset($view) || empty($resId) || empty($whereClause)) {
             $control = array("status" => "ko", "error" => _PB_WITH_ARGUMENTS);
             return $control;
         }
         $docserverAdr = array();
         $db = new Database();
         $query = "select res_id, docserver_id, path, filename, format, fingerprint from " . $view
           . " where res_id = ? ". $whereClause;
         $stmt = $db->query($query, array($resId));
         if ($stmt->rowCount() > 0) {
             $line = $stmt->fetchObject();
             $format = $line->format;
             array_push($docserverAdr, array("docserver_id" => $line->docserver_id, "path" => $line->path, "filename" => $line->filename, "format" => $format, "fingerprint" => $line->fingerprint, "adr_priority" => ""));
             $control = array("status" => "ok", $docserverAdr, "error" => "");
             return $control;
         } else {
             $control = array("status" => "ko", "error" => _RESOURCE_NOT_FOUND);
             return $control;
         }
     }
 }
