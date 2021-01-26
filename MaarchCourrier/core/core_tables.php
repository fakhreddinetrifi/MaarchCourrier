<?php

/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief Core tables declarations
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/


if (! defined('_DOCSERVERS_TABLE_NAME')) {
    define('_DOCSERVERS_TABLE_NAME', 'docservers');
}
if (!defined('_DOCSERVER_TYPES_TABLE_NAME')) {
    define('_DOCSERVER_TYPES_TABLE_NAME', 'docserver_types');
}
if (! defined('_LC_CYCLE_STEPS_TABLE_NAME')) {
    define('_LC_CYCLE_STEPS_TABLE_NAME', 'lc_cycle_steps');
}
if (! defined('HISTORY_TABLE')) {
    define('HISTORY_TABLE', 'history');
}
if (! defined('SECURITY_TABLE')) {
    define('SECURITY_TABLE', 'security');
}
if (! defined('SESSION_SECURITY_TABLE')) {
    define('SESSION_SECURITY_TABLE', 'session_security');
}
if (! defined('STATUS_TABLE')) {
    define('STATUS_TABLE', 'status');
}
if (! defined('USERGROUPS_TABLE')) {
    define('USERGROUPS_TABLE', 'usergroups');
}
if (! defined('USERGROUPS_SERVICES_TABLE')) {
    define('USERGROUPS_SERVICES_TABLE', 'usergroups_services');
}
if (! defined('USERS_TABLE')) {
    define('USERS_TABLE', 'users');
}
