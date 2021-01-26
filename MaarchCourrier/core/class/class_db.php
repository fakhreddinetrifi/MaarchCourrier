<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief   Embedded sql functions (connection, database selection, query ).
* Allow to changes the databases server
*
* @file
* @author  Claire Figueras <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
* @author  Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Embedded sql functions (connection, database selection, query ).
* Allow to changes the databases server
*
* <ul>
*  <li>Compatibility with the following databases : Mysql, Postgres,
*       Mssql Server, Oracle
*  <li>Connection to the Maarch database</li>
* <li>Execution of SQL queries to the Maarch database</li>
* <li>Getting results of SQL queries</li>
* <li>Managing the database errors</li>
* </ul>
* @ingroup core
*/
class dbquery extends functions
{
    /**
    * Debug mode activation.
    * Integer 1,0
         */
    private $_debug;             // debug mode

    /**
    * Debug query (debug mode). String
    */
    private $_debugQuery;       // request for the debug mode

    /**
    * SQL link identifier
    * Integer
    */
    public $_sqlLink;          // sql link identifier


    /**
    * To know where the script was stopped
    *  Integer
    */
    private $_sqlError;    // to know where the script was stopped

    /**
    * SQL query
    * String
         */
    public $query;              // query

    /**
    * Number of queries made with this identifier
         * Integer
         */
    private $_nbQuery;          // number of queries made with this identifier

    /**
    * Sent query result
         * String
         */
    private $_result;            // sent query result

    /**
    * OCI query identifier
    * @access private
    * @var integer
    */
    private $_statement  ;       // OCI query identifier

    private $_server;
    private $_port;
    private $_user;
    private $_password;
    private $_database;
    private $_databasetype;
    //private $workspace;

    public function __construct()
    {
        $args = func_get_args();
        if (count($args) < 1) {
            if (isset($_SESSION['config']['databaseserver'])) {
                $this->_server = $_SESSION['config']['databaseserver'];
            }
            if (isset($_SESSION['config']['databaseserverport'])) {
                $this->_port = $_SESSION['config']['databaseserverport'];
            }
            if (isset($_SESSION['config']['databaseuser'])) {
                $this->_user = $_SESSION['config']['databaseuser'];
            }
            if (isset($_SESSION['config']['databasepassword'])) {
                $this->_password = $_SESSION['config']['databasepassword'];
            }
            if (isset($_SESSION['config']['databasename'])) {
                $this->_database = $_SESSION['config']['databasename'];
            }
            if (isset($_SESSION['config']['databasetype'])) {
                $this->_databasetype = $_SESSION['config']['databasetype'];
            }
        } else {
            $errorArgs = true;
            if (is_array($args[0])) {
                if (! isset($args[0]['server'])) {
                    $this->_server = '127.0.0.1';
                } else {
                    $this->_server = $args[0]['server'];
                }
                if (! isset($args[0]['databasetype'])) {
                    $this->_databasetype = 'MYSQL';
                } else {
                    $this->_databasetype = $args[0]['databasetype'];
                }
                if (! isset($args[0]['port'])) {
                    $this->_port = '3304';
                } else {
                    $this->_port = $args[0]['port'];
                }
                if (! isset($args[0]['user'])) {
                    $this->_user = 'root';
                } else {
                    $this->_user = $args[0]['user'];
                }
                if (! isset($args[0]['pass'])) {
                    $this->_password = '';
                } else {
                    $this->_password = $args[0]['pass'];
                }
                if (! isset($args[0]['base'])) {
                    $this->_database = '';
                } else {
                    $this->_database = $args[0]['base'];
                }
                $errorArgs = false;
            } elseif (is_string($args[0]) && file_exists($args[0])) {
                $xmlconfig = simplexml_load_file($args[0]);
                $config = $xmlconfig->CONFIG_BASE;
                $this->_server = (string) $config->databaseserver;
                $this->_port = (string) $config->databaseserverport;
                $this->_databasetype = (string) $config->databasetype;
                $this->_database = (string) $config->databasename;
                $this->_user = (string) $config->databaseuser;
                $this->_password = (string) $config->databasepassword;
                $errorArgs = false;
            }
            if ($errorArgs) {
                $this->_sqlError = 5; // error constructor
                $this->error();
            }
        }
    }
    /**
    * Connects to the database
    *
    */
    public function connect()
    {
        $this->_debug = 0;
        $this->_nbQuery = 0;
        
        switch ($this->_databasetype) {
        case 'MYSQL':
            $this->_sqlLink = @mysqli_connect(
                $this->_server,
                $this->_user,
                $this->_password,
                $this->_database,
                $this->_port
            );
            break;
            
        case 'POSTGRESQL':
            $this->_sqlLink = @pg_connect(
                'host=' . $this->_server .
                ' user=' . $this->_user .
                ' password=' . $this->_password .
                ' dbname=' . $this->_database .
                ' port=' . $this->_port
            );
            break;
            
        case 'SQLSERVER':
            $this->_sqlLink = @mssql_connect(
                $this->_server,
                $this->_user,
                $this->_password
            );
            break;
            
        case 'ORACLE':
            if ($this->_server <> '') {
                $this->_sqlLink = oci_connect(
                    $this->_user,
                    $this->_password,
                    '//' .
                    $this->_server . '/' .
                    $this->_database,
                    'UTF8'
                );
            } else {
                $this->_sqlLink = oci_connect(
                    $this->_user,
                    $this->_password,
                    $this->_database,
                    'UTF8'
                );
            }
            break;
            
        default:
            $this->_sqlLink = false;
            break;
        }

        if (! $this->_sqlLink) {
            $this->_sqlError = 1; // error connexion
            $this->error();
        } else {
            $this->select_db();
        }
    }

    /**
    * Database selection (only for SQLSERVER)
    */
    public function select_db()
    {
        if ($this->_databasetype == 'SQLSERVER') {
            if (! @mssql_select_db($this->_database)) {
                $this->_sqlError = 2;
                $this->error();
            }
        }
    }
    
    /**
    * Execution the sql query
    *
    * @param  $sqlQuery string SQL query
    * @param  $catchError bool In case of error, catch the error or not,
    *           if not catched, the error is displayed (false by default)
    * @param  $noFilter bool true if you don't want to filter on ; and --
    */
    public function query(
        $sqlQuery,
        $catchError = false,
        $noFilter = false,
        &$params = array()
    ) {
        if (!$this->_sqlLink) {
            $this->connect();
        }
        $canExecute = true;
        // if filter, we looking for ; or -- in the sql query
        if (!$noFilter) {
            $func = new functions();
            $sqlQuery = $func->wash_html($sqlQuery, '');
            $ctrl1 = array();
            $ctrl1 = explode(";", $sqlQuery);
            if (count($ctrl1) > 1) {
                $canExecute = false;
                $this->_sqlError = 7;
                $this->error();
            }
            $ctrl2 = array();
            $ctrl2 = explode("--", $sqlQuery);
            if (count($ctrl2) > 1) {
                $canExecute = false;
                $this->_sqlError = 7;
                $this->error();
            }
        }

        // query
        if ($canExecute) {
            $this->_debugQuery = $sqlQuery;
            
            switch ($this->_databasetype) {
            case 'MYSQL':
                $this->query = @mysqli_query($this->_sqlLink, $sqlQuery);
                break;

            case 'POSTGRESQL':
                $this->query = @pg_query($this->_sqlLink, $sqlQuery);
                break;
                
            case 'SQLSERVER':
                $this->query = @mssql_query($sqlQuery);
                break;
                
            case 'ORACLE':
                $this->query = @oci_parse($this->_sqlLink, $sqlQuery);
                                
                if ($this->query == false) {
                    if ($catchError) {
                        return false;
                    }
                    $this->_sqlError = 6;
                    $this->error();
                    exit();
                } else {
                    if (count($params) > 0) {
                        foreach ($params as $paramname => &$paramvar) {
                            $binded = oci_bind_by_name($this->query, $paramname, $paramvar, 100, SQLT_CHR);
                        }
                    }

                    if (! @oci_execute($this->query)) {
                        if ($catchError) {
                            return false;
                        }
                        $this->_sqlError = 3;
                        $this->error();
                    }
                    if (count($params) > 0) {
                        //
                    }
                }
                break;
                
            default:
                $this->query = false;
            }
            
            if ($this->query == false && !$catchError) {
                $this->_sqlError = 3;
                $this->error();
            }
            
            $this->_nbQuery ++;
            
            return $this->query;
        } else {
            return false;
        }
    }
    
    public function rollback()
    {
        switch ($this->_databasetype) {
        case 'MYSQL':
            @mysqli_query($this->_sqlLink, 'ROLLBACK');
            break;
        case 'SQLSERVER':
            break;
        case 'POSTGRESQL':
            @pg_query($this->_sqlLink, 'ROLLBACK');
            break;
        case 'ORACLE':
            break;
        }
    }
    
    public function commit()
    {
        switch ($this->_databasetype) {
        case 'MYSQL':
            @mysqli_query($this->_sqlLink, 'COMMIT');
            break;
        case 'SQLSERVER':
            break;
        case 'POSTGRESQL':
            @pg_query($this->_sqlLink, 'COMMIT');
            break;
        case 'ORACLE':
            break;
        }
    }
    
    public function getError()
    {
        switch ($this->_databasetype) {
            case 'MYSQL':
                $sqlError = @mysqli_errno($this->_sqlLink);
                break;
                
            case 'SQLSERVER':
                $sqlError = @mssql_get_last_message();
                break;
                
            case 'POSTGRESQL':
                @pg_send_query($this->_sqlLink, $this->_debugQuery);
                $res = @pg_get_result($this->_sqlLink);
                $sqlError .= @pg_result_error($res);
                break;
                
            case 'ORACLE':
                $res = @oci_error($this->statement);
                $sqlError = $res['message'];
                break;
                
            default:

            }
        return $sqlError;
    }

    /**
    * Returns the query results in an array
    *
    * @return array
    */
    public function fetch_array()
    {
        switch ($this->_databasetype) {
        case 'MYSQL': return @mysqli_fetch_array($this->query);
        case 'SQLSERVER': return @mssql_fetch_array($this->query);
        case 'POSTGRESQL': return @pg_fetch_array($this->query);
        case 'ORACLE':
            $tmpStatement = array();
            $tmpStatement = @oci_fetch_array($this->query);

            if (is_array($tmpStatement)) {
                foreach (array_keys($tmpStatement) as $key) {
                    if (! is_numeric($key)
                        && oci_field_type($this->query, $key) == 'CLOB'
                    ) {
                        if (isset($tmpStatement[$key])) {
                            $tmp = $tmpStatement[$key];
                            $tmpStatement[$key] = $tmp->read($tmp->size());
                        }
                    }
                }
                return array_change_key_case($tmpStatement, CASE_LOWER);
            }
            // no break
        default: return false;
        }
    }
    
    /**
    * Returns the query results in an array
    *
    * @return array
    */
    public function fetch_assoc()
    {
        switch ($this->_databasetype) {
        case 'MYSQL': return @mysqli_fetch_assoc($this->query);
        case 'SQLSERVER': return @mssql_fetch_assoc($this->query);
        case 'POSTGRESQL': return @pg_fetch_assoc($this->query);
        case 'ORACLE':
            $tmpStatement = array();
            $tmpStatement = @oci_fetch_assoc($this->query);

            if (is_array($tmpStatement)) {
                foreach (array_keys($tmpStatement) as $key) {
                    if (! is_numeric($key)
                        && oci_field_type($this->query, $key) == 'CLOB'
                    ) {
                        if (isset($tmpStatement[$key])) {
                            $tmp = $tmpStatement[$key];
                            $tmpStatement[$key] = $tmp->read($tmp->size());
                        }
                    }
                }
                return array_change_key_case($tmpStatement, CASE_LOWER);
            }
            // no break
        default: return false;
        }
    }

    /**
    * Returns the number of results for the current query
    *
    * @return integer Results number
    */
    public function nb_result()
    {
        switch ($this->_databasetype) {
        case 'MYSQL': return @mysqli_num_rows($this->query);
        case 'POSTGRESQL': return @pg_num_rows($this->query);
        case 'SQLSERVER': return @mssql_num_rows($this->query);
        case 'ORACLE':
        if (file_exists($GLOBALS['configFile'])) {
            $dbNbResult = new dbquery($GLOBALS['configFile']);
        } else {
            $dbNbResult = new dbquery();
        }
            $dbNbResult->connect();
            $dbNbResult->query("SELECT COUNT(*) FROM  (" . $this->_debugQuery . ")", true);
            $row = $dbNbResult->fetch_array();
            return $row[0];
        default: return false;
        }
    }

    /**
    * Closes database connexion
    *
    */
    public function disconnect()
    {
        switch ($this->_databasetype) {
        case 'MYSQL':
            if (! mysqli_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'SQLSERVER':
            if (! mssql_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'POSTGRESQL':
            if (! pg_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'ORACLE':
            if (! oci_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        default:

        }
    }

    /**
    * SQL Error management
    *
    */
    private function error()
    {
        require_once('core' . DIRECTORY_SEPARATOR . 'class'
            . DIRECTORY_SEPARATOR . 'class_history.php');
        $trace = new history();
        
        // Connexion error
        if ($this->_sqlError == 1) {
            echo '- <b>' . _DB_CONNEXION_ERROR . '</b>';
            if ($_SESSION['config']['debug'] == 'true') {
                echo ' -<br /><br />' . _DATABASE_SERVER . ' : '
                    . $this->_server . '<br/>' . _DB_PORT . ' : ' . $this->_port
                    . '<br/>' . _DB_TYPE . ' : ' . $this->_databasetype
                    . '<br/>' . _DB_NAME . ' : ' . $this->_database . '<br/>'
                    . _DB_USER . ' : ' . $this->_user . '<br/>' . _PASSWORD
                    . ' : ' . $this->_password;
            }
            header('HTTP/1.1 500 Internal server error');
            exit();
        }

        // Selection error
        if ($this->_sqlError == 2) {
            echo '- <b>' . _SELECTION_BASE_ERROR . '</b>';
            if ($_SESSION['config']['debug'] == 'true') {
                echo ' -<br /><br />' . _DATABASE . ' : ' . $this->_database;
            }
            $trace->add("", 0, "SELECTDB", "DBERROR", _SELECT_DB_FAILED." : ".$this->_database, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_FATAL);
            exit();
        }

        // Query error
        if ($this->_sqlError == 3) {
            $sqlError = $this->getError();
            
            $trace->add(
                "",
                0,
                "QUERY",
                "DBERROR",
                _QUERY_DB_FAILED . ": '" . $sqlError . "' "
                . _QUERY . ": [" . $this->protect_string_db($this->_debugQuery)."]",
                $_SESSION['config']['databasetype'],
                "database",
                true,
                _KO,
                _LEVEL_ERROR
            );
            
            throw new Exception(_QUERY_DB_FAILED.": '".$sqlError."' "._QUERY.": [".$this->protect_string_db($this->_debugQuery)."]");
        }

        // Closing connexion error
        if ($this->_sqlError == 4) {
            echo '- <b>' . _CLOSE_CONNEXION_ERROR . '</b> -<br /><br />';
            $trace->add("", 0, "CLOSE", "DBERROR", _CLOSE_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }

        // Constructor error
        if ($this->_sqlError == 5) {
            echo '- <b>' . _DB_INIT_ERROR . '</b> <br />';
            $trace->add("", 0, "INIT", "DBERROR", _INIT_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }
        // Query Preparation error (ORACLE & DB2)
        if ($this->_sqlError == 6) {
            echo '- <b>' . _QUERY_PREP_ERROR . '</b> <br />';
            $trace->add("", 0, "QUERY", "DBERROR", _PREPARE_QUERY_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }
        // Query Preparation error (ORACLE & DB2)
        if ($this->_sqlError == 7) {
            $_SESSION['error'] .= _SQL_QUERY_NOT_SECURE;
            $trace->add("", 0, "QUERY", "DBERROR", _SQL_QUERY_NOT_SECURE, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            //exit();
        }
    }

    /**
    * Shows the query for debug
    *
    */
    public function show()
    {
        echo _LAST_QUERY . ' : <textarea cols="70" rows="10">'
            . $this->_debugQuery . '</textarea>';
    }
    
    /*************************************************************************
    * Returns the word to get the current timestamp on a query
    *
    * Return
    *   (string) timestamp word
    *
    *************************************************************************/
    public function current_datetime()
    {
        switch ($this->_databasetype) {
        case 'MYSQL': return 'CURRENT_TIMESTAMP';
        case 'POSTGRESQL': return 'CURRENT_TIMESTAMP';
        case 'SQLSERVER': return 'CURRENT_TIMESTAMP';
        case 'ORACLE': return 'SYSDATE';
        default: return ' ';
        }
    }
}
