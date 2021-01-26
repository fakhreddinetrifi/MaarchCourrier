<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
 * @brief   Contains all the functions to manage the users groups security
 * and connexion to the application
 *
 * @file
 *
 * @author Claire Figueras <dev@maarch.org>
 * @date $date$
 *
 * @version $Revision$
 * @ingroup core
 */

/**
 * @brief   contains all the functions to manage the users groups security
 * through session variables
 *
 *<ul>
 *  <li>Management of application connexion</li>
 *  <li>Management of user rigths</li>
 *</ul>
 * @ingroup core
 */

class security extends Database
{
    /**
     * @param  $textToHash
     *
     * @return string hashedText
     */
    public function getPasswordHash($textToHash)
    {
        return password_hash($textToHash, PASSWORD_DEFAULT);
    }

    /**
     * Returns the view of a collection from the collection identifier.
     *
     * @param string $coll_id Collection identifier
     *
     * @return string View name or empty string if not found
     */
    public function retrieve_view_from_coll_id($coll_id)
    {
        for ($i = 0; $i < count($_SESSION['collections']); ++$i) {
            if ($_SESSION['collections'][$i]['id'] == $coll_id) {
                return $_SESSION['collections'][$i]['view'];
            }
        }

        return '';
    }
}
