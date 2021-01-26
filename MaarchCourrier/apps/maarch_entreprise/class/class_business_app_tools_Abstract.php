<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief Contains the apps tools class
*
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once 'core/core_tables.php';

abstract class business_app_tools_Abstract extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * Build Maarch business app configuration into sessions vars with a xml
    * configuration file
    */
    public function build_business_app_config()
    {
        // build Maarch business app configuration into sessions vars

        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'config.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'config.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'config.xml';
        }

        if (file_exists($path)) {
            $xmlconfig = simplexml_load_file($path);
        } else {
            $xmlconfig = false;
            exit('<i style="color:red;">Fichier de configuration manquant ...</i><br/><br/>Si un custom est utilis&eacute; assurez-vous que l\'url soit correct');
        }

        if ($xmlconfig <> false) {
            $config = $xmlconfig->CONFIG;

            $uriBeginning = strpos($_SERVER['SCRIPT_NAME'], 'apps');
            $url = $_SESSION['config']['coreurl']
                 .substr($_SERVER['SCRIPT_NAME'], $uriBeginning);
            $_SESSION['config']['businessappurl'] = str_replace(
                'index.php',
                '',
                $url
            );

            $_SESSION['config']['databaseserver'] =
                (string) $config->databaseserver;
            $_SESSION['config']['databaseserverport'] =
                (string) $config->databaseserverport;
            $_SESSION['config']['databasetype'] =
                (string) $config->databasetype;
            $_SESSION['config']['databasename'] =
                (string) $config->databasename;
            $_SESSION['config']['databaseschema'] =
                (string) $config->databaseschema;
            $_SESSION['config']['databaseuser'] =
                (string) $config->databaseuser;
            $_SESSION['config']['databasepassword'] =
                (string) $config->databasepassword;
            $_SESSION['config']['databasesearchlimit'] =
                (string) $config->databasesearchlimit;
            $_SESSION['config']['nblinetoshow'] =
                (string) $config->nblinetoshow;
            $_SESSION['config']['limitcharsearch'] =
                (string) $config->limitcharsearch;
            $_SESSION['config']['lang']                = (string) $config->lang;
            $_SESSION['config']['adminmail']           = (string) $config->adminmail;
            $_SESSION['config']['adminname']           = (string) $config->adminname;
            $_SESSION['config']['debug']               = (string) $config->debug;
            $_SESSION['config']['applicationname']     = (string) $config->applicationname;
            $_SESSION['config']['defaultPage']         = (string) $config->defaultPage;
            $_SESSION['config']['cookietime']          = (string) $config->cookieTime;
            $_SESSION['config']['userdefaultpassword'] = (string) $config->userdefaultpassword;
            if (isset($config->showfooter)) {
                $_SESSION['config']['showfooter'] = (string) $config->showfooter;
            } else {
                $_SESSION['config']['showfooter'] = 'true';
            }

            $tablename = $xmlconfig->TABLENAME;
            $_SESSION['tablename']['doctypes_first_level']  = (string) $tablename->doctypes_first_level;
            $_SESSION['tablename']['doctypes_second_level'] = (string) $tablename->doctypes_second_level;
            $_SESSION['tablename']['doctypes_indexes']      = (string) $tablename->doctypes_indexes;
            $_SESSION['tablename']['tags']                  = (string) $tablename->tags;
            
            $_SESSION['config']['tmppath'] = \SrcCore\models\CoreConfigModel::getTmpPath();
            
            $i = 0;

            if (isset($_SESSION['custom_override_id']) && file_exists(
                'custom/' . $_SESSION['custom_override_id'] . '/'
                . $_SESSION['config']['lang'] . '.php'
            )
            ) {
                include_once 'custom/' . $_SESSION['custom_override_id'] . '/'
                . $_SESSION['config']['lang'] . '.php';
            }
            include_once 'apps' . DIRECTORY_SEPARATOR
                    . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                    . 'lang' . DIRECTORY_SEPARATOR . $_SESSION['config']['lang']
                    . '.php';
            $_SESSION['collections'] = array();
            $_SESSION['coll_categories'] = array();
            foreach ($xmlconfig->COLLECTION as $col) {
                $tmp = (string) $col->label;
                if (!empty($tmp) && defined($tmp) && constant($tmp) <> null) {
                    $tmp = constant($tmp);
                }
                $collId = (string) $col->id;

                if (isset($col->table) && ! empty($col->table)) {
                    $_SESSION['collections'][$i] = array(
                        'id'                   => (string) $col->id,
                        'label'                => (string) $tmp,
                        'table'                => (string) $col->table,
                        'version_table'        => (string) $col->version_table,
                        'view'                 => (string) $col->view,
                        'adr'                  => (string) $col->adr,
                        'index_file'           => (string) $col->index_file,
                        'script_add'           => (string) $col->script_add,
                        'script_search'        => (string) $col->script_search,
                        'script_search_result' => (string) $col->script_search_result,
                        'script_details'       => (string) $col->script_details,
                    );

                    $_SESSION['coll_categories']['letterbox_coll'] = [
                        'incoming' => defined('_INCOMING') ? _INCOMING : '_INCOMING',
                        'outgoing' => defined('_OUTGOING') ? _OUTGOING : '_OUTGOING',
                        'internal' => defined('_INTERNAL') ? _INTERNAL : '_INTERNAL',
                        'ged_doc' => defined('_GED_DOC') ? _GED_DOC : '_GED_DOC'
                    ];

                    $i++;
                } else {
                    $_SESSION['collections'][$i] = array(
                        'id' => (string) $col->id,
                        'label' => (string) $tmp,
                        'view' => (string) $col->view,
                        'adr' => (string) $col->adr,
                        'index_file' => (string) $col->index_file,
                        'script_add' => (string) $col->script_add,
                        'script_search' => (string) $col->script_search,
                        'script_search_result' => (string) $col->script_search_result,
                        'script_details' => (string) $col->script_details,
                    );
                }
            }
            $history = $xmlconfig->HISTORY;
            $_SESSION['history']['usersdel']        = (string) $history->usersdel;
            $_SESSION['history']['usersban']        = (string) $history->usersban;
            $_SESSION['history']['usersadd']        = (string) $history->usersadd;
            $_SESSION['history']['usersup']         = (string) $history->usersup;
            $_SESSION['history']['usersval']        = (string) $history->usersval;
            $_SESSION['history']['doctypesdel']     = (string) $history->doctypesdel;
            $_SESSION['history']['doctypesadd']     = (string) $history->doctypesadd;
            $_SESSION['history']['doctypesup']      = (string) $history->doctypesup;
            $_SESSION['history']['doctypesval']     = (string) $history->doctypesval;
            $_SESSION['history']['doctypesprop']    = (string) $history->doctypesprop;
            $_SESSION['history']['usergroupsdel']   = (string) $history->usergroupsdel;
            $_SESSION['history']['usergroupsban']   = (string) $history->usergroupsban;
            $_SESSION['history']['usergroupsadd']   = (string) $history->usergroupsadd;
            $_SESSION['history']['usergroupsup']    = (string) $history->usergroupsup;
            $_SESSION['history']['usergroupsval']   = (string) $history->usergroupsval;
            $_SESSION['history']['structuredel']    = (string) $history->structuredel;
            $_SESSION['history']['structureadd']    = (string) $history->structureadd;
            $_SESSION['history']['structureup']     = (string) $history->structureup;
            $_SESSION['history']['subfolderdel']    = (string) $history->subfolderdel;
            $_SESSION['history']['subfolderadd']    = (string) $history->subfolderadd;
            $_SESSION['history']['subfolderup']     = (string) $history->subfolderup;
            $_SESSION['history']['resadd']          = (string) $history->resadd;
            $_SESSION['history']['resup']           = (string) $history->resup;
            $_SESSION['history']['resdel']          = (string) $history->resdel;
            $_SESSION['history']['resview']         = (string) $history->resview;
            $_SESSION['history']['userlogin']       = (string) $history->userlogin;
            $_SESSION['history']['userlogout']      = (string) $history->userlogout;
            $_SESSION['history']['actionadd']       = (string) $history->actionadd;
            $_SESSION['history']['actionup']        = (string) $history->actionup;
            $_SESSION['history']['actiondel']       = (string) $history->actiondel;
            $_SESSION['history']['contactadd']      = (string) $history->contactadd;
            $_SESSION['history']['contactup']       = (string) $history->contactup;
            $_SESSION['history']['contactdel']      = (string) $history->contactdel;
            $_SESSION['history']['statusadd']       = (string) $history->statusadd;
            $_SESSION['history']['statusup']        = (string) $history->statusup;
            $_SESSION['history']['statusdel']       = (string) $history->statusdel;
            $_SESSION['history']['docserversadd']   = (string) $history->docserversadd;
            $_SESSION['history']['docserversdel']   = (string) $history->docserversdel;
            $_SESSION['history']['docserversallow'] = (string) $history->docserversallow;
            $_SESSION['history']['docserversban']   = (string) $history->docserversban;
            $_SESSION['history']['docserverslocationsadd']   = (string) $history->docserverslocationsadd;
            $_SESSION['history']['docserverslocationsdel']   = (string) $history->docserverslocationsdel;
            $_SESSION['history']['docserverslocationsallow'] = (string) $history->docserverslocationsallow;
            $_SESSION['history']['docserverslocationsban']   = (string) $history->docserverslocationsban;
            $_SESSION['history']['docserverstypesadd']       = (string) $history->docserverstypesadd;
            $_SESSION['history']['docserverstypesdel']       = (string) $history->docserverstypesdel;
            $_SESSION['history']['docserverstypesallow']     = (string) $history->docserverstypesallow;
            $_SESSION['history']['docserverstypesban']       = (string) $history->docserverstypesban;
            $_SESSION['history_keywords'] = array();
            foreach ($xmlconfig->KEYWORDS as $keyword) {
                $tmp = (string) $keyword->label;
                if (!empty($tmp) && defined($tmp) && constant($tmp) <> null) {
                    $tmp = constant($tmp);
                }

                array_push(
                    $_SESSION['history_keywords'],
                    array(
                        'id'    => (string) $keyword->id,
                        'label' => $tmp,
                    )
                );
            }

            $i = 0;
            foreach ($xmlconfig->MODULES as $modules) {
                $_SESSION['modules'][$i] = array(
                    'moduleid' => (string) $modules->moduleid,
                );
                $i ++;
            }
            $this->_loadActionsPages();
        }
    }

    /**
    * Load actions in session
    */
    public function _loadActionsPages()
    {
        if (isset($_SESSION['config']['corepath'])
            && isset($_SESSION['config']['app_id'])
            && isset($_SESSION['config']['lang']
        )
        ) {
            $core = new core_tools();
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'core'
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom'
                      . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                      . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR
                      . 'xml' . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            } else {
                $path = 'core' . DIRECTORY_SEPARATOR . 'xml'
                      . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            }
            $xmlfile = simplexml_load_file($path);
            $langPath = 'apps' . DIRECTORY_SEPARATOR
                       . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                       . 'lang' . DIRECTORY_SEPARATOR
                       . $_SESSION['config']['lang'] . '.php';

            $i = 0;
            foreach ($xmlfile->ACTIONPAGE as $actionPage) {
                $label = (string) $actionPage->LABEL;
                if (!empty($label) && defined($label)
                    && constant($label) <> null
                ) {
                    $label = constant($label);
                }
                $keyword = '';
                if (isset($actionPage->KEYWORD)
                    && ! empty($actionPage->KEYWORD)
                ) {
                    $keyword = (string) $actionPage->KEYWORD;
                }
                $createFlag = 'N';
                if (isset($actionPage->FLAG_CREATE)
                    && (string) $actionPage->FLAG_CREATE == 'true'
                ) {
                    $createFlag = 'Y';
                }
                $_SESSION['actions_pages'][$i] = array(
                    'ID'          => (string) $actionPage->ID,
                    'LABEL'       => $label,
                    'NAME'        => (string) $actionPage->NAME,
                    'ORIGIN'      => (string) $actionPage->ORIGIN,
                    'MODULE'      => (string) $actionPage->MODULE,
                    'KEYWORD'     => $keyword,
                    'FLAG_CREATE' => $createFlag,
                );
                $i++;
            }
        }

        //LOAD actions in other modules
        foreach ($_SESSION['modules'] as $key => $value) {
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom'
                      . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                      . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR
                      . 'xml' . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            } else {
                $path = 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR . 'xml'
                      . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            }

            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            ) || file_exists(
                $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )

            ) {
                $xmlfile = simplexml_load_file($path);

                $langPath = 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR
                      . 'lang' . DIRECTORY_SEPARATOR. $_SESSION['config']['lang'] . '.php';

                include_once($langPath);
                foreach ($xmlfile->ACTIONPAGE as $actionPage) {
                    $label = (string) $actionPage->LABEL;
                    if (!empty($label) && defined($label)
                    && constant($label) <> null
                ) {
                        $label = constant($label);
                    }
                    $keyword = '';
                    if (isset($actionPage->KEYWORD)
                    && ! empty($actionPage->KEYWORD)
                ) {
                        $keyword = (string) $actionPage->KEYWORD;
                    }
                    $createFlag = 'N';
                    if (isset($actionPage->FLAG_CREATE)
                    && (string) $actionPage->FLAG_CREATE == 'true'
                ) {
                        $createFlag = 'Y';
                    }
                    $_SESSION['actions_pages'][$i] = array(
                    'ID'          => (string) $actionPage->ID,
                    'LABEL'       => $label,
                    'NAME'        => (string) $actionPage->NAME,
                    'ORIGIN'      => (string) $actionPage->ORIGIN,
                    'MODULE'      => (string) $actionPage->MODULE,
                    'KEYWORD'     => $keyword,
                    'FLAG_CREATE' => $createFlag
                );
                    $i++;
                }
            }
        }
    }
}
