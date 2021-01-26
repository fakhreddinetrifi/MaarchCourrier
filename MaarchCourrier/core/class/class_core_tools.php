<?php
/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/****************************************************************************/
/*                                                                          */
/*                                                                          */
/*               THIS PAGE CAN NOT BE OVERWRITTEN IN A CUSTOM               */
/*                                                                          */
/*                                                                          */
/* **************************************************************************/

/**
 * @defgroup core Framework core
 */

/**
 * @brief   Contains all the functions to load core and modules
 *
 * @file
 *
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 *
 * @version $Revision$
 * @ingroup core
 */

/**
 * @brief   Contains all the functions to load core and modules
 *
 * <ul>
 * <li>Loads core tables into session</li>
 * <li>Loads modules settings into session</li>
 * <li>Builds the application menu</li>
 *  <li>Management and building the framework</li>
 *  <li>Modules services loading</li>
 *  <li>Execution of the module services </li>
 *</ul>
 *
 * @ingroup core
 */
class core_tools extends functions
{
    /**
     * Load Maarch core configuration into sessions vars from the core/xml/config.xml file.
     */
    public function build_core_config($pathtoxmlcore)
    {
        $xmlconfig = simplexml_load_file($pathtoxmlcore);

        // Loads  core tables into session ($_SESSION['tablename'] array)
        $TABLENAME = $xmlconfig->TABLENAME;
        $_SESSION['tablename']['actions'] = (string) $TABLENAME->actions;
        $_SESSION['tablename']['docservers'] = (string) $TABLENAME->docservers;
        $_SESSION['tablename']['doctypes'] = (string) $TABLENAME->doctypes;
        $_SESSION['tablename']['history'] = (string) $TABLENAME->history;
        $_SESSION['tablename']['security'] = (string) $TABLENAME->security;
        $_SESSION['tablename']['status'] = (string) $TABLENAME->status;
        $_SESSION['tablename']['usergroups'] = (string) $TABLENAME->usergroups;
        $_SESSION['tablename']['usergroup_services'] = (string) $TABLENAME->usergroups_services;
        $_SESSION['tablename']['users'] = (string) $TABLENAME->users;
    }

    /**
     * Load Maarch modules configuration into sessions vars from modules/module_name/xml/config.xml files.
     *
     * @param $modules array  Enabled modules of the application
     */
    public function load_modules_config($modules, $mode_batch = false)
    {
        require_once 'core/class/class_request.php';
        // Browses enabled modules
        for ($i = 0; $i < count($modules); ++$i) {
            if (file_exists(
                $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR
                .$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR
                .'modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid']
                .DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR
                .'config.xml'
            )
            ) {
                $configPath = $_SESSION['config']['corepath'].'custom'
                    .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                    .DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR
                    .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'xml'
                    .DIRECTORY_SEPARATOR.'config.xml';
            } else {
                $configPath = 'modules'.DIRECTORY_SEPARATOR
                    .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'xml'
                    .DIRECTORY_SEPARATOR.'config.xml';
            }

            if (file_exists('modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php')) {
                include_once 'modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php';
            }
            if (file_exists($configPath)) {
                // Reads the config.xml file of the current module
                $xmlconfig = simplexml_load_file($configPath);

                // Loads into $_SESSION['modules_loaded'] module's informations
                foreach ($xmlconfig->CONFIG as $CONFIG) {
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['name'] =
                        (string) $CONFIG->name;
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['path'] =
                        'modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid']
                        .DIRECTORY_SEPARATOR;
                    $comment = (string) $CONFIG->comment;
                    if (!empty($comment) && defined($comment)
                        && constant($comment) != null
                    ) {
                        $comment = constant($comment);
                    }
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['comment'] =
                        $comment;

                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['fileprefix'] = (string) $CONFIG->fileprefix;
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['loaded'] = (string) $CONFIG->loaded;
                }
            }

            if (file_exists(
                $_SESSION['config']['corepath'].'custom'
                    .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                    .DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR
                    .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'class'
                    .DIRECTORY_SEPARATOR.'class_modules_tools.php'
            )
            ) {
                $path_module_tools = $_SESSION['config']['corepath'].'custom'
                    .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                    .DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR
                    .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'class'
                    .DIRECTORY_SEPARATOR.'class_modules_tools.php';
            } else {
                $path_module_tools = 'modules'.DIRECTORY_SEPARATOR
                    .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'class'
                    .DIRECTORY_SEPARATOR.'class_modules_tools.php';
            }
            if (file_exists($path_module_tools)) {
                require_once $path_module_tools;
                $modules_tools = new $modules[$i]['moduleid']();
                //Loads the tables of the module into session
                $modules_tools->build_modules_tables();
                //Loads log keywords of the module
            }
            foreach ($xmlconfig->KEYWORDS as $keyword) {
                $tmp = (string) $keyword->label;
                if (!empty($tmp) && defined($tmp) && constant($tmp) != null) {
                    $tmp = constant($tmp);
                }

                $id = (string) $keyword->id;
                if (!$this->is_var_in_history_keywords_tab($id)) {
                    array_push(
                        $_SESSION['history_keywords'],
                        array(
                            'id' => $id,
                            'label' => $tmp,
                        )
                    );
                }
            }
        }
        if (!$mode_batch) {
            //Loads logs keywords of the actions
            $db = new Database();
            $stmt = $db->query(
                'select id, label_action from '
                .$_SESSION['tablename']['actions']
                ." where history = 'Y'"
            );
            while ($res = $stmt->fetchObject()) {
                array_push(
                    $_SESSION['history_keywords'],
                    array(
                        'id' => 'ACTION#'.$res->id,
                        'label' => $this->show_string($res->label_action),
                    )
                );
            }
        }
    }

    /**
     * Check if the log keyword is known in the apps.
     *
     * @param $id  string Log keyword to check
     *
     * @return bool True if the keyword is found, False otherwise
     */
    public function is_var_in_history_keywords_tab($id)
    {
        $found = false;
        for ($i = 0; $i < count($_SESSION['history_keywords']); ++$i) {
            if ($_SESSION['history_keywords'][$i]['id'] == $id) {
                $found = $_SESSION['history_keywords'][$i]['label'];
                break;
            }
        }

        return $found;
    }

    /**
     * Loads language variables into session.
     */
    public static function load_lang($lang = 'fr', $maarch_directory = '', $maarch_apps = '')
    {
        if (isset($_SESSION['config']['lang']) && !empty($_SESSION['config']['lang'])) {
            $lang = $_SESSION['config']['lang'];
        }
        if (isset($_SESSION['config']['corepath']) && !empty($_SESSION['config']['corepath'])) {
            $maarch_directory = $_SESSION['config']['corepath'];
        }
        if (isset($_SESSION['config']['app_id']) && !empty($_SESSION['config']['app_id'])) {
            $maarch_apps = $_SESSION['config']['app_id'];
        }
        //Loading custom lang file if present, this means that language constants are defined in the custom language file before other language files
        if (isset($_SESSION['custom_override_id']) && !empty($_SESSION['custom_override_id'])) {
            self::load_lang_custom_override($_SESSION['custom_override_id']);
        }

        if (isset($lang) && file_exists($maarch_directory.'apps/maarch_entreprise/lang'.DIRECTORY_SEPARATOR.$lang.'.php')) {
            include $maarch_directory.'apps/maarch_entreprise/lang'.DIRECTORY_SEPARATOR.$lang.'.php';
        } else {
            $_SESSION['error'] = 'Language file missing';
        }
        if (isset($_SESSION['modules'])) {
            self::load_lang_modules($_SESSION['modules']);
        }
    }

    /**
     * Loads language variables of each module.
     *
     * @param $modules array Enabled modules of the application
     */
    private static function load_lang_modules($modules)
    {
        for ($i = 0; $i < count($modules); ++$i) {
            $file_path = 'custom'
                .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                .DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR
                .$modules[$i]['moduleid'].DIRECTORY_SEPARATOR
                .'lang'.DIRECTORY_SEPARATOR
                .$_SESSION['config']['lang'].'.php';
            if (!file_exists($file_path)) {
                $file_path = 'modules'
                .DIRECTORY_SEPARATOR.$modules[$i]['moduleid']
                .DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR
                .$_SESSION['config']['lang'].'.php';
            }
            if (isset($_SESSION['config']['lang']) && file_exists($file_path)) {
                include $file_path;
            } elseif ($_SESSION['config']['debug'] === 'true') {
                $_SESSION['info'] .= 'Language file missing for module : '
                .$modules[$i]['moduleid'].'<br/>';
            }
        }
    }

    private static function load_lang_custom_override($custom_id)
    {
        $pathname = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$custom_id.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php';

        if (file_exists($pathname)) {
            include $pathname;
        }
    }

    /**
     * Executes the module' s services in the page.
     *
     * @param $modules_services  array List of the module's services
     * @param $whereami  string Page where to execute the service
     * @param $servicenature string  Nature of the service (by default, the function takes all the services natures)
     * @param  $id_service string Identifier of one specific service (empty by default)
     * @param  $id_module string Identifier of one specific module (empty by default)
     */
    public function execute_modules_services($modules_services, $whereami, $servicenature = 'all', $id_service = '', $id_module = '')
    {
        $executedServices = array();
        if (!empty($id_service) && !empty($id_module)) {
            for ($i = 0; $i < count($modules_services[$id_module]); ++$i) {
                if ($modules_services[$id_module][$i]['id'] == $id_service
                    && isset($modules_services[$id_module][$i]['whereamiused'])
                ) {
                    for ($k = 0; $k < count(
                        $modules_services[$id_module][$i]['whereamiused']
                    ); ++$k
                    ) {
                        $name = $id = $width = $height = $frameborder = $scrolling = $style = '';
                        if ($modules_services[$id_module][$i]['whereamiused'][$k]['page'] == $whereami) {
                            if ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == 'frame'
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && !in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );

                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                ) && !empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                )
                                ) {
                                    $name = 'name="'.$modules_services[$id_module][$i]['whereamiused'][$k]['frame_id'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                ) && !empty(
                                        $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                )
                                ) {
                                    $id = 'id="'.$modules_services[$id_module][$i]['whereamiused'][$k]['frame_id'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['width']
                                ) && strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['width']
                                ) > 0
                                ) {
                                    $width = 'width="'.$modules_services[$id_module][$i]['whereamiused'][$k]['width'].'" ';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['height']
                                ) && strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['height']
                                ) > 0
                                ) {
                                    $height = 'height="'.$modules_services[$id_module][$i]['whereamiused'][$k]['height'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['border']
                                ) && strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['border']
                                ) > 0
                                ) {
                                    $frameborder = 'frameborder="'
                                        .$modules_services[$id_module][$i]['whereamiused'][$k]['border'].'" ';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['scrolling']
                                ) && !empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['scrolling']
                                )
                                ) {
                                    $scrolling = 'scrolling="'
                                        .$modules_services[$id_module][$i]['whereamiused'][$k]['scrolling'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['style']
                                ) && !empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['style']
                                )
                                ) {
                                    $style = 'style="'
                                        .$modules_services[$id_module][$i]['whereamiused'][$k]['style'].'"';
                                }

                                $iframeStr = '<iframe src="'
                                    .$_SESSION['config']['businessappurl']
                                    .'index.php?display=true&module='
                                    .$id_module.'&page='
                                    .$modules_services[$id_module][$i]['servicepage']
                                    .'" '.$name.' '.$id.' '.$width
                                    .' '.$height.' '.$frameborder.' '
                                    .$scrolling.' '.$style.'></iframe>';

                                return $iframeStr;
                            //break;
                            } elseif ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == 'popup'
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && !in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );
                                echo $modules_services[$id_module][$i]['name']; ?>
<br />
<a href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$id_module.'&page='.$modules_services[$id_module][$i]['servicepage']; ?>' target='_blank'><?php echo _ACCESS_TO_SERVICE; ?></a><br /><br />
<?php
                                break;
                            } elseif ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == 'button'
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && !in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );
                                $tmp = $modules_services[$id_module][$i]['whereamiused'][$k]['button_label'];
                                if (!empty($tmp) && defined($tmp)
                                    && constant($tmp) != null
                                ) {
                                    $tmp = constant($tmp);
                                } ?>
<input type="button" name="<?php functions::xecho($modules_services[$id_module][$i]['id']); ?>" value="<?php functions::xecho($tmp); ?>" onclick="window.open('<?php echo   $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$id_module.'&page='.$modules_services[$id_module][$i]['servicepage']; ?>', '<?php functions::xecho($modules_services[$id_module][$i]['id']); ?>','width=<?php functions::xecho($modules_services[$id_module][$i]['whereamiused'][$k]['width']); ?>,height=<?php functions::xecho($modules_services[$id_module][$i]['whereamiused'][$k]['height']); ?>,scrollbars=yes,resizable=yes' );" class="button" /><br />
<?php
                                break;
                            } elseif ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == 'include'
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && !in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );

                                include 'modules'.DIRECTORY_SEPARATOR
                                    .$id_module.DIRECTORY_SEPARATOR
                                    .$modules_services[$id_module][$i]['servicepage'];
                                break;
                            }
                        }
                    }
                }
            }
        } else {
            $tab_view = array();
            if (isset($modules_services)) {
                foreach (array_keys($modules_services) as $value) {
                    if (isset($modules_services[$value])) {
                        for ($iService = 0; $iService < count($modules_services[$value]);
                            ++$iService
                        ) {
                            if (isset($modules_services[$value][$iService])
                                && isset($modules_services[$value][$iService]['whereamiused'])
                                && count($modules_services[$value][$iService]['whereamiused']) > 0
                            ) {
                                for ($k = 0; $k < count(
                                    $modules_services[$value][$iService]['whereamiused']
                                ); ++$k
                                ) {
                                    if (isset(
                                        $modules_services[$value][$iService]['whereamiused'][$k]['page']
                                    ) && $modules_services[$value][$iService]['whereamiused'][$k]['page'] == $whereami
                                    ) {
                                        if ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == 'frame'
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == 'all' || $servicenature == 'frame')
                                            && !in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );

                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            ) && !empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            )
                                            ) {
                                                $name = 'name="'
                                                    .$modules_services[$value][$iService]['whereamiused'][$k]['frame_id'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            ) && !empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            )
                                            ) {
                                                $iServiced = 'id="'
                                                    .$modules_services[$value][$iService]['whereamiused'][$k]['frame_id'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['width']
                                            ) && strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['width']
                                            ) > 0
                                            ) {
                                                $width = 'width="'.$modules_services[$value][$iService]['whereamiused'][$k]['width'].'" ';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['height']
                                            ) && strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['height']
                                            ) > 0
                                            ) {
                                                $height = 'height="'.$modules_services[$value][$iService]['whereamiused'][$k]['height'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['border']
                                            ) && strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['border']
                                            ) > 0
                                            ) {
                                                $frameborder = 'frameborder="'.$modules_services[$value][$iService]['whereamiused'][$k]['border'].'" ';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['scrolling']
                                            ) && !empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['scrolling']
                                            )
                                            ) {
                                                $scrolling = 'scrolling="'.$modules_services[$value][$iService]['whereamiused'][$k]['scrolling'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['style']
                                            ) && !empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['style']
                                            )
                                            ) {
                                                $style = 'style="'.$modules_services[$value][$iService]['whereamiused'][$k]['style'].'"';
                                            }

                                            $iServiceframeStr = '<iframe src="'
                                                .$_SESSION['config']['businessappurl']
                                                .'index.php?display=true&module='
                                                .$value.'&page='
                                                .$modules_services[$value][$iService]['servicepage']
                                                .'" '.$name.' '.$iServiced.' '
                                                .$width.' '.$height.' '
                                                .$frameborder.' '.$scrolling
                                                .' '.$style.'></iframe>';

                                            return $iServiceframeStr;
                                        } elseif ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == 'tab'
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == 'tab')
                                            && !in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            $arrLabel = $modules_services[$value][$iService]['whereamiused'][$k]['tab_label'];

                                            if (!empty($arrLabel)
                                                && defined($arrLabel)
                                                && constant($arrLabel) != null
                                            ) {
                                                $arrLabel = constant($arrLabel);
                                            }
                                            $arrOrder = $modules_services[$value][$iService]['whereamiused'][$k]['tab_order'];

                                            $frameSrc = $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$value.'&page='.$modules_services[$value][$iService]['servicepage'];
                                            $tab_view[$arrOrder]['tab_label'] = $arrLabel;
                                            $tab_view[$arrOrder]['frame_src'] = $frameSrc;
                                        } elseif ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == 'popup'
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == 'all' || $servicenature == 'popup')
                                            && !in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            echo $modules_services[$value][$iService]['name']; ?>
<br />
<a href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$value.'&page='.$modules_services[$value][$iService]['servicepage']; ?>' target='_blank'><?php echo _ACCESS_TO_SERVICE; ?></a><br /><br />
<?php
                                        } elseif ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == 'button'
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == 'all' || $servicenature == 'button')
                                            && !in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            $tmp = $modules_services[$value][$iService]['whereamiused'][$k]['button_label'];

                                            if (!empty($tmp) && defined($tmp)
                                                && constant($tmp) != null
                                            ) {
                                                $tmp = constant($tmp);
                                            } ?>
<input type="button" name="<?php functions::xecho($modules_services[$value][$iService]['id']); ?>" value="<?php functions::xecho($tmp); ?>" onclick="window.open('<?php echo  $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$iServiced_module.'&page='.$modules_services[$iServiced_module][$iService]['servicepage']; ?>', '<?php functions::xecho($modules_services[$value][$iService]['id']); ?>','width=<?php functions::xecho($modules_services[$value][$iService]['whereamiused'][$k]['width']); ?>,height=<?php functions::xecho($modules_services[$value][$iService]['whereamiused'][$k]['height']); ?>,scrollbars=yes,resizable=yes' );" class="button" /><br />
<?php
                                        } elseif (isset($_SESSION['user']['services'][$modules_services[$value][$iService]['id']])
                                            && $modules_services[$value][$iService]['whereamiused'][$k]['nature'] == 'include'
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == 'all' || $servicenature == 'include')
                                            && !in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            include 'modules'.DIRECTORY_SEPARATOR
                                                .$value.DIRECTORY_SEPARATOR
                                                .$modules_services[$value][$iService]['servicepage'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($servicenature == 'tab') {
                for ($u = 1; $u <= count($tab_view); ++$u) {
                    if ($u == 1) {
                        ?>
<td class="indexingtab">
    <a href="javascript://" onclick="opentab('myframe', '<?php functions::xecho($tab_view[$u]['frame_src']); ?>');">
        <?php functions::xecho($tab_view[$u]['tab_label']); ?>
    </a>
    <?php
                            $_SESSION['first_tab_to_open'] = $tab_view[$u]['frame_src']; ?>
</td>
<?php
                    } else {
                        ?>
<td class="indexingtab">
    <a href="javascript://" onclick="opentab('myframe', '<?php functions::xecho($tab_view[$u]['frame_src']); ?>');">
        <?php functions::xecho($tab_view[$u]['tab_label']); ?>
    </a>
</td>
<?php
                    }
                }
            }
        }
    }

    /**
     * Loads the html declaration and doctype.
     */
    public function load_html()
    {
        /*<?xml version="1.0" encoding="UTF-8"?>*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php functions::xecho($_SESSION['config']['lang']); ?>" lang="<?php functions::xecho($_SESSION['config']['lang']); ?>">
<?php
    }

    /**
     * Loads the html header.
     *
     * @param  $title string Title tag value (empty by default)
     */
    public function load_header($title = '', $load_css = true, $load_js = true)
    {
        if (empty($title)) {
            $title = $_SESSION['config']['applicationname'];
        } ?>

<head>
    <title><?php functions::xecho($title); ?>
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="<?php functions::xecho($_SESSION['config']['lang']); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/svg+xml" href="static.php?filename=logo_only.svg" />
    <?php
            if ($load_css) {
                $this->load_css();
            } ?>
</head>
<?php
    }

    /**
     * Loads the modules and aplication css.
     */
    private function load_css()
    {
        ?>
<link rel="stylesheet" href="../../node_modules/@fortawesome/fontawesome-free/css/all.css" media="screen" />
<?php
    }

    /**
     * Cleans the page variable and looks if she exists or not before including her.
     */
    public function insert_page()
    {
        if (!isset($_SESSION['config']['app_id']) && $_SESSION['config']['app_id'] == '') {
            $_SESSION['config']['app_id'] = 'maarch_entreprise';
        }
        if (isset($_GET['amp;module']) && $_GET['amp;module'] != '') {
            $_GET['module'] = $_GET['amp;module'];
            $_REQUEST['module'] = $_REQUEST['amp;module'];
        }
        if (isset($_GET['amp;baskets']) && $_GET['amp;baskets'] != '') {
            $_GET['baskets'] = $_GET['amp;baskets'];
            $_REQUEST['baskets'] = $_REQUEST['amp;baskets'];
        }
        // Cleans the page variables and looks if she exists or not before including her
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            // CVA 31 oct 2014 Security Local File Inclusion
            if ($_GET['module'] == 'tags') {
                $this->f_page = str_replace(
                    array('../', '..%2F'),
                    array('', ''),
                    $_GET['page']
                );
            } else {
                $this->f_page = str_replace(
                    array('../', '..%2F'),
                    array('', ''),
                    $this->wash($_GET['page'], 'file', '', 'yes')
                );
            }
        } else {
            $this->loadDefaultPage();

            return true;
        }
        if (isset($_GET['module']) && $_GET['module'] != 'core') {
            // Page is defined in a module
            $found = false;
            for ($cptM = 0; $cptM < count($_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']]); ++$cptM) {
                if (
                    $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM]
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM]
                        == 'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->loadDefaultPage();
            }
        } elseif (isset($_GET['module']) && $_GET['module'] == 'core') {
            // Page is defined the core
            $found = false;
            for ($cptM = 0; $cptM < count($_SESSION['maarchFilesWhiteList']['core']); ++$cptM) {
                if (
                    $_SESSION['maarchFilesWhiteList']['core'][$cptM]
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['core'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['core'][$cptM]
                        == 'core'.DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['core'][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->loadDefaultPage();
            }
        } elseif (isset($_GET['admin']) && !empty($_GET['admin'])) {
            if (
                !isset($_SESSION['user']['services']['admin'])
                && $_GET['page'] != 'modify_user'
                && $_GET['page'] != 'user_modif'
            ) {
                $this->loadDefaultPage();
            } else {
                $found = false;
                for ($cptM = 0; $cptM < count($_SESSION['maarchFilesWhiteList']['apps']); ++$cptM) {
                    if (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                            == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.'.php'
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    } elseif (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                            == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.'.php'
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $this->loadDefaultPage();
                }
            }
        } elseif (isset($_GET['dir']) && !empty($_GET['dir'])) {
            // Page is defined in a dir directory of the application
            $found = false;
            for ($cptM = 0; $cptM < count($_SESSION['maarchFilesWhiteList']['apps']); ++$cptM) {
                if (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                        == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->loadDefaultPage();
            }
        } else {
            // Page is defined in the application
            $found = false;
            for ($cptM = 0; $cptM < count($_SESSION['maarchFilesWhiteList']['apps']); ++$cptM) {
                if (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM]
                        == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.'.php'
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->loadDefaultPage();
            }
        }

        return true;
    }

    /**
     * Loads the default page.
     */
    public function loadDefaultPage()
    {
        if (isset($_SESSION['target_page']) && trim($_SESSION['target_page']) != '' && trim($_SESSION['target_module']) != '') {
            $target = 'page='.$_SESSION['target_page'].'&module='.$_SESSION['target_module'];
        } elseif (isset($_SESSION['target_page']) && trim($_SESSION['target_page']) != '' && trim($_SESSION['target_admin']) != '') {
            $target = 'page='.$_SESSION['target_page'].'&admin='.$_SESSION['target_admin'];
        } elseif (isset($_SESSION['target_page']) && trim($_SESSION['target_page']) != '' && trim($_SESSION['target_module']) == '' && trim($_SESSION['target_admin']) == '') {
            $target = 'page='.$_SESSION['target_page'];
        }
        $_SESSION['target_page'] = '';
        $_SESSION['target_module'] = '';
        $_SESSION['target_admin'] = '';
        if (isset($target) && trim($target) != '') {
            $tmpTab = array();
            $tmpTab = explode('&', $target);
            if (count($tmpTab) == 1) {
                $page = str_replace('page=', '', $tmpTab[0]);
                require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$page.'.php';
            } elseif (count($tmpTab) == 2) {
                $tabPage = array();
                $tabModuleOrAdmin = array();
                $tabPage = explode('=', $tmpTab[0]);
                $tabModuleOrAdmin = explode('=', $tmpTab[1]);
                if ($tabModuleOrAdmin[0] == 'module') {
                    require 'modules'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].'.php';
                } else {
                    //admin case
                    if ($tabPage[1] == 'users' || $tabPage[1] == 'groups' || $tabPage[1] == 'admin_archi' || $tabPage[1] == 'history' || $tabPage[1] == 'history_batch'
                       || $tabPage[1] == 'status' || $tabPage[1] == 'action' || $tabPage[1] == 'xml_param_services' || $tabPage[1] == 'modify_user'
                       ) {
                        require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].'.php';
                    } else {
                        require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'welcome.php';
                    }
                }
            } else {
                require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'welcome.php';
            }
        } elseif (trim($_SESSION['config']['defaultPage']) != '') {
            $tmpTab = array();
            $tmpTab = explode('&', $_SESSION['config']['defaultPage']);
            if (count($tmpTab) == 1) {
                $page = str_replace('page=', '', $tmpTab[0]);
                require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$page.'.php';
            } elseif (count($tmpTab) == 2) {
                $tabPage = array();
                $tabModuleOrAdmin = array();
                $tabPage = explode('=', $tmpTab[0]);
                $tabModuleOrAdmin = explode('=', $tmpTab[1]);
                if ($tabModuleOrAdmin[0] == 'module') {
                    require 'modules'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].'.php';
                } else {
                    require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].'.php';
                }
            } else {
                require 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'welcome.php';
            }
        } else {
            require 'apps'.DIRECTORY_SEPARATOR.'welcome.php';
        }
    }

    /**
     * Tests if the module is loaded.
     *
     * @param  $module_id  string Module identifier the module to test
     *
     * @return bool True if the module is found, False otherwise
     */
    public function is_module_loaded($module_id)
    {
        if (isset($_SESSION['modules_loaded'])) {
            if (is_array($_SESSION['modules_loaded'])) {
                foreach (array_keys($_SESSION['modules_loaded']) as $value) {
                    if ($value == $module_id && $_SESSION['modules_loaded'][$value]['loaded'] == 'true') {
                        return true;
                    }
                }

                return false;
            }
        }
    }

    /**
     * Gets the path of an action.
     *
     * @param  $id_service  string Action identifier
     *
     * @return Action page or action identifier if not found
     */
    public function get_path_action_page($action_id)
    {
        $found = false;
        $ind = -1;
        for ($i = 0; $i < count($_SESSION['actions_pages']); ++$i) {
            if ($_SESSION['actions_pages'][$i]['ID'] == $action_id) {
                $found = true;
                $ind = $i;
                break;
            }
        }
        if (!$found) {
            return $action_id;
        } else {
            $path = $action_id;
            if (strtoupper($_SESSION['actions_pages'][$ind]['ORIGIN']) == 'APPS') {
                $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['NAME'].'.php';
            } elseif (strtoupper($_SESSION['actions_pages'][$ind]['ORIGIN']) == 'MODULE') {
                $path = 'modules'.DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['MODULE'].DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['NAME'].'.php';
            }

            return $path;
        }
    }

    public function get_custom_id()
    {
        if (!file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.'custom.xml')) {
            return '';
        }
        $linkToApps = false;
        $arr = explode('/', $_SERVER['SCRIPT_NAME']);
        if ($key = array_search('rest', $arr)) {
            unset($arr[$key]);
        }
        $arr = array_values($arr);
        for ($cptArr = 0; $cptArr < count($arr); ++$cptArr) {
            if ($arr[$cptArr] == 'apps') {
                $linkToApps = true;
            }
        }
        if ($linkToApps) {
            $path = $arr[count($arr) - 4];
        } else {
            $path = $arr[count($arr) - 2];
        }

        $xml = simplexml_load_file($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.'custom.xml');
        foreach ($xml->custom as $custom) {
            if (trim($path) != '' && isset($custom->path) && $custom->path == trim($path)) {
                return (string) $custom->custom_id;
            }
            if ($custom->ip == $_SERVER['SERVER_ADDR']) {
                return (string) $custom->custom_id;
            }
            if ($custom->external_domain == $_SERVER['HTTP_HOST'] xor $custom->domain == $_SERVER['HTTP_HOST']) {
                return (string) $custom->custom_id;
            }
        }

        return '';
    }
}
