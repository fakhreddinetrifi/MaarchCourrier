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
    define("_PI_COMMENT_ROOT", "Packaging information:  Archiving package utilisation (Archival Information package)");
}
if (!defined("_PI_COMMENT_FINGERPRINT")) {
    define("_PI_COMMENT_FINGERPRINT", "Print associated to the CI/ IC file");
}
if (!defined("_PI_COMMENT_AIU")) {
    define("_PI_COMMENT_AIU", "Number of present resources in the AIP");
}
if (!defined("_PI_COMMENT_CONTENT")) {
    define("_PI_COMMENT_CONTENT", "Digital resources in their native format (name + file extension)");
}
if (!defined("_PI_COMMENT_PDI")) {
    define("_PI_COMMENT_PDI", "Preservation Description Information: Descriptors catalogue of Source, Reference, Context, Integrity and Access Rights of present resources in <CONTENT_FILE>. For practical reasons, the processing history is stored apart in. See pdi.xsd for the commented structure");
}
if (!defined("_HISTORY_COMMENT_ROOT")) {
    define("_HISTORY_COMMENT_ROOT", "Preservation Description Information - History : Events list on the resource, each event game is identified by its file name in <CONTENT_FILE>. Chronologically classified");
}
if (!defined("_PDI_COMMENT_ROOT")) {
    define("_PDI_COMMENT_ROOT", "Preservation Description Information : Descriptors list of resource, classified by category : Source, Reference, Context, Integrity et access rights. There is a description by resource, each resource is identified by its file name in <CONTENT_FILE>");
}
if (!defined("_PDI_COMMENT_HISTORY")) {
    define("_PDI_COMMENT_HISTORY", "Preservation Description Information - History : Events list on the resource, each event game is identified by its folder name in <CONTENT_FILE>. Chronologically classified.");
}

/*************OTHER*************************************/
if (!defined("_DOCS")) {
    define("_DOCS", "Mails");
}
