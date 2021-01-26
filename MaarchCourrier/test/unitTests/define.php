<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

require_once 'vendor/autoload.php';

$login = 'superadmin';
$userInfo = \User\models\UserModel::getByLogin(['login' => $login, 'select' => ['id']]);
$id = $userInfo['id'];
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

date_default_timezone_set(\SrcCore\models\CoreConfigModel::getTimezone());

$language = \SrcCore\models\CoreConfigModel::getLanguage();
require_once("src/core/lang/lang-{$language}.php");

class httpRequestCustom
{
    public static function addContentInBody($aArgs, $request)
    {
        $json = json_encode($aArgs);
               
        $stream = fopen('php://memory', 'r+');
        fputs($stream, $json);
        rewind($stream);
        $httpStream = new \Slim\Http\Stream($stream);
        $request = $request->withBody($httpStream);
        $request = $request->withHeader('Content-Type', 'application/json');

        return $request;
    }
}
