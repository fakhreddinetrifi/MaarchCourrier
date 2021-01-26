<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class AttachmentControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'title'         => 'Nulle pierre ne peut être polie sans friction, nul homme ne peut parfaire son expérience sans épreuve.',
            'type'          => 'response_project',
            'chrono'        => 'MAARCH/2019D/24',
            'resIdMaster'   => 100,
            'encodedFile'   => $encodedFile,
            'format'        => 'txt',
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $attachmentController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        self::$id = $responseBody->id;
        $this->assertIsInt(self::$id);

        // CHECK ERROR EMPTY TYPE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgsFail   = $aArgs;
        unset($aArgsFail['type']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgsFail, $request);
        $response = $attachmentController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $response = json_decode((string)$response->getBody(), true);

        $this->assertSame('Body type is empty or not a string', $response['errors']);

        //  READ
        $res = \Attachment\models\AttachmentModel::getById(['id' => self::$id, 'select' => ['*']]);

        $this->assertIsArray($res);

        $this->assertSame($aArgs['title'], $res['title']);
        $this->assertSame($aArgs['type'], $res['attachment_type']);
        $this->assertSame('txt', $res['format']);
        $this->assertSame('A_TRA', $res['status']);
        $this->assertSame(23, (int)$res['typist']);
        $this->assertSame(1, $res['relation']);
        $this->assertSame($aArgs['chrono'], $res['identifier']);
        $this->assertNotNull($res['path']);
        $this->assertNotNull($res['filename']);
        $this->assertNotNull($res['docserver_id']);
        $this->assertNotNull($res['fingerprint']);
        $this->assertNotNull($res['filesize']);
        $this->assertNull($res['origin_id']);
    }

    public function testUpdate()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'title' => 'La plus chétive cabane renferme plus de vertus que les palais des rois.',
            'type'  => 'response_project',
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $attachmentController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        // CHECK ERROR EMPTY TYPE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgsFail   = $aArgs;
        unset($aArgsFail['type']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgsFail, $request);

        $response     = $attachmentController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $response = json_decode((string)$response->getBody(), true);

        $this->assertSame('Body type is empty or not a string', $response['errors']);

        //  READ
        $response = $attachmentController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $res = json_decode((string)$response->getBody(), true);
        $this->assertIsArray($res);

        $this->assertSame($aArgs['title'], $res['title']);
        $this->assertSame($aArgs['type'], $res['type']);
        $this->assertSame('A_TRA', $res['status']);
        $this->assertSame(1, $res['relation']);
    }

    public function testGetByResId()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $attachmentController->getByResId($request, new \Slim\Http\Response(), ['resId' => 100]);
        $response = json_decode((string)$response->getBody(), true);

        $this->assertNotNull($response['attachments']);
        $this->assertIsArray($response['attachments']);

        $this->assertIsBool($response['mailevaEnabled']);

        foreach ($response['attachments'] as $value) {
            if ($value['resId'] == self::$id) {
                $userInfo = \User\models\UserModel::getByLogin(['login' => 'superadmin', 'select' => ['id']]);
                $this->assertSame('La plus chétive cabane renferme plus de vertus que les palais des rois.', $value['title']);
                $this->assertSame('response_project', $value['type']);
                $this->assertSame('A_TRA', $value['status']);
                $this->assertSame($userInfo['id'], (int)$value['typist']);
                $this->assertSame(1, $value['relation']);
                $this->assertSame('MAARCH/2019D/24', $value['chrono']);
                $this->assertNull($value['originId']);
                $this->assertNotNull($value['modificationDate']);
                $this->assertNotNull($value['modifiedBy']);
                $this->assertNotNull($value['typeLabel']);
                $this->assertIsBool($value['canConvert']);
                break;
            }
        }

        // ERROR
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $attachmentController->getByResId($request, new \Slim\Http\Response(), ['resId' => 123940595]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $response['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetThumbnailContent()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // ERROR
        $response = $attachmentController->getThumbnailContent($request, new \Slim\Http\Response(), ['id' => 123940595]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment not found', $response['errors']);
    }

    public function testGetOriginalFileContent()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // ERROR
        $response = $attachmentController->getOriginalFileContent($request, new \Slim\Http\Response(), ['id' => 123940595]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment not found', $response['errors']);
    }

    public function testGetFileContent()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // GET
        $aArgs = [
            "mode" => "base64"
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response = $attachmentController->getFileContent($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('txt', $response['originalFormat']);
        $this->assertNotEmpty($response['encodedDocument']);
    }

    public function testGetByChrono()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // GET
        $aArgs = [
            "chrono" => "MAARCH/2019D/24"
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response = $attachmentController->getByChrono($fullRequest, new \Slim\Http\Response());
        $response = json_decode((string)$response->getBody(), true);
        $this->assertIsInt($response['resId']);
        $this->assertIsInt($response['resIdMaster']);
        $this->assertSame('A_TRA', $response['status']);
        $this->assertSame('La plus chétive cabane renferme plus de vertus que les palais des rois.', $response['title']);

        //Error
        $fullRequest = $request->withQueryParams([]);
        $response = $attachmentController->getByChrono($fullRequest, new \Slim\Http\Response());
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Query chrono is not set', $response['errors']);

        //Error
        $aArgs = [
            "chrono" => "MAARCH/2019D/249888765"
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response = $attachmentController->getByChrono($fullRequest, new \Slim\Http\Response());
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment does not exist', $response['errors']);
    }

    public function testMailing()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // ERROR
        $response = $attachmentController->getMailingById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment is not candidate to mailing', $response['errors']);

        // CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fileContent = file_get_contents('modules/templates/templates/styles/AR_Masse_Simple.docx');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'title'         => 'Sujet de Mailing',
            'type'          => 'response_project',
            'chrono'        => 'MAARCH/2019D/38',
            'resIdMaster'   => 100,
            'encodedFile'   => $encodedFile,
            'format'        => 'docx',
            'status'        => 'SEND_MASS'
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $attachmentController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        $mailingId = $responseBody->id;
        $this->assertIsInt($mailingId);

        // GET
        $response = $attachmentController->getMailingById($request, new \Slim\Http\Response(), ['id' => $mailingId]);
        $this->assertSame(204, $response->getStatusCode());
    }

    public function testSetInSignatureBook()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $attachmentController->setInSignatureBook($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $response['success']);

        // ERROR
        $response = $attachmentController->setInSignatureBook($request, new \Slim\Http\Response(), ['id' => 123940595]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment not found', $response['errors']);
    }

    public function testSetInSendAttachment()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $attachmentController->setInSendAttachment($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $response['success']);

        // ERROR
        $response = $attachmentController->setInSendAttachment($request, new \Slim\Http\Response(), ['id' => 123940595]);
        $response = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment not found', $response['errors']);
    }

    public function testGetAttachmentTypes()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $attachmentController->getAttachmentsTypes($request, new \Slim\Http\Response());
        $response = json_decode((string)$response->getBody(), true);

        $this->assertNotNull($response['attachmentsTypes']);
        $this->assertIsArray($response['attachmentsTypes']);

        foreach ($response['attachmentsTypes'] as $value) {
            $this->assertNotNull($value['label']);
            $this->assertIsBool($value['sign']);
            $this->assertIsBool($value['chrono']);
            $this->assertIsBool($value['attachInMail']);
            $this->assertIsBool($value['show']);
        }
    }

    public function testDelete()
    {
        $attachmentController = new \Attachment\controllers\AttachmentController();

        //  DELETE
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $attachmentController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  DELETE
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $attachmentController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $res      = json_decode((string)$response->getBody(), true);
        $this->assertSame(400, $response->getStatusCode());

        $this->assertSame('Attachment does not exist', $res['errors']);

        //  READ
        $response = $attachmentController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $res = json_decode((string)$response->getBody(), true);
        $this->assertSame(400, $response->getStatusCode());

        $this->assertSame('Attachment does not exist', $res['errors']);
    }
}
