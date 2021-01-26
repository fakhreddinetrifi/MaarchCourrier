<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class FolderPrintControllerTest extends TestCase
{
    private static $noteId = null;
    private static $attachmentId = null;
    private static $attachmentIdLinked = null;
    private static $emailId = null;
    private static $acknowledgementReceiptId = null;

    public function testGenerateFile()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        // CREATE NOTE
        $noteController = new \Note\controllers\NoteController();
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'value'     => "Test d'ajout d'une note par php unit",
            'entities'  => ['COU', 'CAB'],
            'resId'     => $GLOBALS['resources'][0]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $noteController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$noteId = $responseBody->noteId;
        $this->assertIsInt(self::$noteId);

        //  CREATE ATTACHMENT
        $attachmentController = new \Attachment\controllers\AttachmentController();
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $body = [
            'title'         => 'Nulle pierre ne peut être polie sans friction, nul homme ne peut parfaire son expérience sans épreuve.',
            'type'          => 'response_project',
            'chrono'        => 'MAARCH/2019D/14',
            'resIdMaster'   => $GLOBALS['resources'][0],
            'encodedFile'   => $encodedFile,
            'format'        => 'txt',
            'recipientId'   => 1,
            'recipientType' => 'contact'
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $attachmentController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        self::$attachmentId = $responseBody->id;
        $this->assertIsInt(self::$attachmentId);

        $body = [
            'title'         => 'Nulle pierre ne peut être polie sans friction, nul homme ne peut parfaire son expérience sans épreuve.',
            'type'          => 'response_project',
            'chrono'        => 'MAARCH/2019D/15',
            'resIdMaster'   => $GLOBALS['resources'][1],
            'encodedFile'   => $encodedFile,
            'format'        => 'txt',
            'recipientId'   => 1,
            'recipientType' => 'contact'
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $attachmentController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsInt($responseBody['id']);
        self::$attachmentIdLinked = $responseBody['id'];

        // CREATE EMAIL
        self::$emailId = \Email\controllers\EmailController::createEmail([
            'userId'    => $GLOBALS['id'],
            'data'      => [
                'sender'        => ['email' => 'support@maarch.fr'],
                'recipients'    => ['dev@maarch.org'],
                'object'        => 'TU Folder Print Email',
                'body'          => 'TU Folder Print Email' . '<a href="#">'._CLICK_HERE.'</a>',
                'document'      => ['id' => $GLOBALS['resources'][0], 'isLinked' => true, 'original' => true],
                'isHtml'        => true,
                'status'        => 'WAITING'
            ]
        ]);

        // CREATE ACKNOWLEDGEMENT RECEIPT
        $storeResult = \Docserver\controllers\DocserverController::storeResourceOnDocServer([
            'collId'            => 'letterbox_coll',
            'docserverTypeId'   => 'ACKNOWLEDGEMENT_RECEIPTS',
            'encodedResource'   => $encodedFile,
            'format'            => 'txt'
        ]);

        $this->assertEmpty($storeResult['errors']);

        self::$acknowledgementReceiptId = \AcknowledgementReceipt\models\AcknowledgementReceiptModel::create([
            'resId'             => $GLOBALS['resources'][0],
            'type'              => 'simple',
            'format'            => 'html',
            'userId'            => $GLOBALS['id'],
            'contactId'         => 1,
            'docserverId'       => 'ACKNOWLEDGEMENT_RECEIPTS',
            'path'              => $storeResult['directory'],
            'filename'          => $storeResult['file_destination_name'],
            'fingerprint'       => $storeResult['fingerPrint']
        ]);

        //  CREATE LINK
        \Resource\models\ResModel::update(['set' => ['linked_resources' => json_encode([$GLOBALS['resources'][1], $GLOBALS['resources'][1] * 1000])], 'where' => ['res_id = ?'], 'data' => [$GLOBALS['resources'][0]]]);

        // GENERATE FOLDER PRINT

        $folderPrintController = new \Resource\controllers\FolderPrintController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // Errors
        $body = [
            "resources" => [ ]
        ];
        
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources is empty', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [$GLOBALS['resources'][1]],
                ], [
                    "resId"                   => $GLOBALS['resources'][0] * 1000,
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [$GLOBALS['resources'][1]],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('No document to merge', $responseBody['errors']);

        // Attachment errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId, 'wrong format'],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [
                        [
                            'resId'    => $GLOBALS['resources'][1],
                            'document' => true
                        ]
                    ],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment id is not an integer', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId * 1000],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [
                        [
                            'resId'    => $GLOBALS['resources'][1],
                            'document' => true
                        ]
                    ],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment(s) not found', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][1],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [
                        [
                            'resId'    => $GLOBALS['resources'][1],
                            'document' => true
                        ]
                    ],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Attachment not linked to resource', $responseBody['errors']);

        // Note errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId, 'wrong format'],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [
                        [
                            'resId'    => $GLOBALS['resources'][1],
                            'document' => true
                        ]
                    ],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Note id is not an integer', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId, self::$noteId * 1000],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [
                        [
                            'resId'    => $GLOBALS['resources'][1],
                            'document' => true
                        ]
                    ],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Note(s) not found', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][1],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Note not linked to resource', $responseBody['errors']);

        // Linked resources errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => ['wrong format']
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('LinkedResources out of perimeter', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [self::$attachmentId],
                    "notes"                   => [self::$noteId],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [$GLOBALS['resources'][2]]
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('LinkedResources resId is not linked to resource', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [$GLOBALS['resources'][1] * 1000]
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('LinkedResources out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources"         => [$GLOBALS['resources'][1] * 1000]
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('LinkedResources Document does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        // Linked resources attachments errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources" => [],
                    "linkedResourcesAttachments" => ['wrong format'],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('LinkedResources attachment id is not an integer', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => true,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [],
                    "linkedResources" => [],
                    "linkedResourcesAttachments" => [self::$attachmentId * 1000],
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('LinkedResources attachments not found', $responseBody['errors']);

        // Acknowledgement receipt errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => ['wrong format'],
                    "emails"                  => [],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Acknowledgement Receipt id is not an integer', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [self::$acknowledgementReceiptId, self::$acknowledgementReceiptId * 1000],
                    "emails"                  => [],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Acknowledgement Receipt(s) not found', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][1],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [self::$acknowledgementReceiptId],
                    "emails"                  => [],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Acknowledgement Receipt not linked to resource', $responseBody['errors']);

        // Email errors
        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => ['wrong format'],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Email id is not an integer', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][0],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [self::$emailId, self::$emailId * 1000],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Email(s) not found', $responseBody['errors']);

        $body = [
            "resources" => [
                [
                    "resId"                   => $GLOBALS['resources'][1],
                    "document"                => false,
                    "attachments"             => [],
                    "notes"                   => [],
                    "acknowledgementReceipts" => [],
                    "emails"                  => [self::$emailId],
                    "linkedResources"         => []
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Email not linked to resource', $responseBody['errors']);

        // Success
        $body = [
            "resources" => [
                [
                    "resId"                      => $GLOBALS['resources'][0],
                    "document"                   => true,
                    "attachments"                => [self::$attachmentId],
                    "notes"                      => [self::$noteId],
                    "acknowledgementReceipts"    => [self::$acknowledgementReceiptId],
                    "emails"                     => [self::$emailId],
                    "linkedResources"            => [$GLOBALS['resources'][1]],
                    "linkedResourcesAttachments" => 'ALL',
                ]
            ],
            "summarySheet" => [
                [
                    "unit" => "qrcode",
                    "label" => ""
                ],
                [
                    "unit" => "primaryInformations",
                    "label" => "Informations primaires"
                ],
                [
                    "unit" => "senderRecipientInformations",
                    "label" => "Informations de traitement"
                ],
                [
                    "unit" => "secondaryInformations",
                    "label" => "Informations secondaires"
                ],
                [
                    "unit" => "diffusionList",
                    "label" => "Liste de diffusion"
                ],
                [
                    "unit" => "opinionWorkflow",
                    "label" => "Liste d'avis"
                ],
                [
                    "unit" => "visaWorkflow",
                    "label" => "Circuit de visa"
                ]
            ],
            "withSeparator" => true,
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertSame('application/pdf', $headers['Content-Type'][0]);

        // GENERATE FOLDER PRINT 2

        $folderPrintController = new \Resource\controllers\FolderPrintController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            "resources" => [[
                "resId"                   => $GLOBALS['resources'][0],
                "document"                => true,
                "attachments"             => true,
                "notes"                   => true,
                "acknowledgementReceipts" => true,
                "emails"                  => true,
            ]],
            "summarySheet" => [
                [
                    "unit" => "qrcode",
                    "label" => ""
                ],
                [
                    "unit" => "primaryInformations",
                    "label" => "Informations primaires"
                ],
                [
                    "unit" => "senderRecipientInformations",
                    "label" => "Informations de traitement"
                ],
                [
                    "unit" => "secondaryInformations",
                    "label" => "Informations secondaires"
                ],
                [
                    "unit" => "diffusionList",
                    "label" => "Liste de diffusion"
                ],
                [
                    "unit" => "opinionWorkflow",
                    "label" => "Liste d'avis"
                ],
                [
                    "unit" => "visaWorkflow",
                    "label" => "Circuit de visa"
                ]
            ],
            "withSeparator" => true,
        ];
        
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $folderPrintController->generateFile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertSame('application/pdf', $headers['Content-Type'][0]);

        // DELETE NOTE
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $noteController = new \Note\controllers\NoteController();
        $response         = $noteController->delete($request, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(204, $response->getStatusCode());

        // DELETE ATTACHMENT
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $attachmentController->delete($request, new \Slim\Http\Response(), ['id' => self::$attachmentId]);
        $this->assertSame(204, $response->getStatusCode());

        $response     = $attachmentController->delete($request, new \Slim\Http\Response(), ['id' => self::$attachmentIdLinked]);
        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
