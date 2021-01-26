<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Ixbus Controller
 * @author dev@maarch.org
 */

namespace ExternalSignatoryBook\controllers;

use Attachment\models\AttachmentModel;
use Convert\controllers\ConvertPdfController;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use Resource\controllers\StoreController;
use Resource\models\ResModel;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;

/**
  * @codeCoverageIgnore
*/
class IxbusController
{
    public static function createSession($config)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
                        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>
                            <CreateSession xmlns="http://www.srci.fr">
                            <NomUtilisateur>'.$config['data']['userId'].'</NomUtilisateur>
                            <MotdePasse>'.$config['data']['password'].'</MotdePasse>
                            <OrganisationID>'.(int)$config['data']['organizationId'].'</OrganisationID>
                            </CreateSession>
                        </soap:Body>
                        </soap:Envelope>';

        $data = CurlModel::execSOAP([
            'xmlPostString' => $xmlPostString,
            'url'           => $config['data']['url'] . '/parapheurws/service.asmx',
            'soapAction'    => 'http://www.srci.fr/CreateSession',
            'options'       => [CURLOPT_HEADER => 1]
        ]);

        if (!empty($data['cookies'])) {
            $cookie = '';
            foreach ($data['cookies'] as $key => $value) {
                $cookie = $key . '=' . $value . ';';
            }
        } elseif (!empty($data['error'])) {
            return ["error" => $data['error']];
        }

        return ["cookie" => $cookie];
    }

    public static function getInitializeDatas($config)
    {
        $sessionId = IxbusController::createSession($config);
        if (!empty($sessionId['error'])) {
            return ['error' => $sessionId['error']];
        }
        $natures        = IxbusController::getNature(['config' => $config, 'sessionId' => $sessionId['cookie']]);
        $userInfo       = IxbusController::getInfoUtilisateur(['config' => $config, 'login' => $config['data']['userId'], 'password' => $config['data']['password']]);
        $messagesModels = IxbusController::getMessagesModel(['config' => $config, 'sessionId' => $sessionId['cookie'], 'userIdentifiant' => $userInfo->Identifiant]);

        $rawResponse['natures'] = [];
        $rawResponse['messagesModel'] = [];
        if (!empty($natures->Classeur)) {
            foreach ($natures->Classeur as $nature) {
                $rawResponse['natures'][] = (string)$nature->Libelle;
                foreach ($messagesModels->Message as $message) {
                    $messageModel = IxbusController::getMessageNature(['config' => $config, 'messageId' => $message->Identifiant, 'sessionId' => $sessionId['cookie']]);
                    if ((string)$messageModel->IdentifiantClasseur == (string)$nature->Identifiant) {
                        $rawResponse['messagesModel'][(string)$messageModel->IdentifiantMessage] = (string)$message->IdentifiantSpecifique;
                    }
                }
            }
            $rawResponse['messagesModel'] = array_values($rawResponse['messagesModel']);
        }
        return $rawResponse;
    }

    public static function getNature($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
                        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>
                            <GetNaturesAvecDroitsCreer xmlns="http://www.srci.fr">
                            <organisationID>'.$aArgs['config']['data']['organizationId'].'</organisationID>
                            </GetNaturesAvecDroitsCreer>
                        </soap:Body>
                        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetNaturesAvecDroitsCreer',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];

        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetNaturesAvecDroitsCreerResponse->GetNaturesAvecDroitsCreerResult;

        return $response;
    }

    public static function getMessagesModel($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetMessagesModel xmlns="http://www.srci.fr">
              <utilisateurID>'.$aArgs['userIdentifiant'].'</utilisateurID>
              <organisationID>'.$aArgs['config']['data']['organizationId'].'</organisationID>
              <serviceID>-1</serviceID>
              <typeMessage>Production</typeMessage>
            </GetMessagesModel>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetMessagesModel',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetMessagesModelResponse->GetMessagesModelResult;

        return $response;
    }

    public static function getMessageNature($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetMessageNature xmlns="http://www.srci.fr">
              <messageID>'.$aArgs['messageId'].'</messageID>
            </GetMessageNature>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetMessageNature',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetMessageNatureResponse->GetMessageNatureResult;

        return $response;
    }
    
    public static function getInfoUtilisateur($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <getUtilisateur xmlns="http://www.srci.fr">
              <NomUtilisateur>' . $aArgs['login'] . '</NomUtilisateur>
              <MotdePasse>' . $aArgs['password'] . '</MotdePasse>
            </getUtilisateur>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/ixbuswebws/Utilisateur.asmx',
          'soapAction'    => 'http://www.srci.fr/getUtilisateur'
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->getUtilisateurResponse->getUtilisateurResult;

        return $response;
    }

    public static function sendDatas($aArgs)
    {
        $sessionId = IxbusController::createSession($aArgs['config']);
        if (!empty($sessionId['error'])) {
            return ['error' => $sessionId['error']];
        }
        $userInfo  = IxbusController::getInfoUtilisateur(['config' => $aArgs['config'], 'login' => $aArgs['loginIxbus'], 'password' => $aArgs['passwordIxbus']]);

        $attachments = AttachmentModel::get([
            'select'    => [
                'res_id', 'title', 'identifier', 'attachment_type',
                'status', 'typist', 'docserver_id', 'path', 'filename', 'creation_date',
                'validation_date', 'relation', 'origin_id'
            ],
            'where'     => ["res_id_master = ?", "attachment_type not in (?)", "status not in ('DEL', 'OBS', 'FRZ', 'TMP', 'SEND_MASS')", "in_signature_book = 'true'"],
            'data'      => [$aArgs['resIdMaster'], ['incoming_mail_attachment', 'signed_response']]
        ]);

        $attachmentTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachments as $key => $value) {
            if (!$attachmentTypes[$value['attachment_type']]['sign']) {
                unset($attachments[$key]);
            }
        }

        $attachmentToFreeze = [];
        $signature = $aArgs['manSignature'] == 'manual' ? 'true' : 'false';

        $mainResource = ResModel::getById([
          'resId'  => $aArgs['resIdMaster'],
          'select' => ['res_id', 'subject', 'path', 'filename', 'docserver_id', 'format', 'category_id', 'external_id', 'integrations', 'process_limit_date', 'fingerprint']
        ]);

        if (empty($mainResource['process_limit_date'])) {
            $processLimitDate = date('Y-m-d', strtotime(date("Y-m-d"). ' + 14 days'));
        } else {
            $processLimitDateTmp = explode(" ", $mainResource['process_limit_date']);
            $processLimitDate = $processLimitDateTmp[0];
        }

        foreach ($attachments as $value) {
            $resId  = $value['res_id'];
            $collId = 'attachments_coll';

            $adrInfo       = ConvertPdfController::getConvertedPdfById(['resId' => $resId, 'collId' => $collId]);
            $docserverInfo = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
            $filePath      = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];

            $docserverType = DocserverTypeModel::getById(['id' => $docserverInfo['docserver_type_id'], 'select' => ['fingerprint_mode']]);
            $fingerprint = StoreController::getFingerPrint(['filePath' => $filePath, 'mode' => $docserverType['fingerprint_mode']]);
            if ($adrInfo['fingerprint'] != $fingerprint) {
                return ['error' => 'Fingerprints do not match'];
            }

            $encodedZipFile = IxbusController::createZip(['filepath' => $filePath, 'filename' => $adrInfo['filename'], 'res_id_master' => $aArgs['resIdMaster']]);

            $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <SendDossier xmlns="http://www.srci.fr">
                  <ContenuDocumentZip>'. $encodedZipFile .'</ContenuDocumentZip>
                  <NomDocumentPrincipal>'. $adrInfo['filename'] . '</NomDocumentPrincipal>
                  <NomDossier>'. $value['title'] .'</NomDossier>
                  <NomModele>'. $aArgs['messageModel'] .'</NomModele>
                  <NomNature>'. $aArgs['classeurName'] .'</NomNature>
                  <DateLimite>'.$processLimitDate.'</DateLimite>
                  <LoginResponsable>'. $userInfo->NomUtilisateur .'</LoginResponsable>
                  <Confidentiel>false</Confidentiel>
                  <DocumentModifiable>true</DocumentModifiable>
                  <AnnexesSignables>false</AnnexesSignables>
                  <SignatureManuscrite>'.$signature.'</SignatureManuscrite>
                </SendDossier>
              </soap:Body>
            </soap:Envelope>';

            $data = CurlModel::execSOAP([
              'xmlPostString' => $xmlPostString,
              'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
              'soapAction'    => 'http://www.srci.fr/SendDossier',
              'Cookie'        => $sessionId['cookie']
            ])['response'];
            $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->SendDossierResponse->SendDossierResult;

            $attachmentToFreeze[$collId][$resId] = (string)$response;
        }

        // Send main document if in signature book
        $mainDocumentIntegration = json_decode($mainResource['integrations'], true);
        $externalId              = json_decode($mainResource['external_id'], true);
        if ($mainDocumentIntegration['inSignatureBook'] && empty($externalId['signatureBookId'])) {
            $resId  = $mainResource['res_id'];
            $collId = 'letterbox_coll';

            $adrInfo       = ConvertPdfController::getConvertedPdfById(['resId' => $resId, 'collId' => $collId]);
            $docserverInfo = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
            $filePath      = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];

            $docserverType = DocserverTypeModel::getById(['id' => $docserverInfo['docserver_type_id'], 'select' => ['fingerprint_mode']]);
            $fingerprint = StoreController::getFingerPrint(['filePath' => $filePath, 'mode' => $docserverType['fingerprint_mode']]);
            if ($adrInfo['fingerprint'] != $fingerprint) {
                return ['error' => 'Fingerprints do not match'];
            }

            $encodedZipFile = IxbusController::createZip(['filepath' => $filePath, 'filename' => $adrInfo['filename'], 'res_id_master' => $resId]);

            $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <SendDossier xmlns="http://www.srci.fr">
                  <ContenuDocumentZip>'. $encodedZipFile .'</ContenuDocumentZip>
                  <NomDocumentPrincipal>'. $adrInfo['filename'] . '</NomDocumentPrincipal>
                  <NomDossier>'. $mainResource['subject'] .'</NomDossier>
                  <NomModele>'. $aArgs['messageModel'] .'</NomModele>
                  <NomNature>'. $aArgs['classeurName'] .'</NomNature>
                  <DateLimite>'.$processLimitDate.'</DateLimite>
                  <LoginResponsable>'. $userInfo->NomUtilisateur .'</LoginResponsable>
                  <Confidentiel>false</Confidentiel>
                  <DocumentModifiable>true</DocumentModifiable>
                  <AnnexesSignables>false</AnnexesSignables>
                  <SignatureManuscrite>'.$signature.'</SignatureManuscrite>
                </SendDossier>
              </soap:Body>
            </soap:Envelope>';

            $data = CurlModel::execSOAP([
              'xmlPostString' => $xmlPostString,
              'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
              'soapAction'    => 'http://www.srci.fr/SendDossier',
              'Cookie'        => $sessionId['cookie']
            ])['response'];
            $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->SendDossierResponse->SendDossierResult;

            $attachmentToFreeze[$collId][$resId] = (string)$response;
        }
      
        return ['sended' => $attachmentToFreeze];
    }

    public static function createZip($aArgs)
    {
        $zip = new \ZipArchive();

        $pathInfo    = pathinfo($aArgs['filepath'], PATHINFO_FILENAME);
        $tmpPath     = CoreConfigModel::getTmpPath();
        $zipFilename = $tmpPath . $pathInfo."_".rand().".zip";

        if ($zip->open($zipFilename, \ZipArchive::CREATE) === true) {
            $zip->addFile($aArgs['filepath'], $aArgs['filename']);
            
            $adrInfo             = ConvertPdfController::getConvertedPdfById(['resId' => $aArgs['res_id_master'], 'collId' => 'letterbox_coll']);
            if (!empty($adrInfo['docserver_id'])) {
                $docserverInfo       = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
                $arrivedMailfilePath = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];
                $zip->addFile($arrivedMailfilePath, 'courrier_arrivee.pdf');
            }

            $zip->close();

            $fileContent = file_get_contents($zipFilename);
            $base64 =  base64_encode($fileContent);
            return $base64;
        } else {
            echo 'Impossible de créer l\'archive;';
        }
    }

    public static function retrieveSignedMails($aArgs)
    {
        $sessionId = IxbusController::createSession($aArgs['config']);
        if (!empty($sessionId['error'])) {
            return ['error' => $sessionId['error']];
        }
        $version = $aArgs['version'];
        foreach ($aArgs['idsToRetrieve'][$version] as $resId => $value) {
            $etatDossier = IxbusController::getEtatDossier(['config' => $aArgs['config'], 'sessionId' => $sessionId['cookie'], 'dossier_id' => $value['external_id']]);

            // Refused
            if ((string)$etatDossier == $aArgs['config']['data']['ixbusIdEtatRefused']) {
                $aArgs['idsToRetrieve'][$version][$resId]['status'] = 'refused';
                $notes = IxbusController::getDossier(['config' => $aArgs['config'], 'sessionId' => $sessionId['cookie'], 'dossier_id' => $value['external_id']]);
                $aArgs['idsToRetrieve'][$version][$resId]['noteContent'] = (string)$notes->MotifRefus;
            // Validated
            } elseif ((string)$etatDossier == $aArgs['config']['data']['ixbusIdEtatValidated']) {
                $aArgs['idsToRetrieve'][$version][$resId]['status'] = 'validated';
                $signedDocument = IxbusController::getAnnexes(['config' => $aArgs['config'], 'sessionId' => $sessionId['cookie'], 'dossier_id' => $value['external_id']]);
                $aArgs['idsToRetrieve'][$version][$resId]['format'] = 'pdf'; // format du fichier récupéré
                $aArgs['idsToRetrieve'][$version][$resId]['encodedFile'] = (string)$signedDocument->Fichier;

                $notes = IxbusController::getAnnotations(['config' => $aArgs['config'], 'sessionId' => $sessionId['cookie'], 'dossier_id' => $value['external_id']]);
                $aArgs['idsToRetrieve'][$version][$resId]['noteContent'] = (string)$notes->Annotation->Texte;
            } else {
                unset($aArgs['idsToRetrieve'][$version][$resId]);
            }
        }

        // retourner seulement les mails récupérés (validés ou signés)
        return $aArgs['idsToRetrieve'];
    }

    public static function getEtatDossier($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetEtatDossier xmlns="http://www.srci.fr">
              <DossierID>'.$aArgs['dossier_id'].'</DossierID>
            </GetEtatDossier>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetEtatDossier',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetEtatDossierResponse->GetEtatDossierResult;

        return $response;
    }

    public static function getAnnotations($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetAnnotations xmlns="http://www.srci.fr">
              <messageID>'.$aArgs['dossier_id'].'</messageID>
            </GetAnnotations>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetAnnotations',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetAnnotationsResponse->GetAnnotationsResult;

        return $response;
    }

    public static function getDossier($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetDossier xmlns="http://www.srci.fr">
              <messageID>'.$aArgs['dossier_id'].'</messageID>
            </GetDossier>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetDossier',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetDossierResponse->GetDossierResult;

        return $response;
    }

    public static function getAnnexes($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetAnnexe xmlns="http://www.srci.fr">
              <messageID>'.$aArgs['dossier_id'].'</messageID>
              <extension>pdf</extension>
            </GetAnnexe>
          </soap:Body>
        </soap:Envelope>';

        $data = CurlModel::execSOAP([
          'xmlPostString' => $xmlPostString,
          'url'           => $aArgs['config']['data']['url'] . '/parapheurws/service.asmx',
          'soapAction'    => 'http://www.srci.fr/GetAnnexe',
          'Cookie'        => $aArgs['sessionId']
        ])['response'];
        $response = $data->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->GetAnnexeResponse->GetAnnexeResult;

        return $response;
    }
}
