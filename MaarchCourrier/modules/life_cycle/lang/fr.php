<?php

/*
 *
 *   Copyright 2011 Maarch
 *
 *   This file is part of Maarch Framework.
 *
 *   Maarch Framework is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Maarch Framework is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
 */

/*************BATCH*************************************/
if (!defined("_PI_COMMENT_ROOT")) {
    define("_PI_COMMENT_ROOT", "Packaging information: Utilisation du paquet d archivage (Archival Information package)");
}
if (!defined("_PI_COMMENT_FINGERPRINT")) {
    define("_PI_COMMENT_FINGERPRINT", "Empreinte associée au fichier CI");
}
if (!defined("_PI_COMMENT_AIU")) {
    define("_PI_COMMENT_AIU", "Nombre de ressources présentes dans l AIP");
}
if (!defined("_PI_COMMENT_CONTENT")) {
    define("_PI_COMMENT_CONTENT", "Ressources digitales dans leur format natif (nom + extension de fichier)");
}
if (!defined("_PI_COMMENT_PDI")) {
    define("_PI_COMMENT_PDI", "Preservation Description Information: Catalogue des descripteurs de Provenance, Référence, Contexte, Intégrité et Droits d accès des ressources présentes dans <CONTENT_FILE>. Pour des raisons pratiques l historique de traitement est stocké à part dans <HISTORY_FILE>. Voir pdi.xsd pour la structure commentée");
}
if (!defined("_HISTORY_COMMENT_ROOT")) {
    define("_HISTORY_COMMENT_ROOT", "Preservation Description Information - Historique : Liste des évènements sur la ressource, chaque jeu d évènement étant identifié par son nom de fichier dans <CONTENT_FILE>. Trié par date ascendante");
}
if (!defined("_PDI_COMMENT_ROOT")) {
    define("_PDI_COMMENT_ROOT", "Preservation Description Information : Liste des qualificateurs de ressource, rangés par catégorie : Provenance, Référence, Contexte, Intégrité et Droits d accès. Il y a une description par ressource, chaque ressource étant identifiée par son nom de fichier dans <CONTENT_FILE>");
}
if (!defined("_PDI_COMMENT_HISTORY")) {
    define("_PDI_COMMENT_HISTORY", "Preservation Description Information - Historique : Liste des évènements sur la ressource, chaque jeu d évènement étant identifié par son nom de fichier dans <CONTENT_FILE>. Trié par date ascendante.");
}

/*************OTHER*************************************/
if (!defined("_DOCS")) {
    define("_DOCS", "Courriers");
}
