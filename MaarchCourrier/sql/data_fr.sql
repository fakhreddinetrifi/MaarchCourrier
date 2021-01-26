
-- Create USERGROUPS & USERGROUPS_SERVICES
TRUNCATE TABLE usergroups;
TRUNCATE TABLE usergroups_services;
DELETE FROM usergroups WHERE group_id = 'COURRIER';
DELETE FROM usergroups_services WHERE group_id = 'COURRIER';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (1, 'COURRIER', 'Opérateur de numérisation', True, '{"actions":["22", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'AGENT';
DELETE FROM usergroups_services WHERE group_id = 'AGENT';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (2, 'AGENT', 'Utilisateur', True, '{"actions":["22", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'RESP_COURRIER';
DELETE FROM usergroups_services WHERE group_id = 'RESP_COURRIER';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (3, 'RESP_COURRIER', 'Superviseur Courrier', True, '{"actions":["22", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'RESPONSABLE';
DELETE FROM usergroups_services WHERE group_id = 'RESPONSABLE';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (4, 'RESPONSABLE', 'Manager', True, '{"actions":["22", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'ADMINISTRATEUR_N1';
DELETE FROM usergroups_services WHERE group_id = 'ADMINISTRATEUR_N1';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (5, 'ADMINISTRATEUR_N1', 'Admin. Fonctionnel N1', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'ADMINISTRATEUR_N2';
DELETE FROM usergroups_services WHERE group_id = 'ADMINISTRATEUR_N2';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (6, 'ADMINISTRATEUR_N2', 'Admin. Fonctionnel N2', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'DIRECTEUR';
DELETE FROM usergroups_services WHERE group_id = 'DIRECTEUR';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (7, 'DIRECTEUR', 'Directeur', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'ELU';
DELETE FROM usergroups_services WHERE group_id = 'ELU';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (8, 'ELU', 'Elu', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'CABINET';
DELETE FROM usergroups_services WHERE group_id = 'CABINET';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (9, 'CABINET', 'Cabinet', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'ARCHIVISTE';
DELETE FROM usergroups_services WHERE group_id = 'ARCHIVISTE';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (10, 'ARCHIVISTE', 'Archiviste', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'MAARCHTOGEC';
DELETE FROM usergroups_services WHERE group_id = 'MAARCHTOGEC';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (11, 'MAARCHTOGEC', 'Envoi dématérialisé', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'SERVICE';
DELETE FROM usergroups_services WHERE group_id = 'SERVICE';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (12, 'SERVICE', 'Service', False, '{"actions" : [], "entities" : [], "keywords" : []}');
DELETE FROM usergroups WHERE group_id = 'WEBSERVICE';
DELETE FROM usergroups_services WHERE group_id = 'WEBSERVICE';
INSERT INTO usergroups (id, group_id, group_desc, can_index, indexation_parameters) VALUES (13, 'WEBSERVICE', 'Utilisateurs de WebService', True, '{"actions":["22", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}');
select setval('usergroups_id_seq', (select max(id)+1 from usergroups), false);
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_status_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'entities_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'admin_registered_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'manage_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_documents_with_notes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'manage_tags_application');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', '_print_sep');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'physical_archive_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'manage_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'view_documents_with_notes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_visa_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_visa_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_avis_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_avis_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_diffusion_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'admin_registered_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_documents_with_notes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'sign_document');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'visa_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'manage_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'view_documents_with_notes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'config_visa_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'config_visa_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'sign_document');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'visa_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'modify_visa_in_signatureBook');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'config_avis_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'config_avis_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'avis_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_users');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_groups');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_architecture');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_history_batch');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_status');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_actions');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_indexing_models');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_custom_fields');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_status_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_parameters');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_priorities');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_email_server');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_shippings');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'manage_entities');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_difflist_types');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_listmodels');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_diffusion_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'entities_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_registered_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_alfresco');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_search');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'manage_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_documents_with_notes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_visa_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_visa_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_avis_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_templates');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'manage_tags_application');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_notif');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', '_print_sep');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'physical_archive_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'physical_archive_batch_manage');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_life_cycle');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin_templates');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ELU', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ELU', 'sign_document');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ELU', 'visa_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ELU', 'avis_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'avis_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'export_seda_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MAARCHTOGEC', 'include_folders_and_followed_resources_perimeter');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MAARCHTOGEC', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('WEBSERVICE', 'include_folders_and_followed_resources_perimeter');

-- Create DOCTYPES
TRUNCATE TABLE DOCTYPES_FIRST_LEVEL;
TRUNCATE TABLE DOCTYPES_SECOND_LEVEL;
TRUNCATE TABLE DOCTYPES;

INSERT INTO doctypes_first_level (doctypes_first_level_id, doctypes_first_level_label, css_style, enabled) VALUES (1, 'COURRIERS', '#000000', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (1, '01. Correspondances', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('101', 'Abonnements – documentation – archives', 'Y', 1, 1, 30, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('102', 'Convocation', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('103', 'Demande de documents', 'Y', 1, 1, 30, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('104', 'Demande de fournitures et matériels', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('105', 'Demande de RDV', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('106', 'Demande de renseignements', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('107', 'Demande mise à jour de fichiers', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('108', 'Demande Multi-Objet', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('109', 'Installation provisoire dans un équipement ville', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('110', 'Invitation', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('111', 'Rapport – Compte-rendu – Bilan', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('112', 'Réservation d''un local communal et scolaire', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (2, '02. Cabinet', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('201', 'Pétition', 'Y', 1, 2, 15, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('202', 'Communication', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('203', 'Politique', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('204', 'Relations et solidarité internationales ', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('205', 'Remerciements et félicitations', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('206', 'Sécurité', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('207', 'Suggestion', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (3, '03. Éducation', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('301', 'Culture', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('302', 'Demande scolaire hors inscription et dérogation', 'Y', 1, 3, 60, 14, 1, 'SVR');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('303', 'Éducation nationale', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('304', 'Jeunesse', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('305', 'Lycées et collèges', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('306', 'Parentalité', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('307', 'Petite Enfance', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('308', 'Sport', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (4, '04. Finances', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('401', 'Contestation financière', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('402', 'Contrat de prêt', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('403', 'Garantie d''emprunt', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('404', 'Paiement', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('405', 'Quotient familial', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('406', 'Subvention', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('407', 'Facture ou avoir', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('408', 'Proposition financière', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (5, '05. Juridique', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('501', 'Hospitalisation d''office', 'Y', 1, 5, 2, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('502', 'Mise en demeure', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('503', 'Plainte', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('504', 'Recours contentieux', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('505', 'Recours gracieux et réclamations', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (6, '06. Population ', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('601', 'Débits de boisson', 'Y', 1, 6, 60, 14, 1, 'SVR');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('602', 'Demande d’État Civil', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('603', 'Élections', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('604', 'Étrangers', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('605', 'Marché', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('606', 'Médaille du travail', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('607', 'Stationnement taxi', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('608', 'Vente au déballage', 'Y', 1, 6, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (7, '07. Ressources Humaines', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('701', 'Arrêts de travail et maladie', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('702', 'Assurance du personnel', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('703', 'Candidature', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('704', 'Carrière', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('705', 'Conditions de travail santé', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('706', 'Congés exceptionnels et concours', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('707', 'Formation', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('708', 'Instances RH', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('709', 'Retraite', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('710', 'Stage', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('711', 'Syndicats', 'Y', 1, 7, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (8, '08. Social', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('801', 'Aide à domicile', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('802', 'Aide Financière', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('803', 'Animations retraités', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('804', 'Domiciliation', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('805', 'Dossier de logement', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('806', 'Expulsion', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('807', 'Foyer', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('808', 'Obligation alimentaire', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('809', 'RSA', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('810', 'Scolarisation à domicile', 'Y', 1, 8, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (9, '09. Technique', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('901', 'Aire d''accueil des gens du voyage', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('902', 'Assainissement', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('903', 'Assurance et sinistre', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('904', 'Autorisation d''occupation du domaine public', 'Y', 1, 9, 60, 14, 1, 'SVR');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('905', 'Contrat et convention hors marchés publics', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('906', 'Détention de chiens dangereux', 'Y', 1, 9, 60, 14, 1, 'SVR');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('907', 'Espaces verts – Environnement – Développement durable', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('908', 'Hygiène et Salubrité', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('909', 'Marchés Publics', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('910', 'Mobiliers urbains', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('911', 'NTIC', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('912', 'Opération d''aménagement', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('913', 'Patrimoine', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('914', 'Problème de voisinage', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('915', 'Propreté', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('916', 'Stationnement et circulation', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('917', 'Transports', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('918', 'Travaux', 'Y', 1, 9, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (10, '10. Urbanisme', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1001', 'Alignement', 'Y', 1, 10, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1002', 'Avis d''urbanisme', 'Y', 1, 10, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1003', 'Commerces', 'Y', 1, 10, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1004', 'Numérotation', 'Y', 1, 10, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (11, '11. Silence vaut acceptation', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1101', 'Autorisation de buvette', 'Y', 1, 11, 60, 14, 1, 'SVA');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1102', 'Cimetière', 'Y', 1, 11, 60, 14, 1, 'SVA');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1103', 'Demande de dérogation scolaire', 'Y', 1, 11, 60, 14, 1, 'SVA');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1104', 'Inscription à la cantine et activités périscolaires ', 'Y', 1, 11, 60, 14, 1, 'SVA');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1105', 'Inscription toutes petites sections', 'Y', 1, 11, 90, 14, 1, 'SVA');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1106', 'Travaux ERP', 'Y', 1, 11, 60, 14, 1, 'SVA');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (12, '12. Formulaires', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1201', 'Appel téléphonique', 'Y', 1, 12, 21, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES ('1202', 'Demande intervention voirie', 'Y', 1, 12, 21, 14, 1, 'NORMAL');
select setval('doctypes_first_level_id_seq', (select max(doctypes_first_level_id)+1 from doctypes_first_level), false);
select setval('doctypes_second_level_id_seq', (select max(doctypes_second_level_id)+1 from doctypes_second_level), false);
select setval('doctypes_type_id_seq', (select max(type_id)+1 from doctypes), false);

-- Create USERS
DELETE FROM users WHERE user_id <> 'superadmin';
TRUNCATE TABLE users_entities;
DELETE FROM users WHERE user_id = 'rrenaud';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (1, 'rrenaud', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Robert', 'RENAUD', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (1, 'DGS', '', 'Y');
DELETE FROM users WHERE user_id = 'ccordy';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (2, 'ccordy', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Chloé', 'CORDY', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (2, 'DSI', '', 'Y');
DELETE FROM users WHERE user_id = 'ssissoko';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (3, 'ssissoko', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Sylvain', 'SISSOKO', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (3, 'DSI', '', 'Y');
DELETE FROM users WHERE user_id = 'nnataly';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (4, 'nnataly', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Nancy', 'NATALY', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (4, 'PSO', '', 'Y');
DELETE FROM users WHERE user_id = 'ddur';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (5, 'ddur', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Dominique', 'DUR', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (5, 'ELUS', '', 'Y');
DELETE FROM users WHERE user_id = 'jjane';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (6, 'jjane', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Jenny', 'JANE', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (6, 'CCAS', '', 'Y');
DELETE FROM users WHERE user_id = 'eerina';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (7, 'eerina', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Edith', 'ERINA', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (7, 'CAB', '', 'Y');
DELETE FROM users WHERE user_id = 'kkaar';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (8, 'kkaar', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Katy', 'KAAR', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (8, 'DGA', '', 'Y');
DELETE FROM users WHERE user_id = 'bboule';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (9, 'bboule', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Bruno', 'BOULE', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (9, 'PCU', '', 'Y');
DELETE FROM users WHERE user_id = 'ppetit';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (10, 'ppetit', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Patricia', 'PETIT', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (10, 'VILLE', '', 'Y');
DELETE FROM users WHERE user_id = 'aackermann';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (11, 'aackermann', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Amanda', 'ACKERMANN', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (11, 'PSF', '', 'Y');
DELETE FROM users WHERE user_id = 'ppruvost';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (12, 'ppruvost', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Pierre', 'PRUVOST', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (12, 'DRH', '', 'Y');
DELETE FROM users WHERE user_id = 'ttong';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (13, 'ttong', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Tony', 'TONG', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (13, 'SP', '', 'Y');
DELETE FROM users WHERE user_id = 'sstar';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (14, 'sstar', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Suzanne', 'STAR', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (14, 'FIN', '', 'Y');
DELETE FROM users WHERE user_id = 'ssaporta';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (15, 'ssaporta', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Sabrina', 'SAPORTA', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (15, 'PE', '', 'Y');
DELETE FROM users WHERE user_id = 'ccharles';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (16, 'ccharles', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Charlotte', 'CHARLES', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (16, 'PTE', '', 'Y');
DELETE FROM users WHERE user_id = 'mmanfred';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (17, 'mmanfred', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Martin', 'MANFRED', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (17, 'DGA', '', 'Y');
DELETE FROM users WHERE user_id = 'ddaull';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (18, 'ddaull', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Denis', 'DAULL', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (18, 'DSG', '', 'Y');
DELETE FROM users WHERE user_id = 'bbain';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (19, 'bbain', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Barbara', 'BAIN', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (19, 'PJS', '', 'Y');
DELETE FROM users WHERE user_id = 'jjonasz';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (20, 'jjonasz', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Jean', 'JONASZ', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (20, 'PJU', '', 'Y');
DELETE FROM users WHERE user_id = 'bblier';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (21, 'bblier', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Bernard', 'BLIER', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (21, 'COU', '', 'Y');
DELETE FROM users WHERE user_id = 'ggrand';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, status, preferences) VALUES (22, 'ggrand', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Georges', 'GRAND', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (22, 'COR', '', 'Y');
select setval('users_id_seq', (select max(id)+1 from users), false);

-- Create USERGROUP_CONTENT
TRUNCATE TABLE usergroup_content;
DELETE FROM usergroup_content WHERE user_id = 1;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (1, 4, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (1, 7, '');
DELETE FROM usergroup_content WHERE user_id = 2;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (2, 2, '');
DELETE FROM usergroup_content WHERE user_id = 3;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (3, 4, '');
DELETE FROM usergroup_content WHERE user_id = 4;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (4, 2, '');
DELETE FROM usergroup_content WHERE user_id = 5;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (5, 8, '');
DELETE FROM usergroup_content WHERE user_id = 6;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (6, 4, '');
DELETE FROM usergroup_content WHERE user_id = 7;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (7, 4, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (7, 7, '');
DELETE FROM usergroup_content WHERE user_id = 8;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (8, 2, '');
DELETE FROM usergroup_content WHERE user_id = 9;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (9, 2, '');
DELETE FROM usergroup_content WHERE user_id = 10;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (10, 4, '');
DELETE FROM usergroup_content WHERE user_id = 11;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (11, 2, '');
DELETE FROM usergroup_content WHERE user_id = 12;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (12, 2, '');
DELETE FROM usergroup_content WHERE user_id = 13;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (13, 2, '');
DELETE FROM usergroup_content WHERE user_id = 14;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (14, 4, '');
DELETE FROM usergroup_content WHERE user_id = 15;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (15, 2, '');
DELETE FROM usergroup_content WHERE user_id = 16;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (16, 2, '');
DELETE FROM usergroup_content WHERE user_id = 17;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (17, 4, '');
DELETE FROM usergroup_content WHERE user_id = 18;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (18, 1, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (18, 3, '');
DELETE FROM usergroup_content WHERE user_id = 19;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (19, 2, '');
DELETE FROM usergroup_content WHERE user_id = 20;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (20, 2, '');
DELETE FROM usergroup_content WHERE user_id = 21;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (21, 1, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (21, 5, '');
DELETE FROM usergroup_content WHERE user_id = 22;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (22, 10, '');

-- Create ENTITIES and LIST TEMPLATES
TRUNCATE TABLE entities;
ALTER SEQUENCE entities_id_seq RESTART WITH 1;
TRUNCATE TABLE list_templates;
TRUNCATE TABLE list_templates_items;
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('VILLE', 'Ville de Maarch-les-bains', 'Ville de Maarch-les-bains', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', '', 'Direction');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (1, 'Ville de Maarch-les-bains', 'Ville de Maarch-les-bains', 'diffusionList', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (1, 15, 'user', 'dest', 0);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('CAB', 'Cabinet du Maire', 'Cabinet du Maire', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'VILLE', 'Direction');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (2, 'Cabinet du Maire', 'Cabinet du Maire', 'diffusionList', 2);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (2, 7, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (2, 12, 'entity', 'cc', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (2, 10, 'user', 'cc', 2);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (2, 3, 'user', 'cc', 3);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DGS', 'Direction Générale des Services', 'Direction Générale des Services', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'VILLE', 'Direction');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (3, 'Direction Générale des Services', 'Direction Générale des Services', 'diffusionList', 3);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (3, 1, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (3, 10, 'user', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DGA', 'Direction Générale Adjointe', 'Direction Générale Adjointe', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGS', 'Bureau');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (4, 'Direction Générale Adjointe', 'Direction Générale Adjointe', 'diffusionList', 4);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (4, 17, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (4, 12, 'entity', 'cc', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (4, 8, 'user', 'cc', 2);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PCU', 'Pôle Culturel', 'Pôle Culturel', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGA', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (5, 'Pôle Culturel', 'Pôle Culturel', 'diffusionList', 5);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (5, 9, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (5, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PJS', 'Pôle Jeunesse et Sport', 'Pôle Jeunesse et Sport', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGA', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (6, 'Pôle Jeunesse et Sport', 'Pôle Jeunesse et Sport', 'diffusionList', 6);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (6, 19, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (6, 1, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PE', 'Petite enfance', 'Petite enfance', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'PJS', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (7, 'Petite enfance', 'Petite enfance', 'diffusionList', 7);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (7, 15, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (7, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('SP', 'Sport', 'Sport', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'PJS', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (8, 'Sport', 'Sport', 'diffusionList', 8);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (8, 13, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (8, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PSO', 'Pôle Social', 'Pôle Social', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGA', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (9, 'Pôle Social', 'Pôle Social', 'diffusionList', 9);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (9, 4, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (9, 12, 'entity', 'cc', 1);
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (1009, 'visa Pôle Social', 'visa Pôle Social', 'visaCircuit', 9);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (1009, 17, 'user', 'visa', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (1009, 10, 'user', 'sign', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PTE', 'Pôle Technique', 'Pôle Technique', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGA', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (10, 'Pôle Technique', 'Pôle Technique', 'diffusionList', 10);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (10, 16, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (10, 12, 'entity', 'cc', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (10, 20, 'entity', 'cc', 2);
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (1010, 'visa Pôle Technique', 'visa Pôle Technique', 'visaCircuit', 10);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (1010, 17, 'user', 'visa', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (1010, 10, 'user', 'sign', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DRH', 'Direction des Ressources Humaines', 'Direction des Ressources Humaines', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGS', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (11, 'Direction des Ressources Humaines', 'Direction des Ressources Humaines', 'diffusionList', 11);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (11, 12, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (11, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DSG', 'Secrétariat Général', 'Secrétariat Général', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGS', 'Direction');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (12, 'Secrétariat Général', 'Secrétariat Général', 'diffusionList', 12);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (12, 18, 'user', 'dest', 0);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COU', 'Service Courrier', 'Service Courrier', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DSG', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (13, 'Service Courrier', 'Service Courrier', 'diffusionList', 13);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (13, 21, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (13, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COR', 'Correspondants Archive', 'Correspondants Archive', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'COU', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (14, 'Correspondants Archive', 'Correspondants Archive', 'diffusionList', 14);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (14, 22, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (14, 14, 'user', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PSF', 'Pôle des Services Fonctionnels', 'Services Fonctionnels', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DSG', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (15, 'Services Fonctionnels', 'Pôle des Services Fonctionnels', 'diffusionList', 15);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (15, 11, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (15, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DSI', 'Direction des Systèmes d''Information', 'Direction des Systèmes d''Information', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGS', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (16, 'Direction des Systèmes d''Information', 'Direction des Systèmes d''Information', 'diffusionList', 16);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (16, 3, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (16, 12, 'entity', 'cc', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (16, 2, 'user', 'cc', 2);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('FIN', 'Direction des Finances', 'Direction des Finances', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'DGS', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (17, 'Direction des Finances', 'Direction des Finances', 'diffusionList', 17);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (17, 14, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (17, 12, 'entity', 'cc', 1);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (17, 6, 'user', 'cc', 2);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PJU', 'Pôle Juridique', 'Pôle Juridique', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'FIN', 'Service');
INSERT INTO list_templates (id, title, description, type, entity_id) VALUES (18, 'Pôle Juridique', 'Pôle Juridique', 'diffusionList', 18);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (18, 20, 'user', 'dest', 0);
INSERT INTO list_templates_items (list_template_id, item_id, item_type, item_mode, sequence) VALUES (18, 12, 'entity', 'cc', 1);
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ELUS', 'Ensemble des élus', 'ELUS:Ensemble des élus', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', 'VILLE', 'Direction');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('CCAS', 'Centre Communal d''Action Sociale', 'Centre Communal d''Action Sociale', 'Y', '', '', '', '', '', '', 'support@maarch.fr', '', '', 'Direction');
SELECT setval('list_templates_id_seq', (SELECT max(id)+1 FROM list_templates), false);

-- Create BASKETS
TRUNCATE TABLE baskets;
ALTER SEQUENCE baskets_id_seq RESTART WITH 1;
DELETE FROM baskets WHERE basket_id = 'QualificationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'QualificationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'QualificationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('QualificationBasket', 'Courriers à qualifier', 'Bannette de qualification', 'status=''INIT''', 'letterbox_coll', 'Y', 'N', 'Y',10);
DELETE FROM baskets WHERE basket_id = 'NumericBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'NumericBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'NumericBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('NumericBasket', 'Plis numériques à qualifier', 'Plis numériques à qualifier', 'status = ''NUMQUAL''', 'letterbox_coll', 'Y', 'N', 'Y',20);
DELETE FROM baskets WHERE basket_id = 'EenvBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'EenvBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'EenvBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('EenvBasket', 'Courriers à envoyer', 'Courriers visés/signés prêts à être envoyés', 'status=''EENV'' and dest_user = @user_id', 'letterbox_coll', 'Y', 'N', 'Y',30);
DELETE FROM baskets WHERE basket_id = 'MyBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'MyBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'MyBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('MyBasket', 'Courriers à traiter', 'Bannette de traitement', 'status in (''NEW'', ''COU'', ''STDBY'', ''ENVDONE'') and dest_user = @user_id', 'letterbox_coll', 'Y', 'Y', 'Y',40);
DELETE FROM baskets WHERE basket_id = 'CopyMailBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'CopyMailBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'CopyMailBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('CopyMailBasket', 'Courriers en copie', 'Courriers en copie non clos ou sans suite', '(res_id in (select res_id from listinstance WHERE item_type = ''user_id'' and item_id = @user_id and item_mode = ''cc'') or res_id in (select res_id from listinstance WHERE item_type = ''entity_id'' and item_mode = ''cc'' and item_id in (@my_entities_id))) and status not in ( ''DEL'', ''END'', ''SSUITE'') and res_id not in (select res_id from res_mark_as_read WHERE user_id = @user_id)', 'letterbox_coll', 'Y', 'N', 'Y',50);
DELETE FROM baskets WHERE basket_id = 'AR_Create';
DELETE FROM actions_groupbaskets WHERE basket_id = 'AR_Create';
DELETE FROM groupbasket_redirect WHERE basket_id = 'AR_Create';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('AR_Create', 'AR en masse : non envoyés', 'AR en masse : non envoyés', 'dest_user = @user_id AND res_id NOT IN(select distinct res_id from acknowledgement_receipts) and status not in (''END'') and category_id = ''incoming''', 'letterbox_coll', 'Y', 'N', 'Y',60);
DELETE FROM baskets WHERE basket_id = 'AR_AlreadySend';
DELETE FROM actions_groupbaskets WHERE basket_id = 'AR_AlreadySend';
DELETE FROM groupbasket_redirect WHERE basket_id = 'AR_AlreadySend';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('AR_AlreadySend', 'AR en masse : transmis', 'AR en masse : transmis', 'dest_user = @user_id AND ((res_id IN(SELECT distinct res_id FROM acknowledgement_receipts WHERE creation_date is not null AND send_date is not null) and status not in (''END'')) OR res_id IN (SELECT distinct res_id FROM acknowledgement_receipts WHERE creation_date is not null AND send_date is null AND format = ''pdf'' and (filename is not null or filename <> '''')))', 'letterbox_coll', 'Y', 'N', 'Y',70);
DELETE FROM baskets WHERE basket_id = 'RetourCourrier';
DELETE FROM actions_groupbaskets WHERE basket_id = 'RetourCourrier';
DELETE FROM groupbasket_redirect WHERE basket_id = 'RetourCourrier';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('RetourCourrier', 'Retours Courrier', 'Courriers retournés au service Courrier', 'STATUS=''RET''', 'letterbox_coll', 'Y', 'N', 'Y',80);
DELETE FROM baskets WHERE basket_id = 'DdeAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'DdeAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'DdeAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('DdeAvisBasket', 'Avis : Avis à émettre', 'Courriers nécessitant un avis', 'status = ''EAVIS'' AND res_id IN (SELECT res_id FROM listinstance WHERE item_type = ''user_id'' AND item_id = @user_id AND item_mode = ''avis'' and process_date is NULL)', 'letterbox_coll', 'Y', 'N', 'Y',90);
DELETE FROM baskets WHERE basket_id = 'SupAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SupAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SupAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('SupAvisBasket', 'Avis : En attente de réponse', 'Courriers en attente d''avis', 'status=''EAVIS'' and ((dest_user = @user_id) OR (DEST_USER IN (select user_id from users_entities WHERE entity_id IN( @my_entities)) or DESTINATION in (@subentities[@my_entities]))) and res_id NOT IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is not NULL and res_view_letterbox.res_id = res_id group by res_id) AND res_id IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is NULL and res_view_letterbox.res_id = res_id group by res_id)', 'letterbox_coll', 'Y', 'N', 'Y',100);
DELETE FROM baskets WHERE basket_id = 'RetAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'RetAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'RetAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('RetAvisBasket', 'Avis : Retours partiels', 'Courriers avec avis reçus', 'status=''EAVIS'' and ((dest_user = @user_id) OR (DEST_USER IN (select user_id from users_entities WHERE entity_id IN( @my_entities)) or DESTINATION in (@subentities[@my_entities]))) and res_id IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is not NULL and res_view_letterbox.res_id = res_id group by res_id)', 'letterbox_coll', 'Y', 'N', 'Y',110);
DELETE FROM baskets WHERE basket_id = 'ValidationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ValidationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ValidationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('ValidationBasket', 'Attributions à vérifier', 'Courriers signalés en attente d''instruction pour les services', 'status=''VAL''', 'letterbox_coll', 'Y', 'N', 'Y',120);
DELETE FROM baskets WHERE basket_id = 'InValidationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'InValidationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'InValidationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('InValidationBasket', 'Courriers signalés en attente d''instruction', 'Courriers signalés en attente d''instruction par le responsable', 'destination in (@my_entities, @subentities[@my_entities]) and status=''VAL''', 'letterbox_coll', 'Y', 'N', 'Y',130);
DELETE FROM baskets WHERE basket_id = 'LateMailBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'LateMailBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'LateMailBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('LateMailBasket', 'Courriers en retard', 'Courriers en retard', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'') and (now() > process_limit_date)', 'letterbox_coll', 'Y', 'N', 'Y',140);
DELETE FROM baskets WHERE basket_id = 'DepartmentBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'DepartmentBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'DepartmentBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('DepartmentBasket', 'Courriers de ma direction', 'Bannette de supervision', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'' and status <> ''VAL'')', 'letterbox_coll', 'Y', 'N', 'Y',150);
DELETE FROM baskets WHERE basket_id = 'ParafBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ParafBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ParafBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('ParafBasket', 'Parapheur électronique', 'Courriers à viser ou signer dans mon parapheur', 'status in (''ESIG'', ''EVIS'') AND ((res_id, @user_id) IN (SELECT res_id, item_id FROM listinstance WHERE difflist_type = ''VISA_CIRCUIT'' and process_date ISNULL and res_view_letterbox.res_id = res_id order by listinstance_id asc limit 1))', 'letterbox_coll', 'Y', 'N', 'Y',160);
DELETE FROM baskets WHERE basket_id = 'SuiviParafBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SuiviParafBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SuiviParafBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('SuiviParafBasket', 'Courriers en circuit de visa/signature', 'Courriers en circulation dans les parapheurs électroniques', 'status in (''ESIG'', ''EVIS'') AND dest_user = @user_id', 'letterbox_coll', 'Y', 'N', 'Y',170);
DELETE FROM baskets WHERE basket_id = 'SendToSignatoryBook';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SendToSignatoryBook';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SendToSignatoryBook';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('SendToSignatoryBook', 'Courriers envoyés au parapheur Maarch en attente ou rejetés', 'Courriers envoyés au parapheur Maarch en attente ou rejetés', '(status = ''ATT_MP'' or status = ''REJ_SIGN'') AND dest_user = @user_id', 'letterbox_coll', 'Y', 'Y', 'Y',180);
DELETE FROM baskets WHERE basket_id = 'Maileva_Sended';
DELETE FROM actions_groupbaskets WHERE basket_id = 'Maileva_Sended';
DELETE FROM groupbasket_redirect WHERE basket_id = 'Maileva_Sended';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('Maileva_Sended', 'Courriers transmis via Maileva', 'Courriers transmis via Maileva', 'dest_user = @user_id AND res_id IN(SELECT distinct r.res_id_master from res_attachments r inner join shippings s on s.document_id = r.res_id) and status not in (''END'')', 'letterbox_coll', 'Y', 'N', 'Y',190);
DELETE FROM baskets WHERE basket_id = 'ToArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ToArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ToArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('ToArcBasket', 'Courriers à archiver', 'Courriers arrivés en fin de DUC à envoyer en archive intermédiaire', 'status = ''EXP_SEDA'' OR status = ''END'' OR status = ''SEND_SEDA''', 'letterbox_coll', 'Y', 'N', 'Y',200);
DELETE FROM baskets WHERE basket_id = 'SentArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SentArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SentArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('SentArcBasket', 'Courriers en cours d''archivage', 'Courriers envoyés au SAE, en attente de réponse de transfert', 'status=''ACK_SEDA''', 'letterbox_coll', 'Y', 'N', 'Y',210);
DELETE FROM baskets WHERE basket_id = 'AckArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'AckArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'AckArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('AckArcBasket', 'Courriers archivés', 'Courriers archivés et acceptés dans le SAE', 'status=''REPLY_SEDA''', 'letterbox_coll', 'Y', 'N', 'Y',220);
DELETE FROM baskets WHERE basket_id = 'GedSampleBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'GedSampleBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'GedSampleBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('GedSampleBasket', 'Contrats arrivant à expiration (date fin contrat < 3mois)', 'Contrats arrivant à expiration (date fin contrat < 3mois)', 'custom_fields->>''1'' is not null and custom_fields->>''1'' <> '''' and date(custom_fields->>''1'') < now()+ interval ''3 months''', 'letterbox_coll', 'Y', 'Y', 'Y',230);
DELETE FROM baskets WHERE basket_id = 'IntervBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'IntervBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'IntervBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, flag_notif, enabled, basket_order) VALUES ('IntervBasket', 'Demandes d''''intervention voirie à traiter', 'Demandes d''''intervention voirie à traiter', 'status in (''NEW'', ''COU'', ''STDBY'', ''ENVDONE'') and dest_user = @user_id and type_id = 1202', 'letterbox_coll', 'Y', 'Y', 'Y',240);

-- Create GROUPBASKET
TRUNCATE TABLE groupbasket;
DELETE FROM groupbasket WHERE basket_id = 'QualificationBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('COURRIER', 'QualificationBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"canUpdateData":true,"defaultTab":"info"}');
DELETE FROM groupbasket WHERE basket_id = 'CopyMailBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'CopyMailBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'CopyMailBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
DELETE FROM groupbasket WHERE basket_id = 'RetourCourrier';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('COURRIER', 'RetourCourrier', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"canUpdateData":true,"defaultTab":"info"}');
DELETE FROM groupbasket WHERE basket_id = 'DdeAvisBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'DdeAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'DdeAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('ELU', 'DdeAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'SupAvisBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'SupAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"opinionCircuit"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'SupAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"opinionCircuit"}');
DELETE FROM groupbasket WHERE basket_id = 'RetAvisBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'RetAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"notes"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'RetAvisBasket', '{"templateColumns":5,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getParallelOpinionsNumber","cssClasses":["align_rightData"],"icon":"fa-comment-alt"},{"value":"getOpinionLimitDate","cssClasses":["align_rightData"],"icon":"fa-stopwatch"}]}', 'processDocument', '{"defaultTab":"notes"}');
DELETE FROM groupbasket WHERE basket_id = 'ValidationBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESP_COURRIER', 'ValidationBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"canUpdateData":true,"defaultTab":"diffusionList"}');
DELETE FROM groupbasket WHERE basket_id = 'InValidationBasket';
DELETE FROM groupbasket WHERE basket_id = 'MyBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'MyBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'MyBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('ELU', 'MyBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'LateMailBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'LateMailBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'DepartmentBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'DepartmentBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
DELETE FROM groupbasket WHERE basket_id = 'ParafBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'ParafBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'signatureBookAction', '[]');
DELETE FROM groupbasket WHERE basket_id = 'SuiviParafBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'SuiviParafBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'SuiviParafBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
DELETE FROM groupbasket WHERE basket_id = 'EenvBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'EenvBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'EenvBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'ToArcBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('ARCHIVISTE', 'ToArcBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'SentArcBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('ARCHIVISTE', 'SentArcBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'AckArcBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('ARCHIVISTE', 'AckArcBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'NumericBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('COURRIER', 'NumericBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"canUpdateData":true,"defaultTab":"info"}');
DELETE FROM groupbasket WHERE basket_id = 'SendToSignatoryBook';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'SendToSignatoryBook', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('RESPONSABLE', 'SendToSignatoryBook', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'documentDetails', '[]');
DELETE FROM groupbasket WHERE basket_id = 'AR_Create';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'AR_Create', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'AR_AlreadySend';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'AR_AlreadySend', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'Maileva_Sended';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'Maileva_Sended', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'GedSampleBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'GedSampleBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"defaultTab":"dashboard"}');
DELETE FROM groupbasket WHERE basket_id = 'IntervBasket';
INSERT INTO groupbasket (group_id, basket_id, list_display, list_event, list_event_data) VALUES ('AGENT', 'IntervBasket', '{"templateColumns":7,"subInfos":[{"value":"getPriority","cssClasses":[],"icon":"fa-traffic-light"},{"value":"getCategory","cssClasses":[],"icon":"fa-exchange-alt"},{"value":"getDoctype","cssClasses":[],"icon":"fa-suitcase"},{"value":"getAssignee","cssClasses":[],"icon":"fa-sitemap"},{"value":"getRecipients","cssClasses":[],"icon":"fa-user"},{"value":"getSenders","cssClasses":[],"icon":"fa-book"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_rightData"],"icon":"fa-calendar"}]}', 'processDocument', '{"canUpdateData":true,"defaultTab":"dashboard"}');


-- Create Security
TRUNCATE TABLE security;
DELETE FROM security WHERE group_id = 'COURRIER';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('COURRIER', 'letterbox_coll', '1=1', 'Tous les courriers');
DELETE FROM security WHERE group_id = 'AGENT';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('AGENT', 'letterbox_coll', 'destination in (@my_entities, @subentities[@my_primary_entity])', 'Les courriers de mes services et sous-services');
DELETE FROM security WHERE group_id = 'RESP_COURRIER';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('RESP_COURRIER', 'letterbox_coll', '1=1', 'Tous les courriers');
DELETE FROM security WHERE group_id = 'RESPONSABLE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('RESPONSABLE', 'letterbox_coll', 'destination in (@my_entities, @subentities[@my_primary_entity])', 'Les courriers de mes services et sous-services');
DELETE FROM security WHERE group_id = 'ADMINISTRATEUR_N1';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ADMINISTRATEUR_N1', 'letterbox_coll', '1=1', 'Tous les courriers');
DELETE FROM security WHERE group_id = 'ADMINISTRATEUR_N2';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ADMINISTRATEUR_N2', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'DIRECTEUR';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('DIRECTEUR', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'ELU';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ELU', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'CABINET';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('CABINET', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'ARCHIVISTE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ARCHIVISTE', 'letterbox_coll', '1=1', 'Tous les courriers');
DELETE FROM security WHERE group_id = 'MAARCHTOGEC';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('MAARCHTOGEC', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'SERVICE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('SERVICE', 'letterbox_coll', '1=0', 'Aucun courrier');
DELETE FROM security WHERE group_id = 'WEBSERVICE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('WEBSERVICE', 'letterbox_coll', '1=0', 'Tous les courriers');

-- Create FOLDERS
TRUNCATE TABLE folders;
ALTER SEQUENCE folders_id_seq RESTART WITH 1;
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Compétences fonctionnelles', 'TRUE', 21, NULL, 0);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Vie politique', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Vie citoyenne', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Administration municipale', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Ressources humaines', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Candidatures sur postes ouverts', 'TRUE', 21, 5, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Candidatures spontanées', 'TRUE', 21, 5, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Affaires juridiques', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Finances', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Marchés publics', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Informatique', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Communication', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Événements', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Moyens généraux (matériels et logistiques)', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Archives', 'TRUE', 21, 1, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Compétences techniques', 'TRUE', 21, NULL, 0);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Population', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Police - ordre public', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Stationnement', 'TRUE', 21, 18, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Politique de la ville', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Urbanisme opérationnel', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Urbanisme réglementaire', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Affaires foncières ', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Développement du territoire ', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Habitat', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Biens communaux (domaine privé)', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Espaces publics urbains (domaine public - voiries -réseaux)', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Éclairage public', 'TRUE', 21, 27, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Ouvrages d''art', 'TRUE', 21, 27, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Hygiène', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Santé publique', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Enseignement', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Sports', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Centre de loisirs nautiques', 'TRUE', 21, 33, 2);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Jeunesse', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Culture', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Actions sociales', 'TRUE', 21, 16, 1);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('Cohésion sociale', 'TRUE', 21, 16, 1);

-- Donnees manuelles
------------
--USERGROUPS
------------
UPDATE usergroups set indexation_parameters = '{"actions":["21", "22"], "entities":[], "keywords":["ALL_ENTITIES"]}' where group_id IN ('COURRIER', 'RESP_COURRIER');
UPDATE usergroups set indexation_parameters = '{"actions":["22", "414", "20"], "entities":[], "keywords":["ALL_ENTITIES"]}' where group_id IN ('AGENT');
------------
--USERS 
------------
UPDATE users set external_id = '{"maarchParapheur": 1}' where user_id = 'jjane';
UPDATE users set external_id = '{"maarchParapheur": 3}' where user_id = 'mmanfred';
UPDATE users set external_id = '{"maarchParapheur": 4}' where user_id = 'ppetit';

------------
--ENTITIES_FOLDERS
------------
TRUNCATE TABLE entities_folders;
INSERT INTO entities_folders (folder_id, entity_id, edition)
SELECT folders.id, entities.id, false
FROM folders, entities
WHERE 1=1; 

------------
--USERGROUPS_SERVICES
------------
UPDATE usergroups_services SET parameters = (
    cast('{"groups": [' || (
        SELECT string_agg(cast(id AS VARCHAR), ', ' ORDER BY id) FROM usergroups
    ) || ']}' AS jsonb)
    )
WHERE service_id = 'admin_users';

INSERT INTO usergroups_services (group_id, service_id)
SELECT distinct(group_id), 'manage_personal_data'
FROM usergroups_services WHERE group_id IN (
    SELECT group_id FROM usergroups_services
    WHERE service_id = 'admin_users'
);
------------
--DOCSERVERS
------------
TRUNCATE TABLE docserver_types;
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('DOC', 'Documents numériques', 'Y', 'SHA512');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('CONVERT', 'Conversions de formats', 'Y', 'SHA256');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('FULLTEXT', 'Plein texte', 'Y', 'SHA256');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('TNL', 'Miniatures', 'Y', 'NONE');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('TEMPLATES', 'Modèles de documents', 'Y', 'NONE');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, fingerprint_mode)
VALUES ('ARCHIVETRANSFER', 'Archives numériques', 'Y', 'SHA256');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled)
VALUES ('ACKNOWLEDGEMENT_RECEIPTS', 'Accusés de réception', 'Y');

TRUNCATE TABLE docservers;
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('FASTHD_AI', 'DOC', 'Dépôt documentaire issue d''imports de masse', 'Y', 50000000000, 1, '/opt/maarch/docservers/ai/', '2011-01-07 13:43:48.696644', 'letterbox_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('FASTHD_MAN', 'DOC', 'Dépôt documentaire de numérisation manuelle', 'N', 50000000000, 1290730, '/opt/maarch/docservers/resources/', '2011-01-13 14:47:49.197164', 'letterbox_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('FASTHD_ATTACH', 'DOC', 'Dépôt des pièces jointes', 'N', 50000000000, 1, '/opt/maarch/docservers/attachments/', '2011-01-13 14:47:49.197164', 'attachments_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('CONVERT_MLB', 'CONVERT', 'Dépôt des formats des documents numérisés', 'N', 50000000000, 0, '/opt/maarch/docservers/convert_resources/', '2015-03-16 14:47:49.197164', 'letterbox_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('CONVERT_ATTACH', 'CONVERT', 'Dépôt des formats des pièces jointes', 'N', 50000000000, 0, '/opt/maarch/docservers/convert_attachments/', '2015-03-16 14:47:49.197164', 'attachments_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('TNL_MLB', 'TNL', 'Dépôt des maniatures des documents numérisés', 'N', 50000000000, 0, '/opt/maarch/docservers/thumbnails_resources/', '2015-03-16 14:47:49.197164', 'letterbox_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('TNL_ATTACH', 'TNL', 'Dépôt des maniatures des pièces jointes', 'N', 50000000000, 0, '/opt/maarch/docservers/thumbnails_attachments/', '2015-03-16 14:47:49.197164', 'attachments_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('FULLTEXT_MLB', 'FULLTEXT', 'Dépôt de l''extraction plein texte des documents numérisés', 'N', 50000000000, 0, '/opt/maarch/docservers/fulltext_resources/', '2015-03-16 14:47:49.197164', 'letterbox_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('FULLTEXT_ATTACH', 'FULLTEXT', 'Dépôt de l''extraction plein texte des pièces jointes', 'N', 50000000000, 0, '/opt/maarch/docservers/fulltext_attachments/', '2015-03-16 14:47:49.197164', 'attachments_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('TEMPLATES', 'TEMPLATES', 'Dépôt des modèles de documents', 'N', 50000000000, 71511, '/opt/maarch/docservers/templates/', '2012-04-01 14:49:05.095119', 'templates');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('ARCHIVETRANSFER', 'ARCHIVETRANSFER', 'Dépôt des archives numériques', 'N', 50000000000, 1, '/opt/maarch/docservers/archive_transfer/', '2017-01-13 14:47:49.197164', 'archive_transfer_coll');
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, size_limit_number, actual_size_number, path_template, creation_date, coll_id)
VALUES ('ACKNOWLEDGEMENT_RECEIPTS', 'ACKNOWLEDGEMENT_RECEIPTS', 'Dépôt des AR', 'N', 50000000000, 0, '/opt/maarch/docservers/acknowledgement_receipts/', '2019-04-19 22:22:22.201904', 'letterbox_coll');

------------
--SUPERADMIN USER
------------
DELETE FROM users WHERE user_id='superadmin';
INSERT INTO users (user_id, password, firstname, lastname, phone, mail, status, preferences, mode) VALUES ('superadmin', '$2y$10$Vq244c5s2zmldjblmMXEN./Q2qZrqtGVgrbz/l1WfsUJbLco4E.e.', 'Super', 'ADMIN', '0147245159', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}', 'root_invisible');
--MAARCH2GEC USER
DELETE FROM users WHERE user_id = 'cchaplin';
INSERT INTO users (user_id, password, firstname, lastname, mail, status, preferences, mode) VALUES ('cchaplin', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Charlie', 'CHAPLIN', 'support@maarch.fr', 'OK', '{"documentEdition" : "onlyoffice"}', 'rest');
DELETE FROM usergroup_content WHERE user_id = 24;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (24, 11, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (24, 13, '');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES (24, 'VILLE', '', 'Y');

------------
-- CONTACTS
------------
TRUNCATE TABLE contacts;
INSERT INTO contacts VALUES (1, 'title1', 'Jean-Louis', 'ERCOLANI', 'MAARCH', '', 'Directeur Général', '11', 'Boulevard du Sud-Est', '', '', '99000', 'MAARCH LES BAINS', 'France', 'info@maarch.org', '', NULL, 'Editeur du logiciel libre Maarch', 21, '2015-04-24 12:43:54.97424', '2016-07-25 16:28:38.498185', true, '{}');
INSERT INTO contacts VALUES (2, 'title1', 'Karim', 'SY', 'MAARCH', '', 'Administrateur', '', 'Sacré Coeur 3', '', 'Villa 9653 4ème phase', '', 'DAKAR', 'SENEGAL', 'info@maarch.org', '', NULL, 'Editeur du logiciel libre Maarch', 21, '2015-04-24 12:43:54.97424', '2016-07-25 16:28:38.498185', true, '{}');
INSERT INTO contacts VALUES (3, 'title1', 'Laurent', 'GIOVANNONI', 'MAARCH', '', 'Directeur Général Adjoint', '11', 'Boulevard du Sud-Est', NULL, '', '99000', 'MAARCH LES BAINS', 'FRANCE', 'info@maarch.org', '', NULL, 'Editeur du logiciel libre Maarch', 21, '2015-04-24 12:43:54.97424', '2016-07-25 16:28:38.498185', true, '{}');
--INSERT INTO contacts VALUES (4, 'title1', 'Nicolas', 'MARTIN', 'Préfecture de Maarch Les Bains', '', '', '13', 'RUE LA PREFECTURE', NULL, '', '99000', 'MAARCH LES BAINS', '', '', '', '{"url": "https://cchaplin:maarch@demo.maarchcourrier.com"}', 'Préfecture de Maarch Les Bains', 21, '2018-04-18 12:43:54.97424', '2018-04-18 16:28:38.498185', true, '{"m2m": "45239273100025/COU"}');
INSERT INTO contacts VALUES (4, 'title1', 'Nicolas', 'MARTIN', 'Préfecture de Maarch Les Bains', NULL, NULL, '13', 'RUE LA PREFECTURE', NULL, NULL, '99000', 'MAARCH LES BAINS', NULL, NULL, NULL, '{"url": "https://cchaplin:maarch@demo.maarchcourrier.com"}', NULL, 21, '2018-04-18 12:43:54.97424', '2020-03-24 15:06:58.16582', true, NULL, '{"m2m": "45239273100025/COU"}');
INSERT INTO contacts VALUES (5, 'title2', 'Brigitte', 'BERGER', 'ACME', '', 'Directrice Générale', '25', 'PLACE DES MIMOSAS', NULL, '', '99000', 'MAARCH LES BAINS', 'FRANCE', 'info@maarch.org', '', NULL, 'Archivage et Conservation des Mémoires Electroniques', 21, '2015-04-24 12:43:54.97424', '2016-07-25 16:28:38.498185', true, '{}');
INSERT INTO contacts VALUES (6, 'title1', 'Bernard', 'PASCONTENT', '', '', '', '25', 'route de Pampelone', NULL, '', '99000', 'MAARCH-LES-BAINS', '', 'bernard.pascontent@gmail.com', '06 08 09 07 55', NULL, '', 21, '2019-03-20 13:59:09.23436', NULL, true, '{}');
INSERT INTO contacts VALUES (7, 'title1', 'Jacques', 'DUPONT', '', '', '', '1', 'rue du Peuplier', NULL, '', '92000', 'NANTERRE', '', '', '', NULL, '', 21, '2019-03-20 13:59:09.23436', NULL, true, '{}');
INSERT INTO contacts VALUES (8, 'title1', 'Pierre', 'BRUNEL', '', '', '', '5', 'allée des Pommiers', NULL, '', '99000', 'MAARCH-LES-BAINS', '', 'info@maarch.org', '06 08 09 07 55', NULL, '', 21, '2019-03-20 13:59:09.23436', NULL, true, '{}');
INSERT INTO contacts VALUES (9, 'title1', 'Eric', 'MACKIN', '', '', '', '13', 'rue du Square Carré', NULL, '', '99000', 'MAARCH-LES-BAINS', '', '', '06 11 12 13 14', NULL, '', 21, '2019-03-20 13:59:09.23436', NULL, true, '{}');
INSERT INTO contacts VALUES (10, 'title2', 'Carole', 'COTIN', 'MAARCH', '', 'Directrice Administrative et Qualité', '11', 'Boulevard du Sud-Est', NULL, '', '99000', 'MAARCH LES BAINS', 'FRANCE', 'info@maarch.org', '', NULL, 'Editeur du logiciel libre Maarch', 21, '2015-04-24 12:43:54.97424', '2016-07-25 16:28:38.498185', true, '{}');
INSERT INTO contacts VALUES (11, 'title1', 'Martin Donald', 'PELLE', '', '', '', '17', 'rue de la Demande', NULL, '', '99000', 'MAARCH-LES-BAINS', '', 'info@maarch.org', '01 23 24 21 22', NULL, '', 21, '2019-03-20 13:59:09.23436', NULL, true, '{}');

select setval('contacts_id_seq', (select max(id)+1 from contacts), false);

TRUNCATE TABLE contacts_parameters;
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (1, 'civility', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (2, 'firstname', false, true, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (3, 'lastname', true, true, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (4, 'company', true, false, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (5, 'department', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (6, 'function', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (7, 'addressNumber', false, false, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (8, 'addressStreet', false, true, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (9, 'addressAdditional1', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (10, 'addressAdditional2', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (11, 'addressPostcode', false, true, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (12, 'addressTown', false, true, true, true);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (13, 'addressCountry', false, false, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (14, 'email', false, true, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (15, 'phone', false, true, false, false);
INSERT INTO contacts_parameters (id, identifier, mandatory, filling, searchable, displayable) VALUES (16, 'notes', false, false, false, false);

select setval('contacts_parameters_id_seq', (select max(id)+1 from contacts_parameters), false);

------------
--STATUS-
------------
TRUNCATE TABLE status;
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ATT', 'En attente', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('COU', 'En cours', 'Y', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('DEL', 'Supprimé', 'Y', 'fm-letter-del', 'apps', 'N', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('END', 'Clos / fin du workflow', 'Y', 'fm-letter-status-end', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NEW', 'Nouveau courrier pour le service', 'Y', 'fm-letter-status-new', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('RET', 'Retour courrier ou document en qualification', 'N', 'fm-letter-status-rejected', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('VAL', 'Courrier signalé', 'Y', 'fm-letter-status-aval', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('INIT', 'Nouveau courrier ou document non qualifié', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('VALSG', 'Nouveau courrier ou document en validation SG', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ATT_MP', 'En attente tablette (MP)', 'Y', 'fm-letter-status-wait', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EAVIS', 'Avis demandé', 'N', 'fa-lightbulb', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EENV', 'A e-envoyer', 'N', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ESIG', 'A e-signer', 'N', 'fm-file-fingerprint', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EVIS', 'A e-viser', 'N', 'fm-letter-status-aval', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ESIGAR', 'AR à e-signer', 'N', 'fm-file-fingerprint', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EENVAR', 'AR à e-envoyer', 'N', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SVX', 'En attente  de traitement SVE', 'N', 'fm-letter-status-wait', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SSUITE', 'Sans suite', 'Y', 'fm-letter-del', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('A_TRA', 'PJ à traiter', 'Y', 'fa-question', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('FRZ', 'PJ gelée', 'Y', 'fa-pause', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('TRA', 'PJ traitée', 'Y', 'fa-check', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('OBS', 'PJ obsolète', 'Y', 'fa-pause', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('TMP', 'PJ brouillon', 'Y', 'fm-letter-status-inprogress', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EXP_SEDA', 'A archiver', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SEND_SEDA', 'Courrier envoyé au système d''archivage', 'Y', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ACK_SEDA', 'Accusé de réception reçu', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('REPLY_SEDA', 'Courrier archivé', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC', 'Envoyé en GRC', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC_TRT', 'En traitement GRC', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC_ALERT', 'Retourné par la GRC', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('RETRN', 'Retourné', 'Y', 'fm-letter-outgoing', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NO_RETRN', 'Pas de retour', 'Y', 'fm-letter-status-rejected', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('PJQUAL', 'PJ à réconcilier', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NUMQUAL', 'Plis à qualifier', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SEND_MASS', 'Pour publipostage', 'Y', 'fa-mail-bulk', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SIGN', 'PJ signée', 'Y', 'fa-check', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('STDBY', 'Clôturé avec suivi', 'Y', 'fm-letter-status-wait', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ENVDONE', 'Courrier envoyé', 'Y', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');
--INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('AR_OK', 'Accusé de réception créé', 'Y', 'fa-mail-bulk', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('REJ_SIGN', 'Signature refusée sur la tablette (MP)', 'Y', 'fm-letter-status-rejected', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('PND', 'AR Non distribué', 'Y', 'fm-letter-status-rejected', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('DSTRIBUTED', 'AR distribué', 'Y', 'fa-check', 'apps', 'Y', 'Y');

------------
--STATUS IMAGES-
------------
TRUNCATE TABLE status_images;
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-new');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-inprogress');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-info');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-wait');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-validated');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-rejected');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-end');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-newmail');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-attr');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-arev');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-aval');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-aimp');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-imp');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-aenv');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-acla');
INSERT INTO status_images (image_name) VALUES ('fm-letter-status-aarch');
INSERT INTO status_images (image_name) VALUES ('fm-letter');
INSERT INTO status_images (image_name) VALUES ('fm-letter-add');
INSERT INTO status_images (image_name) VALUES ('fm-letter-search');
INSERT INTO status_images (image_name) VALUES ('fm-letter-del');
INSERT INTO status_images (image_name) VALUES ('fm-letter-incoming');
INSERT INTO status_images (image_name) VALUES ('fm-letter-outgoing');
INSERT INTO status_images (image_name) VALUES ('fm-letter-internal');
INSERT INTO status_images (image_name) VALUES ('fm-file-fingerprint');
INSERT INTO status_images (image_name) VALUES ('fm-classification-plan-l1');
INSERT INTO status_images (image_name) VALUES ('fa-question');
INSERT INTO status_images (image_name) VALUES ('fa-check');
INSERT INTO status_images (image_name) VALUES ('fa-pause');
INSERT INTO status_images (image_name) VALUES ('fa-mail-bulk');
INSERT INTO status_images (image_name) VALUES ('fa-lightbulb');

------------
--PARAMETERS
------------
TRUNCATE TABLE parameters;
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('work_batch_autoimport_id', NULL, 1, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('database_version', '20.10.2', NULL, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('user_quota', '', 0, NULL);
INSERT INTO parameters (id, description, param_value_string, param_value_int, param_value_date) VALUES ('defaultDepartment', 'Département par défaut sélectionné dans les autocomplétions de la Base Adresse Nationale', NULL, 75, NULL);
INSERT INTO parameters (id, description, param_value_string) VALUES ('homepage_message', 'Texte apparaissant dans la bannière sur la page d''accueil, mettre un espace pour supprimer la bannière.', '<p>D&eacute;couvrez <strong>Maarch Courrier 20.10</strong> avec <a title="notre guide de visite" href="https://docs.maarch.org/" target="_blank"><span style="color:#f99830;"><strong>notre guide de visite en ligne</strong></span></a>.</p>');
INSERT INTO parameters (id, description, param_value_string) VALUES ('loginpage_message', 'Texte apparaissant sur la page de login.', '<h3><span style="color:#24b0ed"><strong>Découvrez votre application via</strong></span> <a title="le guide de visite" href="https://docs.maarch.org/" target="_blank"><span style="color:#f99830;"><strong>le guide de visite en ligne</strong></span></a></h3>');
INSERT INTO parameters (id, description, param_value_string) VALUES ('thumbnailsSize', 'Résolution des imagettes', '750x900');
INSERT INTO parameters (id, description, param_value_string) VALUES ('traffic_record_summary_sheet', 'Module circulation pour la fiche de liaison', '');
INSERT INTO parameters (id, description, param_value_int) VALUES ('keepDestForRedirection', 'Si activé (1), met le destinataire en copie de la liste de diffusion lors d''une action de redirection', 0);
INSERT INTO parameters (id, description, param_value_int) VALUES ('QrCodePrefix', 'Si activé (1), ajoute "Maarch_" dans le contenu des QrCode générés. (Utilisable avec MaarchCapture >= 1.4)', 0);
INSERT INTO parameters (id, description, param_value_int) VALUES ('workingDays', 'Si activé (1), les délais de traitement sont calculés en jours ouvrés (Lundi à Vendredi). Sinon, en jours calendaire', 1);
INSERT INTO parameters (id, param_value_int) VALUES ('last_deposit_id', 0);
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailNotDistributedStatus', 'PND');
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailDistributedStatus', 'DSTRIBUTED');
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailImportedStatus', 'NEW');
INSERT INTO parameters (id, description, param_value_int) VALUES ('keepDiffusionRoleInOutgoingIndexation', 'Si activé (1), prend en compte les roles du modèle de diffusion de l''entité.', 1);
INSERT INTO parameters (id, param_value_string) VALUES ('bindingDocumentFinalAction', 'copy');
INSERT INTO parameters (id, param_value_string) VALUES ('nonBindingDocumentFinalAction', 'delete');
INSERT INTO parameters (id, param_value_int) VALUES ('minimumVisaRole', 0);
INSERT INTO parameters (id, param_value_int) VALUES ('maximumSignRole', 0);

------------
--DIFFLIST_TYPES
------------
TRUNCATE TABLE difflist_types;
INSERT INTO difflist_types (difflist_type_id, difflist_type_label, difflist_type_roles, allow_entities, is_system) VALUES ('entity_id', 'Diffusion aux services', 'dest copy avis', 'Y', 'Y');
INSERT INTO difflist_types (difflist_type_id, difflist_type_label, difflist_type_roles, allow_entities, is_system) VALUES ('type_id', 'Diffusion selon le type de document', 'dest copy', 'Y', 'Y');
INSERT INTO difflist_types (difflist_type_id, difflist_type_label, difflist_type_roles, allow_entities, is_system) VALUES ('VISA_CIRCUIT', 'Circuit de visa', 'visa sign ', 'N', 'Y');
INSERT INTO difflist_types (difflist_type_id, difflist_type_label, difflist_type_roles, allow_entities, is_system) VALUES ('AVIS_CIRCUIT', 'Circuit d''avis', 'avis ', 'N', 'Y');

------------
--ACTIONS
------------
TRUNCATE TABLE actions;
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (1,  'redirect', 'Rediriger', 'NEW', 'Y', 'redirect', 'Y', 'redirectAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (2,  '', 'Attribuer au service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (3,  '', 'Retourner au service Courrier', 'RET', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (4,  '', 'Enregistrer les modifications', '_NOSTATUS_', 'N', 'confirm_status', 'N', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (5,  '', 'Remettre en traitement', 'COU', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (6,  '', 'Supprimer le courrier', 'DEL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (18, 'redirect', 'Qualifier le courrier', 'NEW', 'N', 'redirect', 'Y', 'redirectAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (19, '', 'Traiter courrier', 'COU', 'N', 'confirm_status', 'N', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (20, '', 'Cloturer', 'END', 'N', 'close_mail', 'Y', 'closeMailAction', '{"requiredFields": []}');
INSERT INTO actions (id, label_action, id_status, is_system, history, component) VALUES (21, 'Envoyer le courrier en validation', 'VAL', 'N', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (22, '', 'Attribuer au service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (23, 'indexing', 'Attribuer au(x) service(s)', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (24, 'indexing', 'Remettre en validation', 'VAL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (36, '', 'Envoyer pour avis', 'EAVIS', 'N', 'send_docs_to_recommendation', 'Y', 'sendToParallelOpinion');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (37, '', 'Donner un avis', '_NOSTATUS_', 'N', 'avis_workflow_simple', 'Y', 'giveOpinionParallelAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (100, '', 'Voir le document', '', 'N', 'view', 'N', '');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (101, '', 'Envoyer pour visa', 'VIS', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (114, '', 'Marquer comme lu', '', 'N', 'mark_as_read', 'N', 'resMarkAsReadAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (122, '', 'Attribuer au service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (123, 'indexing', 'Attribuer au(x) service(s)', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (210, '', 'Transmettre l''AR signé', 'EENVAR', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (400, '', 'Envoyer un AR', '_NOSTATUS_', 'N', 'send_attachments_to_contact', 'Y', 'createAcknowledgementReceiptsAction', '{"mode": "manual"}');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (405, '', 'Viser le courrier', '_NOSTATUS_', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (407, '', 'Renvoyer pour traitement', 'COU', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (408, '', 'Refuser le visa et remonter le circuit', '_NOSTATUS_', 'N',  'rejection_visa_previous', 'N', 'rejectVisaBackToPreviousAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (410, '', 'Transmettre la réponse signée', 'EENV', 'N', 'interrupt_visa', 'Y', 'interruptVisaAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (410, '', 'Transmettre la réponse signée', 'EENV', 'N', 'interrupt_status', 'Y', 'continueVisaCircuitAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (414, '', 'Envoyer au parapheur', 'EVIS', 'N', 'send_to_visa', 'Y', 'sendSignatureBookAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (416, '', 'Valider et poursuivre le circuit', '_NOSTATUS_', 'N', 'visa_workflow', 'Y', 'continueVisaCircuitAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (417, '', 'Envoyer l''AR', 'SVX', 'N', 'send_to_contact_with_mandatory_attachment', 'Y', 'createAcknowledgementReceiptsAction', '{"mode": "manual"}');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (420, '', 'Classer sans suite', 'SSUITE', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (421, '', 'Retourner au Service Courrier', 'RET', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (431, '', 'Envoyer en GRC', 'GRC', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (500, '', 'Transférer au système d''archivage', 'SEND_SEDA', 'N', 'export_seda', 'Y', 'sendToRecordManagementAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (501, '', 'Valider la réception du courrier par le système d''archivage', 'ACK_SEDA', 'N', 'check_acknowledgment', 'Y', 'checkAcknowledgmentRecordManagementAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (502, '', 'Valider l''archivage du courrier', 'REPLY_SEDA', 'N', 'check_reply', 'Y', 'checkReplyRecordManagementAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (503, '', 'Purger le courrier', 'DEL', 'N', 'purge_letter', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (504, '', 'Remise à zero du courrier', 'END', 'N', 'reset_letter', 'Y', 'resetRecordManagementAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (505, '', 'Clôturer avec suivi', 'STDBY', 'N', 'close_mail', 'Y', 'closeMailAction', '{"requiredFields": []}');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (506, '', 'Terminer le suivi', 'END', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (507, '', 'Acter l’envoi', 'ENVDONE', 'N', 'confirm_status', 'Y', 'confirmAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (522, '', 'Envoyer en validation DGS', 'VAL', 'N', 'confirm_status', 'Y', 'confirmAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (523, 'indexing', 'Envoyer en validation DGS', 'VAL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (524, '', 'Activer la persistance', '_NOSTATUS_', 'N', 'set_persistent_mode_on', 'N', 'enabledBasketPersistenceAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (525, '', 'Désactiver la persistance', '_NOSTATUS_', 'N', 'set_persistent_mode_off', 'N', 'disabledBasketPersistenceAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (526, '', 'Libérer les courriers', 'VAL', 'Y', 'Y', 'confirm_status', 'N', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (527, '', 'Envoyer sur la tablette (Maarch Parapheur)', 'ATT_MP', 'N', 'sendToExternalSignatureBook', 'Y', 'sendExternalSignatoryBookAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (528, '', 'Générer les accusés de réception', '_NOSTATUS_', 'N', 'create_acknowledgement_receipt', 'Y', 'createAcknowledgementReceiptsAction', '{"mode": "both"}');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (529, '', 'Envoyer un pli postal Maileva', '_NOSTATUS_', 'N', 'send_shipping', 'Y', 'sendShippingAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, parameters) VALUES (530, '', 'Re-Générér les accusés de réception papier si pb impression', '_NOSTATUS_', 'N', 'create_acknowledgement_receipt', 'Y', 'createAcknowledgementReceiptsAction', '{"mode": "both"}');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (531, '', 'Envoyer pour annotation sur la tablette (Maarch Parapheur)', 'ATT_MP', 'N', 'sendToExternalSignatureBook', 'Y', 'sendExternalSignatoryBookAction');
--INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component, required_fields) VALUES (532, '', 'Cloturer intervention', 'END', 'N', 'close_mail', 'Y', 'closeMailAction','["indexingCustomField_2"]');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (532, '', 'Enregistrer et imprimer le recommandé', 'NEW', 'N', 'saveAndPrintRegisteredMail', 'Y', 'saveAndPrintRegisteredMailAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (533, '', 'Enregistrer le recommandé et rester sur la page d''indexation', 'NEW', 'N', 'saveAndIndexRegisteredMail', 'Y', 'saveAndIndexRegisteredMailAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (534, '', 'Imprimer le recommandé', '_NOSTATUS_', 'N', 'printRegisteredMail', 'Y', 'printRegisteredMailAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (535, '', 'Imprimer le descriptif de pli', '_NOSTATUS_', 'N', 'printDepositList', 'Y', 'printDepositListAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (536, '', 'Enregistrer le recommandé', 'NEW', 'N', 'saveRegisteredMail', 'Y', 'saveRegisteredMailAction');
Select setval('actions_id_seq', (select max(id)+1 from actions), false);

TRUNCATE TABLE actions_categories;
INSERT INTO actions_categories (action_id, category_id) VALUES (20, 'incoming');
INSERT INTO actions_categories (action_id, category_id) VALUES (20, 'outgoing');
INSERT INTO actions_categories (action_id, category_id) VALUES (20, 'internal');
INSERT INTO actions_categories (action_id, category_id) VALUES (20, 'ged_doc');
INSERT INTO actions_categories (action_id, category_id) VALUES (22, 'incoming');
INSERT INTO actions_categories (action_id, category_id) VALUES (22, 'outgoing');
INSERT INTO actions_categories (action_id, category_id) VALUES (22, 'internal');
INSERT INTO actions_categories (action_id, category_id) VALUES (22, 'ged_doc');
INSERT INTO actions_categories (action_id, category_id) VALUES (414, 'incoming');
INSERT INTO actions_categories (action_id, category_id) VALUES (414, 'outgoing');
INSERT INTO actions_categories (action_id, category_id) VALUES (414, 'internal');
INSERT INTO actions_categories (action_id, category_id) VALUES (414, 'ged_doc');
INSERT INTO actions_categories (action_id, category_id) VALUES (532, 'registeredMail');
INSERT INTO actions_categories (action_id, category_id) VALUES (533, 'registeredMail');
INSERT INTO actions_categories (action_id, category_id) VALUES (534, 'registeredMail');
INSERT INTO actions_categories (action_id, category_id) VALUES (535, 'registeredMail');
INSERT INTO actions_categories (action_id, category_id) VALUES (536, 'registeredMail');

------------
-- BANNETTES SECONDAIRES
TRUNCATE TABLE users_baskets_preferences;
INSERT INTO users_baskets_preferences (user_serial_id, group_serial_id, basket_id, display)
SELECT usergroup_content.user_id, usergroups.id, groupbasket.basket_id, TRUE FROM usergroups, groupbasket, usergroup_content
WHERE groupbasket.group_id = usergroups.group_id AND usergroups.id = usergroup_content.group_id
ORDER BY usergroup_content.user_id;

------------
--ACTIONS_GROUPBASKETS
------------
TRUNCATE TABLE actions_groupbaskets;
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (24, '', 'COURRIER', 'RetourCourrier', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (22, '', 'COURRIER', 'RetourCourrier', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (531, '', 'COURRIER', 'RetourCourrier', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (18, '', 'COURRIER', 'QualificationBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (18, '', 'COURRIER', 'NumericBasket', 'N', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (22, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'Y');
--INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (23, '', 'RESP_COURRIER', 'ValidationBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (420, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (531, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'CopyMailBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (114, '', 'AGENT', 'CopyMailBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'MyBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (414, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (36, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, 'closing_date IS NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
--INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (505, 'closing_date IS NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (506, 'closing_date IS NOT NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (400, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'DepartmentBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'RetAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'AGENT', 'RetAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'AGENT', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'DdeAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'SupAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'AGENT', 'SupAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'EenvBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (507, '', 'AGENT', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (529, '', 'AGENT', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'AGENT', 'EenvBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'SuiviParafBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (527, '', 'AGENT', 'MyBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'AR_Create', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (528, '', 'AGENT', 'AR_Create', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'AR_AlreadySend', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (530, '', 'AGENT', 'AR_AlreadySend', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'GedSampleBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'IntervBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (532, '', 'AGENT', 'IntervBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (414, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (36, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, 'closing_date IS NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
--INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (505, 'closing_date IS NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (506, 'closing_date IS NOT NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (400, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (527, '', 'RESPONSABLE', 'MyBasket', 'Y', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'CopyMailBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (114, '', 'RESPONSABLE', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'ValidAnswerBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'DepartmentBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'RESPONSABLE', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'DdeAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'SupAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'RESPONSABLE', 'SupAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'RetAvisBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'RESPONSABLE', 'RetAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (405, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (416, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (407, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (408, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (410, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'EenvBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (507, '', 'RESPONSABLE', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (529, '', 'RESPONSABLE', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'RESPONSABLE', 'EenvBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'SuiviParafBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'SendToSignatoryBook', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'AGENT', 'SendToSignatoryBook', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'SendToSignatoryBook', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'RESPONSABLE', 'SendToSignatoryBook', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'ELU', 'MyBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'ELU', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'ELU', 'DdeAvisBasket', 'N', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'ARCHIVISTE', 'ToArcBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (500, '', 'ARCHIVISTE', 'ToArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (501, '', 'ARCHIVISTE', 'ToArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (502, '', 'ARCHIVISTE', 'SentArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'ARCHIVISTE', 'SentArcBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'ARCHIVISTE', 'AckArcBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (503, '', 'ARCHIVISTE', 'AckArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (504, '', 'ARCHIVISTE', 'AckArcBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'CABINET', 'SuiviBasket', 'N', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (524, '', 'CABINET', 'SuiviBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (525, '', 'CABINET', 'SuiviBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'SERVICE', 'ValidationBasket', 'N', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'Maileva_Sended', 'N', 'Y', 'Y');

------------
--GROUPBASKET_REDIRECT
------------
TRUNCATE TABLE groupbasket_redirect;
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('COURRIER', 'QualificationBasket', 18, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('COURRIER', 'NumericBasket', 18, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'MY_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'ENTITIES_BELOW', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'ENTITIES_JUST_UP', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'SAME_LEVEL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'MY_ENTITIES', 'USERS');

INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'MY_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'ENTITIES_BELOW', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'ENTITIES_JUST_UP', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'SAME_LEVEL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'MY_ENTITIES', 'USERS');

INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'MY_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'ENTITIES_BELOW', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'ENTITIES_JUST_UP', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'SAME_LEVEL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'MY_ENTITIES', 'USERS');

INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('ELU', 'MyBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'MyBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('AGENT', 'DepartmentBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'MyBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES ('RESPONSABLE', 'DepartmentBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
Select setval('groupbasket_redirect_system_id_seq', (select max(system_id)+1 from groupbasket_redirect), false);

------------
--KEYWORDS / TAGS
------------
TRUNCATE TABLE tags;
INSERT INTO tags (label) VALUES ('SEMINAIRE');
INSERT INTO tags (label) VALUES ('INNOVATION');
INSERT INTO tags (label) VALUES ('MAARCH');
INSERT INTO tags (label) VALUES ('ENVIRONNEMENT');
INSERT INTO tags (label) VALUES ('PARTENARIAT');
INSERT INTO tags (label) VALUES ('JUMELAGE');
INSERT INTO tags (label) VALUES ('ECONOMIE');
INSERT INTO tags (label) VALUES ('ASSOCIATIONS');
INSERT INTO tags (label) VALUES ('RH');
INSERT INTO tags (label) VALUES ('BUDGET');
INSERT INTO tags (label) VALUES ('QUARTIERS');
INSERT INTO tags (label) VALUES ('LITTORAL');
INSERT INTO tags (label) VALUES ('SPORT');

------------
--TEMPLATES
------------
TRUNCATE TABLE templates;
--INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (3, 'Appel téléphonique', 'Appel Téléphonique', '', 'OFFICE', '0000#', 'appel_telephonique.docx', 'ODT: invitation', 'letterbox_attachment', 'indexingFile', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (2, '[notification] Notifications événement', 'Notifications des événements système', '<p><font face="verdana,geneva" size="1">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p><font face="verdana,geneva" size="1"> </font></p>
<p><font face="verdana,geneva" size="1">Voici la liste des &eacute;v&eacute;nements de l''application qui vous sont notifi&eacute;s ([notification.description]) :</font></p>
<table style="width: 800px; height: 36px;" border="0" cellspacing="1" cellpadding="1">
<tbody>
<tr>
<td style="width: 150px; background-color: #0099ff;"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Date</font></strong></font></td>
<td style="width: 150px; background-color: #0099ff;"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Utilisateur </font></strong></font><font face="verdana,geneva" size="1"><strong></strong></font></td>
<td style="width: 500px; background-color: #0099ff;"><font face="verdana,geneva" size="1"><strong><font color="#FFFFFF">Description</font></strong></font></td>
</tr>
<tr>
<td><font face="verdana,geneva" size="1">[events.event_date;block=tr;frm=dd/mm/yyyy hh:nn:ss]</font></td>
<td><font face="verdana,geneva" size="1">[events.user_id]</font></td>
<td><font face="verdana,geneva" size="1">[events.event_info]</font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, '', 'notif_events', 'notifications', NULL);
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (5, '[notification courrier] Alerte 2', '[notification] Alerte 2', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des courriers dont la date limite de traitement est dépassée :n</font></p>
<table style="border: 1pt solid #000000; width: 1582px; height: 77px;" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist_label]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[sender.company;block=tr] [sender.firstname] [sender.lastname] [sender.function] [sender.address_number] [sender.address_street] [sender.address_postcode] [sender.address_town]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktoprocess]" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications', NULL);
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (6, '[notification courrier] Alerte 1', '[notification] Alerte 1', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2"> </font></p>
<p> </p>
<p><font face="arial,helvetica,sans-serif" size="2">Voici la liste des courriers toujours en attente de traitement :</font></p>
<p> </p>
<table style="border: 1pt solid #000000; width: 1582px; height: 77px;" border="1" cellspacing="1" cellpadding="5" frame="box">
<tbody>
<tr>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Référence</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Origine</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif"><strong><font size="2">Emetteur</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Date</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Objet</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#000000"><strong>Type</strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2" color="#FFFFFF"><strong>Liens</strong></font></td>
</tr>
<tr>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.res_id]</font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist_label]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[sender.company;block=tr] [sender.firstname] [sender.lastname] [sender.function] [sender.address_number] [sender.address_street] [sender.address_postcode] [sender.address_town]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktoprocess]" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications', NULL);
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (7, '[notification courrier] Diffusion de courrier', 'Alerte de courriers présents dans les bannettes', '<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Bonjour <strong>[recipient.firstname] [recipient.lastname]</strong>,</p>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Voici la liste des nouveaux courriers pr&eacute;sents dans cette bannette :</p>
<table style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%;">
<tbody>
<tr>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">R&eacute;f&eacute;rence</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Origine</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Emetteur</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Date</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Objet</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Type</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">&nbsp;</th>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.res_id]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.typist_label]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[sender.company;block=tr] [sender.firstname] [sender.lastname][sender.function][sender.address_number][sender.address_street][sender.address_postcode][sender.address_town]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.subject]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.type_label]</td>
<td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodetail]" name="detail">D&eacute;tail</a> <a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodoc]" name="doc">Afficher</a></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; width: 100%; text-align: center; font-size: 9px; font-style: italic; opacity: 0.5;">Message g&eacute;n&eacute;r&eacute; via l''application MaarchCourrier</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications', NULL);
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (8, '[notification courrier] Nouvelle annotation', '[notification] Nouvelle annotation', '<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Bonjour <strong>[recipient.firstname] [recipient.lastname]</strong>,</p>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Voici les nouvelles annotations sur les courriers suivants :</p>
<table style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%;">
<tbody>
<tr>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">R&eacute;f&eacute;rence</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Num</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Date</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Objet</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Note</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Contact</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">&nbsp;</th>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.res_id]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.# ;frm=0000]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.subject]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.content;block=tr]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[sender.company;block=tr] [sender.firstname] [sender.lastname]</td>
<td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodetail]" name="detail">D&eacute;tail</a> <a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodoc]" name="doc">Afficher</a></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; width: 100%; text-align: center; font-size: 9px; font-style: italic; opacity: 0.5;">Message g&eacute;n&eacute;r&eacute; via l''application MaarchCourrier</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'notes', 'notifications', NULL);
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (900, '[TRT] Passer me voir', 'Passer me voir', 'Passer me voir à mon bureau, merci.', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (901, '[TRT] Compléter', 'Compléter', 'Le projet de réponse doit être complété/révisé sur les points suivants : 

- ', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (902, '[AVIS] Demande avis', 'Demande avis', 'Merci de me fournir les éléments de langage pour répondre à ce courrier.', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (904, '[AVIS] Avis favorable', 'Avis favorable', 'Merci de répondre favorablement à la demande inscrite dans ce courrier', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (905, '[CLOTURE] Clôture pour REJET', 'Clôture pour REJET', 'Clôture pour REJET', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (906, '[CLOTURE] Clôture pour ABANDON', 'Clôture pour ABANDON', 'Clôture pour ABANDON', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (907, '[CLOTURE] Clôture RAS', 'Clôture RAS', 'Clôture NORMALE', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (908, '[CLOTURE] Clôture AUTRE', 'Clôture AUTRE', 'Clôture pour ce motif : ', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (909, '[REJET] Erreur affectation', 'Erreur affectation', 'Ce courrier ne semble pas concerner mon service', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (910, '[REJET] Anomalie de numérisation', 'Anomalie de numérisation', 'Le courrier présente des anomalies de numérisation', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1033, 'AR EN MASSE TYPE SIMPLE', 'Cas d’une demande n’impliquant pas de décision implicite de l’administration', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception</strong></span></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
<p>Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. Il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''&eacute;tude des pi&egrave;ces fournies.</p>
<p>Si l''instruction de votre demande n&eacute;cessite des informations ou des pi&egrave;ces compl&eacute;mentaires, nos services vous en ferons la demande</p>
<p>&nbsp;</p>
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p>[userPrimaryEntity.entity_label]</p>
<p>Ville de Maarch-les-Bains</p>
<p>&nbsp;</p>
</div>', 'OFFICE_HTML', '0000#', '0001_1443263267.docx', '', 'letterbox_attachment', 'acknowledgementReceipt', 'simple');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1034, 'AR EN MASSE TYPE SVA', 'Cas d’une demande impliquant une décision implicite d''acceptation de l’administration', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception de votre demande intervenant<br />dans le cadre d''une d&eacute;cision implicite d''acceptation<br /></strong></span></p>
<p>Num&eacute;ro d''enregistrement :<strong> [res_letterbox.alt_identifier]</strong></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''''&eacute;tude des pi&egrave;ces fournies.<br /><br />Votre demande est susceptible de faire l''objet d''''une d&eacute;cision implicite d''''acceptation en l''absence de r&eacute;ponse dans les jours suivant sa r&eacute;ception, soit le <strong>[res_letterbox.process_limit_date]</strong>.<br /><br />Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.<br /><br />Le cas &eacute;ch&eacute;ant, le d&eacute;lai de d&eacute;cision implicite d''acceptation ne d&eacute;butera qu''''apr&egrave;s la production des pi&egrave;ces demand&eacute;es.<br /><br />En cas de d&eacute;cision implicite d''''acceptation vous avez la possibilit&eacute; de demander au service charg&eacute; du dossier une attestation conform&eacute;ment aux dispositions de l''article 22 de la loi n&deg; 2000-321 du 12 avril 2000 relative aux droits des citoyens dans leurs relations avec les administrations modifi&eacute;e.
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p><span style="color: #236fa1;">Ville de Maarch-les-Bains</span><br />[userPrimaryEntity.entity_label]</p>
<p>Courriel : [userPrimaryEntity.email]<br />T&eacute;l&eacute;phone : [user.phone]</p>
</div>', 'OFFICE_HTML', NULL, NULL, 'DOCX: AR_Masse_SVA', 'letterbox_attachment', 'acknowledgementReceipt', 'sva');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1042, 'PR - Demande de Place en crèche', 'Projet de réponse demande de place en crèche', NULL, 'OFFICE', '0000#', '0007_701346109.docx', 'DOCX: PR04_CRECHE', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1037, 'PR - Dérogation carte scolaire', 'Projet de réponse dérogation carte scolaire', NULL, 'OFFICE', '0000#', '0002_2059891324.docx', 'DOCX: PR03_SCOLAIRE', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1045, 'AR TYPE SVR - Courriel Manuel', 'A utiliser avec l''action "Générer les AR"', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception de votre demande intervenant<br />dans le cadre d''une d&eacute;cision implicite de rejet<br /></strong></span></p>
<p>Num&eacute;ro d''enregistrement :<strong> [res_letterbox.alt_identifier]</strong></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''''&eacute;tude des pi&egrave;ces fournies.<br /><br />Votre demande est susceptible de faire l''objet d''une d&eacute;cision implicite de rejet en l''absence de r&eacute;ponse dans les jours suivant sa r&eacute;ception, soit le <strong>[res_letterbox.process_limit_date]</strong>.<br /><br />Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.<br /><br />Dans ce cas, le d&eacute;lai de d&eacute;cision implicite de rejet serait alors suspendu le temps de produire les pi&egrave;ces demand&eacute;es.<br /><br />Si vous estimez que la d&eacute;cision qui sera prise par l''administration est contestable, vous pourrez formuler :<br /><br />- Soit un recours gracieux devant l''auteur de la d&eacute;cision<br />- Soit un recours hi&eacute;rarchique devant le Maire<br />- Soit un recours contentieux devant le Tribunal Administratif territorialement comp&eacute;tent.<br /><br />Le recours gracieux ou le recours hi&eacute;rarchique peuvent &ecirc;tre faits sans condition de d&eacute;lais.<br /><br />Le recours contentieux doit intervenir dans un d&eacute;lai de deux mois &agrave; compter de la notification de la d&eacute;cision.<br /><br />Toutefois, si vous souhaitez en cas de rejet du recours gracieux ou du recours hi&eacute;rarchique former un recours contentieux, ce recours gracieux ou hi&eacute;rarchique devra avoir &eacute;t&eacute; introduit dans le d&eacute;lai sus-indiqu&eacute; du recours contentieux.<br /><br />Vous conserverez ainsi la possibilit&eacute; de former un recours contentieux, dans un d&eacute;lai de deux mois &agrave; compter de la d&eacute;cision intervenue sur ledit recours gracieux ou hi&eacute;rarchique.<br />
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p><span style="color: #236fa1;">Ville de Maarch-les-Bains</span><br />[userPrimaryEntity.entity_label]</p>
<p>Courriel : [userPrimaryEntity.email]<br />T&eacute;l&eacute;phone : [user.phone]</p>
</div>', 'HTML', NULL, NULL, NULL, 'letterbox_attachment', 'sendmail', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1035, 'AR EN MASSE TYPE SVR', 'Cas d’une demande impliquant une décision implicite de rejet de l’administration', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception de votre demande intervenant<br />dans le cadre d''une d&eacute;cision implicite de rejet<br /></strong></span></p>
<p>Num&eacute;ro d''enregistrement :<strong> [res_letterbox.alt_identifier]</strong></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''''&eacute;tude des pi&egrave;ces fournies.<br /><br />Votre demande est susceptible de faire l''objet d''une d&eacute;cision implicite de rejet en l''absence de r&eacute;ponse dans les jours suivant sa r&eacute;ception, soit le <strong>[res_letterbox.process_limit_date]</strong>.<br /><br />Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.<br /><br />Dans ce cas, le d&eacute;lai de d&eacute;cision implicite de rejet serait alors suspendu le temps de produire les pi&egrave;ces demand&eacute;es.<br /><br />Si vous estimez que la d&eacute;cision qui sera prise par l''administration est contestable, vous pourrez formuler :<br /><br />- Soit un recours gracieux devant l''auteur de la d&eacute;cision<br />- Soit un recours hi&eacute;rarchique devant le Maire<br />- Soit un recours contentieux devant le Tribunal Administratif territorialement comp&eacute;tent.<br /><br />Le recours gracieux ou le recours hi&eacute;rarchique peuvent &ecirc;tre faits sans condition de d&eacute;lais.<br /><br />Le recours contentieux doit intervenir dans un d&eacute;lai de deux mois &agrave; compter de la notification de la d&eacute;cision.<br /><br />Toutefois, si vous souhaitez en cas de rejet du recours gracieux ou du recours hi&eacute;rarchique former un recours contentieux, ce recours gracieux ou hi&eacute;rarchique devra avoir &eacute;t&eacute; introduit dans le d&eacute;lai sus-indiqu&eacute; du recours contentieux.<br /><br />Vous conserverez ainsi la possibilit&eacute; de former un recours contentieux, dans un d&eacute;lai de deux mois &agrave; compter de la d&eacute;cision intervenue sur ledit recours gracieux ou hi&eacute;rarchique.<br />
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p><span style="color: #236fa1;">Ville de Maarch-les-Bains</span><br />[userPrimaryEntity.entity_label]</p>
<p>Courriel : [userPrimaryEntity.email]<br />T&eacute;l&eacute;phone : [user.phone]</p>
</div>', 'OFFICE_HTML', NULL, NULL, 'DOCX: AR_Masse_SVR', 'letterbox_attachment', 'acknowledgementReceipt', 'svr');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (20, 'Courriel d''accompagnement', 'Modèle de courriel d''accompagnement', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>[user.firstname] [user.lastname]<br />[userPrimaryEntity.role]<br />[userPrimaryEntity.entity_label]<br /><br />T&eacute;l&eacute;phone : &nbsp;&nbsp; &nbsp;[user.phone]<br />Courriel : &nbsp;&nbsp; &nbsp;[user.mail]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Veuillez trouver en pi&egrave;ce jointe &agrave; ce courriel notre r&eacute;ponse &agrave; votre demande du [res_letterbox.admission_date].</p>
<p>Bien cordialement.</p>
<p>[user.firstname] [user.lastname]<br />[userPrimaryEntity.role]<br />[userPrimaryEntity.entity_label]<br /><br /></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>', 'HTML', NULL, NULL, 'DOCX: standard_nosign', 'letterbox_attachment', 'sendmail', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1036, 'SVE - Courriel de réorientation', 'Modèle de courriel de réorientation d''une saisine SVE', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>[destination.entity_label]<br /><br />T&eacute;l&eacute;phone : &nbsp;&nbsp; &nbsp;[user.phone]<br />Courriel : &nbsp;&nbsp;&nbsp; [destination.email]</p>
<p>[destination.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
Le [res_letterbox.doc_date], vous avez transmis par voie &eacute;lectronique &agrave; la Ville une demande qui ne rel&egrave;ve pas de sa comp&eacute;tence.<br /><br />Votre demande cit&eacute;e en objet de ce courriel a &eacute;t&eacute; transmise &agrave;</div>
<div class="is-node">&nbsp;</div>
<div class="is-node">(veuillez renseigner le nom de l''AUTORITE COMPETENTE).<br />
<p><br /><br /></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>', 'HTML', NULL, NULL, 'DOCX: AR_Masse_SVA', 'letterbox_attachment', 'sendmail', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1038, 'EC - Générique', 'Enregistrement de courrier générique', NULL, 'OFFICE', '0000#', '0003_141957512.docx', 'DOCX: EC01_GENERIC', 'letterbox_attachment', 'indexingFile', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1039, 'EC - Invitation', 'Enregistrement de courrier invitation', NULL, 'OFFICE', '0000#', '0004_1073460227.docx', 'DOCX: EC02_INVITATION', 'letterbox_attachment', 'indexingFile', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1040, 'PR - Générique', 'Projet de réponse générique', NULL, 'OFFICE', '0000#', '0005_1965884423.docx', 'DOCX: PR01_GENERIC', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1041, 'PR - Invitation', 'Projet de réponse invitation', NULL, 'OFFICE', '0000#', '0006_1359063643.docx', 'DOCX: PR02_INVITATION', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1043, 'AR TYPE SIMPLE- Courriel Manuel', 'A utiliser avec l''action "Générer les AR"', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception</strong></span></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
<p>Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. Il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''&eacute;tude des pi&egrave;ces fournies.</p>
<p>Si l''instruction de votre demande n&eacute;cessite des informations ou des pi&egrave;ces compl&eacute;mentaires, nos services vous en ferons la demande</p>
<p>&nbsp;</p>
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p>[userPrimaryEntity.entity_label]</p>
<p>Ville de Maarch-les-Bains</p>
<p>&nbsp;</p>
</div>', 'HTML', NULL, NULL, NULL, 'letterbox_attachment', 'sendmail', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1044, 'AR TYPE SVA - Courriel Manuel', 'A utiliser avec l''action "Générer les AR"', '<div id="write" class="is-node"><br /><hr /><span style="color: #236fa1;">H&ocirc;tel de ville</span><br /><span style="color: #236fa1;">Place de la Libert&eacute;</span><br /><span style="color: #236fa1;">99000 Maarch-les-bains</span>
<p>&nbsp;</p>
<p><span style="color: #236fa1;"><strong>Accus&eacute; de r&eacute;ception de votre demande intervenant<br />dans le cadre d''une d&eacute;cision implicite d''acceptation<br /></strong></span></p>
<p>Num&eacute;ro d''enregistrement :<strong> [res_letterbox.alt_identifier]</strong></p>
<p>Service instructeur : <strong>[userPrimaryEntity.entity_label]</strong> <br />Courriel : [userPrimaryEntity.email]</p>
<p>[userPrimaryEntity.city], le [datetime.date;frm=dddd dd mmmm yyyy;locale]</p>
<hr />
<p>Bonjour,</p>
<p>Votre demande concernant :</p>
<p><strong>[res_letterbox.subject]</strong></p>
<p>&agrave; bien &eacute;t&eacute; r&eacute;ceptionn&eacute;e par nos services le [res_letterbox.admission_date].</p>
<p><br />La r&eacute;f&eacute;rence de votre dossier est : <strong>[res_letterbox.alt_identifier]</strong></p>
Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''''&eacute;tude des pi&egrave;ces fournies.<br /><br />Votre demande est susceptible de faire l''objet d''''une d&eacute;cision implicite d''''acceptation en l''absence de r&eacute;ponse dans les jours suivant sa r&eacute;ception, soit le <strong>[res_letterbox.process_limit_date]</strong>.<br /><br />Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.<br /><br />Le cas &eacute;ch&eacute;ant, le d&eacute;lai de d&eacute;cision implicite d''acceptation ne d&eacute;butera qu''''apr&egrave;s la production des pi&egrave;ces demand&eacute;es.<br /><br />En cas de d&eacute;cision implicite d''''acceptation vous avez la possibilit&eacute; de demander au service charg&eacute; du dossier une attestation conform&eacute;ment aux dispositions de l''article 22 de la loi n&deg; 2000-321 du 12 avril 2000 relative aux droits des citoyens dans leurs relations avec les administrations modifi&eacute;e.
<p>Nous vous conseillons de conserver ce message jusqu''&agrave; la fin du traitement de votre dossier.</p>
<p>&nbsp;</p>
<p><span style="color: #236fa1;">Ville de Maarch-les-Bains</span><br />[userPrimaryEntity.entity_label]</p>
<p>Courriel : [userPrimaryEntity.email]<br />T&eacute;l&eacute;phone : [user.phone]</p>
</div>', 'HTML', NULL, NULL, NULL, 'letterbox_attachment', 'sendmail', 'all');

INSERT INTO templates (template_label, template_comment, template_content, template_type, template_style, template_target, template_attachment_type) VALUES ('Quota d''utilisateur', 'Modèle de notification pour le quota utilisateur', '<p>Quota utilisateur atteint</p>', 'HTML', 'ODT: rep_standard', 'notifications', 'all');

--INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1033, 'AccuseReceptionPapier', 'Modèle d''accusé de réception papier', '', 'OFFICE_HTML', '0000#', 'ar_derogation.odt', 'ODT: ar_derogation', 'letterbox_attachment', 'acknowledgementReceipt', 'simple');
------------
Select setval('templates_seq', (select max(template_id)+1 from templates), false);

------------
--PRIORITES
------------
TRUNCATE TABLE priorities;
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1357nbvc', 'Normal', '#009dc5', 30, 1);
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1379nbvc', 'Urgent', '#ffa500', 8, 2);
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1391nbvc', 'Très urgent', '#ff0000', 4, 3);

------------
--NOTIFICATIONS
------------
TRUNCATE TABLE notifications;

INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties)
VALUES (1, 'USERS', '[administration] Actions sur les utilisateurs de l''application', 'users%', 'EMAIL', 2, 'user', 'superadmin', '', '');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (2, 'RET2', 'Courriers en retard de traitement', 'alert2', 'EMAIL', 5, 'dest_user', '', '', '', 'Y');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (3, 'RET1', 'Courriers arrivant à échéance', 'alert1', 'EMAIL', 6, 'dest_user', '', '', '', 'Y');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (4, 'BASKETS', 'Notification de bannettes', 'baskets', 'EMAIL', 7, 'dest_user', '', '', '', 'Y');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (5, 'ANC', 'Nouvelle annotation sur courrier en copie', 'noteadd', 'EMAIL', 8, 'copy_list', '', '', '', 'Y');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (6, 'AND', 'Nouvelle annotation sur courrier destinataire', 'noteadd', 'EMAIL', 8, 'dest_user', '', '', '', 'Y');
INSERT INTO notifications (notification_sid, notification_id, description, event_id, notification_mode, template_id, diffusion_type, diffusion_properties, attachfor_type, attachfor_properties, is_enabled)
VALUES (7, 'RED', 'Redirection de courrier', '1', 'EMAIL', 7, 'dest_user', '', '', '', 'Y');
INSERT INTO notifications (template_id, notification_id, description, is_enabled, event_id, notification_mode, diffusion_type, diffusion_properties) SELECT template_id, 'QUOTA', 'Alerte lorsque le quota est dépassé', 'Y', 'user_quota', 'EMAIL', 'user', 'superadmin' FROM templates WHERE template_label = 'Quota d''utilisateur';
Select setval('notifications_seq', (select max(notification_sid)+1 from notifications), false);
------------
--TEMPLATES_ASSOCIATION
------------
--Rebuild template association for OFFICE documents
------------
TRUNCATE TABLE templates_association;
INSERT INTO templates_association
(template_id, value_field)
SELECT template_id, entity_id
FROM templates, entities
WHERE template_type in ('OFFICE','TXT') or template_target = 'acknowledgementReceipt';
Select setval('templates_association_id_seq', (select max(id)+1 from templates_association), false);
-----
-- Archive identifiers
-----

UPDATE entities SET business_id = concat('45239273100025/',entity_id);

UPDATE entities SET business_id = concat('45239273100025/',entity_id);
--UPDATE entities SET archival_agreement = 'MAARCH_LES_BAINS_ACTES_V2';

UPDATE doctypes SET retention_final_disposition = 'destruction';
UPDATE doctypes SET retention_rule = 'compta_3_03';
UPDATE doctypes SET duration_current_use = 365;

-----
-- Password management
-----
TRUNCATE TABLE password_rules;
INSERT INTO password_rules (label, "value", enabled) VALUES ('minLength', 6, true);
INSERT INTO password_rules (label, "value") VALUES ('complexityUpper', 0);
INSERT INTO password_rules (label, "value") VALUES ('complexityNumber', 0);
INSERT INTO password_rules (label, "value") VALUES ('complexitySpecial', 0);
INSERT INTO password_rules (label, "value") VALUES ('lockAttempts', 3);
INSERT INTO password_rules (label, "value") VALUES ('lockTime', 5);
INSERT INTO password_rules (label, "value") VALUES ('historyLastUse', 2);
INSERT INTO password_rules (label, "value") VALUES ('renewal', 90);

-----
-- Contacts completion ratio
-----
TRUNCATE TABLE contacts_filling;
INSERT INTO contacts_filling (enable, first_threshold, second_threshold) VALUES (true, 33, 66);

/* Configurations */
TRUNCATE TABLE configurations;
INSERT INTO configurations (privilege, value) VALUES ('admin_email_server', '{"type":"smtp","host":"smtp.gmail.com","port":465,"user":"notifications.maarch@gmail.com","password":"1OkrZQPFNJmI7uY8::7e870f0f61ecc7e9fb6e7065dd31aea5","auth":true,"secure":"ssl","from":"notifications.maarch@gmail.com","charset":"utf-8"}');
INSERT INTO configurations (privilege, value) VALUES ('admin_search', '{"listEvent": {"defaultTab": "dashboard"},"listDisplay":{"templateColumns":6,"subInfos":[{"value":"getPriority","cssClasses":["align_leftData"],"icon":"fa-traffic-light"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_leftData"],"icon":"fa-calendar"},{"value":"getAssignee","cssClasses":["align_leftData"],"icon":"fa-sitemap"},{"value":"getDoctype","cssClasses":["align_leftData"],"icon":"fa-suitcase"},{"value":"getRecipients","cssClasses":["align_leftData"],"icon":"fa-user"},{"value":"getSenders","cssClasses":["align_leftData"],"icon":"fa-book"}]}}');
INSERT INTO configurations (privilege, value) VALUES ('admin_sso', '{"url":"","mapping":[{"ssoId":"","maarchId":"login"}]}');

/* Modèle d’envois postaux */
TRUNCATE TABLE shipping_templates;
INSERT INTO shipping_templates (id, label, description, options, fee, entities, account) VALUES (1, 'Modèle d''exemple d''envoi postal', 'Modèle d''exemple d''envoi postal', '{"shapingOptions":[],"sendMode":"fast"}', '{"firstPagePrice":0.4,"nextPagePrice":0.5,"postagePrice":0.9}', '["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "17", "18", "16", "19", "20"]', '{"id":"sandbox.562","password":"VPh5AY6i::82f88fe97cead428e0885084f93a684c"}');
Select setval('shipping_templates_id_seq', (select max(id)+1 from shipping_templates), false);

/* Champs customs */
TRUNCATE TABLE custom_fields;
INSERT INTO custom_fields (id, label, type, "values") VALUES (1, 'Date de fin de contrat', 'date', '[]');
INSERT INTO custom_fields (id, label, type, "values") VALUES (2, 'Adresse d''intervention', 'banAutocomplete', '[]');
INSERT INTO custom_fields (id, label, type, values) VALUES (3, 'Nature', 'select', '["Courrier simple", "Courriel", "Courrier suivi", "Courrier avec AR", "Fax", "Chronopost", "Fedex", "Courrier AR", "Coursier", "Pli numérique", "Autre"]');
INSERT INTO custom_fields (id, label, type, "values") VALUES (4, 'Référence courrier expéditeur', 'string', '[]');
INSERT INTO custom_fields (id, label, type, "values") VALUES (5, 'Num recommandé', 'string', '[]');
SELECT setval('custom_fields_id_seq', (select max(id)+1 from custom_fields), false);

/* Modèles d'enregistrement */
TRUNCATE TABLE indexing_models;
INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (1, 'incoming', 'Courrier Arrivée', TRUE, 23, FALSE, TRUE);
INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (2, 'outgoing', 'Courrier Départ', FALSE, 23, FALSE, TRUE);
INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (3, 'internal', 'Note Interne', FALSE, 23, FALSE, TRUE);
INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (4, 'ged_doc', 'Document GED', FALSE, 23, FALSE, TRUE);
--INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (5, 'incoming', 'Courrier Arrivée - Formulaire complet', FALSE, 23, FALSE, TRUE);
--INSERT INTO indexing_models (id, category, label, "default", owner, private, enabled) VALUES (6, 'incoming', 'Allo Mairie – Intervention voirie', FALSE, 23, FALSE, TRUE);
Select setval('indexing_models_id_seq', (select max(id)+1 from indexing_models), false);

TRUNCATE TABLE indexing_models_fields;
/* Arrivée */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'doctype', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'priority', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'documentDate', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'arrivalDate', TRUE, '"_TODAY"', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'subject', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'senders', TRUE, '[]', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'destination', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'processLimitDate', TRUE, null, 'process');

/* Départ */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'doctype', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'priority', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'confidentiality', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'documentDate', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'departureDate', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'subject', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'senders', FALSE, null, 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'recipients', TRUE, null, 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'initiator', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'destination', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'processLimitDate', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'folders', FALSE, null, 'classifying');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'tags', FALSE, null, 'classifying');

/* Interne */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'doctype', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'priority', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'confidentiality', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'documentDate', TRUE, '"_TODAY"', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'subject', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'senders', false, '[]', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'initiator', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'destination', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'processLimitDate', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'folders', FALSE, null, 'classifying');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'tags', FALSE, null, 'classifying');

/* GED */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'doctype', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'documentDate', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'subject', TRUE, null, 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'senders', FALSE, null, 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'destination', TRUE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'indexingCustomField_1', FALSE, null, 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'folders', FALSE, null, 'classifying');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'tags', FALSE, null, 'classifying');

/* Arrivée - formulaire complet */
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'doctype', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'priority', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'confidentiality', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'documentDate', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'arrivalDate', TRUE, '"_TODAY"', 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'subject', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'indexingCustomField_3', FALSE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'indexingCustomField_4', FALSE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'senders', TRUE, null, 'contact');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'recipients', FALSE, null, 'contact');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'initiator', TRUE, null, 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'destination', TRUE, null, 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'processLimitDate', TRUE, null, 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'folders', FALSE, null, 'classifying');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (5, 'tags', FALSE, null, 'classifying');

/* Allo Mairie – Demande d'intervention*/
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'doctype', TRUE, '1202', 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'priority', TRUE, null, 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'documentDate', TRUE, '"_TODAY"', 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'arrivalDate', TRUE, '"_TODAY"', 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'subject', TRUE, '"Demande intervention - "', 'mail');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'senders', TRUE, null, 'contact');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'indexingCustomField_2', FALSE, null, 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'destination', TRUE, '10', 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'processLimitDate', TRUE, null, 'process');
--INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (6, 'folders', FALSE, '[33]', 'classifying');

INSERT INTO parameters (id, description, param_value_string) VALUES ('siret', 'Numéro SIRET de l''entreprise', '45239273100025');

/* Recomande*/
INSERT INTO registered_mail_issuing_sites (id, label, post_office_label, account_number, address_number, address_street, address_additional1, address_additional2, address_postcode, address_town, address_country) VALUES (1, 'MAARCH - Nanterre', 'La poste Nanterre', 1234567, '10', 'AVENUE DE LA GRANDE ARMEE', '', '', '75017', 'PARIS', 'FRANCE');
SELECT setval('registered_mail_issuing_sites_id_seq', (select max(id)+1 from registered_mail_issuing_sites), false);

INSERT INTO registered_mail_issuing_sites_entities (site_id, entity_id) VALUES (1, 6);
INSERT INTO registered_mail_issuing_sites_entities (site_id, entity_id) VALUES (1, 13);

INSERT INTO registered_mail_number_range (type, tracking_account_number, range_start, range_end, creator, creation_date, status, current_number) VALUES ('2C', 'SuiviNumber', 1, 10, 23, '2020-09-14 14:38:09.008644', 'OK', 1);
INSERT INTO registered_mail_number_range (type, tracking_account_number, range_start, range_end, creator, creation_date, status, current_number) VALUES ('RW', 'SuiviNumberInternational', 1, 10, 23, '2020-09-14 14:39:32.972626', 'OK', 1);
INSERT INTO registered_mail_number_range (type, tracking_account_number, range_start, range_end, creator, creation_date, status, current_number) VALUES ('2D', 'suiviNumber', 1, 10, 23, '2020-09-14 14:39:16.779322', 'OK', 1);
