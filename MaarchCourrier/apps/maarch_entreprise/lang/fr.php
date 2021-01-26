<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

*
* @brief   fr
*
* @author  dev <dev@maarch.org>
* @ingroup lang
*/

if (!defined('_ID_TO_DISPLAY')) { // value res_id || chrono_number
    define('_ID_TO_DISPLAY', 'chrono_number');
}
/************** Administration **************/
if (!defined('_ADDED')) {
    define('_ADDED', 'ajouté');
}
if (!defined('_UPDATED')) {
    define('_UPDATED', 'modifié');
}
if (!defined('_DELETED')) {
    define('_DELETED', 'supprimé');
}
if (!defined('_PLEASE_CHOOSE_AN_ATTACHMENT')) {
    define('_PLEASE_CHOOSE_AN_ATTACHMENT', 'Ajoutez une pièce jointe svp.');
}
if (!defined('_ADD_ATTACHMENT_TO_SEND_TO_CONTACT')) {
    define('_ADD_ATTACHMENT_TO_SEND_TO_CONTACT', "Veuillez ajouter une pièce jointe avant d'envoyer ce courrier au contact");
}
if (!defined('_PROCESSING_MODE')) {
    define('_PROCESSING_MODE', 'Mode de traitement');
}
if (!defined('_ADMIN_USERS')) {
    define('_ADMIN_USERS', 'Utilisateurs');
}
if (!defined('_ADMIN_GROUPS')) {
    define('_ADMIN_GROUPS', "Groupes d'utilisateurs");
}
if (!defined('_VIEW_HISTORY')) {
    define('_VIEW_HISTORY', 'Historique');
}
if (!defined('_VIEW_HISTORY_BATCH')) {
    define('_VIEW_HISTORY_BATCH', 'Historique des batchs');
}
if (!defined('_VIEW_HISTORY_BATCH_DESC')) {
    define('_VIEW_HISTORY_BATCH_DESC', "Consulter l'historique des batchs");
}
if (!defined('_ADMIN_SERVICE')) {
    define('_ADMIN_SERVICE', "Service d'administration");
}
if (!defined('_XML_PARAM_SERVICE_DESC')) {
    define('_XML_PARAM_SERVICE_DESC', 'Visualisation configuration XML des services');
}
if (!defined('_XML_PARAM_SERVICE')) {
    define('_XML_PARAM_SERVICE', 'Visualisation configuration XML des services');
}
if (!defined('_MODULES_SERVICES')) {
    define('_MODULES_SERVICES', 'Services définis par les modules');
}
if (!defined('_APPS_SERVICES')) {
    define('_APPS_SERVICES', "Services définis par l'application");
}
if (!defined('_ADMIN_STATUS_DESC')) {
    define('_ADMIN_STATUS_DESC', 'Créer ou modifier des statuts.');
}
if (!defined('_NO_RIGHTS_ON')) {
    define('_NO_RIGHTS_ON', 'Aucun droit sur');
}
if (!defined('_NO_LABEL_FOUND')) {
    define('_NO_LABEL_FOUND', 'Aucun label trouvé pour ce service');
}

if (!defined('_VERSION_BASE_AND_XML_BASEVERSION_NOT_MATCH')) {
    define('_VERSION_BASE_AND_XML_BASEVERSION_NOT_MATCH', 'Attention: Le modèle de données de Maarch doit être mis à jour...');
}

/*********************** communs ***********************************/
if (!defined('_MODE')) {
    define('_MODE', 'Mode');
}
/************** Listes **************/
if (!defined('_GO_TO_PAGE')) {
    define('_GO_TO_PAGE', 'Aller à la page');
}
if (!defined('_NEXT')) {
    define('_NEXT', 'Suivante');
}
if (!defined('_NEXT_PAGE')) {
    define('_NEXT_PAGE', 'Suivant');
}
if (!defined('_PREVIOUS')) {
    define('_PREVIOUS', 'Précédente');
}
if (!defined('_PREVIOUS_PAGE')) {
    define('_PREVIOUS_PAGE', 'Précédent');
}
if (!defined('_ALPHABETICAL_LIST')) {
    define('_ALPHABETICAL_LIST', 'Liste alphabétique');
}
if (!defined('_ASC_SORT')) {
    define('_ASC_SORT', 'Tri ascendant');
}
if (!defined('_DESC_SORT')) {
    define('_DESC_SORT', 'Tri descendant');
}
if (!defined('_ACCESS_LIST_STANDARD')) {
    define('_ACCESS_LIST_STANDARD', 'Affichage des listes simples');
}
if (!defined('_ACCESS_LIST_EXTEND')) {
    define('_ACCESS_LIST_EXTEND', 'Affichage des listes étendues');
}
if (!defined('_DISPLAY')) {
    define('_DISPLAY', 'Affichage');
}
if (!defined('_NO_RECORD')) {
    define('_NO_RECORD', 'Aucun élément');
}
if (!defined('_RECORD')) {
    define('_RECORD', 'élément(s)');
}

/************** Actions **************/
if (!defined('_DELETE')) {
    define('_DELETE', 'Supprimer');
}
if (!defined('_ADD')) {
    define('_ADD', 'Ajouter');
}
if (!defined('_REMOVE')) {
    define('_REMOVE', 'Enlever');
}
if (!defined('_MODIFY')) {
    define('_MODIFY', 'Modifier');
}
if (!defined('_SUSPEND')) {
    define('_SUSPEND', 'Suspendre');
}
if (!defined('_AUTHORIZE')) {
    define('_AUTHORIZE', 'Autoriser');
}
if (!defined('_CHOOSE')) {
    define('_CHOOSE', 'Choisir');
}
if (!defined('_SEND')) {
    define('_SEND', 'Envoyer');
}
if (!defined('_SEARCH')) {
    define('_SEARCH', 'Rechercher');
}
if (!defined('_RESET')) {
    define('_RESET', 'Réinitialiser');
}
if (!defined('_VALIDATE')) {
    define('_VALIDATE', 'Valider');
}
if (!defined('_CANCEL')) {
    define('_CANCEL', 'Annuler');
}
if (!defined('_ADDITION')) {
    define('_ADDITION', 'Ajout');
}
if (!defined('_MODIFICATION')) {
    define('_MODIFICATION', 'Modification');
}
if (!defined('_DIFFUSION')) {
    define('_DIFFUSION', 'Diffusion');
}
if (!defined('_DELETION')) {
    define('_DELETION', 'Suppression');
}
if (!defined('_SUSPENSION')) {
    define('_SUSPENSION', 'Suspension');
}
if (!defined('_VALIDATION')) {
    define('_VALIDATION', 'Validation');
}
if (!defined('_REDIRECTION')) {
    define('_REDIRECTION', 'Rediriger');
}
if (!defined('_REDIRECTION_DESC')) {
    define('_REDIRECTION_DESC', 'Ouvre une modal permettant de choisir un nouvel utilisateur traitant OU de redéfinir l\'entité traitante avec une nouvelle liste de diffusion.');
}
if (!defined('_DUPLICATION')) {
    define('_DUPLICATION', 'Duplication');
}
if (!defined('_PROPOSITION')) {
    define('_PROPOSITION', 'Proposition');
}
if (!defined('_ERR')) {
    define('_ERR', 'Erreur');
}
if (!defined('_CLOSE')) {
    define('_CLOSE', 'Fermer');
}
if (!defined('_CLOSE_WINDOW')) {
    define('_CLOSE_WINDOW', 'Fermer la fenêtre');
}
if (!defined('_DIFFUSE')) {
    define('_DIFFUSE', 'Diffuser');
}
if (!defined('_DOWN')) {
    define('_DOWN', 'Descendre');
}
if (!defined('_UP')) {
    define('_UP', 'Monter');
}
if (!defined('_REDIRECT')) {
    define('_REDIRECT', 'Rediriger');
}
if (!defined('_DELETED')) {
    define('_DELETED', 'Supprimé');
}
if (!defined('_CONTINUE')) {
    define('_CONTINUE', 'Continuer');
}
if (!defined('_VIEW')) {
    define('_VIEW', 'Visualisation');
}
if (!defined('_CHOOSE_ACTION')) {
    define('_CHOOSE_ACTION', 'Choisissez une action');
}
if (!defined('_ACTIONS')) {
    define('_ACTIONS', 'Action(s)');
}
if (!defined('_ACTION_PAGE')) {
    define('_ACTION_PAGE', "Page de résultat de l'action");
}
if (!defined('_INFOS_ACTIONS')) {
    define('_INFOS_ACTIONS', 'Vous devez choisir au moins un statut et / ou un script.');
}
if (!defined('_SAVE_CONFIRM')) {
    define('_SAVE_CONFIRM', 'Confirmation Enregistrement');
}
if (!defined('_SAVED_ALREADY_EXIST')) {
    define('_SAVED_ALREADY_EXIST', 'Enregistrement déjà existant');
}
if (!defined('_OK_FOR_CONFIRM')) {
    define('_OK_FOR_CONFIRM', "Confirmez-vous l\'enregistrement? ");
}

/************** Intitulés formulaires et listes **************/
if (!defined('_ID')) {
    define('_ID', 'Identifiant');
}
if (!defined('_PASSWORD')) {
    define('_PASSWORD', 'Mot de passe');
}
if (!defined('_GROUP')) {
    define('_GROUP', 'Groupe');
}
if (!defined('_USER')) {
    define('_USER', 'Utilisateur');
}
if (!defined('_SENDER')) {
    define('_SENDER', 'Expéditeur');
}
if (!defined('_DESC')) {
    define('_DESC', 'Description');
}
if (!defined('_LASTNAME')) {
    define('_LASTNAME', 'Nom');
}
if (!defined('_THE_LASTNAME')) {
    define('_THE_LASTNAME', 'Le nom');
}
if (!defined('_THE_FIRSTNAME')) {
    define('_THE_FIRSTNAME', 'Le prénom');
}
if (!defined('_THE_ID')) {
    define('_THE_ID', "L'identifiant");
}
if (!defined('_FIRSTNAME')) {
    define('_FIRSTNAME', 'Prénom');
}
if (!defined('_INITIALS')) {
    define('_INITIALS', 'Initiales');
}
if (!defined('_STATUS')) {
    define('_STATUS', 'Statut');
}
if (!defined('_DEPARTMENT')) {
    define('_DEPARTMENT', 'Département');
}
if (!defined('_FUNCTION')) {
    define('_FUNCTION', 'Fonction');
}
if (!defined('_NUMBER')) {
    define('_NUMBER', 'Numero');
}
if (!defined('_PHONE_NUMBER')) {
    define('_PHONE_NUMBER', 'Numéro de téléphone');
}
if (!defined('_MAIL')) {
    define('_MAIL', 'Courrier');
}
if (!defined('_EMAIL')) {
    define('_EMAIL', 'Courriel');
}
if (!defined('_DOCTYPE')) {
    define('_DOCTYPE', 'Type de document');
}
if (!defined('_DOCTYPES_MAIL')) {
    define('_DOCTYPES_MAIL', 'Type(s) de courrier');
}
if (!defined('_TYPE')) {
    define('_TYPE', 'Type');
}
if (!defined('_URL')) {
    define('_URL', 'URL');
}
if (!defined('_NO_REPLACEMENT')) {
    define('_NO_REPLACEMENT', 'AUCUN REMPLACEMENT');
}
if (!defined('_SELECT_ALL')) {
    define('_SELECT_ALL', 'Sélectionner tout');
}
if (!defined('_DATE')) {
    define('_DATE', 'Date');
}
if (!defined('_ACTION')) {
    define('_ACTION', 'Action');
}
if (!defined('_COMMENTS')) {
    define('_COMMENTS', 'Commentaires');
}
if (!defined('_ENABLED')) {
    define('_ENABLED', 'Autorisé');
}
if (!defined('_DISABLED')) {
    define('_DISABLED', 'Suspendu');
}
if (!defined('_NOT_ENABLED')) {
    define('_NOT_ENABLED', 'Suspendu');
}
if (!defined('_RECIPIENT')) {
    define('_RECIPIENT', 'Destinataire');
}
if (!defined('_START')) {
    define('_START', 'Début');
}
if (!defined('_END')) {
    define('_END', 'Fin');
}
if (!defined('_NOT_VALID')) {
    define('_NOT_VALID', 'non valide');
}

if (!defined('_KEYWORD')) {
    define('_KEYWORD', 'Mot clé');
}

if (!defined('_NO_KEYWORD')) {
    define('_NO_KEYWORD', 'Aucun mot clé');
}

if (!defined('_INDEXING')) {
    define('_INDEXING', 'Indexation');
}

if (!defined('_QUALIFY')) {
    define('_QUALIFY', 'Qualification');
}

/************** Messages pop up **************/
if (!defined('_REALLY_SUSPEND')) {
    define('_REALLY_SUSPEND', 'Voulez-vous vraiment suspendre ');
}
if (!defined('_REALLY_AUTHORIZE')) {
    define('_REALLY_AUTHORIZE', 'Voulez-vous vraiment autoriser ');
}
if (!defined('_REALLY_DELETE')) {
    define('_REALLY_DELETE', 'Voulez-vous vraiment supprimer ');
}
if (!defined('_REALLY_CONTINUE')) {
    define('_REALLY_CONTINUE', 'Voulez-vous vraiment continuer ');
}
if (!defined('_DEFINITIVE_ACTION')) {
    define('_DEFINITIVE_ACTION', 'Cette action est définitive');
}
if (!defined('_AND')) {
    define('_AND', ' et ');
}

/************** Divers **************/
if (!defined('_YES')) {
    define('_YES', 'Oui');
}
if (!defined('_NO')) {
    define('_NO', 'Non');
}
if (!defined('_UNKNOWN')) {
    define('_UNKNOWN', 'Inconnu');
}
if (!defined('_SINCE')) {
    define('_SINCE', 'Depuis');
}
if (!defined('_FOR')) {
    define('_FOR', "Jusqu'à");
}
if (!defined('_HELLO')) {
    define('_HELLO', 'Bonjour');
}
if (!defined('_OBJECT')) {
    define('_OBJECT', 'Objet');
}
if (!defined('_BACK')) {
    define('_BACK', 'Retour');
}
if (!defined('_FORMAT')) {
    define('_FORMAT', 'Format');
}
if (!defined('_SIZE')) {
    define('_SIZE', 'Taille');
}
if (!defined('_DOC')) {
    define('_DOC', 'Document ');
}
if (!defined('_THE_DOC')) {
    define('_THE_DOC', 'Le document');
}
if (!defined('_BYTES')) {
    define('_BYTES', 'octets');
}
if (!defined('_OR')) {
    define('_OR', 'ou');
}
if (!defined('_NOT_AVAILABLE')) {
    define('_NOT_AVAILABLE', 'Indisponible');
}
if (!defined('_SELECTION')) {
    define('_SELECTION', 'Sélection');
}
if (!defined('_AND')) {
    define('_AND', ' et ');
}
if (!defined('_FILE')) {
    define('_FILE', 'Fichier');
}
if (!defined('_UNTIL')) {
    define('_UNTIL', 'au');
}
if (!defined('_ALL')) {
    define('_ALL', 'Tous');
}

//class functions
if (!defined('_SECOND')) {
    define('_SECOND', 'seconde');
}
if (!defined('_SECONDS')) {
    define('_SECONDS', 'secondes');
}
if (!defined('_PAGE_GENERATED_IN')) {
    define('_PAGE_GENERATED_IN', 'Page générée en');
}
if (!defined('_IS_EMPTY')) {
    define('_IS_EMPTY', 'est vide');
}
if (!defined('_MUST_MAKE_AT_LEAST')) {
    define('_MUST_MAKE_AT_LEAST', 'doit faire au minimum');
}
if (!defined('_CHARACTER')) {
    define('_CHARACTER', 'caractère');
}
if (!defined('_CHARACTERS')) {
    define('_CHARACTERS', 'caractères');
}
if (!defined('MUST_BE_LESS_THAN')) {
    define('MUST_BE_LESS_THAN', 'ne doit pas faire plus de');
}
if (!defined('_WRONG_FORMAT')) {
    define('_WRONG_FORMAT', "n'est pas dans le bon format");
}
if (!defined('_WELCOME')) {
    define('_WELCOME', 'Bienvenue sur Maarch Courrier !');
}
if (!defined('_WELCOME_TITLE')) {
    define('_WELCOME_TITLE', 'Accueil');
}
if (!defined('_HELP')) {
    define('_HELP', 'Aide');
}
if (!defined('_SEARCH_ADV_SHORT')) {
    define('_SEARCH_ADV_SHORT', 'Recherche Avancée');
}
if (!defined('_SEARCH_SCOPE')) {
    define('_SEARCH_SCOPE', 'Portée de la recherche');
}
if (!defined('_SEARCH_SCOPE_HELP')) {
    define('_SEARCH_SCOPE_HELP', 'Le périmètre correspond aux courriers visibles par mon groupe ou dans mes bannettes');
}
if (!defined('_RESULTS')) {
    define('_RESULTS', 'Résultat(s)');
}
if (!defined('_USERS_LIST_SHORT')) {
    define('_USERS_LIST_SHORT', 'Liste utilisateurs');
}
if (!defined('_MODELS_LIST_SHORT')) {
    define('_MODELS_LIST_SHORT', 'Liste modèles');
}
if (!defined('_GROUPS_LIST_SHORT')) {
    define('_GROUPS_LIST_SHORT', 'Liste groupes');
}
if (!defined('_DEPARTMENTS_LIST_SHORT')) {
    define('_DEPARTMENTS_LIST_SHORT', 'Liste entités');
}
if (!defined('_BITMASK')) {
    define('_BITMASK', 'Paramètre Bitmask');
}
if (!defined('_DOCTYPES_LIST_SHORT')) {
    define('_DOCTYPES_LIST_SHORT', 'Liste types');
}
if (!defined('_BAD_MONTH_FORMAT')) {
    define('_BAD_MONTH_FORMAT', 'Le mois est incorrect');
}
if (!defined('_BAD_DAY_FORMAT')) {
    define('_BAD_DAY_FORMAT', 'Le jour est incorrect');
}
if (!defined('_BAD_YEAR_FORMAT')) {
    define('_BAD_YEAR_FORMAT', "L'année est incorrect");
}
if (!defined('_BAD_FEBRUARY')) {
    define('_BAD_FEBRUARY', 'Le mois de février ne peux contenir que 29 jours maximum');
}
if (!defined('_CHAPTER_SHORT')) {
    define('_CHAPTER_SHORT', 'Chap ');
}
if (!defined('_PROCESS_SHORT')) {
    define('_PROCESS_SHORT', 'Traitement');
}
if (!defined('_CARD')) {
    define('_CARD', 'Fiche');
}

/************************* First login ***********************************/
if (!defined('_LOGIN')) {
    define('_LOGIN', 'Connexion');
}
if (!defined('_RELOGIN')) {
    define('_RELOGIN', 'Reconnexion');
}
if (!defined('_RA_CODE')) {
    define('_RA_CODE', "Code d'accès complémentaire");
}

/*************************  index  page***********************************/
if (!defined('_LOGO_ALT')) {
    define('_LOGO_ALT', "Retour à la page d'accueil");
}
if (!defined('_LOGOUT')) {
    define('_LOGOUT', 'Déconnexion');
}
if (!defined('_MENU')) {
    define('_MENU', 'Menu');
}
if (!defined('_SUMMARY')) {
    define('_SUMMARY', 'Sommaire');
}
if (!defined('_MANAGE_REL_MODEL')) {
    define('_MANAGE_REL_MODEL', 'Gérer le modèle de relance');
}
if (!defined('_MANAGE_DOCTYPES')) {
    define('_MANAGE_DOCTYPES', 'Gérer les types de document');
}
if (!defined('_MANAGE_DOCTYPES_DESC')) {
    define('_MANAGE_DOCTYPES_DESC', 'Administrer les types de document. Les types de document sont rattachés à une collection documentaire. Pour chaque type, vous pouvez définir les index à saisir et ceux qui sont obligatoires.');
}
if (!defined('_VIEW_HISTORY2')) {
    define('_VIEW_HISTORY2', "Visualisation de l'historique");
}
if (!defined('_VIEW_HISTORY_BATCH2')) {
    define('_VIEW_HISTORY_BATCH2', "Visualisation de l'historique des batchs");
}
if (!defined('_WORDING')) {
    define('_WORDING', 'Libellé');
}
if (!defined('_COLLECTION')) {
    define('_COLLECTION', 'Collection');
}

/************************* Administration ***********************************/


/************** Groupes : Liste + Formulaire**************/

if (!defined('_GROUPS_LIST')) {
    define('_GROUPS_LIST', 'Liste des groupes');
}
if (!defined('_ADMIN_GROUP')) {
    define('_ADMIN_GROUP', "Groupe d'administration");
}
if (!defined('_ADD_GROUP')) {
    define('_ADD_GROUP', 'Ajouter un groupe');
}
if (!defined('_ALL_GROUPS')) {
    define('_ALL_GROUPS', 'Tous les groupes');
}
if (!defined('_GROUPS')) {
    define('_GROUPS', 'groupes');
}

if (!defined('_GROUP_ADDITION')) {
    define('_GROUP_ADDITION', "Ajout d'un groupe");
}
if (!defined('_GROUP_MODIFICATION')) {
    define('_GROUP_MODIFICATION', "Modification d'un groupe");
}
if (!defined('_SEE_GROUP_MEMBERS')) {
    define('_SEE_GROUP_MEMBERS', 'Voir la liste des utilisateurs de ce groupe');
}
if (!defined('_SEE_DOCSERVERS_')) {
    define('_SEE_DOCSERVERS_', 'Voir la liste des docservers de ce type');
}
if (!defined('_SEE_DOCSERVERS_LOCATION')) {
    define('_SEE_DOCSERVERS_LOCATION', 'Voir la liste des docservers de ce lieu');
}
if (!defined('_OTHER_RIGHTS')) {
    define('_OTHER_RIGHTS', 'Autres droits');
}
if (!defined('_MODIFY_GROUP')) {
    define('_MODIFY_GROUP', 'Accepter les changements');
}
if (!defined('_THE_GROUP')) {
    define('_THE_GROUP', 'Le groupe');
}
if (!defined('_HAS_NO_SECURITY')) {
    define('_HAS_NO_SECURITY', "n'a aucune sécurité définie");
}

if (!defined('_DEFINE_A_GRANT')) {
    define('_DEFINE_A_GRANT', 'Définissez au moins un accès');
}
if (!defined('_MANAGE_RIGHTS')) {
    define('_MANAGE_RIGHTS', 'Ce groupe a accès aux ressources suivantes');
}
if (!defined('_TABLE')) {
    define('_TABLE', 'Table');
}
if (!defined('_WHERE_CLAUSE')) {
    define('_WHERE_CLAUSE', 'Clause WHERE');
}
if (!defined('_INSERT')) {
    define('_INSERT', 'Insertion');
}
if (!defined('_UPDATE')) {
    define('_UPDATE', 'Mise à jour');
}
if (!defined('_REMOVE_ACCESS')) {
    define('_REMOVE_ACCESS', 'Supprimer accès');
}
if (!defined('_MODIFY_ACCESS')) {
    define('_MODIFY_ACCESS', 'Modifier accès');
}
if (!defined('_UPDATE_RIGHTS')) {
    define('_UPDATE_RIGHTS', 'Mise à jour des droits');
}
if (!defined('_ADD_GRANT')) {
    define('_ADD_GRANT', 'Ajouter accès');
}
if (!defined('_UP_GRANT')) {
    define('_UP_GRANT', 'Modifier accès');
}
if (!defined('_USERS_LIST_IN_GROUP')) {
    define('_USERS_LIST_IN_GROUP', 'Liste des utilisateurs du groupe');
}

if (!defined('_CHOOSE_GROUP_ADMIN')) {
    define('_CHOOSE_GROUP_ADMIN', 'Choisir un groupe');
}

/************** Utilisateurs : Liste + Formulaire**************/

if (!defined('_USERS_LIST')) {
    define('_USERS_LIST', 'Liste des utilisateurs');
}
if (!defined('_ADD_USER')) {
    define('_ADD_USER', 'Ajouter un utilisateur');
}
if (!defined('_ALL_USERS')) {
    define('_ALL_USERS', 'Tous les utilisateurs');
}
if (!defined('_USERS')) {
    define('_USERS', 'utilisateurs');
}
if (!defined('_USER_ADDITION')) {
    define('_USER_ADDITION', "Ajout d'un utilisateur");
}
if (!defined('_USER_MODIFICATION')) {
    define('_USER_MODIFICATION', "Modification d'un utilisateur");
}
if (!defined('_MODIFY_USER')) {
    define('_MODIFY_USER', "Modifier l'utilisateur");
}

if (!defined('_NOTES')) {
    define('_NOTES', 'Annotations');
}
if (!defined('_NOTE1')) {
    define('_NOTE1', 'Les champs obligatoires sont marqués par un astérisque rouge ');
}
if (!defined('_NOTE2')) {
    define('_NOTE2', 'Le groupe primaire est obligatoire');
}
if (!defined('_NOTE3')) {
    define('_NOTE3', 'Le premier groupe sélectionné sera le groupe primaire');
}
if (!defined('_USER_GROUPS_TITLE')) {
    define('_USER_GROUPS_TITLE', "L'utilisateur appartient aux groupes suivants");
}
if (!defined('_USER_ENTITIES_TITLE')) {
    define('_USER_ENTITIES_TITLE', "L'utilisateur appartient aux entités suivantes");
}
if (!defined('_DELETE_GROUPS')) {
    define('_DELETE_GROUPS', 'Supprimer le(s) groupe(s)');
}
if (!defined('_ADD_TO_GROUP')) {
    define('_ADD_TO_GROUP', 'Ajouter à un groupe');
}
if (!defined('_USER_BELONGS_NO_GROUP')) {
    define('_USER_BELONGS_NO_GROUP', "L'utilisateur n'appartient à aucun groupe");
}
if (!defined('_USER_BELONGS_NO_ENTITY')) {
    define('_USER_BELONGS_NO_ENTITY', "L'utilisateur n'appartient à aucune  entité");
}
if (!defined('_CHOOSE_ONE_GROUP')) {
    define('_CHOOSE_ONE_GROUP', 'Choisissez au moins un groupe');
}
if (!defined('_CHOOSE_GROUP')) {
    define('_CHOOSE_GROUP', 'Choisissez un groupe');
}
if (!defined('_ROLE')) {
    define('_ROLE', 'Rôle');
}

if (!defined('_USER_ACCESS_DEPARTMENT')) {
    define('_USER_ACCESS_DEPARTMENT', "L'utilisateur a accès aux entités suivantes");
}
if (!defined('_FIRST_PSW')) {
    define('_FIRST_PSW', 'Le premier mot de passe ');
}
if (!defined('_SECOND_PSW')) {
    define('_SECOND_PSW', 'Le deuxième mot de passe ');
}

if (!defined('_PASSWORD_MODIFICATION')) {
    define('_PASSWORD_MODIFICATION', 'Changement du mot de passe');
}
if (!defined('_PASSWORD_FOR_USER')) {
    define('_PASSWORD_FOR_USER', "Le mot de passe pour l'utilisateur");
}
if (!defined('_HAS_BEEN_RESET')) {
    define('_HAS_BEEN_RESET', 'a été réinitialisé');
}
if (!defined('_NEW_PASW_IS')) {
    define('_NEW_PASW_IS', 'Le nouveau mot de passe est ');
}
if (!defined('_DURING_NEXT_CONNEXION')) {
    define('_DURING_NEXT_CONNEXION', 'Lors de la prochaine connexion');
}
if (!defined('_MUST_CHANGE_PSW')) {
    define('_MUST_CHANGE_PSW', 'doit modifier son mot de passe');
}

if (!defined('_NEW_PASSWORD_USER')) {
    define('_NEW_PASSWORD_USER', "Réinitialisation du mot de passe de l'utilisateur");
}
if (!defined('_PASSWORD_NOT_CHANGED')) {
    define('_PASSWORD_NOT_CHANGED', 'Problème lors du changement de mot de passe');
}
if (!defined('_ALREADY_CREATED_AND_DELETED')) {
    define('_ALREADY_CREATED_AND_DELETED', "L'utilisateur demandé a été supprimé. Cliquer sur réactiver en haut à gauche pour le rajouter");
}
if (!defined('_REACTIVATE')) {
    define('_REACTIVATE', 'Réactiver');
}
/************** Types de document : Liste + Formulaire**************/

if (!defined('_DOCTYPES_LIST')) {
    define('_DOCTYPES_LIST', 'Liste des types de document');
}
if (!defined('_TYPES')) {
    define('_TYPES', 'types');
}
if (!defined('_MORE_THAN_ONE')) {
    define('_MORE_THAN_ONE', 'Pièce itérative');
}
if (!defined('_START_DATE')) {
    define('_START_DATE', 'Date de début');
}
if (!defined('_END_DATE')) {
    define('_END_DATE', 'Date de fin');
}
if (!defined('_FIELD')) {
    define('_FIELD', 'Champ');
}
if (!defined('_USED')) {
    define('_USED', 'Utilisé');
}
if (!defined('_MANDATORY')) {
    define('_MANDATORY', 'Obligatoire');
}
if (!defined('_ITERATIVE')) {
    define('_ITERATIVE', 'Itératif');
}
if (!defined('_NATURE_FIELD')) {
    define('_NATURE_FIELD', 'Nature champ');
}
if (!defined('_TYPE_FIELD')) {
    define('_TYPE_FIELD', 'Type champ');
}
if (!defined('_DB_COLUMN')) {
    define('_DB_COLUMN', 'Colonne BDD');
}
if (!defined('_FIELD_VALUES')) {
    define('_FIELD_VALUES', 'Valeurs');
}

/************** structures : Liste + Formulaire**************/

if (!defined('_STRUCTURE')) {
    define('_STRUCTURE', 'Chemise');
}
if (!defined('_FONT_COLOR')) {
    define('_FONT_COLOR', 'Couleur de la police');
}
if (!defined('_FONT_SIZE')) {
    define('_FONT_SIZE', 'Taille de la police');
}
if (!defined('_CSS_STYLE')) {
    define('_CSS_STYLE', 'Style');
}
if (!defined('_CHOOSE_STYLE')) {
    define('_CHOOSE_STYLE', 'Choisissez un style');
}

/********************** colors style ***************************/
if (!defined('_BLACK')) {
    define('_BLACK', 'Noir');
}
if (!defined('_BEIGE')) {
    define('_BEIGE', 'Beige');
}
if (!defined('_BLUE')) {
    define('_BLUE', 'Bleu');
}
if (!defined('_BLUE_BOLD')) {
    define('_BLUE_BOLD', 'Bleu (gras)');
}
if (!defined('_GREY')) {
    define('_GREY', 'Gris');
}
if (!defined('_YELLOW')) {
    define('_YELLOW', 'Jaune');
}
if (!defined('_BROWN')) {
    define('_BROWN', 'Marron');
}
if (!defined('_BLACK_BOLD')) {
    define('_BLACK_BOLD', 'Noir (gras)');
}
if (!defined('_ORANGE')) {
    define('_ORANGE', 'Orange');
}
if (!defined('_ORANGE_BOLD')) {
    define('_ORANGE_BOLD', 'Orange (gras)');
}
if (!defined('_PINK')) {
    define('_PINK', 'Rose');
}
if (!defined('_RED')) {
    define('_RED', 'Rouge');
}
if (!defined('_GREEN')) {
    define('_GREEN', 'Vert');
}
if (!defined('_PURPLE')) {
    define('_PURPLE', 'Violet');
}

/************** sous-dossiers : Liste + Formulaire**************/
if (!defined('_SUBFOLDER')) {
    define('_SUBFOLDER', 'sous-chemise');
}

if (!defined('_ATTACH_STRUCTURE')) {
    define('_ATTACH_STRUCTURE', 'Rattacher à une chemise');
}
if (!defined('_CHOOSE_STRUCTURE')) {
    define('_CHOOSE_STRUCTURE', 'Choissisez une chemise');
}

if (!defined('_DEL_SUBFOLDER')) {
    define('_DEL_SUBFOLDER', 'Suppression de la sous-chemise');
}
if (!defined('_SUBFOLDER_DELETED')) {
    define('_SUBFOLDER_DELETED', 'Sous-chemise supprimée');
}

/************** Status **************/

if (!defined('_STATUS_LIST')) {
    define('_STATUS_LIST', 'Liste des statuts');
}
if (!defined('_ADD_STATUS')) {
    define('_ADD_STATUS', 'Ajouter nouveau statut');
}
if (!defined('_ALL_STATUS')) {
    define('_ALL_STATUS', 'Tous les statuts');
}
if (!defined('_STATUS_PLUR')) {
    define('_STATUS_PLUR', 'Statut(s)');
}
if (!defined('_STATUS_SING')) {
    define('_STATUS_SING', 'statut');
}

if (!defined('_TO_PROCESS')) {
    define('_TO_PROCESS', 'A traiter');
}
if (!defined('_IN_PROGRESS')) {
    define('_IN_PROGRESS', 'En cours');
}
if (!defined('_FIRST_WARNING')) {
    define('_FIRST_WARNING', '1ere Relance');
}
if (!defined('_SECOND_WARNING')) {
    define('_SECOND_WARNING', '2e Relance');
}
if (!defined('_CLOSED')) {
    define('_CLOSED', 'Clos');
}
if (!defined('_NEW')) {
    define('_NEW', 'Nouveaux');
}
if (!defined('_NEW_ITEM')) {
    define('_NEW_ITEM', 'Nouveau');
}
if (!defined('_LATE')) {
    define('_LATE', 'En retard');
}

if (!defined('_STATUS_MODIFIED')) {
    define('_STATUS_MODIFIED', "Modification d'un statut");
}
if (!defined('_IS_SYSTEM')) {
    define('_IS_SYSTEM', 'Système');
}
if (!defined('_ADMIN_STATUS')) {
    define('_ADMIN_STATUS', 'Statuts');
}

/************* Actions **************/

if (!defined('_ACTION_LIST')) {
    define('_ACTION_LIST', 'Liste des actions');
}

if (!defined('_ADMIN_ACTIONS')) {
    define('_ADMIN_ACTIONS', 'Actions');
}
// if (!defined('_KEYWORD_REDIRECT_DESC')) {
//     define('_KEYWORD_REDIRECT_DESC', "Permet de définir les options disponibles sur l'action depuis la bannette, notamment les entités et / ou les utilisateurs disponibles pour la redirection.");
// }
// if (!defined('_KEYWORD_INDEXING_DESC')) {
//     define('_KEYWORD_INDEXING_DESC', "Permet de définir les options disponibles sur l'action depuis la bannette, notamment les entités traitant disponibles et le/les statut(s) potentiels pour un enregistrement / modification de document.");
// }

/************** Historique**************/
if (!defined('_HISTORY_TITLE')) {
    define('_HISTORY_TITLE', 'Historique des événements');
}
if (!defined('_HISTORY_BATCH_TITLE')) {
    define('_HISTORY_BATCH_TITLE', 'Historique des événements des batchs');
}
if (!defined('_BATCH_NAME')) {
    define('_BATCH_NAME', 'Nom batch');
}
if (!defined('_CHOOSE_BATCH')) {
    define('_CHOOSE_BATCH', 'Choisir batch');
}
if (!defined('_BATCH_ID')) {
    define('_BATCH_ID', 'Id batch');
}
if (!defined('_TOTAL_PROCESSED')) {
    define('_TOTAL_PROCESSED', 'Documents traités');
}
if (!defined('_TOTAL_ERRORS')) {
    define('_TOTAL_ERRORS', 'Documents en erreurs');
}
if (!defined('_ONLY_ERRORS')) {
    define('_ONLY_ERRORS', 'Seulement avec erreurs');
}
if (!defined('_INFOS')) {
    define('_INFOS', 'Infos');
}

/************** Admin de l'architecture  (plan de classement) **************/
if (!defined('_ARCHITECTURE')) {
    define('_ARCHITECTURE', 'Plan de classement');
}

/************************* Messages d'erreurs ***********************************/
if (!defined('_MORE_INFOS')) {
    define('_MORE_INFOS', "Pour plus d'informations, contactez votre administrateur ");
}
if (!defined('_ALREADY_EXISTS')) {
    define('_ALREADY_EXISTS', 'existe déjà');
}
if (!defined('_DOCSERVER_ERROR')) {
    define('_DOCSERVER_ERROR', 'Erreur avec le docserver');
}
if (!defined('_NO_AVAILABLE_DOCSERVER')) {
    define('_NO_AVAILABLE_DOCSERVER', 'Pas de docserver disponible');
}
if (!defined('_NOT_ENOUGH_DISK_SPACE')) {
    define('_NOT_ENOUGH_DISK_SPACE', 'Pas assez de place disponible sur le serveur');
}

// class usergroups
if (!defined('_NO_GROUP')) {
    define('_NO_GROUP', "Le groupe n'existe pas !");
}
if (!defined('_NO_SECURITY_AND_NO_SERVICES')) {
    define('_NO_SECURITY_AND_NO_SERVICES', "n'a aucune sécurité définie et aucun service");
}
if (!defined('_GROUP_ADDED')) {
    define('_GROUP_ADDED', 'Nouveau groupe ajouté');
}
if (!defined('_SYNTAX_ERROR_WHERE_CLAUSE')) {
    define('_SYNTAX_ERROR_WHERE_CLAUSE', 'erreur de syntaxe dans la clause where');
}
if (!defined('_GROUP_UPDATED')) {
    define('_GROUP_UPDATED', 'Groupe modifié');
}
if (!defined('_AUTORIZED_GROUP')) {
    define('_AUTORIZED_GROUP', 'Groupe autorisé');
}
if (!defined('_SUSPENDED_GROUP')) {
    define('_SUSPENDED_GROUP', 'Groupe suspendu');
}
if (!defined('_DELETED_GROUP')) {
    define('_DELETED_GROUP', 'Groupe supprimé');
}
if (!defined('_GROUP_UPDATE')) {
    define('_GROUP_UPDATE', 'Modification du groupe;');
}
if (!defined('_GROUP_AUTORIZATION')) {
    define('_GROUP_AUTORIZATION', 'Autorisation du groupe');
}
if (!defined('_GROUP_SUSPENSION')) {
    define('_GROUP_SUSPENSION', 'Suspension du groupe');
}
if (!defined('_GROUP_DELETION')) {
    define('_GROUP_DELETION', 'Suppression du groupe');
}
if (!defined('_GROUP_DESC')) {
    define('_GROUP_DESC', 'La description du groupe ');
}
if (!defined('_GROUP_ID')) {
    define('_GROUP_ID', "L'identifiant du groupe");
}
if (!defined('_EXPORT_RIGHT')) {
    define('_EXPORT_RIGHT', "Droits d'export");
}

//class users
if (!defined('_USER_NO_GROUP')) {
    define('_USER_NO_GROUP', "Vous n'appartenez à aucun groupe");
}
if (!defined('_SUSPENDED_ACCOUNT')) {
    define('_SUSPENDED_ACCOUNT', 'Votre compte utilisateur a été suspendu');
}
if (!defined('_BAD_LOGIN_OR_PSW')) {
    define('_BAD_LOGIN_OR_PSW', "Mauvais nom d'utilisateur ou mauvais mot de passe");
}
define('_ACCOUNT_LOCKED_FOR', 'Nombre de tentatives de connexion dépassée. Votre compte est verrouillé pendant');
define('_ACCOUNT_LOCKED_UNTIL', 'Nombre de tentatives de connexion dépassée. Compte verrouillé jusqu\'au');
if (!defined('_EMPTY_PSW')) {
    define('_EMPTY_PSW', 'Mot de passe actuel vide');
}
if (!defined('_AUTORIZED_USER')) {
    define('_AUTORIZED_USER', 'Utilisateur autorisé');
}
if (!defined('_SUSPENDED_USER')) {
    define('_SUSPENDED_USER', 'Utilisateur suspendu');
}
if (!defined('_DELETED_USER')) {
    define('_DELETED_USER', 'Utilisateur supprimé');
}
if (!defined('_USER_DELETION')) {
    define('_USER_DELETION', "Suppression de l'utilisateur");
}
if (!defined('_USER_AUTORIZATION')) {
    define('_USER_AUTORIZATION', "Autorisation de l'utilisateur");
}
if (!defined('_USER_SUSPENSION')) {
    define('_USER_SUSPENSION', "Suspension de l'utilisateur");
}
if (!defined('_USER_UPDATED')) {
    define('_USER_UPDATED', 'Utilisateur modifié');
}
if (!defined('_USER_UPDATE')) {
    define('_USER_UPDATE', "Modification d'un utilisateur");
}
if (!defined('_USER_ADDED')) {
    define('_USER_ADDED', 'Nouvel utilisateur ajouté');
}
if (!defined('_THE_USER')) {
    define('_THE_USER', "L'utilisateur ");
}
if (!defined('_USER_ID')) {
    define('_USER_ID', "L'identifiant de l'utilisateur");
}
if (!defined('_MY_INFO')) {
    define('_MY_INFO', 'Mon Profil');
}

//class types
if (!defined('_UNKNOWN_PARAM')) {
    define('_UNKNOWN_PARAM', 'Paramètres inconnus');
}
if (!defined('_DOCTYPE_UPDATE')) {
    define('_DOCTYPE_UPDATE', 'Modification du type de document');
}
if (!defined('_DOCTYPE_DELETION')) {
    define('_DOCTYPE_DELETION', 'Suppression du type de document');
}
if (!defined('_THE_DOCTYPE')) {
    define('_THE_DOCTYPE', 'Le type de document ');
}
if (!defined('_THE_WORDING')) {
    define('_THE_WORDING', 'Le libellé ');
}
if (!defined('_THE_TABLE')) {
    define('_THE_TABLE', 'La table ');
}
if (!defined('_PIECE_TYPE')) {
    define('_PIECE_TYPE', 'Type de pièce');
}

//class db
if (!defined('_CONNEXION_ERROR')) {
    define('_CONNEXION_ERROR', 'Erreur à la connexion');
}
if (!defined('_SELECTION_BASE_ERROR')) {
    define('_SELECTION_BASE_ERROR', 'Erreur à la sélection de la base');
}
if (!defined('_QUERY_ERROR')) {
    define('_QUERY_ERROR', 'Erreur à la requête');
}
if (!defined('_CLOSE_CONNEXION_ERROR')) {
    define('_CLOSE_CONNEXION_ERROR', 'Erreur à la fermeture de la connexion');
}
if (!defined('_ERROR_NUM')) {
    define('_ERROR_NUM', "L'erreur n°");
}
if (!defined('_HAS_JUST_OCCURED')) {
    define('_HAS_JUST_OCCURED', 'vient de se produire');
}
if (!defined('_MESSAGE')) {
    define('_MESSAGE', 'Message');
}
if (!defined('_QUERY')) {
    define('_QUERY', 'Requête');
}
if (!defined('_LAST_QUERY')) {
    define('_LAST_QUERY', 'Dernière requête');
}

//Autres
if (!defined('_NO_GROUP_SELECTED')) {
    define('_NO_GROUP_SELECTED', 'Aucun groupe sélectionné');
}
if (!defined('_NOW_LOG_OUT')) {
    define('_NOW_LOG_OUT', 'Vous êtes maintenant déconnecté');
}
if (!defined('_DOC_NOT_FOUND')) {
    define('_DOC_NOT_FOUND', 'Document introuvable');
}
if (!defined('_DOUBLED_DOC')) {
    define('_DOUBLED_DOC', 'Problème de doublons');
}
if (!defined('_NO_DOC_OR_NO_RIGHTS')) {
    define('_NO_DOC_OR_NO_RIGHTS', "Ce document n'existe pas ou vous n'avez pas les droits nécessaires pour y accéder");
}
if (!defined('_INEXPLICABLE_ERROR')) {
    define('_INEXPLICABLE_ERROR', 'Une erreur inexplicable est survenue');
}
if (!defined('_TRY_AGAIN_SOON')) {
    define('_TRY_AGAIN_SOON', 'Veuillez réessayer dans quelques instants');
}
if (!defined('_NO_OTHER_RECIPIENT')) {
    define('_NO_OTHER_RECIPIENT', "Il n'y a pas d'autre destinataire de ce courrier");
}
if (!defined('_WAITING_INTEGER')) {
    define('_WAITING_INTEGER', 'Entier attendu');
}
if (!defined('_WAITING_FLOAT')) {
    define('_WAITING_FLOAT', 'Nombre flottant attendu');
}

if (!defined('_DEFINE')) {
    define('_DEFINE', 'Préciser');
}
if (!defined('_NUM')) {
    define('_NUM', 'N°');
}
if (!defined('_ROAD')) {
    define('_ROAD', 'Rue');
}
if (!defined('_POSTAL_CODE')) {
    define('_POSTAL_CODE', 'Code Postal');
}
if (!defined('_CITY')) {
    define('_CITY', 'Ville');
}

if (!defined('_CHOOSE_USER')) {
    define('_CHOOSE_USER', 'Vous devez choisir un utilisateur');
}
if (!defined('_CHOOSE_USER2')) {
    define('_CHOOSE_USER2', 'Choisissez un utilisateur');
}
if (!defined('_NUM2')) {
    define('_NUM2', 'Numéro');
}
if (!defined('_UNDEFINED')) {
    define('_UNDEFINED', 'N.C.');
}
if (!defined('_UNDEFINED_DATA')) {
    define('_UNDEFINED_DATA', 'Non défini');
}
if (!defined('_SERVICE')) {
    define('_SERVICE', 'Service');
}
if (!defined('_AVAILABLE_SERVICES')) {
    define('_AVAILABLE_SERVICES', 'Services disponibles');
}

// Mois
if (!defined('_JANUARY')) {
    define('_JANUARY', 'Janvier');
}
if (!defined('_FEBRUARY')) {
    define('_FEBRUARY', 'Février');
}
if (!defined('_MARCH')) {
    define('_MARCH', 'Mars');
}
if (!defined('_APRIL')) {
    define('_APRIL', 'Avril');
}
if (!defined('_MAY')) {
    define('_MAY', 'Mai');
}
if (!defined('_JUNE')) {
    define('_JUNE', 'Juin');
}
if (!defined('_JULY')) {
    define('_JULY', 'Juillet');
}
if (!defined('_AUGUST')) {
    define('_AUGUST', 'Août');
}
if (!defined('_SEPTEMBER')) {
    define('_SEPTEMBER', 'Septembre');
}
if (!defined('_OCTOBER')) {
    define('_OCTOBER', 'Octobre');
}
if (!defined('_NOVEMBER')) {
    define('_NOVEMBER', 'Novembre');
}
if (!defined('_DECEMBER')) {
    define('_DECEMBER', 'Décembre');
}

if (!defined('_NOW_LOGOUT')) {
    define('_NOW_LOGOUT', 'Vous êtes maintenant déconnecté.');
}

if (!defined('_WELCOME2')) {
    define('_WELCOME2', 'Bienvenue');
}

if (!defined('_CONTRACT_HISTORY')) {
    define('_CONTRACT_HISTORY', 'Historique des contrats');
}

if (!defined('_CLICK_CALENDAR')) {
    define('_CLICK_CALENDAR', 'Cliquez pour choisir une date');
}
if (!defined('_MODULES')) {
    define('_MODULES', 'Modules');
}
if (!defined('_CHOOSE_MODULE')) {
    define('_CHOOSE_MODULE', 'Choisissez un module');
}
if (!defined('_FOLDER')) {
    define('_FOLDER', 'Dossier');
}
if (!defined('_INDEX')) {
    define('_INDEX', 'Index');
}

//COLLECTIONS
if (!defined('_MAILS')) {
    define('_MAILS', 'Courriers');
}
if (!defined('_DOCUMENTS')) {
    define('_DOCUMENTS', 'Prets immobiliers');
}
if (!defined('_INVOICES')) {
    define('_INVOICES', 'Factures');
}
if (!defined('_SAMPLE')) {
    define('_SAMPLE', 'Collection exemple');
}
if (!defined('_CHOOSE_COLLECTION')) {
    define('_CHOOSE_COLLECTION', 'Choisir une collection');
}
if (!defined('_COLLECTION')) {
    define('_COLLECTION', 'Collection');
}
if (!defined('_EVENT')) {
    define('_EVENT', 'Evènement');
}
if (!defined('_LINK')) {
    define('_LINK', 'Lien');
}

if (!defined('_FILING')) {
    define('_FILING', 'Typologie');
}

if (!defined('_CHOOSE_DIFFUSION_LIST')) {
    define('_CHOOSE_DIFFUSION_LIST', 'Choisissez une liste de diffusion');
}
if (!defined('_DIFF_LIST_HISTORY')) {
    define('_DIFF_LIST_HISTORY', 'Historique de diffusion');
}
if (!defined('_DIFF_LIST_VISA_HISTORY')) {
    define('_DIFF_LIST_VISA_HISTORY', 'Historique du circuit de visa');
}
if (!defined('_DIFF_LIST_AVIS_HISTORY')) {
    define('_DIFF_LIST_AVIS_HISTORY', "Historique du circuit d'avis");
}

if (!defined('_MODIFY_BY')) {
    define('_MODIFY_BY', 'Modifié par');
}
if (!defined('_DIFFLIST_NEVER_MODIFIED')) {
    define('_DIFFLIST_NEVER_MODIFIED', "La liste de diffusion n'a jamais été modifiée");
}

if (!defined('_NO_RIGHT')) {
    define('_NO_RIGHT', 'Erreur');
}
if (!defined('_NO_RIGHT_TXT')) {
    define('_NO_RIGHT_TXT', "Vous avez tenté d'accéder à un document auquel vous n'avez pas droit ou le document n'existe pas...");
}
if (!defined('_NUM_GED')) {
    define('_NUM_GED', 'N° GED');
}

///// Manage action error
if (!defined('_AJAX_PARAM_ERROR')) {
    define('_AJAX_PARAM_ERROR', 'Erreur passage paramètres Ajax');
}
if (!defined('_ACTION_CONFIRM')) {
    define('_ACTION_CONFIRM', "Voulez-vous effectuer l'action suivante : ");
}
if (!defined('_ADD_ATTACHMENT_OR_NOTE')) {
    define('_ADD_ATTACHMENT_OR_NOTE', 'Ajoutez une pièce jointe ou une annotation pour ce(s) courrier(s)');
}
if (!defined('_CLOSE_MAIL_WITH_ATTACHMENT')) {
    define('_CLOSE_MAIL_WITH_ATTACHMENT', 'Clôturer un courrier avec pièce jointe ou annotation');
}
// if (!defined('_CLOSE_MAIL_WITH_ATTACHMENT_DESC')) {
//     define('_CLOSE_MAIL_WITH_ATTACHMENT_DESC', "Permet de mettre à jour la date de clôture d'un courrier ('closing_date' de la table res_letterbox) avec présence OBLIGATOIRE de pièce(s) jointe(s) / annotation(s).");
// }
if (!defined('_ACTION_NOT_IN_DB')) {
    define('_ACTION_NOT_IN_DB', 'Action non enregistrée en base');
}
if (!defined('_ERROR_PARAM_ACTION')) {
    define('_ERROR_PARAM_ACTION', "Erreur paramètrage de l'action");
}
if (!defined('_SQL_ERROR')) {
    define('_SQL_ERROR', 'Erreur SQL');
}
if (!defined('_ACTION_DONE')) {
    define('_ACTION_DONE', 'Action effectuée');
}
if (!defined('_ACTION_PAGE_MISSING')) {
    define('_ACTION_PAGE_MISSING', "Page de résultat de l'action manquante");
}
if (!defined('_ERROR_SCRIPT')) {
    define('_ERROR_SCRIPT', "Page de résultat de l'action : erreur dans le script ou fonction manquante");
}
if (!defined('_SERVER_ERROR')) {
    define('_SERVER_ERROR', 'Erreur serveur');
}
if (!defined('_CHOOSE_ONE_DOC')) {
    define('_CHOOSE_ONE_DOC', 'Choisissez au moins un document');
}
if (!defined('_CHOOSE_ONE_OBJECT')) {
    define('_CHOOSE_ONE_OBJECT', 'Choisissez au moins un élément');
}
if (!defined('_SIMPLE_CONFIRM')) {
    define('_SIMPLE_CONFIRM', 'Confirmation simple');
}
if (!defined('_SIMPLE_CONFIRM_DESC')) {
    define('_SIMPLE_CONFIRM_DESC', "Ouvre simplement une modal de confirmation de l'action à effetuer.");
}
if (!defined('_CHECK_INVOICE')) {
    define('_CHECK_INVOICE', 'Vérifier facture');
}

if (!defined('_REDIRECT_TO')) {
    define('_REDIRECT_TO', 'Rediriger vers');
}
if (!defined('_NO_STRUCTURE_ATTACHED')) {
    define('_NO_STRUCTURE_ATTACHED', "Ce type de document n'est attaché à aucune chemise");
}

///// Credits
if (!defined('_PROCESS_LIMIT_DATE')) {
    define('_PROCESS_LIMIT_DATE', 'Date limite de traitement');
}
if (!defined('_LATE_PROCESS')) {
    define('_LATE_PROCESS', 'En retard');
}
if (!defined('_PROCESS_DELAY')) {
    define('_PROCESS_DELAY', 'Délai de traitement (en jours)');
}
if (!defined('_ALARM1_DELAY')) {
    define('_ALARM1_DELAY', 'Délai relance 1 (jours) avant terme');
}
if (!defined('_ALARM2_DELAY')) {
    define('_ALARM2_DELAY', 'Délai relance 2 (jours) après terme');
}
if (!defined('_CATEGORY')) {
    define('_CATEGORY', 'Catégorie');
}
if (!defined('_CHOOSE_CATEGORY')) {
    define('_CHOOSE_CATEGORY', 'Choisissez une catégorie');
}
if (!defined('_RECEIVING_DATE')) {
    define('_RECEIVING_DATE', "Date d'arrivée");
}
if (!defined('_SUBJECT')) {
    define('_SUBJECT', 'Objet');
}
if (!defined('_AUTHOR')) {
    define('_AUTHOR', 'Auteur');
}
if (!defined('_AUTHOR_DOC')) {
    define('_AUTHOR_DOC', 'Auteur du document');
}
if (!defined('_DOCTYPE_MAIL')) {
    define('_DOCTYPE_MAIL', 'Type de courrier');
}
if (!defined('_PROCESS_LIMIT_DATE_USE')) {
    define('_PROCESS_LIMIT_DATE_USE', 'Activer la date limite');
}
if (!defined('_DEPARTMENT_DEST')) {
    define('_DEPARTMENT_DEST', 'Entité traitante');
}
if (!defined('_DEPARTMENT_EXP')) {
    define('_DEPARTMENT_EXP', 'Entité rédactrice');
}

// Mail Categories
if (!defined('_INCOMING')) {
    define('_INCOMING', 'Courrier Arrivée');
}
if (!defined('_OUTGOING')) {
    define('_OUTGOING', 'Courrier Départ');
}
if (!defined('_INTERNAL')) {
    define('_INTERNAL', 'Courrier Interne');
}
if (!defined('_ATTACHMENT')) {
    define('_ATTACHMENT', 'Pièce jointe');
}

if (!defined('_GED_DOC')) {
    define('_GED_DOC', 'Document GED');
}
if (!defined('_MARKET_DOCUMENT')) {
    define('_MARKET_DOCUMENT', 'Document de Sous-dossier');
}
if (!defined('_EMPTY')) {
    define('_EMPTY', 'Vide');
}

// Mail Natures
if (!defined('_CHOOSE_NATURE')) {
    define('_CHOOSE_NATURE', 'Choisir');
}
if (!defined('_SIMPLE_MAIL')) {
    define('_SIMPLE_MAIL', 'Courrier simple');
}
if (!defined('_EMAIL')) {
    define('_EMAIL', 'Courriel');
}
if (!defined('_FAX')) {
    define('_FAX', 'Fax');
}
if (!defined('_CHRONOPOST')) {
    define('_CHRONOPOST', 'Chronopost');
}
if (!defined('_FEDEX')) {
    define('_FEDEX', 'Fedex');
}
if (!defined('_REGISTERED_MAIL')) {
    define('_REGISTERED_MAIL', 'Courrier AR');
}
if (!defined('_COURIER')) {
    define('_COURIER', 'Coursier');
}
if (!defined('_OTHER')) {
    define('_OTHER', 'Autre');
}

//Priorities
if (!defined('_NORMAL')) {
    define('_NORMAL', 'Normal');
}
if (!defined('_VERY_HIGH')) {
    define('_VERY_HIGH', 'Très urgent');
}
if (!defined('_HIGH')) {
    define('_HIGH', 'Urgent');
}
if (!defined('_LOW')) {
    define('_LOW', 'Basse');
}
if (!defined('_VERY_LOW')) {
    define('_VERY_LOW', 'Très Basse');
}

if (!defined('_INDEXING_MLB')) {
    define('_INDEXING_MLB', 'Enregistrer un courrier');
}
if (!defined('_ADV_SEARCH_MLB')) {
    define('_ADV_SEARCH_MLB', 'Rechercher');
}

if (!defined('_ADV_SEARCH_TITLE')) {
    define('_ADV_SEARCH_TITLE', 'Recherche avancée de courrier');
}
if (!defined('_MAIL_OBJECT')) {
    define('_MAIL_OBJECT', 'Objet du courrier');
}

if (!defined('_N_GED')) {
    define('_N_GED', 'Numéro GED ');
}
if (!defined('_GED_NUM')) {
    define('_GED_NUM', 'N° GED');
}
if (!defined('_GED_DOC')) {
    define('_GED_DOC', 'Document GED');
}

if (!defined('_REG_DATE')) {
    define('_REG_DATE', "Date d'enregistrement");
}
if (!defined('_PROCESS_DATE')) {
    define('_PROCESS_DATE', 'Date de traitement');
}
if (!defined('_CHOOSE_STATUS')) {
    define('_CHOOSE_STATUS', 'Choisissez un statut');
}
if (!defined('_TO_CC')) {
    define('_TO_CC', 'En copie');
}
if (!defined('_ADD_CC')) {
    define('_ADD_CC', 'En copie');
}
if (!defined('_ADD_COPIES')) {
    define('_ADD_COPIES', 'Ajouter des personnes en copie');
}
//Circuits de visa
if (!defined('_TO_SIGN')) {
    define('_TO_SIGN', 'Pour signature');
}
if (!defined('_VISA_USER')) {
    define('_VISA_USER', 'Pour visa');
}
if (!defined('_VISA_USER_SEARCH')) {
    define('_VISA_USER_SEARCH', 'VISEUR');
}
if (!defined('_VISA_USER_SEARCH_MIN')) {
    define('_VISA_USER_SEARCH_MIN', 'Viseur');
}
//Circuits d'avis
if (!defined('_TO_VIEW')) {
    define('_TO_VIEW', 'Pour avis');
}
if (!defined('_TO_SHARED_VIEW')) {
    define('_TO_SHARED_VIEW', 'Pour avis partagé');
}
if (!defined('_FOLLOWED_INFO')) {
    define('_FOLLOWED_INFO', 'Pour information suivie');
}

if (!defined('_PROCESS_NOTES')) {
    define('_PROCESS_NOTES', 'Annotations de traitement');
}
if (!defined('_DIRECT_CONTACT')) {
    define('_DIRECT_CONTACT', 'Prise de contact direct');
}
if (!defined('_NO_ANSWER')) {
    define('_NO_ANSWER', 'Pas de réponse');
}
if (!defined('_ANSWER')) {
    define('_ANSWER', 'Réponse');
}
if (!defined('_DETAILS')) {
    define('_DETAILS', 'Fiche détaillée');
}
if (!defined('_DOWNLOAD')) {
    define('_DOWNLOAD', 'Télécharger');
}
if (!defined('_SEARCH_RESULTS')) {
    define('_SEARCH_RESULTS', 'Résultat de la recherche');
}

if (!defined('_MAIL_PRIORITY')) {
    define('_MAIL_PRIORITY', 'Priorité du courrier');
}
if (!defined('_CHOOSE_PRIORITY')) {
    define('_CHOOSE_PRIORITY', 'Choisissez une priorité');
}
if (!defined('_ADD_PARAMETERS')) {
    define('_ADD_PARAMETERS', 'Ajouter des critères');
}
if (!defined('_CHOOSE_PARAMETERS')) {
    define('_CHOOSE_PARAMETERS', 'Choisissez vos critères');
}
if (!defined('_CHOOSE_ENTITES_SEARCH_TITLE')) {
    define('_CHOOSE_ENTITES_SEARCH_TITLE', 'Ajoutez le/les entité(s) désirée(s) pour restreindre la recherche');
}
if (!defined('_CHOOSE_DOCTYPES_SEARCH_TITLE')) {
    define('_CHOOSE_DOCTYPES_SEARCH_TITLE', 'Ajoutez le(s) type(s) de document désiré(s) pour restreindre la recherche');
}
if (!defined('_CHOOSE_DOCTYPES_MAIL_SEARCH_TITLE')) {
    define('_CHOOSE_DOCTYPES_MAIL_SEARCH_TITLE', 'Ajoutez le(s) type(s) de courrier désiré(s) pour restreindre la recherche');
}
if (!defined('_DESTINATION_SEARCH')) {
    define('_DESTINATION_SEARCH', 'Entité(s) affectée(s)');
}
if (!defined('_ADD_PARAMETERS_HELP')) {
    define('_ADD_PARAMETERS_HELP', 'Pour affiner le résultat, vous pouvez ajouter des critères à votre recherche... ');
}
if (!defined('_MAIL_OBJECT_HELP')) {
    define('_MAIL_OBJECT_HELP', "Saisissez un mot ou un groupe de mots de l'objet du courrier");
}
if (!defined('_N_GED_HELP')) {
    define('_N_GED_HELP', '');
}
if (!defined('_CHOOSE_RECIPIENT_SEARCH_TITLE')) {
    define('_CHOOSE_RECIPIENT_SEARCH_TITLE', 'Ajoutez le/les destinataire(s) désiré(s) pour restreindre la recherche');
}
if (!defined('_MULTI_FIELD')) {
    define('_MULTI_FIELD', 'Multi-champs');
}
if (!defined('_MULTI_FIELD_HELP')) {
    define('_MULTI_FIELD_HELP', 'Objet, code à barres, Num GED, Num chrono, annotations de traitement');
}
if (!defined('_SAVE_QUERY')) {
    define('_SAVE_QUERY', 'Enregistrer ma recherche');
}
if (!defined('_SAVE_QUERY_TITLE')) {
    define('_SAVE_QUERY_TITLE', 'Enregistrement de recherche');
}
if (!defined('_QUERY_NAME')) {
    define('_QUERY_NAME', 'Nom de ma recherche');
}
if (!defined('_QUERY_SAVED')) {
    define('_QUERY_SAVED', 'Recherche sauvegardée');
}

if (!defined('_LOAD_QUERY')) {
    define('_LOAD_QUERY', 'Charger la recherche');
}
if (!defined('_DELETE_QUERY')) {
    define('_DELETE_QUERY', 'Supprimer la recherche');
}
if (!defined('_CHOOSE_SEARCH')) {
    define('_CHOOSE_SEARCH', 'Choisir une recherche');
}
if (!defined('_THIS_SEARCH')) {
    define('_THIS_SEARCH', 'cette recherche');
}
if (!defined('_MY_SEARCHES')) {
    define('_MY_SEARCHES', 'Mes recherches');
}
if (!defined('_CLEAR_SEARCH')) {
    define('_CLEAR_SEARCH', 'Effacer les critères');
}
if (!defined('_CHOOSE_STATUS_SEARCH_TITLE')) {
    define('_CHOOSE_STATUS_SEARCH_TITLE', 'Ajoutez le/les statut(s) désiré(s) pour restreindre la recherche');
}
if (!defined('_ERROR_IE_SEARCH')) {
    define('_ERROR_IE_SEARCH', 'Cet élément est déjà défini !');
}
if (!defined('_DEST_USER')) {
    define('_DEST_USER', 'Destinataire');
}
if (!defined('_DOCTYPES')) {
    define('_DOCTYPES', 'Type(s) de document');
}
if (!defined('_DOCTYPE_INDEXES')) {
    define('_DOCTYPE_INDEXES', 'Index du type de document');
}
if (!defined('_ERROR_DOCTYPE')) {
    define('_ERROR_DOCTYPE', 'Type de document non valide');
}
if (!defined('_ADMISSION_DATE')) {
    define('_ADMISSION_DATE', "Date d'arrivée");
}
if (!defined('_FOUND_DOC')) {
    define('_FOUND_DOC', 'courrier(s) trouvé(s)');
}
if (!defined('_FOUND_LOGS')) {
    define('_FOUND_LOGS', 'fichier(s) de logs trouvé(s)');
}
if (!defined('_PROCESS')) {
    define('_PROCESS', 'Traitement ');
}
if (!defined('_DOC_NUM')) {
    define('_DOC_NUM', 'document n° ');
}
if (!defined('_LETTER_NUM')) {
    define('_LETTER_NUM', 'courrier n° ');
}
if (!defined('_GENERAL_INFO')) {
    define('_GENERAL_INFO', 'Informations générales');
}
if (!defined('_ON_DOC_NUM')) {
    define('_ON_DOC_NUM', ' sur le document n°');
}
if (!defined('_PRIORITY')) {
    define('_PRIORITY', 'Priorité');
}
if (!defined('_MAIL_DATE')) {
    define('_MAIL_DATE', 'Date du courrier');
}
if (!defined('_DOC_HISTORY')) {
    define('_DOC_HISTORY', 'Historique');
}
if (!defined('_CHOOSE_FILE')) {
    define('_CHOOSE_FILE', 'Choisissez le fichier');
}
if (!defined('_CHOOSE_TYPE')) {
    define('_CHOOSE_TYPE', 'Choisissez un type');
}

if (!defined('_WRONG_FILE_TYPE')) {
    define('_WRONG_FILE_TYPE', "Ce type de fichier n'est pas permis");
}

if (!defined('_LETTERBOX')) {
    define('_LETTERBOX', 'Collection des courriers');
}
if (!defined('_ATTACHMENTS_COLL')) {
    define('_ATTACHMENTS_COLL', 'Collection des attachements');
}
if (!defined('_ATTACHMENTS_VERS_COLL')) {
    define('_ATTACHMENTS_VERS_COLL', "Collection des version d'attachements");
}
if (!defined('_APA_COLL')) {
    define('_APA_COLL', "Collection de l'archivage physique");
}
if (!defined('_DOCUMENTS_LIST')) {
    define('_DOCUMENTS_LIST', 'Liste');
}

/********* Contacts ************/
if (!defined('_ADMIN_CONTACTS')) {
    define('_ADMIN_CONTACTS', 'Contacts');
}
if (!defined('_ADMIN_CONTACTS_DESC')) {
    define('_ADMIN_CONTACTS_DESC', 'Administration des contacts');
}
if (!defined('_CONTACTS_LIST')) {
    define('_CONTACTS_LIST', 'Liste des contacts');
}
if (!defined('_CONTACT_ADDITION')) {
    define('_CONTACT_ADDITION', 'Ajouter contact');
}
if (!defined('_CONTACTS')) {
    define('_CONTACTS', 'contact(s)');
}
if (!defined('_CONTACT')) {
    define('_CONTACT', 'Contact');
}
if (!defined('_NEW_CONTACT')) {
    define('_NEW_CONTACT', 'Nouveau contact');
}
if (!defined('_ALL_CONTACTS')) {
    define('_ALL_CONTACTS', 'Tous les contacts');
}
if (!defined('_ADD_CONTACT')) {
    define('_ADD_CONTACT', "Ajout d'un contact");
}
if (!defined('_ADD_NEW_CONTACT')) {
    define('_ADD_NEW_CONTACT', 'Ajouter un nouveau contact');
}
if (!defined('_UPDATE_CONTACT')) {
    define('_UPDATE_CONTACT', 'Modification des contacts');
}
if (!defined('_PHONE')) {
    define('_PHONE', 'Téléphone');
}
if (!defined('_ADDRESS')) {
    define('_ADDRESS', 'Adresse');
}
if (!defined('_NO_ADDRESS_GIVEN')) {
    define('_NO_ADDRESS_GIVEN', "Aucune information sur l'adresse");
}
if (!defined('_NO_RESULTS_AUTOCOMPLETE_CONTACT_INFO')) {
    define('_NO_RESULTS_AUTOCOMPLETE_CONTACT_INFO', "Si vous voulez faire une recherche sur le prénom ET le nom, assurez-vous de l'avoir écrit dans cet ordre.");
}
if (!defined('_STREET')) {
    define('_STREET', 'Voie');
}
if (!defined('_COMPLEMENT')) {
    define('_COMPLEMENT', 'Tour, bâtiment, immeuble, résidence');
}
if (!defined('_TOWN')) {
    define('_TOWN', 'Ville');
}
if (!defined('_COUNTRY')) {
    define('_COUNTRY', 'Pays');
}
if (!defined('_SOCIETY')) {
    define('_SOCIETY', 'Société');
}
if (!defined('_COMP')) {
    define('_COMP', 'Autres');
}
if (!defined('_COMP_DATA')) {
    define('_COMP_DATA', 'Informations complémentaires');
}
if (!defined('_CONTACT_ADDED')) {
    define('_CONTACT_ADDED', 'Contact ajouté');
}
if (!defined('_CONTACT_MODIFIED')) {
    define('_CONTACT_MODIFIED', 'Contact modifié');
}
if (!defined('_CONTACT_DELETED')) {
    define('_CONTACT_DELETED', 'Contact supprimé');
}
if (!defined('_MODIFY_CONTACT')) {
    define('_MODIFY_CONTACT', 'Modifier un contact');
}
if (!defined('_IS_CORPORATE_PERSON')) {
    define('_IS_CORPORATE_PERSON', 'Personne morale');
}
if (!defined('_IS_INTERNAL_CONTACT')) {
    define('_IS_INTERNAL_CONTACT', 'Contact interne');
}
if (!defined('_IS_EXTERNAL_CONTACT')) {
    define('_IS_EXTERNAL_CONTACT', 'Contact externe');
}
if (!defined('_SEARCH_DIRECTORY')) {
    define('_SEARCH_DIRECTORY', 'Recherche annuaire');
}
if (!defined('_CONTACT_ID')) {
    define('_CONTACT_ID', 'ID Contact');
}
if (!defined('_INDIVIDUAL')) {
    define('_INDIVIDUAL', 'Particulier');
}
if (!defined('_CONTACT_TARGET')) {
    define('_CONTACT_TARGET', "Pour quel contact est il possible d'utiliser ce type ?");
}
if (!defined('_CONTACT_TARGET_LIST')) {
    define('_CONTACT_TARGET_LIST', 'Cible du type de contact');
}
if (!defined('_CONTACT_TYPE_CREATION')) {
    define('_CONTACT_TYPE_CREATION', "Possibilité de créer un contact de ce type hors panneau d'administration ?");
}
if (!defined('_IS_PRIVATE')) {
    define('_IS_PRIVATE', 'Coordonnées confidentielles');
}
if (!defined('_TITLE2')) {
    define('_TITLE2', 'Civilité');
}
if (!defined('_WARNING_MESSAGE_DEL_CONTACT')) {
    define('_WARNING_MESSAGE_DEL_CONTACT', "Avertissement :<br> La suppression d'un contact entraine la réaffectation des documents et des courriers à un nouveau contact.");
}
if (!defined('_CONTACT_DELETION')) {
    define('_CONTACT_DELETION', 'Suppression de contact');
}
if (!defined('_CONTACT_REAFFECT')) {
    define('_CONTACT_REAFFECT', 'Réaffectation des documents et des courriers');
}
if (!defined('_UPDATE_CONTACTS')) {
    define('_UPDATE_CONTACTS', 'Mise à jour des contacts');
}
if (!defined('_CONTACT_TYPE')) {
    define('_CONTACT_TYPE', 'Type de contact');
}
if (!defined('_MULTI_EXTERNAL')) {
    define('_MULTI_EXTERNAL', 'Multi externe');
}
if (!defined('_MULTI_CONTACT')) {
    define('_MULTI_CONTACT', 'Multi contacts');
}
if (!defined('_SINGLE_CONTACT')) {
    define('_SINGLE_CONTACT', 'Mono contact');
}
if (!defined('_SHOW_MULTI_CONTACT')) {
    define('_SHOW_MULTI_CONTACT', 'Voir les contacts');
}
if (!defined('_STRUCTURE_ORGANISM')) {
    define('_STRUCTURE_ORGANISM', 'Structure');
}
if (!defined('_TYPE_OF_THE_CONTACT')) {
    define('_TYPE_OF_THE_CONTACT', 'Quel est le type du contact ?');
}
if (!defined('_WRITE_IN_UPPER')) {
    define('_WRITE_IN_UPPER', 'Saisir en majuscule');
}
if (!defined('_EXAMPLE_PURPOSE')) {
    define('_EXAMPLE_PURPOSE', 'Exemple : Direction générale/Domicile');
}
if (!defined('_EXAMPLE_SELECT_CONTACT_TYPE')) {
    define('_EXAMPLE_SELECT_CONTACT_TYPE', '');
}
if (!defined('_HELP_SELECT_CONTACT_CREATED')) {
    define('_HELP_SELECT_CONTACT_CREATED', '');
}

if (!defined('_MANAGE_DUPLICATES')) {
    define('_MANAGE_DUPLICATES', 'Gestion des doublons');
}
if (!defined('_DUPLICATES_BY_SOCIETY')) {
    define('_DUPLICATES_BY_SOCIETY', 'Doublons par organisme/société');
}
if (!defined('_DUPLICATES_BY_NAME')) {
    define('_DUPLICATES_BY_NAME', 'Doublons par nom/prénom');
}
if (!defined('_IS_ATTACHED_TO_DOC')) {
    define('_IS_ATTACHED_TO_DOC', 'Attaché à des documents ?');
}
if (!defined('_CONTACT_CHECK')) {
    define('_CONTACT_CHECK', 'Au moins un courrier enregistré récemment est affecté au même contact.');
}
if (!defined('_NO_SOCIETY_DUPLICATES')) {
    define('_NO_SOCIETY_DUPLICATES', 'Pas de doublon pour les contacts moraux');
}
if (!defined('_NO_NAME_DUPLICATES')) {
    define('_NO_NAME_DUPLICATES', 'Pas de doublon pour les contacts physique (prénom nom)');
}

if (!defined('_YOU_MUST_SELECT_CONTACT')) {
    define('_YOU_MUST_SELECT_CONTACT', 'Vous devez sélectionner un contact ');
}
if (!defined('_DOC_SENDED_BY_CONTACT')) {
    define('_DOC_SENDED_BY_CONTACT', '<b>Documents et/ou courriers liés à ce contact</b>');
}
if (!defined('_CONTACT_INFO')) {
    define('_CONTACT_INFO', 'Fiche contact');
}
if (!defined('_SHIPPER')) {
    define('_SHIPPER', 'Expéditeur');
}
if (!defined('_DEST')) {
    define('_DEST', 'Destinataire');
}
if (!defined('_THIRD_DEST')) {
    define('_THIRD_DEST', 'Tiers Bénéficiaire');
}
if (!defined('_INTERNAL2')) {
    define('_INTERNAL2', 'Interne');
}
if (!defined('_EXTERNAL')) {
    define('_EXTERNAL', 'Externe');
}
if (!defined('_CHOOSE_SHIPPER')) {
    define('_CHOOSE_SHIPPER', 'Choisir un expéditeur');
}
if (!defined('_CHOOSE_DEST')) {
    define('_CHOOSE_DEST', 'Choisir un destainataire');
}
if (!defined('_DOC_DATE')) {
    define('_DOC_DATE', 'Date du courrier');
}
if (!defined('_CONTACT_CARD')) {
    define('_CONTACT_CARD', 'Fiche contact');
}
if (!defined('_CREATE_CONTACT')) {
    define('_CREATE_CONTACT', 'Ajouter un contact ou une adresse');
}
if (!defined('_USE_AUTOCOMPLETION')) {
    define('_USE_AUTOCOMPLETION', "Utiliser l'autocomplétion");
}

if (!defined('_USER_DATA')) {
    define('_USER_DATA', 'Fiche utilisateur');
}
if (!defined('_SHIPPER_TYPE')) {
    define('_SHIPPER_TYPE', "Type d'expéditeur");
}
if (!defined('_DEST_TYPE')) {
    define('_DEST_TYPE', 'Type de destinataire');
}
if (!defined('_VALIDATE_MAIL')) {
    define('_VALIDATE_MAIL', 'Validation courrier');
}
if (!defined('_LETTER_INFO')) {
    define('_LETTER_INFO', 'Informations sur le courrier');
}
if (!defined('_DATE_START')) {
    define('_DATE_START', "Date d'arrivée");
}
if (!defined('_LIMIT_DATE_PROCESS')) {
    define('_LIMIT_DATE_PROCESS', 'Date limite de traitement');
}

if (!defined('_MANAGE_CONTACTS_DESC')) {
    define('_MANAGE_CONTACTS_DESC', 'Gestion des contacts');
}
if (!defined('_MANAGE_CONTACTS')) {
    define('_MANAGE_CONTACTS', 'Gestion des contacts <br/>(Niveau 2)');
}

if (!defined('_SEE_ALL_ADDRESSES')) {
    define('_SEE_ALL_ADDRESSES', 'Voir toutes les adresses');
}

if (!defined('_MANAGE_CONTACT_ADDRESSES_LIST_DESC')) {
    define('_MANAGE_CONTACT_ADDRESSES_LIST_DESC', 'Gestion des adresses');
}
if (!defined('_MANAGE_CONTACT_ADDRESSES_LIST')) {
    define('_MANAGE_CONTACT_ADDRESSES_LIST', 'Gestion des adresses');
}

if (!defined('_VIEW_TREE_CONTACTS_DESC')) {
    define('_VIEW_TREE_CONTACTS_DESC', 'Arborescence des contacts');
}
if (!defined('_VIEW_TREE_CONTACTS')) {
    define('_VIEW_TREE_CONTACTS', 'Arborescence des contacts');
}

if (!defined('_VIEW_CONTACTS_GROUPS_DESC')) {
    define('_VIEW_CONTACTS_GROUPS_DESC', 'Groupements de contacts utilisés à l\'indexation d\'un courrier');
}
if (!defined('_VIEW_CONTACTS_GROUPS')) {
    define('_VIEW_CONTACTS_GROUPS', 'Groupements de contacts');
}

if (!defined('_CONTACTS_GROUP')) {
    define('_CONTACTS_GROUP', 'Groupement de contacts');
}

if (!defined('_ADDRESSES_LIST')) {
    define('_ADDRESSES_LIST', 'Liste des adresses');
}
if (!defined('_SEARCH_ADDRESSES')) {
    define('_SEARCH_ADDRESSES', 'Rechercher Nom/Adresse');
}
if (!defined('_ADDRESS_NB')) {
    define('_ADDRESS_NB', "Nombre d'adresse");
}

if (!defined('_CONTACT_TYPES_LIST')) {
    define('_CONTACT_TYPES_LIST', 'Liste des types de contact');
}
if (!defined('_DESC_CONTACT_TYPES')) {
    define('_DESC_CONTACT_TYPES', 'Type de contact');
}
if (!defined('_NEW_CONTACT_TYPE_ADDED')) {
    define('_NEW_CONTACT_TYPE_ADDED', "Ajout d'un nouveau type de contact");
}
if (!defined('_ALL_CONTACT_TYPES')) {
    define('_ALL_CONTACT_TYPES', 'Tous les types');
}
if (!defined('_CONTACT_TYPE')) {
    define('_CONTACT_TYPE', 'Types de contact');
}
if (!defined('_THIS_CONTACT_TYPE')) {
    define('_THIS_CONTACT_TYPE', 'Ce type de contact');
}
if (!defined('_CONTACT_TYPE_MISSING')) {
    define('_CONTACT_TYPE_MISSING', 'Type de contact est manquant');
}
if (!defined('_CONTACT_TYPES')) {
    define('_CONTACT_TYPES', 'type(s) de contact');
}
if (!defined('_A_CONTACT_TYPE')) {
    define('_A_CONTACT_TYPE', 'un type de contact');
}
if (!defined('_NEW_CONTACT_TYPE')) {
    define('_NEW_CONTACT_TYPE', 'Nouveau type de contact');
}
if (!defined('_CONTACT_TYPE_MODIF')) {
    define('_CONTACT_TYPE_MODIF', 'Modification du type de contact');
}
if (!defined('_ID_CONTACT_TYPE_PB')) {
    define('_ID_CONTACT_TYPE_PB', "Il y a un problème avec l'identifiant du type de contact");
}
if (!defined('_THE_CONTACT_TYPE')) {
    define('_THE_CONTACT_TYPE', 'Le type de contact');
}
if (!defined('_CONTACT_TYPE_DEL')) {
    define('_CONTACT_TYPE_DEL', 'Suppression du type de contact');
}
if (!defined('_DELETED_CONTACT_TYPE')) {
    define('_DELETED_CONTACT_TYPE', 'Type de contact supprimé');
}
if (!defined('_WARNING_MESSAGE_DEL_CONTACT_TYPE')) {
    define('_WARNING_MESSAGE_DEL_CONTACT_TYPE', "Avertissement : La suppression d'un type de contact entraine la réaffectation des contacts à un nouveau type de contact.");
}
if (!defined('_CONTACT_TYPE_REAFFECT')) {
    define('_CONTACT_TYPE_REAFFECT', 'Réaffectation des contacts');
}
if (!defined('_ALL')) {
    define('_ALL', 'Tous');
}
if (!defined('_CONTACT_ALREADY_CREATED')) {
    define('_CONTACT_ALREADY_CREATED', 'Contacts déjà existants');
}
if (!defined('_CONTACT_ALREADY_CREATED_INFORMATION')) {
    define('_CONTACT_ALREADY_CREATED_INFORMATION', '(pour information)');
}

if (!defined('_SEARCH_CONTACTS')) {
    define('_SEARCH_CONTACTS', 'Rechercher un contact');
}

if (!defined('_YOU_SHOULD_ADD_AN_ADDRESS')) {
    define('_YOU_SHOULD_ADD_AN_ADDRESS', "Après avoir validé, n'oubliez pas d'ajouter une adresse à ce contact");
}
if (!defined('_ADDRESSES')) {
    define('_ADDRESSES', 'adresse(s)');
}
if (!defined('_ADDRESSES_MAJ')) {
    define('_ADDRESSES_MAJ', 'Adresse');
}
if (!defined('_MORAL_CONTACT')) {
    define('_MORAL_CONTACT', 'Contact moral');
}
if (!defined('_DOC_S')) {
    define('_DOC_S', 'courriers(s)');
}
if (!defined('_CONTACTS_CONFIRMATION')) {
    define('_CONTACTS_CONFIRMATION', 'Confirmation de création');
}
if (!defined('_YOUR_CONTACT_LOOKS_LIKE_ANOTHER')) {
    define('_YOUR_CONTACT_LOOKS_LIKE_ANOTHER', '<b>Votre contact ressemble à un ou des contacts déjà existants :</b>');
}
if (!defined('_CONFIRM_CREATE_CONTACT')) {
    define('_CONFIRM_CREATE_CONTACT', 'Confirmez vous la création de votre contact ?');
}
if (!defined('_CONFIRM_EDIT_CONTACT')) {
    define('_CONFIRM_EDIT_CONTACT', 'Confirmez vous la modification de votre contact ?');
}
if (!defined('_CONTACTS_CONFIRMATION_MODIFICATION')) {
    define('_CONTACTS_CONFIRMATION_MODIFICATION', 'Confirmation de modification');
}

if (!defined('_CREATE_BY')) {
    define('_CREATE_BY', 'Créé par');
}
if (!defined('_SOCIETY_SHORT')) {
    define('_SOCIETY_SHORT', 'Sigle de la structure');
}
if (!defined('_CHOOSE_CONTACT_TYPES')) {
    define('_CHOOSE_CONTACT_TYPES', 'Choisissez le type de contact');
}
if (!defined('_ORGANISM')) {
    define('_ORGANISM', 'Organisme');
}

if (!defined('_NEW_CONTACT_ADDRESS')) {
    define('_NEW_CONTACT_ADDRESS', 'Ajouter une nouvelle adresse');
}
if (!defined('_A_CONTACT_ADDRESS')) {
    define('_A_CONTACT_ADDRESS', 'une adresse');
}
if (!defined('_ALL_CONTACT_ADDRESSES')) {
    define('_ALL_CONTACT_ADDRESSES', 'Toutes les adresses');
}
if (!defined('_THE_CONTACT')) {
    define('_THE_CONTACT', 'Le contact');
}
if (!defined('_CONTACT_ADDRESSES_ASSOCIATED')) {
    define('_CONTACT_ADDRESSES_ASSOCIATED', 'Adresses associées à ce contact');
}
if (!defined('_VIEW_CONTACT')) {
    define('_VIEW_CONTACT', 'Visualisation du contact');
}
if (!defined('_VIEW_CONTACTS')) {
    define('_VIEW_CONTACTS', 'Voir les contacts');
}
if (!defined('_VIEW_ADDRESS')) {
    define('_VIEW_ADDRESS', "Visualisation de l'adresse");
}
if (!defined('_TREE_INFO')) {
    define('_TREE_INFO', '(Types de contact/Contacts/Adresses)');
}
if (!defined('_CONFIDENTIAL_ADDRESS')) {
    define('_CONFIDENTIAL_ADDRESS', 'coordonnées confidentielles');
}
if (!defined('_MAIN_ADDRESS')) {
    define('_MAIN_ADDRESS', 'Adresse principale');
}

if (!defined('_MANAGE_CONTACT_ADDRESSES')) {
    define('_MANAGE_CONTACT_ADDRESSES', '<h2>Gérer les adresses associées</h2>');
}
if (!defined('_MANAGE_CONTACT_ADDRESSES_IMG')) {
    define('_MANAGE_CONTACT_ADDRESSES_IMG', 'Gérer les adresses associées');
}
if (!defined('_DEPARTEMENT')) {
    define('_DEPARTEMENT', 'Département');
}
if (!defined('_ADDITION_ADDRESS')) {
    define('_ADDITION_ADDRESS', 'Ajouter une adresse');
}
if (!defined('_THE_ADDRESS')) {
    define('_THE_ADDRESS', "L'adresse");
}
if (!defined('_MODIFY_ADDRESS')) {
    define('_MODIFY_ADDRESS', "Modification de l'adresse");
}
if (!defined('_WEBSITE')) {
    define('_WEBSITE', 'Site internet');
}
if (!defined('_OCCUPANCY')) {
    define('_OCCUPANCY', 'N° app, étage, escalier');
}
if (!defined('_ADDRESS_ADDED')) {
    define('_ADDRESS_ADDED', 'Adresse ajoutée');
}
if (!defined('_ADD_ADDRESS')) {
    define('_ADD_ADDRESS', 'Ajouter une adresse');
}
if (!defined('_EDIT_ADDRESS')) {
    define('_EDIT_ADDRESS', "Modifier l'adresse");
}
if (!defined('_ADDRESS_EDITED')) {
    define('_ADDRESS_EDITED', 'Adresse modifiée');
}
if (!defined('_DELETED_ADDRESS')) {
    define('_DELETED_ADDRESS', 'Adresse supprimée');
}
if (!defined('_ADDRESS_DEL')) {
    define('_ADDRESS_DEL', "Suppression d'une adresse");
}
if (!defined('_WARNING_MESSAGE_DEL_CONTACT_ADDRESS')) {
    define('_WARNING_MESSAGE_DEL_CONTACT_ADDRESS', "Avertissement : La suppression d'une adresse entraine la réaffectation des courriers à une nouvelle adresse.");
}
if (!defined('_CONTACT_ADDRESS_REAFFECT')) {
    define('_CONTACT_ADDRESS_REAFFECT', 'Réaffectation des courriers');
}
if (!defined('_NEW_ADDRESS')) {
    define('_NEW_ADDRESS', 'Nouvelle adresse');
}
if (!defined('_CHOOSE_CONTACT_ADDRESS')) {
    define('_CHOOSE_CONTACT_ADDRESS', 'Choisissez une adresse');
}
if (!defined('_THE_CONTACT_ADDRESS')) {
    define('_THE_CONTACT_ADDRESS', 'L\'adresse du contact');
}
if (!defined('_CONTACT_ADDRESS')) {
    define('_CONTACT_ADDRESS', 'Adresse du contact');
}

if (!defined('_USE')) {
    define('_USE', 'Utiliser');
}
if (!defined('_HELP_PRIVATE')) {
    define('_HELP_PRIVATE', "<i>Les champs marques par <span class=\"blue_asterisk\">*</span> sont cachés dans la fiche contact si l'adresse est confidentielle</i>");
}

if (!defined('_SALUTATION')) {
    define('_SALUTATION', 'Formule de politesse');
}
if (!defined('_SALUTATION_HEADER')) {
    define('_SALUTATION_HEADER', 'De début de courrier');
}
if (!defined('_SALUTATION_FOOTER')) {
    define('_SALUTATION_FOOTER', 'De fin de courrier');
}

if (!defined('_BACK_TO_RESULTS_LIST')) {
    define('_BACK_TO_RESULTS_LIST', 'Retour à la liste de résultats');
}
if (!defined('_ADD_ADDRESS_TO_CONTACT')) {
    define('_ADD_ADDRESS_TO_CONTACT', 'Ajouter une nouvelle adresse à un contact existant');
}
if (!defined('_ADD_ADDRESS_TO_CONTACT_DESC')) {
    define('_ADD_ADDRESS_TO_CONTACT_DESC', 'Cette partie est utilisée pour ajouter une adresse à un contact qui existe déjà.');
}
if (!defined('_WHICH_CONTACT')) {
    define('_WHICH_CONTACT', 'A quel contact voulez-vous ajouter une adresse ?');
}
if (!defined('_CHOOSE_THIS_CONTACT')) {
    define('_CHOOSE_THIS_CONTACT', 'Choisir ce contact');
}
if (!defined('_CHOOSE_A_CONTACT')) {
    define('_CHOOSE_A_CONTACT', 'Choisissez un contact');
}

if (!defined('_CREATE_CONTACTS')) {
    define('_CREATE_CONTACTS', 'Tous les contacts');
}
if (!defined('_LINKED_CONTACT')) {
    define('_LINKED_CONTACT', 'Lié au contact');
}

if (!defined('_COMMUNICATION_TYPE')) {
    define('_COMMUNICATION_TYPE', 'Moyen de communication');
}
if (!defined('_COMMUNICATION_VALUE')) {
    define('_COMMUNICATION_VALUE', 'Valeur');
}
if (!defined('_COMMUNICATION_ADDED')) {
    define('_COMMUNICATION_ADDED', 'Communication ajoutée');
}
if (!defined('_COMMUNICATION_MODIFIED')) {
    define('_COMMUNICATION_MODIFIED', 'Communication modifiée');
}
if (!defined('_COMMUNICATION_DELETED')) {
    define('_COMMUNICATION_DELETED', 'Communication supprimée');
}
if (!defined('_CHOOSE_COMMUNICATION_TYPES')) {
    define('_CHOOSE_COMMUNICATION_TYPES', 'Choissisez le moyen de communication');
}
if (!defined('_CONTACT_COMMUNICATION_DEFINE')) {
    define('_CONTACT_COMMUNICATION_DEFINE', 'Moyen de communication défini');
}

//// INDEXING SEARCHING
if (!defined('_NO_COLLECTION_ACCESS_FOR_THIS_USER')) {
    define('_NO_COLLECTION_ACCESS_FOR_THIS_USER', 'Aucun accès aux collections documentaires pour cet utilisateur');
}
if (!defined('_CREATION_DATE')) {
    define('_CREATION_DATE', 'Date de création');
}
if (!defined('_MODIFICATION_DATE')) {
    define('_MODIFICATION_DATE', 'Date de modification');
}
if (!defined('_NO_RESULTS')) {
    define('_NO_RESULTS', 'Aucun résultat');
}
if (!defined('_FOUND_DOCS')) {
    define('_FOUND_DOCS', 'courrier(s) trouvé(s)');
}
if (!defined('_MY_CONTACTS')) {
    define('_MY_CONTACTS', 'Créer des contacts depuis indexation/qualification');
}
if (!defined('_MY_CONTACTS_MENU')) {
    define('_MY_CONTACTS_MENU', 'Mes contacts');
}
if (!defined('_MAARCH_INFO')) {
    define('_MAARCH_INFO', 'Nous Contacter');
}
if (!defined('_MY_COLLEAGUES')) {
    define('_MY_COLLEAGUES', 'Mes collegues');
}
if (!defined('_DETAILLED_PROPERTIES')) {
    define('_DETAILLED_PROPERTIES', 'Propriétés détaillées');
}
if (!defined('_PROPERTIES')) {
    define('_PROPERTIES', 'Détails');
}
if (!defined('_VIEW_DOC_NUM')) {
    define('_VIEW_DOC_NUM', 'Visualisation du courrier n°');
}
if (!defined('_VIEW_DETAILS_NUM')) {
    define('_VIEW_DETAILS_NUM', 'Visualisation de la fiche détaillée du courrier n°');
}
if (!defined('_TO')) {
    define('_TO', 'vers');
}
if (!defined('_FILE_PROPERTIES')) {
    define('_FILE_PROPERTIES', 'Propriétés du fichier');
}
if (!defined('_FILE_DATA')) {
    define('_FILE_DATA', 'Informations sur le courrier');
}
if (!defined('_VIEW_DOC')) {
    define('_VIEW_DOC', 'Voir le courrier');
}
if (!defined('_DOWNLOAD_LOCAL_DOC_COPY')) {
    define('_DOWNLOAD_LOCAL_DOC_COPY', 'Télécharger une copie locale');
}
if (!defined('_VISUALIZE')) {
    define('_VISUALIZE', 'Visualiser');
}
if (!defined('_TYPIST')) {
    define('_TYPIST', 'Opérateur');
}
if (!defined('_LOT')) {
    define('_LOT', 'Lot');
}
if (!defined('_ARBOX')) {
    define('_ARBOX', 'Boite');
}
if (!defined('_ARBOXES')) {
    define('_ARBOXES', 'Boites');
}
if (!defined('_ARBATCHES')) {
    define('_ARBATCHES', 'Lot');
}
if (!defined('_CHOOSE_BOXES_SEARCH_TITLE')) {
    define('_CHOOSE_BOXES_SEARCH_TITLE', 'Sélectionnez la ou les boites pour restreindre la recherche');
}
if (!defined('_PAGECOUNT')) {
    define('_PAGECOUNT', 'Nb pages');
}
if (!defined('_ISPAPER')) {
    define('_ISPAPER', 'Papier');
}
if (!defined('_SCANDATE')) {
    define('_SCANDATE', 'Date de numérisation');
}
if (!defined('_SCANUSER')) {
    define('_SCANUSER', 'Utilisateur du scanner');
}
if (!defined('_SCANLOCATION')) {
    define('_SCANLOCATION', 'Lieu de numérisation');
}
if (!defined('_SCANWKSATION')) {
    define('_SCANWKSATION', 'Station de numérisation');
}
if (!defined('_SCANBATCH')) {
    define('_SCANBATCH', 'Lot de numérisation');
}
if (!defined('_SOURCE')) {
    define('_SOURCE', 'Source');
}
if (!defined('_DOCLANGUAGE')) {
    define('_DOCLANGUAGE', 'Langage du document');
}
if (!defined('_MAILDATE')) {
    define('_MAILDATE', 'Date du courrier');
}
if (!defined('_MD5')) {
    define('_MD5', 'Empreinte Numérique');
}
if (!defined('_WORK_BATCH')) {
    define('_WORK_BATCH', 'Lot de chargement');
}
if (!defined('_DONE')) {
    define('_DONE', 'Actions effectuées');
}
if (!defined('_CLOSING_DATE')) {
    define('_CLOSING_DATE', 'Date de clôture');
}
if (!defined('_FULLTEXT')) {
    define('_FULLTEXT', 'Plein texte');
}
if (!defined('_FULLTEXT_HELP')) {
    define('_FULLTEXT_HELP', 'Recherche dans le contenu des courriers');
}
if (!defined('_FULLTEXT_ERROR')) {
    define('_FULLTEXT_ERROR', "Entrées invalides pour la recherche plein texte. Si vous avez mis le signe \"*\", il doit y avoir au moins 3 caractères devant, et pas de signes comme ,':!+");
}
if (!defined('_FILE_NOT_SEND')) {
    define('_FILE_NOT_SEND', "Le fichier n'a pas été envoyé");
}
if (!defined('_TRY_AGAIN')) {
    define('_TRY_AGAIN', 'Veuillez réessayer');
}
if (!defined('_INDEX_UPDATED')) {
    define('_INDEX_UPDATED', 'Index mis à jour');
}
if (!defined('_UPDATE_DOC_STATUS')) {
    define('_UPDATE_DOC_STATUS', 'Statut du document mis à jour');
}

if (!defined('_DOCTYPE_MANDATORY')) {
    define('_DOCTYPE_MANDATORY', 'Le type de pièce est obligatoire');
}
if (!defined('_CHECK_FORM_OK')) {
    define('_CHECK_FORM_OK', 'Vérification formulaire OK');
}
if (!defined('_MISSING_FORMAT')) {
    define('_MISSING_FORMAT', 'Il manque le format');
}
if (!defined('_ERROR_RES_ID')) {
    define('_ERROR_RES_ID', 'Problème lors du calcul du res_id');
}
if (!defined('_NEW_DOC_ADDED')) {
    define('_NEW_DOC_ADDED', 'Nouveau document enregistré');
}
if (!defined('_STATUS_UPDATED')) {
    define('_STATUS_UPDATED', 'Statut mis à jour');
}

if (!defined('_QUICKLAUNCH')) {
    define('_QUICKLAUNCH', 'Raccourcis');
}
if (!defined('_SHOW_DETAILS_DOC')) {
    define('_SHOW_DETAILS_DOC', 'Voir les détails du document');
}
if (!defined('_VIEW_DOC_FULL')) {
    define('_VIEW_DOC_FULL', 'Voir le document');
}
if (!defined('_DETAILS_DOC_FULL')) {
    define('_DETAILS_DOC_FULL', 'Voir la fiche du document');
}
if (!defined('_IDENTIFIER')) {
    define('_IDENTIFIER', 'Référence');
}
if (!defined('_CHRONO_NUMBER')) {
    define('_CHRONO_NUMBER', 'Numéro chrono');
}
if (!defined('_CHRONO_NUMBER_SHORT')) {
    define('_CHRONO_NUMBER_SHORT', 'chrono');
}
if (!defined('_ATTACHMENT_TYPE')) {
    define('_ATTACHMENT_TYPE', 'Type de pièce jointe');
}
if (!defined('_NO_CHRONO_NUMBER_DEFINED')) {
    define('_NO_CHRONO_NUMBER_DEFINED', "Le numéro chrono n'est pas défini");
}
if (!defined('_FOR_CONTACT_C')) {
    define('_FOR_CONTACT_C', 'Pour : ');
}
if (!defined('_TO_CONTACT_C')) {
    define('_TO_CONTACT_C', 'De : ');
}
if (!defined('_CASE_NUMBER_ERROR')) {
    define('_CASE_NUMBER_ERROR', "Numero de l'affaire n’est pas dans le bon format : Entier attendu");
}
if (!defined('_NUMERO_GED')) {
    define('_NUMERO_GED', "Numero GED n'est pas dans le bon format : Entier attendu");
}

if (!defined('_APPS_COMMENT')) {
    define('_APPS_COMMENT', 'Application Maarch');
}
if (!defined('_CORE_COMMENT')) {
    define('_CORE_COMMENT', 'Coeur du Framework');
}
if (!defined('_CLEAR_FORM')) {
    define('_CLEAR_FORM', 'Effacer le formulaire');
}

if (!defined('_NOT_ALLOWED')) {
    define('_NOT_ALLOWED', 'interdit');
}
if (!defined('_CHOOSE_TITLE')) {
    define('_CHOOSE_TITLE', 'Choisissez une civilité');
}
if (!defined('_INDEXING_STATUSES')) {
    define('_INDEXING_STATUSES', 'Indexer vers les status');
}
if (!defined('_UNCHANGED')) {
    define('_UNCHANGED', 'Inchangé');
}
if (!defined('_PARAM_AVAILABLE_STATUS_ON_GROUP_BASKETS')) {
    define('_PARAM_AVAILABLE_STATUS_ON_GROUP_BASKETS', "Paramétrage des status d'indexation");
}

/******************** Specific ************/
if (!defined('_PROJECT')) {
    define('_PROJECT', 'Dossier');
}
if (!defined('_MARKET')) {
    define('_MARKET', 'Sous-dossier');
}
if (!defined('_DAYS')) {
    define('_DAYS', 'jours');
}
if (!defined('_LAST_DAY')) {
    define('_LAST_DAY', 'Dernier jour');
}
if (!defined('_CONTACT_NAME')) {
    define('_CONTACT_NAME', 'Contact facture');
}
if (!defined('_AMOUNT')) {
    define('_AMOUNT', 'Montant facture');
}
if (!defined('_CUSTOMER')) {
    define('_CUSTOMER', 'Client facture');
}
if (!defined('_PO_NUMBER')) {
    define('_PO_NUMBER', 'BDC facture');
}
if (!defined('_INVOICE_NUMBER')) {
    define('_INVOICE_NUMBER', 'Num facture');
}

/******************** fulltext search Helper ************/
if (!defined('_HELP_GLOBAL_SEARCH')) {
    define('_HELP_GLOBAL_SEARCH', "Recherche sur l'objet, le titre, la description, le contenu du document ou sur le numéro de GED");
}
if (!defined('_HELP_FULLTEXT_SEARCH')) {
    define('_HELP_FULLTEXT_SEARCH', 'Aide sur la recherche plein texte');
}
if (!defined('_GLOBAL_SEARCH')) {
    define('_GLOBAL_SEARCH', 'Recherche globale');
}
if (!defined('_TIPS_FULLTEXT')) {
    define('_TIPS_FULLTEXT', 'Astuces de recherche');
}

if (!defined('_TIPS_KEYWORD1')) {
    define('_TIPS_KEYWORD1', 'Pour effectuer une recherche avec joker sur plusieurs caractères');
}
if (!defined('_TIPS_KEYWORD2')) {
    define('_TIPS_KEYWORD2', 'Pour effectuer une recherche sur un groupe de mots, une phrase');
}

if (!defined('_TIPS_KEYWORD3')) {
    define('_TIPS_KEYWORD3', 'Pour effectuer une recherche approximative');
}

if (!defined('_HELP_FULLTEXT_SEARCH_EXEMPLE1')) {
    define('_HELP_FULLTEXT_SEARCH_EXEMPLE1', '<b>auto*</b> trouve autoroute et automobile ');
}
if (!defined('_HELP_FULLTEXT_SEARCH_EXEMPLE2')) {
    define('_HELP_FULLTEXT_SEARCH_EXEMPLE2', "<b>route nationale</b> trouve l'expression entière route nationale
        <p> <b>Sans guillemet</b> la recherche trouve des documents contenant les mots <b>route<BIG> et </BIG>nationale</b></p>
        <p> N'utilisez surtout pas de tiret ! Pour rechercher des mots contenant un tiret comme sous-préfecture,
        saisissez simplement les mots <b>sous préfecture</b> séparés par un espace. ");
}
if (!defined('_HELP_FULLTEXT_SEARCH_EXEMPLE3')) {
    define('_HELP_FULLTEXT_SEARCH_EXEMPLE3', 'vite~ trouve vote, vite');
}
if (!defined('_TIPS_FULLTEXT_TEXT')) {
    define('_TIPS_FULLTEXT_TEXT', 'La recherche peut se faire sur des nombres');
}
if (!defined('_CLOSE_MAIL')) {
    define('_CLOSE_MAIL', 'Clôturer un courrier');
}
if (!defined('_CLOSE_MAIL_DESC')) {
    define('_CLOSE_MAIL_DESC', "Permet de mettre à jour la date de clôture d'un courrier ('closing_date' de la table res_letterbox). ESSENTIEL afin de terminer votre workflow de courrier.");
}

/******************** Keywords Helper ************/
if (!defined('_HELP_KEYWORD0')) {
    define('_HELP_KEYWORD0', "id de l'utilisateur connecté");
}
if (!defined('_HELP_BY_CORE')) {
    define('_HELP_BY_CORE', 'Mots clés de Maarch Core');
}

if (!defined('_FIRSTNAME_UPPERCASE')) {
    define('_FIRSTNAME_UPPERCASE', 'PRENOM');
}
if (!defined('_TITLE_STATS_USER_LOG')) {
    define('_TITLE_STATS_USER_LOG', "Accès à l'application");
}

if (!defined('_DELETE_DOC')) {
    define('_DELETE_DOC', 'Supprimer ce courrier');
}
if (!defined('_THIS_DOC')) {
    define('_THIS_DOC', 'ce courrier');
}
if (!defined('_MODIFY_DOC')) {
    define('_MODIFY_DOC', 'Modifier des informations');
}
if (!defined('_BACK_TO_WELCOME')) {
    define('_BACK_TO_WELCOME', "Retourner à la page d'accueil");
}
if (!defined('_CLOSE_MAIL')) {
    define('_CLOSE_MAIL', 'Clôturer un courrier');
}

/************** Réouverture courrier **************/

if (!defined('_REF_ID')) {
    define('_REF_ID', 'n° chrono');
}

if (!defined('_OWNER')) {
    define('_OWNER', 'Propriétaire');
}
if (!defined('_OPT_INDEXES')) {
    define('_OPT_INDEXES', 'Informations complémentaires');
}
if (!defined('_NUM_BETWEEN')) {
    define('_NUM_BETWEEN', 'Compris entre');
}
if (!defined('_MUST_CORRECT_ERRORS')) {
    define('_MUST_CORRECT_ERRORS', 'Vous devez corriger les erreurs suivantes ');
}
if (!defined('_CLICK_HERE_TO_CORRECT')) {
    define('_CLICK_HERE_TO_CORRECT', 'Cliquez ici pour les corriger');
}

if (!defined('_FILETYPE')) {
    define('_FILETYPE', 'Type de fichier');
}
if (!defined('_WARNING')) {
    define('_WARNING', 'Attention ');
}
if (!defined('_STRING')) {
    define('_STRING', 'Chaine de caractères');
}
if (!defined('_INTEGER')) {
    define('_INTEGER', 'Entier');
}
if (!defined('_FLOAT')) {
    define('_FLOAT', 'Flottant');
}
if (!defined('_ITEM_NOT_IN_LIST')) {
    define('_ITEM_NOT_IN_LIST', 'Elèment absent de la liste des valeurs autorisées');
}
if (!defined('_PB_WITH_FINGERPRINT_OF_DOCUMENT')) {
    define('_PB_WITH_FINGERPRINT_OF_DOCUMENT', "L'empreinte numérique initiale du document ne correspond pas à celle du document référencé");
}
if (!defined('_MISSING')) {
    define('_MISSING', 'manquant(e)');
}
if (!defined('_NATURE')) {
    define('_NATURE', 'Nature');
}
if (!defined('_NO_DEFINED_TREES')) {
    define('_NO_DEFINED_TREES', 'Aucun arbre défini');
}

if (!defined('_IF_CHECKS_MANDATORY_MUST_CHECK_USE')) {
    define('_IF_CHECKS_MANDATORY_MUST_CHECK_USE', 'Si vous cliquez sur un champ obligatoire, vous devez également cocher la case utilisé');
}
if (!defined('_SEARCH_DOC')) {
    define('_SEARCH_DOC', 'Rechercher un document');
}
if (!defined('_DOCSERVER_COPY_ERROR')) {
    define('_DOCSERVER_COPY_ERROR', ' Erreur lors de la copie sur le DocServer');
}
if (!defined('_MAKE_NEW_SEARCH')) {
    define('_MAKE_NEW_SEARCH', 'Effectuer une nouvelle recherche');
}
if (!defined('_NO_PAGE')) {
    define('_NO_PAGE', 'Aucune page');
}
if (!defined('_DB_CONNEXION_ERROR')) {
    define('_DB_CONNEXION_ERROR', 'Erreur de connexion à la base de données');
}
if (!defined('_DATABASE_SERVER')) {
    define('_DATABASE_SERVER', 'Serveur de base de données');
}
if (!defined('_DB_PORT')) {
    define('_DB_PORT', 'Port');
}
if (!defined('_DB_TYPE')) {
    define('_DB_TYPE', 'Type');
}
if (!defined('_DB_USER')) {
    define('_DB_USER', 'Utilisateur');
}
if (!defined('_DATABASE')) {
    define('_DATABASE', 'Base');
}
if (!defined('_TREE_ROOT')) {
    define('_TREE_ROOT', 'Racine');
}
if (!defined('_MODE')) {
    define('_MODE', 'Mode');
}
if (!defined('_TITLE_STATS_CHOICE_PERIOD')) {
    define('_TITLE_STATS_CHOICE_PERIOD', 'Pour une période');
}

/******************** Authentification method  ************/

if (!defined('_STANDARD_LOGIN')) {
    define('_STANDARD_LOGIN', 'Authentification Maarch');
}
if (!defined('_SERVICE_REST_LOGIN')) {
    define('_SERVICE_REST_LOGIN', 'Authentification via Webservice');
}
if (!defined('_CAS_LOGIN')) {
    define('_CAS_LOGIN', 'Authentification CAS');
}
if (!defined('_ACTIVEX_LOGIN')) {
    define('_ACTIVEX_LOGIN', 'Authentification Ms Internet Explorer - ActiveX');
}
if (!defined('_HOW_CAN_I_LOGIN')) {
    define('_HOW_CAN_I_LOGIN', "Je n'arrive pas à me connecter...");
}
if (!defined('_CONNECT')) {
    define('_CONNECT', 'Se connecter');
}
if (!defined('_LOGIN_MODE')) {
    define('_LOGIN_MODE', "Type d'authentification");
}
if (!defined('_SSO_LOGIN')) {
    define('_SSO_LOGIN', 'Login via SSO');
}
if (!defined('_LDAP')) {
    define('_LDAP', 'Annuaire LDAP ');
}

/******** Admin groups **********/

if (!defined('_WHERE_CLAUSE_TARGET')) {
    define('_WHERE_CLAUSE_TARGET', 'Cible de la clause WHERE');
}
if (!defined('_WHERE_TARGET')) {
    define('_WHERE_TARGET', 'Cible');
}
if (!defined('_DOCS')) {
    define('_DOCS', 'Documents');
}
if (!defined('_GO_MANAGE_USER')) {
    define('_GO_MANAGE_USER', 'Modifier');
}
if (!defined('_GO_MANAGE_DOCSERVER')) {
    define('_GO_MANAGE_DOCSERVER', 'Modifier');
}
if (!defined('_TASKS')) {
    define('_TASKS', 'Actions disponibles sur les courriers');
}
if (!defined('_PERIOD')) {
    define('_PERIOD', 'Période');
}
if (!defined('_COMMENTS_MANDATORY')) {
    define('_COMMENTS_MANDATORY', 'Description obligatoire');
}

/******* Security Bitmask label ********/

if (!defined('_ADD_RECORD_LABEL')) {
    define('_ADD_RECORD_LABEL', 'Ajouter un document');
}
if (!defined('_DATA_MODIFICATION_LABEL')) {
    define('_DATA_MODIFICATION_LABEL', 'Modifier');
}
if (!defined('_DELETE_RECORD_LABEL')) {
    define('_DELETE_RECORD_LABEL', 'Supprimer');
}
if (!defined('_VIEW_LOG_LABEL')) {
    define('_VIEW_LOG_LABEL', 'Voir les journaux');
}

if (!defined('_PLUS')) {
    define('_PLUS', 'Plus');
}
if (!defined('_MINUS')) {
    define('_MINUS', 'Moins');
}

/*********ADMIN DOCSERVERS**********************/
if (!defined('_MANAGE_DOCSERVERS')) {
    define('_MANAGE_DOCSERVERS', 'Gérer les zones de stockage ');
}
if (!defined('_MANAGE_DOCSERVERS_DESC')) {
    define('_MANAGE_DOCSERVERS_DESC', 'Ajouter, modifier, supprimer les zones de stockage ');
}
if (!defined('_MANAGE_DOCSERVERS_LOCATIONS')) {
    define('_MANAGE_DOCSERVERS_LOCATIONS', 'Gérer les lieux de stockage de documents ');
}
if (!defined('_MANAGE_DOCSERVERS_LOCATIONS_DESC')) {
    define('_MANAGE_DOCSERVERS_LOCATIONS_DESC', 'Ajouter, supprimer, modifier les lieux de stockage de documents ');
}
if (!defined('_MANAGE_DOCSERVER_TYPES')) {
    define('_MANAGE_DOCSERVER_TYPES', 'Gérer les types de zones de stockage ');
}
if (!defined('_MANAGE_DOCSERVER_TYPES_DESC')) {
    define('_MANAGE_DOCSERVER_TYPES_DESC', 'Ajouter, modifier, supprimer les types de zones de stockage ');
}
if (!defined('_DOCSERVER_ID')) {
    define('_DOCSERVER_ID', 'Identifiant docserver');
}

/**********DOCSERVERS****************/
if (!defined('_YOU_CANNOT_DELETE')) {
    define('_YOU_CANNOT_DELETE', 'Suppression imposible');
}
if (!defined('_UNKNOWN')) {
    define('_UNKNOWN', 'Inconnu');
}
if (!defined('_UNKOWN')) {
    define('_UNKOWN', 'est inconnu');
}
if (!defined('_YOU_CANNOT_DISABLE')) {
    define('_YOU_CANNOT_DISABLE', 'Suspension impossible');
}
if (!defined('_DOCSERVER_TYPE_DISABLED')) {
    define('_DOCSERVER_TYPE_DISABLED', 'Type de zone de stockage suspendu');
}
if (!defined('_SIZE_LIMIT_UNAPPROACHABLE')) {
    define('_SIZE_LIMIT_UNAPPROACHABLE', 'Taille limite inaccessible');
}
if (!defined('_DOCSERVER_TYPE_ENABLED')) {
    define('_DOCSERVER_TYPE_ENABLED', 'Type de zone de stockage actif');
}
if (!defined('_SIZE_LIMIT_LESS_THAN_ACTUAL_SIZE')) {
    define('_SIZE_LIMIT_LESS_THAN_ACTUAL_SIZE', 'Taille limite inférieure à la taille actuelle');
}
if (!defined('_THE_DOCSERVER_DOES_NOT_HAVE_THE_ADEQUATE_RIGHTS')) {
    define('_THE_DOCSERVER_DOES_NOT_HAVE_THE_ADEQUATE_RIGHTS', "Cette zone de stockage n'a pas les droits suffisants...");
}
if (!defined('_DOCSERVER_DISABLED')) {
    define('_DOCSERVER_DISABLED', 'Zone de stockage suspendue');
}
if (!defined('_DOCSERVER_ENABLED')) {
    define('_DOCSERVER_ENABLED', 'Zone de stockage active');
}
if (!defined('_ALREADY_EXISTS_FOR_THIS_TYPE_OF_DOCSERVER')) {
    define('_ALREADY_EXISTS_FOR_THIS_TYPE_OF_DOCSERVER', 'existe déjà pour ce type');
}
if (!defined('_DOCSERVER_LOCATION_ENABLED')) {
    define('_DOCSERVER_LOCATION_ENABLED', 'Lieu de stockage actif');
}
if (!defined('_LINK_EXISTS')) {
    define('_LINK_EXISTS', 'Un lien avec un autre objet existe');
}

/***************DOCSERVERS TYPES*************************************/
if (!defined('_DOCSERVER_TYPE_ID')) {
    define('_DOCSERVER_TYPE_ID', 'Identifiant du type de zone de stockage ');
}
if (!defined('_DOCSERVER_TYPE')) {
    define('_DOCSERVER_TYPE', 'Type de zone ');
}
if (!defined('_DOCSERVER_TYPES_LIST')) {
    define('_DOCSERVER_TYPES_LIST', 'Liste de types de zone de stockage');
}
if (!defined('_ALL_DOCSERVER_TYPES')) {
    define('_ALL_DOCSERVER_TYPES', 'Tout afficher ');
}
if (!defined('_DOCSERVER_TYPE_LABEL')) {
    define('_DOCSERVER_TYPE_LABEL', 'Label du type de zone de stockage ');
}
if (!defined('_DOCSERVER_TYPES')) {
    define('_DOCSERVER_TYPES', 'Type(s) de zone de stockage ');
}
if (!defined('_IS_CONTAINER')) {
    define('_IS_CONTAINER', 'Conteneur');
}
if (!defined('_IS_COMPRESSED')) {
    define('_IS_COMPRESSED', 'Compressé');
}
if (!defined('_IS_META')) {
    define('_IS_META', 'Contient les métadonnées');
}
if (!defined('_IS_LOGGED')) {
    define('_IS_LOGGED', 'Contient les journaux');
}
if (!defined('_IS_SIGNED')) {
    define('_IS_SIGNED', 'Contient une empreinte');
}
if (!defined('_COMPRESS_MODE')) {
    define('_COMPRESS_MODE', 'Mode de compression');
}
if (!defined('_META_TEMPLATE')) {
    define('_META_TEMPLATE', 'Modèle de métadonnées');
}
if (!defined('_LOG_TEMPLATE')) {
    define('_LOG_TEMPLATE', 'Modèle de journal');
}
if (!defined('_FINGERPRINT_MODE')) {
    define('_FINGERPRINT_MODE', "Algorythme de calcul d'empreinte");
}
if (!defined('_CONTAINER_MAX_NUMBER')) {
    define('_CONTAINER_MAX_NUMBER', 'Taille maximale du conteneur');
}
if (!defined('_DOCSERVER_TYPE_MODIFICATION')) {
    define('_DOCSERVER_TYPE_MODIFICATION', 'Modification de type de zone de stockage ');
}
if (!defined('_DOCSERVER_TYPE_ADDITION')) {
    define('_DOCSERVER_TYPE_ADDITION', 'Ajouter un type de zone de stockage');
}
if (!defined('_DOCSERVER_TYPE_ADDED')) {
    define('_DOCSERVER_TYPE_ADDED', 'Type de zone de stockage ajouté ');
}
if (!defined('_DOCSERVER_TYPE_UPDATED')) {
    define('_DOCSERVER_TYPE_UPDATED', 'Type de zone de stockage mis à jour ');
}
if (!defined('_DOCSERVER_TYPE_DELETED')) {
    define('_DOCSERVER_TYPE_DELETED', 'Type de zone de stockage supprimé ');
}
if (!defined('_NOT_CONTAINER')) {
    define('_NOT_CONTAINER', 'Pas un container');
}
if (!defined('_CONTAINER')) {
    define('_CONTAINER', 'Un container');
}
if (!defined('_NOT_COMPRESSED')) {
    define('_NOT_COMPRESSED', 'Non compressé');
}
if (!defined('_COMPRESSED')) {
    define('_COMPRESSED', 'Compressé');
}
if (!defined('_COMPRESSION_MODE')) {
    define('_COMPRESSION_MODE', 'Mode de compression');
}
if (!defined('_GZIP_COMPRESSION_MODE')) {
    define('_GZIP_COMPRESSION_MODE', 'Mode de compression GZIP (tar.gz) est uniquement disponible pour la consultation');
}

/***************DOCSERVERS*********************************/
if (!defined('_DOCSERVERS')) {
    define('_DOCSERVERS', 'Zone(s) de stockage ');
}
if (!defined('_DEVICE_LABEL')) {
    define('_DEVICE_LABEL', 'Label dispositif ');
}
if (!defined('_SIZE_FORMAT')) {
    define('_SIZE_FORMAT', 'Format de la taille ');
}
if (!defined('_SIZE_LIMIT')) {
    define('_SIZE_LIMIT', 'Taille maximale ');
}
if (!defined('_ACTUAL_SIZE')) {
    define('_ACTUAL_SIZE', 'Taille actuelle ');
}
if (!defined('_COLL_ID')) {
    define('_COLL_ID', 'Identifiant de la collection');
}
if (!defined('_PATH_TEMPLATE')) {
    define('_PATH_TEMPLATE', "Chemin d'accès");
}
if (!defined('_ADR_PRIORITY')) {
    define('_ADR_PRIORITY', 'Priorité de sequence de zone de stockage');
}
if (!defined('_IS_READONLY')) {
    define('_IS_READONLY', 'Autorisé en lecture seule');
}
if (!defined('_PERCENTAGE_FULL')) {
    define('_PERCENTAGE_FULL', 'Pourcentage de remplissage');
}
if (!defined('_PATH_OF_DOCSERVER_UNAPPROACHABLE')) {
    define('_PATH_OF_DOCSERVER_UNAPPROACHABLE', 'Chemin inaccessible ');
}
if (!defined('_ALL_DOCSERVERS')) {
    define('_ALL_DOCSERVERS', 'Tout afficher ');
}
if (!defined('_DOCSERVER')) {
    define('_DOCSERVER', 'un docserver');
}
if (!defined('_DOCSERVER_MODIFICATION')) {
    define('_DOCSERVER_MODIFICATION', 'Modification de zone de stockage');
}
if (!defined('_DOCSERVER_ADDITION')) {
    define('_DOCSERVER_ADDITION', 'Ajouter une zone de stockage');
}
if (!defined('_DOCSERVER_UPDATED')) {
    define('_DOCSERVER_UPDATED', 'Zone de stockage mise à jour');
}
if (!defined('_DOCSERVER_DELETED')) {
    define('_DOCSERVER_DELETED', 'Zone de stockage supprimée');
}
if (!defined('_DOCSERVER_ADDED')) {
    define('_DOCSERVER_ADDED', 'Zone de stockage ajoutée');
}
if (!defined('_DOCSERVERS_LIST')) {
    define('_DOCSERVERS_LIST', 'Liste des zones de stockage ');
}
if (!defined('_GB')) {
    define('_GB', 'Gigaoctets ');
}
if (!defined('_TB')) {
    define('_TB', 'Teraoctets ');
}
if (!defined('_MB')) {
    define('_MB', 'Megaoctets ');
}
if (!defined('_SIZE_LIMIT_NUMBER')) {
    define('_SIZE_LIMIT_NUMBER', 'Taille limite');
}
if (!defined('_DOCSERVER_ATTACHED_TO_RES_X')) {
    define('_DOCSERVER_ATTACHED_TO_RES_X', 'Des documents sont stockés sur cette espace de stockage');
}

/************DOCSERVER LOCATIONS******************************/
if (!defined('_IPV4')) {
    define('_IPV4', 'Adresse IPv4');
}
if (!defined('_IPV6')) {
    define('_IPV6', 'Adresse IPv6');
}
if (!defined('_NET_DOMAIN')) {
    define('_NET_DOMAIN', 'Domaine');
}
if (!defined('_MASK')) {
    define('_MASK', 'Masque');
}
if (!defined('_NET_LINK')) {
    define('_NET_LINK', 'URL du frontal');
}
if (!defined('_IP_V4_ADRESS_NOT_VALID')) {
    define('_IP_V4_ADRESS_NOT_VALID', 'Adresse IPV4 inaccessible');
}
if (!defined('_IP_V4_FORMAT_NOT_VALID')) {
    define('_IP_V4_FORMAT_NOT_VALID', 'Mauvais format adresse IPV4');
}
if (!defined('_IP_V6_NOT_VALID')) {
    define('_IP_V6_NOT_VALID', 'Mauvais format adresse IPV6');
}
if (!defined('_MASK_NOT_VALID')) {
    define('_MASK_NOT_VALID', 'Masque non valide');
}

/************FAILOVER******************************/
if (!defined('_FAILOVER')) {
    define('_FAILOVER', 'Reprise sur erreur');
}
if (!defined('_FILE_NOT_EXISTS_ON_THE_SERVER')) {
    define('_FILE_NOT_EXISTS_ON_THE_SERVER', "Le fichier n'existe pas sur le docserver");
}
if (!defined('_NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS')) {
    define('_NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS', 'Aucun droit sur la resource demandée ou elle est non disponible');
}

if (!defined('_PROCESS_DELAY')) {
    define('_PROCESS_DELAY', 'Délai de traitementee');
}
if (!defined('_ALERT_DELAY_1')) {
    define('_ALERT_DELAY_1', 'Délai de 1ere alerte');
}
if (!defined('_ALERT_DELAY_2')) {
    define('_ALERT_DELAY_2', 'Délai de 2eme alerte');
}

if (!defined('_ERROR_PARAMETERS_FUNCTION')) {
    define('_ERROR_PARAMETERS_FUNCTION', 'Erreur de paramètres...');
}
if (!defined('_SYNTAX_OK')) {
    define('_SYNTAX_OK', 'Syntaxe OK');
}

/************TECHNICAL INFOS******************************/
if (!defined('_TECHNICAL_INFORMATIONS')) {
    define('_TECHNICAL_INFORMATIONS', 'Infos techniques');
}
if (!defined('_SOURCE_FILE_PROPERTIES')) {
    define('_SOURCE_FILE_PROPERTIES', 'Propriétés du fichier source');
}
if (!defined('_FINGERPRINT')) {
    define('_FINGERPRINT', 'Empreinte numérique');
}
if (!defined('_OFFSET')) {
    define('_OFFSET', 'Offset');
}
if (!defined('_SETUP')) {
    define('_SETUP', 'Configurer');
}

if (!defined('_WELCOME_TEXT_LOAD')) {
    define('_WELCOME_TEXT_LOAD', "Chargement texte page d'accueil");
}
if (!defined('_WRONG_FUNCTION_OR_WRONG_PARAMETERS')) {
    define('_WRONG_FUNCTION_OR_WRONG_PARAMETERS', 'Mauvais appel ou mauvaus paramètre');
}
if (!defined('_INDEXING_INSERT_ERROR')) {
    define('_INDEXING_INSERT_ERROR', "Indexation : erreur lors de l'insertion");
}
if (!defined('_LOGIN_HISTORY')) {
    define('_LOGIN_HISTORY', "Connexion de l'utilisateur");
}
if (!defined('_LOGOUT_HISTORY')) {
    define('_LOGOUT_HISTORY', "Déconnexion de l'utilisateur");
}
if (!defined('_TO_MASTER_DOCUMENT')) {
    define('_TO_MASTER_DOCUMENT', 'au document maitre n°');
}

//print details
if (!defined('_DETAILS_PRINT')) {
    define('_DETAILS_PRINT', 'Fiche de liaison N°');
}
if (!defined('_NOTES_1')) {
    define('_NOTES_1', 'Exemple Annotations service 1');
}
if (!defined('_NOTES_2')) {
    define('_NOTES_2', 'Exemple Annotations service 2');
}
if (!defined('_NOTES_3')) {
    define('_NOTES_3', 'Exemple Annotations service 3');
}
if (!defined('_WHERE_CLAUSE_NOT_SECURE')) {
    define('_WHERE_CLAUSE_NOT_SECURE', 'Clause where non sécurisée');
}
if (!defined('_SQL_QUERY_NOT_SECURE')) {
    define('_SQL_QUERY_NOT_SECURE', 'requete SQL non sécurisée');
}

//service to put doc on validation from details page
if (!defined('_PUT_DOC_ON_VALIDATION')) {
    define('_PUT_DOC_ON_VALIDATION', 'Envoyer le document en validation');
}
if (!defined('_REALLY_PUT_DOC_ON_VALIDATION')) {
    define('_REALLY_PUT_DOC_ON_VALIDATION', "Confirmer l\'envoi en validation");
}

if (!defined('_CAN_T_CONNECT_WITH_THIS_IP')) {
    define('_CAN_T_CONNECT_WITH_THIS_IP', 'Vous ne pouvez pas vous connecter depuis un emplacement non répertorié.');
}

if (!defined('_LOADING_INFORMATIONS')) {
    define('_LOADING_INFORMATIONS', 'Chargement des informations');
}
if (!defined('_BY')) {
    define('_BY', 'par');
}
if (!defined('_REVERSE_CHECK')) {
    define('_REVERSE_CHECK', 'Inverser la sélection');
}
if (!defined('_CHECK_ALL')) {
    define('_CHECK_ALL', 'Tout cocher');
}
if (!defined('_UNCHECK_ALL')) {
    define('_UNCHECK_ALL', '/ décocher');
}

//EXPORT
if (!defined('_EXPORT_LIST')) {
    define('_EXPORT_LIST', 'Exporter (format csv)');
}

/******************** Action put in copy ************/
if (!defined('_ADD_LINKS')) {
    define('_ADD_LINKS', 'Ajouter des liaisons');
}
if (!defined('_DEL_LINK')) {
    define('_DEL_LINK', 'Supprimer la liaison');
}
if (!defined('_LINK_TO_DOC')) {
    define('_LINK_TO_DOC', 'Lier à un courrier');
}
if (!defined('_LINK_REFERENCE')) {
    define('_LINK_REFERENCE', 'Pour lier vous devez choisir un courrier existant');
}
if (!defined('_LINKED_TO')) {
    define('_LINKED_TO', 'Lié au courrier ');
}
if (!defined('_NOW_LINK_WITH_THIS_ONE')) {
    define('_NOW_LINK_WITH_THIS_ONE', ' est maintenant lié à ce courrier');
}
if (!defined('_ARE_NOW_LINK_WITH_THIS_ONE')) {
    define('_ARE_NOW_LINK_WITH_THIS_ONE', ' sont maintenant liés à ce courrier');
}
if (!defined('_ARE_NOW_LINK_WITH_MANY_DOCUMENTS')) {
    define('_ARE_NOW_LINK_WITH_MANY_DOCUMENTS', ' est lié à plusieurs courriers');
}
if (!defined('_LINK_TAB')) {
    define('_LINK_TAB', 'Liaisons');
}
if (!defined('_LINK_DESC_FOR')) {
    define('_LINK_DESC_FOR', 'Document(s) liés à ce courrier');
}
if (!defined('_LINK_ASC_FOR')) {
    define('_LINK_ASC_FOR', 'Document(s) au(x)quel(s) est lié ce courrier');
}
if (!defined('_ADD_A_LINK')) {
    define('_ADD_A_LINK', 'Ajouter une liaison');
}
if (!defined('_LINK_ACTION')) {
    define('_LINK_ACTION', 'Lier');
}
if (!defined('_LINK_ALREADY_EXISTS')) {
    define('_LINK_ALREADY_EXISTS', 'Cette liaison existe déjà');
}
if (!defined('_THE_DOCUMENT_LINK')) {
    define('_THE_DOCUMENT_LINK', 'Le courrier ');
}
if (!defined('_THE_DOCUMENTS_LINK')) {
    define('_THE_DOCUMENTS_LINK', 'Les courriers ');
}
if (!defined('_LINK_TO_THE_DOCUMENT')) {
    define('_LINK_TO_THE_DOCUMENT', 'Le lien au courrier ');
}
if (!defined('_NO_LINK_WITH_THIS_ONE')) {
    define('_NO_LINK_WITH_THIS_ONE', "n'est plus lié, à celui ci");
}
if (!defined('_LINK_DELETED')) {
    define('_LINK_DELETED', 'à été supprimé');
}

/******************** Versions ************/
if (!defined('_VERSIONS')) {
    define('_VERSIONS', 'Versions');
}
if (!defined('_CREATE_NEW_VERSION')) {
    define('_CREATE_NEW_VERSION', 'Créer une nouvelle version de document');
}
if (!defined('_CONTENT_MANAGEMENT_COMMENT')) {
    define('_CONTENT_MANAGEMENT_COMMENT', 'Gestion des Versions de document');
}
if (!defined('_VIEW_VERSIONS')) {
    define('_VIEW_VERSIONS', 'Voir les versions de document');
}
if (!defined('_ADD_NEW_VERSION')) {
    define('_ADD_NEW_VERSION', 'Ajouter une nouvelle version de document');
}
if (!defined('_VIEW_ORIGINAL')) {
    define('_VIEW_ORIGINAL', 'Voir le document original');
}
if (!defined('_PJ')) {
    define('_PJ', 'Pièces jointes');
}

/******************** Liste avec réponses ************/
if (!defined('_CONSULT')) {
    define('_CONSULT', 'Consulter');
}
if (!defined('_DOCUMENTS_LIST_WITH_ATTACHMENTS')) {
    define('_DOCUMENTS_LIST_WITH_ATTACHMENTS', 'Liste avec filtre et réponses');
}
if (!defined('_DOCUMENTS_LIST_BY_MODIFICATION')) {
    define('_DOCUMENTS_LIST_BY_MODIFICATION', 'Liste filtrée par date de modification');
}
if (!defined('_QUALIFY_FIRST')) {
    define('_QUALIFY_FIRST', 'La fiche détaillée est vide car le courrier doit être qualifié');
}

/******************** persistent mode ************/
if (!defined('_SET_PERSISTENT_MODE_ON')) {
    define('_SET_PERSISTENT_MODE_ON', 'Activer la persistance');
}
if (!defined('_SET_PERSISTENT_MODE_ON_DESC')) {
    define('_SET_PERSISTENT_MODE_ON_DESC', "Permet de conserver un courrier dans une bannette quelque soit son état. Insère la donnée dans la table 'basket_persistent_mode'.");
}
if (!defined('_SET_PERSISTENT_MODE_OFF')) {
    define('_SET_PERSISTENT_MODE_OFF', 'Désactiver la persistance');
}
if (!defined('_SET_PERSISTENT_MODE_OFF_DESC')) {
    define('_SET_PERSISTENT_MODE_OFF_DESC', "Réinitialise le comportement de visualisation du courrier dans la bannette. Supprime la donnée dans la table 'basket_persistent_mode'.");
}

/************************ Lists ************************/
if (!defined('_ADMIN_LISTS')) {
    define('_ADMIN_LISTS', 'Gestions des listes');
}
if (!defined('_ADMIN_LISTS_DESC')) {
    define('_ADMIN_LISTS_DESC', 'Définir les listes de resultats.');
}
if (!defined('_LISTS_LIST')) {
    define('_LISTS_LIST', 'Listes');
}
if (!defined('_LISTS_COMMENT')) {
    define('_LISTS_COMMENT', 'Gestion des listes');
}
if (!defined('_LOCK_LIST')) {
    define('_LOCK_LIST', 'Verrouillage de liste');
}
if (!defined('_LOCKED')) {
    define('_LOCKED', 'Verrouillé');
}
if (!defined('_PRINCIPAL_LIST')) {
    define('_PRINCIPAL_LIST', 'Liste principale');
}
if (!defined('_SUBLIST')) {
    define('_SUBLIST', 'Sous-liste');
}
if (!defined('_TOGGLE')) {
    define('_TOGGLE', 'Afficher / Masquer');
}
if (!defined('_HELP_LIST_KEYWORDS')) {
    define('_HELP_LIST_KEYWORDS', 'Aide sur les clauses de verrouillage');
}
if (!defined('_HELP_LIST_KEYWORD1')) {
    define('_HELP_LIST_KEYWORD1', '<b>Les opérateurs de comparaison</b> permettent de comparer deux valeurs: a == b :Egal, a <> b ou a != b :Différent, a < b : Plus petit que, a > b : Plus grand.');
}
if (!defined('_HELP_LIST_KEYWORD2')) {
    define('_HELP_LIST_KEYWORD2', '<b>Les opérateurs logiques</b>: a && b: ET ( And )   Vrai si a ET b sont vrais, a || b OU ( Or ) Vrai si a OU b est vrai.');
}
if (!defined('_HELP_LIST_KEYWORD_EXEMPLE_TITLE')) {
    define('_HELP_LIST_KEYWORD_EXEMPLE_TITLE', "Condition de verrouillage des lignes la liste/sous-liste.<br><br>L'ajout du paramètre <b>@@nom_du_champ@@</b> permet de faire référence à la valeur du champ de critère. Il est possible de mettre plusieurs @@nom_du_champ@@ différents dans la déclaration.");
}
if (!defined('_HELP_LIST_KEYWORD_EXEMPLE')) {
    define('_HELP_LIST_KEYWORD_EXEMPLE', "Ex: @@status@@ <> 'NEW' || '@@type_id@@ <> '10'<br><br>Ex: (@@doctype_secon_level =='50' && @@dest_user@@=='bblier\") || doctype_secon_level == '10'");
}
if (!defined('_SYNTAX_ERROR_LOCK_CLAUSE')) {
    define('_SYNTAX_ERROR_LOCK_CLAUSE', 'Erreur dans la syntaxe de la clause de verrouillage');
}
if (!defined('_DOCUMENTS_LIST_WITH_FILTERS')) {
    define('_DOCUMENTS_LIST_WITH_FILTERS', 'Liste avec filtres');
} //liste
if (!defined('_DOCUMENTS_LIST_WITH_ATTACHMENTS')) {
    define('_DOCUMENTS_LIST_WITH_ATTACHMENTS', 'Liste avec filtre et réponses');
} //liste
if (!defined('_DOCUMENTS_LIST_COPIES')) {
    define('_DOCUMENTS_LIST_COPIES', 'Liste des copies');
} //liste + template
if (!defined('_DOCUMENTS_LIST_EXTEND')) {
    define('_DOCUMENTS_LIST_EXTEND', 'Liste étendue');
} //liste + template
if (!defined('_DOCUMENTS_LIST')) {
    define('_DOCUMENTS_LIST', 'Liste simple');
} //template
if (!defined('_DOCUMENTS_LIST_SEARCH')) {
    define('_DOCUMENTS_LIST_SEARCH', 'Liste étendue');
} //template
if (!defined('_CLICK_ICON_TO_TOGGLE')) {
    define('_CLICK_ICON_TO_TOGGLE', "Cliquez sur l'icone pour Afficher / Masquer");
}
if (!defined('_SHOW')) {
    define('_SHOW', 'Afficher');
}
if (!defined('_LINES')) {
    define('_LINES', ' lignes');
}
if (!defined('_NO_TEMPLATE_FILE_AVAILABLE')) {
    define('_NO_TEMPLATE_FILE_AVAILABLE', 'Template non disponible');
}

if (!defined('_GED')) {
    define('_GED', 'n° Ged');
}

//EMAIL INDEXES
if (!defined('_EMAIL_FROM_ADDRESS')) {
    define('_EMAIL_FROM_ADDRESS', 'Email de');
}
if (!defined('_EMAIL_TO_ADDRESS')) {
    define('_EMAIL_TO_ADDRESS', 'Email pour');
}
if (!defined('_EMAIL_CC_ADDRESS')) {
    define('_EMAIL_CC_ADDRESS', 'Email copie');
}
if (!defined('_EMAIL_ID')) {
    define('_EMAIL_ID', 'Email ID');
}
if (!defined('_EMAIL_ACCOUNT')) {
    define('_EMAIL_ACCOUNT', 'Email compte');
}
if (!defined('_HELP_KEYWORD_EMAIL')) {
    define('_HELP_KEYWORD_EMAIL', "Email de l'utilisateur connecté");
}
if (!defined('_EMAIL_DRAFT_SAVED')) {
    define("_EMAIL_DRAFT_SAVED", "Brouillon enregistré");
}

if (!defined('_INITIATOR')) {
    define('_INITIATOR', 'Entité initiatrice');
}
if (!defined('_INITIATORS')) {
    define('_INITIATORS', 'Entité(s) initiatrice(s)');
}

if (!defined('_QUALIF_BUSINESS')) {
    define('_QUALIF_BUSINESS', 'Qualification des documents de la collection business');
}
if (!defined('_PROCESS_BUSINESS')) {
    define('_PROCESS_BUSINESS', 'Traitement des documents de la collection business');
}
if (!defined('_BUSINESS_LIST')) {
    define('_BUSINESS_LIST', 'Liste de documents business');
}

if (!defined('_INDEXING_BUSINESS')) {
    define('_INDEXING_BUSINESS', '[business] Enregistrer un document');
}
if (!defined('_ADV_SEARCH_BUSINESS')) {
    define('_ADV_SEARCH_BUSINESS', '[business] Rechercher un document');
}

if (!defined('_DEPARTMENT_OWNER')) {
    define('_DEPARTMENT_OWNER', 'Entité propriétaire');
}

/********************Parameters **************/
if (!defined('_PARAMETER')) {
    define('_PARAMETER', 'Paramètre');
}
if (!defined('_PARAMETER_S')) {
    define('_PARAMETER_S', 'Paramètre(s)');
}
if (!defined('_ALL_PARAMETERS')) {
    define('_ALL_PARAMETERS', 'Tous');
}
if (!defined('_ADMIN_PARAMETERS')) {
    define('_ADMIN_PARAMETERS', 'Gérer les paramètres');
}
if (!defined('_ADMIN_PRIORITIES')) {
    define('_ADMIN_PRIORITIES', 'Priorités');
}
if (!defined('_PRIORITY_DAYS')) {
    define('_PRIORITY_DAYS', 'Délai de traitement en jours');
}
if (!defined('_WORKING_DAYS')) {
    define('_WORKING_DAYS', 'Jours ouvrés');
}
if (!defined('_CALENDAR_DAYS')) {
    define('_CALENDAR_DAYS', 'Jours calendaires');
}

if (!defined('_ADD_PARAMETER')) {
    define('_ADD_PARAMETER', 'Nouveau paramètre');
}
if (!defined('_VALUE')) {
    define('_VALUE', 'Valeur');
}
if (!defined('_STRING')) {
    define('_STRING', 'Chaîne de caractères');
}
if (!defined('_INT')) {
    define('_INT', 'Nombre entier');
}
if (!defined('_DATE')) {
    define('_DATE', 'Date');
}
if (!defined('_ID_IS_MANDATORY')) {
    define('_ID_IS_MANDATORY', 'Identifiant obligatoire');
}
if (!defined('_INVALID_PARAMETER_ID')) {
    define('_INVALID_PARAMETER_ID', 'Identifiant invalide (seuls les caractères A-Z, a-z, 0-9 et _ sont autorisés');
}
if (!defined('_VALUE_IS_MANDATORY')) {
    define('_VALUE_IS_MANDATORY', 'Valeur obligatoire');
}

if (!defined('_GLOBAL_SEARCH_BUSINESS')) {
    define('_GLOBAL_SEARCH_BUSINESS', 'Recherche globale de documents');
}
if (!defined('_QUICK_SEARCH')) {
    define('_QUICK_SEARCH', 'Recherche rapide');
}

if (!defined('_FROM_WS')) {
    define('_FROM_WS', 'Depuis un web service');
}
if (!defined('_DOCUMENT')) {
    define('_DOCUMENT', 'document');
}

if (!defined('_NOT_EXISTS')) {
    define('_NOT_EXISTS', "n'existe pas");
}

/*************** FOLDER **************/

//***Business Collection***/

if (!defined('_CHOOSE_TYPE')) {
    define('_CHOOSE_TYPE', 'Choisissez une typologie');
}
if (!defined('_DEPARTMENT_OWNER')) {
    define('_DEPARTMENT_OWNER', "Entité d'appartenance");
}
if (!defined('_FOLDER')) {
    define('_FOLDER', 'Dossier seriel');
}
//choose status on valid
if (!defined('_CHOOSE_CURRENT_STATUS')) {
    define('_CHOOSE_CURRENT_STATUS', 'Conserver statut actuel');
}

//PRINT
if (!defined('_PRINT_DETAILS')) {
    define('_PRINT_DETAILS', 'Imprimer fiche de liaison');
}
if (!defined('_PRINT_DOC_FROM_LIST')) {
    define('_PRINT_DOC_FROM_LIST', 'Imprimer les fiches de liaison des courriers');
}
if (!defined('_PRINT_LIST')) {
    define('_PRINT_LIST', 'Imprimer la liste');
}
if (!defined('_PRINT_CATEGORY')) {
    define('_PRINT_CATEGORY', 'Catégorie');
}
if (!defined('_PRINT_DOC_DATE')) {
    define('_PRINT_DOC_DATE', 'Date du courrier');
}
if (!defined('_PRINT_PROCESS_LIMIT_DATE')) {
    define('_PRINT_PROCESS_LIMIT_DATE', 'Date limite de traitement');
}
if (!defined('_PRINT_PRIORITY')) {
    define('_PRINT_PRIORITY', 'Priorité');
}
if (!defined('_PRINT_CONTACT')) {
    define('_PRINT_CONTACT', 'CONTACT');
}
if (!defined('_PRINT_SUBJECT')) {
    define('_PRINT_SUBJECT', 'OBJET');
}
if (!defined('_PRINT_DATE')) {
    define('_PRINT_DATE', "Date d'impression");
}
if (!defined('_PRINT_FOLDER')) {
    define('_PRINT_FOLDER', 'Dossier');
}
if (!defined('_PRINT_ARBOX')) {
    define('_PRINT_ARBOX', "Boite d'archive");
}
if (!defined('_PRINT_STATUS')) {
    define('_PRINT_STATUS', 'Statut');
}
if (!defined('_PRINT_ALT_IDENTIFIER')) {
    define('_PRINT_ALT_IDENTIFIER', 'Numéro chrono');
}
if (!defined('_PRINTED_FILE_NUMBER')) {
    define('_PRINTED_FILE_NUMBER', 'Fiche de liaison');
}
if (!defined('_CREATED_ON')) {
    define('_CREATED_ON', 'Créé le');
}

//MULTICONTACTS
if (!defined('_MULTI')) {
    define('_MULTI', 'Multi');
}
if (!defined('_MULTI_CONTACTS')) {
    define('_MULTI_CONTACTS', 'Contacts multiples');
}
if (!defined('_CONTACT_EXTERNAL')) {
    define('_CONTACT_EXTERNAL', 'Contact externe');
}
if (!defined('_CONTACT_INTERNAL')) {
    define('_CONTACT_INTERNAL', 'Contact interne');
}

//RECOMMANDE
if (!defined('_MONITORING_NUMBER')) {
    define('_MONITORING_NUMBER', 'N° recommandé');
}

//EXPORT CONTACT
if (!defined('_EXPORT_CONTACT')) {
    define('_EXPORT_CONTACT', 'Exporter les contacts');
}

//INDEXATION WITHOUT FILE
if (!defined('_WITHOUT_FILE')) {
    define('_WITHOUT_FILE', 'Sans fichier');
}

//ONLY ALPHANUM
if (!defined('_ONLY_ALPHANUM')) {
    define('_ONLY_ALPHANUM', 'Seuls les caractères alphanumériques sont acceptés');
}
if (!defined('_ONLY_ALPHABETIC')) {
    define('_ONLY_ALPHABETIC', 'Seuls les caractères alphabétiques sont acceptés');
}

if (!defined('_CLOSE_MAIL_AND_INDEX')) {
    define('_CLOSE_MAIL_AND_INDEX', "Clôturer un courrier et lancer l'indexation");
}
if (!defined('_CLOSE_MAIL_AND_INDEX_DESC')) {
    define('_CLOSE_MAIL_AND_INDEX_DESC', "Permet de mettre à jour la date de clôture d'un courrier ('closing_date' de la table res_letterbox) ET ouvre la page d'indexation afin d'enregistrer un nouveau courrier.");
}
if (!defined('_DOC_NOT_CLOSED')) {
    define('_DOC_NOT_CLOSED', "Ce courrier n'est pas clôturé");
}

if (!defined('_CHOOSE_ENTITY_SUBENTITIES')) {
    define('_CHOOSE_ENTITY_SUBENTITIES', 'Choisissez une entité (+ sous-entité(s))');
}

if (!defined('_TAG_ADMIN')) {
    define('_TAG_ADMIN', 'Administration des mot-clé');
}

if (!defined('_REFERENCE_MAIL')) {
    define('_REFERENCE_MAIL', 'Référence courrier expéditeur');
}

if (!defined('_OTHERS_INFORMATIONS')) {
    define('_OTHERS_INFORMATIONS', 'Autres informations (signataires, consignes, etc...)');
}

if (!defined('_ALL_HISTORY')) {
    define('_ALL_HISTORY', 'Historique complet');
}

if (!defined('_DESCRIPTION')) {
    define('_DESCRIPTION', 'Description');
}

if (!defined('_MOVE_CONTACT_ADDRESS')) {
    define('_MOVE_CONTACT_ADDRESS', "Déplacement de l'adresse vers un autre contact");
}
if (!defined('_INFO_MOVE_CONTACT_ADDRESS')) {
    define('_INFO_MOVE_CONTACT_ADDRESS', "Cette partie est utilisée si vous souhaitez déplacer l'adresse vers un nouveau contact. Les documents (s'il y en a) resteront attachés à cette même adresse.");
}

if (!defined('_MOVE')) {
    define('_MOVE', 'Déplacer');
}
if (!defined('_DELETE_CONTACT_ADDRESS')) {
    define('_DELETE_CONTACT_ADDRESS', "Supprimer l'adresse");
}
if (!defined('_REALLY_MOVE')) {
    define('_REALLY_MOVE', 'Voulez-vous vraiment déplacer ');
}

if (!defined('_ADDRESS_MOVED')) {
    define('_ADDRESS_MOVED', 'Adresse déplacée');
}

if (!defined('_SAVE_MODIFICATION')) {
    define('_SAVE_MODIFICATION', 'Enregistrer les modifications');
}

if (!defined('_CONFIDENTIALITY')) {
    define('_CONFIDENTIALITY', 'Confidentiel');
}
if (!defined('_CONFIDENTIAL')) {
    define('_CONFIDENTIAL', 'Confidentiel');
}
if (!defined('_CONFIDENTIAL_DOCUMENTS')) {
    define('_CONFIDENTIAL_DOCUMENTS', 'courrier(s) confidentiel(s)');
}

if (!defined('_SIGNATORY_NAME')) {
    define('_SIGNATORY_NAME', 'Nom du signataire');
}
if (!defined('_SIGNATORY_GROUP')) {
    define('_SIGNATORY_GROUP', 'Groupe du signataire');
}

if (!defined('_FORMAT_PHONE')) {
    define('_FORMAT_PHONE', 'Format : 06 01 02 03 04');
}

if (!defined('_SIGNATURE')) {
    define('_SIGNATURE', 'Signature');
}

// Actions parapheur
if (!defined('_SEND_MAIL')) {
    define('_SEND_MAIL', 'Envoi du dossier par courriel');
}
if (!defined('_IMPRIM_DOSSIER')) {
    define('_IMPRIM_DOSSIER', 'Impression du dossier');
}
if (!defined('_PROCEED_WORKFLOW')) {
    define('_PROCEED_WORKFLOW', 'Poursuivre le circuit de visa');
}
if (!defined('_PROCEED_WORKFLOW_DESC')) {
    define('_PROCEED_WORKFLOW_DESC', "Met à jour la date du visa de l'actuel viseur / signataire présent dans le circuit de visa du courrier ('process_date' de la table listinstance).");
}
if (!defined('_INTERRUPT_WORKFLOW')) {
    define('_INTERRUPT_WORKFLOW', 'Interrompre le circuit de visa');
}
if (!defined('_INTERRUPT_WORKFLOW_DESC')) {
    define('_INTERRUPT_WORKFLOW_DESC', "Met à jour la date du visa de l'actuel viseur / signataire et de tous les viseurs suivant présents dans le circuit de visa du courrier ('process_date' de la table listinstance). Insère également un message d'interruption sur le viseur actuel ('process_comment' de la table listinstance).");
}
if (!defined('_RESET_VISA_WORKFLOW')) {
    define('_RESET_VISA_WORKFLOW', 'Réinitialiser le circuit de visa');
}
if (!defined('_RESET_VISA_WORKFLOW_DESC')) {
    define('_RESET_VISA_WORKFLOW_DESC', "Réinitialise la date de visa de tous les viseurs présents dans le circuit de visa du courrier ('process_date' de la table listinstance).");
}
if (!defined('_REJECTION_WORKFLOW_PREVIOUS')) {
    define('_REJECTION_WORKFLOW_PREVIOUS', 'Refuser le visa - retour au précédent viseur');
}
if (!defined('_REJECTION_WORKFLOW_PREVIOUS_DESC')) {
    define('_REJECTION_WORKFLOW_PREVIOUS_DESC', "Réinitialise la date de visa du précédent viseur présent dans le circuit de visa du courrier ('process_date' de la table listinstance).");
}
if (!defined('_REDIRECT_WORKFLOW_ENTITY')) {
    define('_REDIRECT_WORKFLOW_ENTITY', 'Rediriger à l\'entité initiatrice');
}
if (!defined('_REDIRECT_WORKFLOW_ENTITY_DESC')) {
    define('_REDIRECT_WORKFLOW_ENTITY_DESC', "Renvoie le document vers l'entité initiatrice du courrier sans modifier le circuit de visa actuel.");
}
if (!defined('_VISA_MAIL')) {
    define('_VISA_MAIL', 'Viser le courrier');
}
if (!defined('_PREPARE_VISA')) {
    define('_PREPARE_VISA', 'Préparer le circuit de visa');
}
if (!defined('_SEND_TO_VISA')) {
    define('_SEND_TO_VISA', 'Envoyer pour visa');
}
if (!defined('_SEND_TO_VISA_DESC')) {
    define('_SEND_TO_VISA_DESC', 'Contrôle si un circuit de visa est configuré ET si un ou plusieurs projets de réponses sont associés au courrier.');
}

if (!defined('_MAIL_WILL_DISAPPEAR')) {
    define('_MAIL_WILL_DISAPPEAR', 'Ce courrier sort de votre périmètre. Vous ne pourrez plus y accéder ensuite.');
}

//control technical params
if (!defined('_COMPONENT')) {
    define('_COMPONENT', 'Composant');
}

if (!defined('_MARK_AS_READ')) {
    define('_MARK_AS_READ', 'Marquer comme lu');
}
if (!defined('_MARK_AS_READ_DESC')) {
    define('_MARK_AS_READ_DESC', "Marque le courrier comme 'lu' dans la bannette. Insère la donnée dans la table 'res_mark_as_read' (utile si utilisée dans les clauses de bannettes).");
}

if (!defined('_USE_PREVIOUS_ADDRESS')) {
    define('_USE_PREVIOUS_ADDRESS', 'Réutiliser une adresse');
}

if (!defined('_SEARCH_INDICATION')) {
    define('_SEARCH_INDICATION', ' indique que la recherche se fait sur les courriers et les pièces jointes.');
}

if (!defined('_CONNECTION_CAS_OK')) {
    define('_CONNECTION_CAS_OK', 'Authentification CAS OK');
}
if (!defined('_CAS_SAML_NOT_SUPPORTED')) {
    define('_CAS_SAML_NOT_SUPPORTED', 'Le protocal SAML 1.1 n est pas encore géré.');
}
if (!defined('_PROTOCOL_NOT_SUPPORTED')) {
    define('_PROTOCOL_NOT_SUPPORTED', 'Ce protocol du CAS n est pas prise en compte.');
}
if (!defined('_USER_NOT_EXIST')) {
    define('_USER_NOT_EXIST', 'Cet utilisateur n existe pas dans l application.');
}
if (!defined('_VISIBLE_BY')) {
    define('_VISIBLE_BY', 'Visible par');
}

// SEDA
if (!defined('_FINAL_DISPOSITION')) {
    define('_FINAL_DISPOSITION', 'Sort final');
}
if (!defined('_CHOOSE_FINAL_DISPOSITION')) {
    define('_CHOOSE_FINAL_DISPOSITION', 'Choix du sort final');
}
if (!defined('_DESTROY')) {
    define('_DESTROY', 'Destruction');
}
if (!defined('_KEEP')) {
    define('_KEEP', 'Conservation');
}
if (!defined('_RETENTION_RULE')) {
    define('_RETENTION_RULE', 'Règle de conservation');
}
if (!defined('_DURATION_CURRENT_USE')) {
    define('_DURATION_CURRENT_USE', "Durée d'utilité courante");
}
if (!defined('_UNSELECT_ALL')) {
    define('_UNSELECT_ALL', 'Tout désélectionner');
}
if (!defined('_ARCHIVE_TRANSFER_COLL')) {
    define('_ARCHIVE_TRANSFER_COLL', 'Archivage');
}

/***** Global ******/
if (!defined('_UNREACHABLE_DOCSERVER')) {
    define('_UNREACHABLE_DOCSERVER', 'Chemin docserver inatteignable');
}
/***** Global ******/

/***** Profile *****/
if (!defined('_MANAGE_MY_SIGNATURES')) {
    define('_MANAGE_MY_SIGNATURES', 'Gérer mes signatures');
}
if (!defined('_MY_GROUPS')) {
    define('_MY_GROUPS', 'Mes Groupes');
}
if (!defined('_MY_ENTITIES')) {
    define('_MY_ENTITIES', 'Mes Entités');
}
if (!defined('_PRIMARY_ENTITY')) {
    define('_PRIMARY_ENTITY', 'Entité primaire');
}
if (!defined('_SECONDARY_ENTITY')) {
    define('_SECONDARY_ENTITY', 'Entité secondaire');
}
if (!defined('_MY_INFORMATIONS')) {
    define('_MY_INFORMATIONS', 'Mes Informations');
}
if (!defined('_DIGITAL_FINGERPRINT')) {
    define('_DIGITAL_FINGERPRINT', 'Empreinte Numérique');
}
if (!defined('_UPDATE_PSW')) {
    define('_UPDATE_PSW', 'Modifier votre mot de passe');
}
if (!defined('_CURRENT_PSW')) {
    define('_CURRENT_PSW', 'Mot de passe actuel');
}
if (!defined('_NEW_PSW')) {
    define('_NEW_PSW', 'Nouveau mot de passe');
}
if (!defined('_UPDATED_PROFILE')) {
    define('_UPDATED_PROFILE', 'Votre profil a bien été modifié');
}

if (!defined('_WRONG_PSW')) {
    define('_WRONG_PSW', 'Le mot de passe actuel n\'est pas correct');
}
if (!defined('_EMPTY_PSW_FORM')) {
    define('_EMPTY_PSW_FORM', 'Le formulaire de mot de passe n\'est pas complet');
}
if (!defined('_UPDATED_PASSWORD')) {
    define('_UPDATED_PASSWORD', 'Votre mot de passe a bien été modifié');
}

if (!defined('_SB_SIGNATURES')) {
    define('_SB_SIGNATURES', 'Signatures de parapheur');
}
if (!defined('_NEW_SIGNATURE')) {
    define('_NEW_SIGNATURE', 'Nouvelle signature ajoutée');
}
if (!defined('_UPDATED_SIGNATURE')) {
    define('_UPDATED_SIGNATURE', 'Signature modifiée');
}
if (!defined('_DELETED_SIGNATURE')) {
    define('_DELETED_SIGNATURE', 'Signature supprimée');
}
if (!defined('_DEFINE_NEW_SIGNATURE')) {
    define('_DEFINE_NEW_SIGNATURE', 'Nouvelle signature');
}
if (!defined('_SIGNATURE_LABEL')) {
    define('_SIGNATURE_LABEL', 'Label de la signature');
}
if (!defined('_UPDATE_SIGNATURE')) {
    define('_UPDATE_SIGNATURE', 'Modifier la signature');
}
if (!defined('_DELETE_SIGNATURE')) {
    define('_DELETE_SIGNATURE', 'Supprimer la signature');
}
if (!defined('_CLICK_ON')) {
    define('_CLICK_ON', 'Cliquez sur');
}
if (!defined('_TO_ADD_SIGNATURE')) {
    define('_TO_ADD_SIGNATURE', 'pour ajouter une signature');
}
if (!defined('_TO_UPDATE_SIGNATURE')) {
    define('_TO_UPDATE_SIGNATURE', 'pour modifier la signature téléchargée');
}

if (!defined('_EMAIL_SIGNATURES')) {
    define('_EMAIL_SIGNATURES', 'Signatures de mail');
}
if (!defined('_EMPTY_EMAIL_SIGNATURE_FORM')) {
    define('_EMPTY_EMAIL_SIGNATURE_FORM', 'Le formulaire de signature de mail n\'est pas complet');
}
if (!defined('_NEW_EMAIL_SIGNATURE')) {
    define('_NEW_EMAIL_SIGNATURE', 'Nouvelle signature de mail ajoutée');
}
if (!defined('_UPDATED_EMAIL_SIGNATURE')) {
    define('_UPDATED_EMAIL_SIGNATURE', 'Signature de mail modifiée');
}
if (!defined('_DELETED_EMAIL_SIGNATURE')) {
    define('_DELETED_EMAIL_SIGNATURE', 'Signature de mail supprimée');
}

if (!defined('_UNDEFINED_USER')) {
    define('_UNDEFINED_USER', 'Utilisateur non répertorié');
}
if (!defined('_CHOOSE_BASKET_TO_REDIRECT')) {
    define('_CHOOSE_BASKET_TO_REDIRECT', 'Choisissez une bannette');
}
if (!defined('_ACTIVATE_MY_ABSENCE')) {
    define('_ACTIVATE_MY_ABSENCE', 'Activer mon absence');
}
if (!defined('_AUTO_LOGOUT_AFTER_BASKETS_REDIRECTIONS')) {
    define('_AUTO_LOGOUT_AFTER_BASKETS_REDIRECTIONS', 'Vous allez être automatiquement déconnecté après avoir défini vos redirections de bannettes');
}
/***** Profile *****/

/***** Administration *****/
if (!defined('_ADMIN_USER_MODIFICATION')) {
    define('_ADMIN_USER_MODIFICATION', 'Modification de l\'utilisateur');
}
if (!defined('_ADDED_GROUP')) {
    define('_ADDED_GROUP', 'Groupe ajouté');
}
if (!defined('_UPDATED_GROUP')) {
    define('_UPDATED_GROUP', 'Groupe modifié');
}
if (!defined('_DELETED_GROUP')) {
    define('_DELETED_GROUP', 'Groupe supprimé');
}
if (!defined('_ADDED_ENTITY')) {
    define('_ADDED_ENTITY', 'Entité ajoutée');
}
if (!defined('_UPDATED_ENTITY')) {
    define('_UPDATED_ENTITY', 'Entité modifiée');
}
if (!defined('_DELETED_ENTITY')) {
    define('_DELETED_ENTITY', 'Entité supprimé');
}
if (!defined('_MANAGE_SIGNATURES')) {
    define('_MANAGE_SIGNATURES', 'Gérer les signatures');
}
if (!defined('_MANAGE_ABSENCES')) {
    define('_MANAGE_ABSENCES', 'Gérer les absences');
}
if (!defined('_REINITIALIZE_PASSWORD')) {
    define('_REINITIALIZE_PASSWORD', 'Réinitialiser le mot de passe');
}
if (!defined('_RESET_PASSWORD')) {
    define('_RESET_PASSWORD', 'Mot de passe réinitialisé');
}
if (!defined('_ACTIVATE_ABSENCE')) {
    define('_ACTIVATE_ABSENCE', 'Activer l\'absence');
}
if (!defined('_DEACTIVATE_ABSENCE')) {
    define('_DEACTIVATE_ABSENCE', 'Désactiver l\'absence');
}
if (!defined('_ABSENCE_ACTIVATED')) {
    define('_ABSENCE_ACTIVATED', 'L\'utilisateur est maintenant considéré comme absent');
}
if (!defined('_ABSENCE_DEACTIVATED')) {
    define('_ABSENCE_DEACTIVATED', 'L\'utilisateur est maintenant considéré comme actif');
}
if (!defined('_ABS')) {
    define('_ABS', 'Absent');
}
if (!defined('_ACTIVE')) {
    define('_ACTIVE', 'Actif');
}
if (!defined('_INACTIVE')) {
    define('_INACTIVE', 'Inactif');
}
if (!defined('_ADDED_PRIORITY')) {
    define('_ADDED_PRIORITY', 'Priorité ajoutée');
}
if (!defined('_UPDATED_PRIORITY')) {
    define('_UPDATED_PRIORITY', 'Priorité modifiée');
}
if (!defined('_DELETED_PRIORITY')) {
    define('_DELETED_PRIORITY', 'Priorité supprimée');
}
if (!defined('_FOR_USER')) {
    define('_FOR_USER', "pour l'utilisateur");
}
if (!defined('_BY_USER')) {
    define('_BY_USER', "par l'utilisateur");
}
if (!defined('_ADDED_USER')) {
    define('_ADDED_USER', "a ajouté l'utilisateur");
}
if (!defined('_REMOVED_USER')) {
    define('_REMOVED_USER', "a retiré l'utilisateur");
}
if (!defined('_FROM_GROUP')) {
    define('_FROM_GROUP', 'du groupe');
}
if (!defined('_FROM_ENTITY')) {
    define('_FROM_ENTITY', "de l'entité");
}
if (!defined('_IN_GROUP')) {
    define('_IN_GROUP', 'au groupe');
}
if (!defined('_IN_ENTITY')) {
    define('_IN_ENTITY', "à l'entité");
}

/***** Administration *****/

/**** admin update control ****/
if (!defined('_ADMIN_UPDATE_CONTROL')) {
    define('_ADMIN_UPDATE_CONTROL', 'Vérification mise à jour');
}
if (!defined('_YOUR_VERSION')) {
    define('_YOUR_VERSION', 'Votre version actuelle');
}
if (!defined('_AVAILABLE_VERSION_TO_UPDATE')) {
    define('_AVAILABLE_VERSION_TO_UPDATE', 'Versions disponibles');
}
if (!defined('_CLICK_HERE_TO_GO_TO_UPDATE_MANAGEMENT')) {
    define('_CLICK_HERE_TO_GO_TO_UPDATE_MANAGEMENT', 'Commencer la procédure de mise à jour');
}
if (!defined('_NEW_MAJOR_VERSION_AVAILABLE')) {
    define('_NEW_MAJOR_VERSION_AVAILABLE', 'Nouvelle(s) version(s) majeure(s) disponible(s)');
}
if (!defined('_BRANCH_VERSION')) {
    define('_BRANCH_VERSION', 'Branche');
}
if (!defined('_TAG_VERSION')) {
    define('_TAG_VERSION', 'Tag');
}
if (!defined('_CONNECT_YOU_IN_SUPERADMIN')) {
    define('_CONNECT_YOU_IN_SUPERADMIN', 'Vous devez être connecté en superadmin pour accéder à la procédure de mise à jour');
}
if (!defined('_UPDATE_WELCOME')) {
    define('_UPDATE_WELCOME', 'Mise à jour');
}
if (!defined('_UPDATE_WELCOME_INSTALL')) {
    define('_UPDATE_WELCOME_INSTALL', 'Procédure de mise à jour');
}
if (!defined('_UPDATE_DESC_INSTALL')) {
    define('_UPDATE_DESC_INSTALL', 'Procédure de mise à jour de MaarchCourrier (versions mineures uniquement)');
}
if (!defined('_UPDATE_BACKUP')) {
    define('_UPDATE_BACKUP', 'Sauvegarde');
}
if (!defined('_UPDATE_BACKUP_INFOS')) {
    define('_UPDATE_BACKUP_INFOS', 'Sauvegarde de votre version actuelle');
}
if (!defined('_UPDATE_BACKUP_DETAILS')) {
    define('_UPDATE_BACKUP_DETAILS', 'Procédure de sauvegarde de votre version actuelle afin de pouvoir la restaurer si besoin');
}
if (!defined('_ACTUAL_VERSION_PATH')) {
    define('_ACTUAL_VERSION_PATH', 'Chemin de votre installation');
}
if (!defined('_UPDATE_BACKUP_PATH')) {
    define('_UPDATE_BACKUP_PATH', 'Chemin de votre sauvegarde');
}
if (!defined('_BACKUP_ACTUAL_VERSION')) {
    define('_BACKUP_ACTUAL_VERSION', 'Sauvegarder votre version');
}
if (!defined('_UPDATE_DOWNLOAD')) {
    define('_UPDATE_DOWNLOAD', 'Téléchargement');
}
if (!defined('_LAST_RELEASE_INFOS')) {
    define('_LAST_RELEASE_INFOS', 'Téléchargement de la dernière version mineure');
}
if (!defined('_LAST_RELEASE_DETAILS')) {
    define('_LAST_RELEASE_DETAILS', 'Détails des versions mineures disponibles');
}
if (!defined('_CHOOSE_VERSION_TO_UPDATE')) {
    define('_CHOOSE_VERSION_TO_UPDATE', 'Choisissez la nouvelle version');
}
if (!defined('_DOWNLOAD_VERSION')) {
    define('_DOWNLOAD_VERSION', 'Télécharger la version');
}
if (!defined('_UPDATE_DEPLOY')) {
    define('_UPDATE_DEPLOY', 'Déploiement');
}
if (!defined('_UPDATE_DEPLOY_INFOS')) {
    define('_UPDATE_DEPLOY_INFOS', 'Déploiement de la version téléchargée');
}
if (!defined('_UPDATE_DEPLOY_DETAILS')) {
    define('_UPDATE_DEPLOY_DETAILS', 'Déploiement de la version téléchargée');
}
if (!defined('_DEPLOY_VERSION')) {
    define('_DEPLOY_VERSION', 'Déployer la version');
}
if (!defined('_UPDATE_END')) {
    define('_UPDATE_END', 'Mise à jour réussie');
}
if (!defined('_UPDATE_DESC_END')) {
    define('_UPDATE_DESC_END', 'Mise à jour réussie');
}
if (!defined('_NO_AVAILABLE_TAG_TO_UPDATE')) {
    define('_NO_AVAILABLE_TAG_TO_UPDATE', 'Aucune version disponible pour une mise à jour');
}
//PARAMETERS
if (!defined('_PARAM_VALUE_IS_EMPTY')) {
    define('_PARAM_VALUE_IS_EMPTY', ' La valeur du paramètre est vide');
}
if (!defined('_INVALID_PARAM_DATE')) {
    define('_INVALID_PARAM_DATE', ' Paramètre date invalide');
}
if (!defined('_INVALID_INTEGER')) {
    define('_INVALID_INTEGER', 'Entier non valide');
}
if (!defined('_INVALID_STRING')) {
    define('_INVALID_STRING', 'Chaine de caractère invalide');
}
if (!defined('_INVALID_DESCRIPTION')) {
    define('_INVALID_DESCRIPTION', 'Description invalide');
}
if (!defined('_INVALID_ID')) {
    define('_INVALID_ID', 'Identifiant invalide');
}
if (!defined('_INVALID')) {
    define('_INVALID', 'n\'est pas valide');
}
if (!defined('_STATUS_UPDATED')) {
    define('_STATUS_UPDATED', 'Statut mis à jour');
}

/* Generic messages **/
if (!defined('_SAVED_CHANGE')) {
    define('_SAVED_CHANGE', 'Modification enregistrée');
}

/* Generic messages **/

if (!defined('_DOC_CREATED')) {
    define('_DOC_CREATED', 'Document créé');
}
if (!defined('_SAVE_POSITION')) {
    define('_SAVE_POSITION', 'Enregistrer');
}

/***** History *****/

if (!defined('_REF_SEARCH')) {
    define('_REF_SEARCH', 'Rechercher dans le référentiel');
}

if (!defined('_USE_REF')) {
    define('_USE_REF', 'Utiliser le référentiel national');
}

if (!defined('_WARNING_REF')) {
    define('_WARNING_REF', '<b>Fichiers de reférérentiel manquant !</b><br/>Pour utiliser le référentiel national, veuillez contacter votre <a href="mailto:'.$_SESSION['config']['adminmail'].'">administrateur</a>');
}

/**** Management of welcome image ****/
if (!defined('_ERROR')) {
    define('_ERROR', 'Erreur');
}
if (!defined('_IMG_SIZE_NOT_ALLOWED')) {
    define('_IMG_SIZE_NOT_ALLOWED', "Taille d'image trop faible");
}
if (!defined('_SELECT_IMG_FIRST')) {
    define('_SELECT_IMG_FIRST', "Aucun fichier chargé");
}
if (!defined('_IMG_UPLOADED')) {
    define('_IMG_UPLOADED', "Image chargée");
}
if (!defined('_IMG_ALREADY_UPLOADED')) {
    define('_IMG_ALREADY_UPLOADED', "Image déjà chargée");
}
if (!defined('_FILE_FORMAT_NOT_ALLOWED')) {
    define('_FILE_FORMAT_NOT_ALLOWED', "Format d'image non autorisé");
}

if (!defined('_SIMPLE_CONFIRM_WITH_UPDATE_DATE')) {
    define('_SIMPLE_CONFIRM_WITH_UPDATE_DATE', "Confirmation et mise à jour de la date de départ");
}
if (!defined('_BARCODE')) {
    define('_BARCODE', "Code à barres");
}
if (!defined('_CHOOSE_DEPARTMENT_NUMBER')) {
    define('_CHOOSE_DEPARTMENT_NUMBER', "Choisissez le département concerné");
}
if (!defined('_DEPARTMENT_NUMBER')) {
    define('_DEPARTMENT_NUMBER', "Département des expéditeurs");
}
if (!defined('_CHRONO_NUMBER_HELP')) {
    define('_CHRONO_NUMBER_HELP', "Exemple : MAARCH/2018A/36");
}
if (!defined('_BARCODE_HELP')) {
    define('_BARCODE_HELP', "Exemple : ABC000004");
}
if (!defined('_PROCESS_IN_PROGRESS')) {
    define('_PROCESS_IN_PROGRESS', "Traitement en cours");
}
if (!defined('_CONTACTS_FILLING')) {
    define('_CONTACTS_FILLING', "Complétude des informations contacts");
}
if (!defined('_PROCESSED_BY')) {
    define('_PROCESSED_BY', "Traité par");
}
if (!defined('_REDACTOR')) {
    define('_REDACTOR', 'Rédacteur');
}
if (!defined('_ASSIGNEE')) {
    define('_ASSIGNEE', 'Attributaire');
}
if (!defined('_CONTACTS_USERS_LIST')) {
    define('_CONTACTS_USERS_LIST', 'Répertoire contacts / utilisateurs');
}
if (!defined('_ENTITIES_LIST')) {
    define('_ENTITIES_LIST', 'Répertoire entités');
}
if (!defined('_CONTACTS_USERS_SEARCH')) {
    define('_CONTACTS_USERS_SEARCH', 'Rechercher un contact / utilisateur / entité');
}
if (!defined('_CONTACTS_USERS_GROUPS_SEARCH')) {
    define('_CONTACTS_USERS_GROUPS_SEARCH', 'Rechercher un contact / utilisateur / groupement de contacts');
}
if (!defined('_USER_MAARCH_PARAPHEUR')) {
    define('_USER_MAARCH_PARAPHEUR', 'Attributaire Maarch Parapheur');
}
if (!defined('_DOCUMENT_WITH_NOTES')) {
    define('_DOCUMENT_WITH_NOTES', 'Document annoté');
}
if (!defined('_USE_MODEL_MAILING')) {
    define('_USE_MODEL_MAILING', 'Utiliser un modèle d\'enregistrement');
}
if (!defined('_PUT_IN_SIGNATORY_BOOK')) {
    define('_PUT_IN_SIGNATORY_BOOK', 'Intégrer au parapheur');
}
if (!defined('_MAIN_DOCUMENT')) {
    define('_MAIN_DOCUMENT', 'Document principal');
}
if (!defined('_DOWNLOAD_MAIN_DOCUMENT')) {
    define('_DOWNLOAD_MAIN_DOCUMENT', 'Télécharger le document original');
}
if (!defined('_SENDMAIL_PARAM')) {
    define('_SENDMAIL_PARAM', 'Serveur mail');
}
if (!defined('_MAILING_CONFIRMATION')) {
    define('_MAILING_CONFIRMATION', 'Voulez-vous générer toutes les pièces jointes ?');
}
if (!defined('_ELECTRONIC')) {
    define('_ELECTRONIC', 'Électronique');
}
if (!defined('_ACKNOWLEDGEMENT_RECEIPTS')) {
    define('_ACKNOWLEDGEMENT_RECEIPTS', 'Accusés de réception');
}
if (!defined('_PUT_IN_SEND_ATTACH')) {
    define('_PUT_IN_SEND_ATTACH', 'Intégrer aux envois Maileva');
}
if (!defined('_INITIATOR_ENTITY')) {
    define('_INITIATOR_ENTITY', 'Entité initiatrice');
}
if (!defined('_RECIPIENTS')) {
    define('_RECIPIENTS', 'Destinataire(s)');
}
if (!defined('_SECONDARY_INFORMATION')) {
    define("_SECONDARY_INFORMATION", "Informations secondaires");
}
if (!defined('_DEST_INFORMATION')) {
    define("_DEST_INFORMATION", "Informations de destination");
}

if (!defined('_SUMMARY_SHEET')) {
    define('_SUMMARY_SHEET', 'Fiche de liaison');
}

if (!defined('_SENDERS')) {
    define('_SENDERS', 'Expéditeur(s)');
}

if (!defined('_DESTINATION_ENTITY')) {
    define('_DESTINATION_ENTITY', 'Entité traitante');
}

if (!defined('_CREATED')) {
    define('_CREATED', 'Créé le');
}

if (!defined('_ACTION_DATE')) {
    define('_ACTION_DATE', 'Date d\'action');
}

if (!defined('_STATUS_NOT_EXISTS')) {
    define('_STATUS_NOT_EXISTS', 'Statut non défini');
}

if (!defined('_FORGOT_PASSWORD')) {
    define('_FORGOT_PASSWORD', 'Mot de passe oublié ?');
}
if (!defined('_USER_NOT_IN_APP')) {
    define('_USER_NOT_IN_APP', 'Vous n\'êtes pas un utilisateur de cette application');
}
if (!defined('_MISSING_KEYCLOAK_CONFIG')) {
    define('_MISSING_KEYCLOAK_CONFIG', 'Configuration manquante pour Keycloak');
}
