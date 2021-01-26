
-- Create USERGROUPS & USERGROUPS_SERVICES
TRUNCATE TABLE usergroups;
TRUNCATE TABLE usergroups_services;
DELETE FROM usergroups WHERE group_id = 'COURRIER';
DELETE FROM usergroups_services WHERE group_id = 'COURRIER';
INSERT INTO usergroups (group_id,group_desc, can_index, indexation_parameters) VALUES ('COURRIER', 'Scanning operator', TRUE, , '{"actions":["21"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'AGENT';
DELETE FROM usergroups_services WHERE group_id = 'AGENT';
INSERT INTO usergroups (group_id,group_desc, can_index, indexation_parameters) VALUES ('AGENT', 'Agent', TRUE, '{"actions":["21"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'RESP_COURRIER';
DELETE FROM usergroups_services WHERE group_id = 'RESP_COURRIER';
INSERT INTO usergroups (group_id,group_desc, can_index, indexation_parameters) VALUES ('RESP_COURRIER', 'Supervisor', TRUE, '{"actions":["21"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'RESPONSABLE';
DELETE FROM usergroups_services WHERE group_id = 'RESPONSABLE';
INSERT INTO usergroups (group_id,group_desc, can_index, indexation_parameters) VALUES ('RESPONSABLE', 'Manager', TRUE, '{"actions":["21"], "entities":[], "keywords":["ALL_ENTITIES"]}');
DELETE FROM usergroups WHERE group_id = 'ADMINISTRATEUR_N1';
DELETE FROM usergroups_services WHERE group_id = 'ADMINISTRATEUR_N1';
INSERT INTO usergroups (group_id,group_desc) VALUES ('ADMINISTRATEUR_N1', 'Func. Admin n1');
DELETE FROM usergroups WHERE group_id = 'ADMINISTRATEUR_N2';
DELETE FROM usergroups_services WHERE group_id = 'ADMINISTRATEUR_N2';
INSERT INTO usergroups (group_id,group_desc) VALUES ('ADMINISTRATEUR_N2', 'Func. Admin n2');
DELETE FROM usergroups WHERE group_id = 'DIRECTEUR';
DELETE FROM usergroups_services WHERE group_id = 'DIRECTEUR';
INSERT INTO usergroups (group_id,group_desc) VALUES ('DIRECTEUR', 'Director');
DELETE FROM usergroups WHERE group_id = 'ELU';
DELETE FROM usergroups_services WHERE group_id = 'ELU';
INSERT INTO usergroups (group_id,group_desc) VALUES ('ELU', 'Elu');
DELETE FROM usergroups WHERE group_id = 'CABINET';
DELETE FROM usergroups_services WHERE group_id = 'CABINET';
INSERT INTO usergroups (group_id,group_desc) VALUES ('CABINET', 'Cabinet');
DELETE FROM usergroups WHERE group_id = 'ARCHIVISTE';
DELETE FROM usergroups_services WHERE group_id = 'ARCHIVISTE';
INSERT INTO usergroups (group_id,group_desc) VALUES ('ARCHIVISTE', 'Archivist');
DELETE FROM usergroups WHERE group_id = 'MAARCHTOGEC';
DELETE FROM usergroups_services WHERE group_id = 'MAARCHTOGEC';
INSERT INTO usergroups (group_id,group_desc) VALUES ('MAARCHTOGEC', 'Envoi dématérialisé');
DELETE FROM usergroups WHERE group_id = 'SERVICE';
DELETE FROM usergroups_services WHERE group_id = 'SERVICE';
INSERT INTO usergroups (group_id,group_desc) VALUES ('SERVICE', 'Service');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'search_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'my_contacts_menu');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_status_mail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'entities_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'update_diffusion_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'edit_attachments_from_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'modify_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'delete_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'add_tag_to_res');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'create_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', '_print_sep');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'physical_archive_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('COURRIER', 'manage_numeric_package');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'search_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'my_contacts_menu');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_list_diff_in_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'edit_attachments_from_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'update_diffusion_indexing');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'edit_recipient_outside_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'modify_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'delete_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_visa_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_visa_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_avis_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'config_avis_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'add_tag_to_res');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('AGENT', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'search_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'my_contacts_menu');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'edit_recipient_outside_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'update_list_diff_in_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'add_tag_to_res');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESP_COURRIER', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'search_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'my_contacts_menu');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'update_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'add_links');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'edit_recipient_outside_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'update_list_diff_in_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'edit_attachments_from_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'modify_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'delete_attachments');
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
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'add_tag_to_res');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('RESPONSABLE', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'search_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_users');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_groups');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_architecture');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_history_batch');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_status');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_actions');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'my_contacts_menu');
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
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'manage_entities');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_difflist_types');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_listmodels');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'edit_recipient_outside_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'update_list_diff_in_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'entities_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'use_mail_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'edit_attachments_from_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'modify_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'delete_attachments');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'add_new_version');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'view_version_letterbox');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_visa_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_visa_workflow_in_detail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'print_folder_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'config_avis_workflow');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_templates');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'add_tag_to_res');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'create_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'private_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_notif');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', '_print_sep');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'physical_archive_print_sep_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'physical_archive_batch_manage');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N1', 'admin_life_cycle');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'edit_resource');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin_templates');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINISTRATEUR_N2', 'admin_tag');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'create_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_doc_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'view_full_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'sendmail');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'avis_documents');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTE', 'tag_view');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MAARCHTOGEC', 'manage_numeric_package');

-- Create DOCTYPES
TRUNCATE TABLE DOCTYPES_FIRST_LEVEL;
TRUNCATE TABLE DOCTYPES_SECOND_LEVEL;
TRUNCATE TABLE DOCTYPES;
TRUNCATE TABLE MLB_DOCTYPE_EXT;
TRUNCATE TABLE DOCTYPES_INDEXES;
TRUNCATE TABLE TEMPLATES_DOCTYPE_EXT;

INSERT INTO doctypes_first_level (doctypes_first_level_id, doctypes_first_level_label, css_style, enabled) VALUES (1, 'COURRIERS', '#000000', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (1, '01. General Correspondence', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (101, 'Subscriptions – documentation – archives', 'Y', 1, 1, 30, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (102, 'Convocation', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (103, 'Document query', 'Y', 1, 1, 30, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (104, 'Furnitures and hardware query', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (105, 'Appointment query', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (106, 'Information query', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (108, 'Multi-purpose query', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (110, 'Invitation', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (111, 'Memo', 'Y', 1, 1, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (2, '02. Presidency', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (202, 'Communication', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (203, 'Politics', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (204, 'International relations', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (205, 'Thankings and congratulations', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (206, 'Security', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (207, 'Suggestion', 'Y', 1, 2, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (3, '03. Finance and banking', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (301, 'Claim', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (302, 'Contract', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (303, 'Loan', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (304, 'Payment', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (305, 'Invoice', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (306, 'Proposal', 'Y', 1, 3, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (4, '04. Legal', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (401, 'Formal notice', 'Y', 1, 4, 2, 14, 1, 'NORMAL'));
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (402, 'Lawsuit', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (403, 'Claims and complains', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (404, 'Graceful appeals and complaints', 'Y', 1, 4, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (5, '05. Human ressources', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (501, 'Work stoppages and illness', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (501, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (502, 'Staff Insurance', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (502, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (503, 'Application', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (504, 'Career', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (504, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (505, 'Health working conditions', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (505, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (506, 'Exceptional leave and competition', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (506, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (507, 'Training', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (507, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (508, 'HR Instances', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (508, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (509, 'Retirement', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (509, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (510, 'Traineeship', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (510, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (511, 'Unions', 'Y', 1, 5, 60, 14, 1, 'NORMAL');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (511, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (12, '12. Forms', 1, '#000000', 'Y');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (1201, 'Phone call', 'Y', 1, 12, 21, 14, 1, 'NORMAL');
INSERT INTO doctypes (type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, process_delay, delay1, delay2, process_mode) VALUES (1202, 'Intervention query', 'Y', 1, 12, 21, 14, 1, 'NORMAL');
select setval('doctypes_first_level_id_seq', (select max(doctypes_first_level_id)+1 from doctypes_first_level), false);
select setval('doctypes_second_level_id_seq', (select max(doctypes_second_level_id)+1 from doctypes_second_level), false);
select setval('doctypes_type_id_seq', (select max(type_id)+1 from doctypes), false);

-- Create USERS
DELETE FROM users WHERE user_id <> 'superadmin';
TRUNCATE TABLE users_entities;
DELETE FROM users WHERE user_id = 'rrenaud';
DELETE FROM users_entities WHERE user_id = 'rrenaud';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (1, 'rrenaud', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Robert', 'RENAUD', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('rrenaud', 'DGS', '', 'Y');
DELETE FROM users WHERE user_id = 'ccordy';
DELETE FROM users_entities WHERE user_id = 'ccordy';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (2, 'ccordy', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Chloé', 'CORDY', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ccordy', 'DSI', '', 'Y');
DELETE FROM users WHERE user_id = 'ssissoko';
DELETE FROM users_entities WHERE user_id = 'ssissoko';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (3, 'ssissoko', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Sylvain', 'SISSOKO', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ssissoko', 'DSI', '', 'Y');
DELETE FROM users WHERE user_id = 'nnataly';
DELETE FROM users_entities WHERE user_id = 'nnataly';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (4, 'nnataly', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Nancy', 'NATALY', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('nnataly', 'PSO', '', 'Y');
DELETE FROM users WHERE user_id = 'ddur';
DELETE FROM users_entities WHERE user_id = 'ddur';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (5, 'ddur', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Dominique', 'DUR', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ddur', 'ELUS', '', 'Y');
DELETE FROM users WHERE user_id = 'jjane';
DELETE FROM users_entities WHERE user_id = 'jjane';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (6, 'jjane', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Jenny', 'JANE', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('jjane', 'CCAS', '', 'Y');
DELETE FROM users WHERE user_id = 'eerina';
DELETE FROM users_entities WHERE user_id = 'eerina';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (7, 'eerina', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Edith', 'ERINA', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('eerina', 'CAB', '', 'Y');
DELETE FROM users WHERE user_id = 'kkaar';
DELETE FROM users_entities WHERE user_id = 'kkaar';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (8, 'kkaar', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Katy', 'KAAR', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('kkaar', 'DGA', '', 'Y');
DELETE FROM users WHERE user_id = 'bboule';
DELETE FROM users_entities WHERE user_id = 'bboule';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (9, 'bboule', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Bruno', 'BOULE', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bboule', 'PCU', '', 'Y');
DELETE FROM users WHERE user_id = 'ppetit';
DELETE FROM users_entities WHERE user_id = 'ppetit';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (10, 'ppetit', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Patricia', 'PETIT', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ppetit', 'VILLE', '', 'Y');
DELETE FROM users WHERE user_id = 'aackermann';
DELETE FROM users_entities WHERE user_id = 'aackermann';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (11, 'aackermann', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Amanda', 'ACKERMANN', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('aackermann', 'PSF', '', 'Y');
DELETE FROM users WHERE user_id = 'ppruvost';
DELETE FROM users_entities WHERE user_id = 'ppruvost';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (12, 'ppruvost', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Pierre', 'PRUVOST', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ppruvost', 'DRH', '', 'Y');
DELETE FROM users WHERE user_id = 'ttong';
DELETE FROM users_entities WHERE user_id = 'ttong';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (13, 'ttong', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Tony', 'TONG', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ttong', 'SP', '', 'Y');
DELETE FROM users WHERE user_id = 'sstar';
DELETE FROM users_entities WHERE user_id = 'sstar';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (14, 'sstar', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Suzanne', 'STAR', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('sstar', 'FIN', '', 'Y');
DELETE FROM users WHERE user_id = 'ssaporta';
DELETE FROM users_entities WHERE user_id = 'ssaporta';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (15, 'ssaporta', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Sabrina', 'SAPORTA', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ssaporta', 'PE', '', 'Y');
DELETE FROM users WHERE user_id = 'ccharles';
DELETE FROM users_entities WHERE user_id = 'ccharles';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (16, 'ccharles', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Charlotte', 'CHARLES', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ccharles', 'PTE', '', 'Y');
DELETE FROM users WHERE user_id = 'mmanfred';
DELETE FROM users_entities WHERE user_id = 'mmanfred';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (17, 'mmanfred', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Martin', 'MANFRED', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('mmanfred', 'DGA', '', 'Y');
DELETE FROM users WHERE user_id = 'ddaull';
DELETE FROM users_entities WHERE user_id = 'ddaull';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (18, 'ddaull', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Denis', 'DAULL', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ddaull', 'DSG', '', 'Y');
DELETE FROM users WHERE user_id = 'bbain';
DELETE FROM users_entities WHERE user_id = 'bbain';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (19, 'bbain', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Barbara', 'BAIN', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bbain', 'PJS', '', 'Y');
DELETE FROM users WHERE user_id = 'jjonasz';
DELETE FROM users_entities WHERE user_id = 'jjonasz';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (20, 'jjonasz', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Jean', 'JONASZ', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('jjonasz', 'PJU', '', 'Y');
DELETE FROM users WHERE user_id = 'bblier';
DELETE FROM users_entities WHERE user_id = 'bblier';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (21, 'bblier', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Bernard', 'BLIER', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bblier', 'COU', '', 'Y');
DELETE FROM users WHERE user_id = 'ggrand';
DELETE FROM users_entities WHERE user_id = 'ggrand';
INSERT INTO users (id, user_id, password, firstname, lastname, mail, enabled, change_password, status) VALUES (22, 'ggrand', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Georges', 'GRAND', 'info@maarch.org', 'Y', 'N', 'OK');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ggrand', 'COR', '', 'Y');
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
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (18, 4, '');
DELETE FROM usergroup_content WHERE user_id = 19;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (19, 2, '');
DELETE FROM usergroup_content WHERE user_id = 20;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (20, 2, '');
DELETE FROM usergroup_content WHERE user_id = 21;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (21, 1, '');
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (21, 5, '');
DELETE FROM usergroup_content WHERE user_id = 22;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (22, 10, '');


-- Create ENTITIES and LISTMODELS
TRUNCATE TABLE entities;
TRUNCATE TABLE listmodels;
DELETE FROM entities WHERE entity_id = 'ACME';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ACME', 'ACME – A Company that Makes Everything', 'ACME – A Company that Makes Everything', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', '', 'Direction');
DELETE FROM listmodels WHERE object_id = 'ACME' AND object_type = 'entity_id';
DELETE FROM entities WHERE entity_id = 'CAB';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('CAB', 'Presidency', 'Presidency', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'ACME', 'Direction');
DELETE FROM listmodels WHERE object_id = 'CAB' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('CAB', 'entity_id', 0, 'eerina', 'user_id', 'dest', 'Presidency','Presidency', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'CAB', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Presidency','Presidency', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'CAB', 'entity_id', 0, 'ppetit', 'user_id', 'cc', 'Presidency','Presidency', '', 'Y');
DELETE FROM entities WHERE entity_id = 'DGS';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DGS', 'Managing Director Office', 'Managing Director Office', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'ACME', 'Direction');
DELETE FROM listmodels WHERE object_id = 'DGS' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('DGS', 'entity_id', 0, 'rrenaud', 'user_id', 'dest', 'Managing Director Office','Managing Director Office', '', 'Y');
DELETE FROM entities WHERE entity_id = 'DGA';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DGA', 'Operations Department', 'Operations Department', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGS', 'Bureau');
DELETE FROM listmodels WHERE object_id = 'DGA' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('DGA', 'entity_id', 0, 'mmanfred', 'user_id', 'dest', 'Operations Department','Operations Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'DGA', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Operations Department','Operations Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'DGA', 'entity_id', 0, 'kkaar', 'user_id', 'cc', 'Operations Department','Operations Department', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PCU';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PCU', 'Culture and communication branch', 'Culture and communication branch', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGA', 'Service');
DELETE FROM listmodels WHERE object_id = 'PCU' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PCU', 'entity_id', 0, 'bboule', 'user_id', 'dest', 'Culture and communication branch','Culture and communication branch', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PCU', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Culture and communication branch','Culture and communication branch', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PJS';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PJS', 'Youth and sport branch', 'Youth and sport branch', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGA', 'Service');
DELETE FROM listmodels WHERE object_id = 'PJS' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PJS', 'entity_id', 0, 'bbain', 'user_id', 'dest', 'Youth and sport branch','Youth and sport branch', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PJS', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Youth and sport branch','Youth and sport branch', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PE';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PE', 'Children products', 'Children products', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'PJS', 'Service');
DELETE FROM listmodels WHERE object_id = 'PE' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PE', 'entity_id', 0, 'ssaporta', 'user_id', 'dest', 'Children products','Children products', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PE', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Children products','Children products', '', 'Y');
DELETE FROM entities WHERE entity_id = 'SP';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('SP', 'Sport and entertainment', 'Sport and entertainment', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'PJS', 'Service');
DELETE FROM listmodels WHERE object_id = 'SP' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('SP', 'entity_id', 0, 'ttong', 'user_id', 'dest', 'Sport and entertainment','Sport and entertainment', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'SP', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Sport and entertainment','Sport and entertainment', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PSO';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PSO', 'Educational services', 'Educational services', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGA', 'Service');
DELETE FROM listmodels WHERE object_id = 'PSO' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PSO', 'entity_id', 0, 'nnataly', 'user_id', 'dest', 'Educational services','Educational services', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PSO', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Educational services','Educational services', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PSO', 'VISA_CIRCUIT',0, 'mmanfred', 'user_id', 'visa', 'PSO','PSO', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PSO', 'VISA_CIRCUIT',1, 'ppetit', 'user_id', 'visa', 'PSO','PSO', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PTE';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PTE', 'Hardware & machinery', 'Hardware & machinery', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGA', 'Service');
DELETE FROM listmodels WHERE object_id = 'PTE' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PTE', 'entity_id', 0, 'ccharles', 'user_id', 'dest', 'Hardware & machinery','Hardware & machinery', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PTE', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Hardware & machinery','Hardware & machinery', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PTE', 'VISA_CIRCUIT',0, 'mmanfred', 'user_id', 'visa', 'PTE','PTE', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PTE', 'VISA_CIRCUIT',1, 'ppetit', 'user_id', 'visa', 'PTE','PTE', '', 'Y');
DELETE FROM entities WHERE entity_id = 'DRH';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DRH', 'Human Ressources Department', 'Human Ressources Department', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGS', 'Service');
DELETE FROM listmodels WHERE object_id = 'DRH' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('DRH', 'entity_id', 0, 'ppruvost', 'user_id', 'dest', 'Human Ressources Department','Human Ressources Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'DRH', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Human Ressources Department','Human Ressources Department', '', 'Y');
DELETE FROM entities WHERE entity_id = 'DSG';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DSG', 'General Secretariat', 'General Secretariat', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGS', 'Direction');
DELETE FROM listmodels WHERE object_id = 'DSG' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('DSG', 'entity_id', 0, 'ddaull', 'user_id', 'dest', 'General Secretariat','General Secretariat', '', 'Y');
DELETE FROM entities WHERE entity_id = 'COU';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COU', 'Mail room', 'Mail room', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DSG', 'Service');
DELETE FROM listmodels WHERE object_id = 'COU' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('COU', 'entity_id', 0, 'bblier', 'user_id', 'dest', 'Mail room','Mail room', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'COU', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Mail room','Mail room', '', 'Y');
DELETE FROM entities WHERE entity_id = 'COR';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COR', 'Archives and documentation', 'Archives and documentation', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'COU', 'Service');
DELETE FROM listmodels WHERE object_id = 'COR' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('COR', 'entity_id', 0, 'ggrand', 'user_id', 'dest', 'Archives and documentation','Archives and documentation', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PSF';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PSF', 'Facilities management', 'Facilities management', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DSG', 'Service');
DELETE FROM listmodels WHERE object_id = 'PSF' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PSF', 'entity_id', 0, 'aackermann', 'user_id', 'dest', 'Facilities management','Facilities management', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PSF', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Facilities management','Facilities management', '', 'Y');
DELETE FROM entities WHERE entity_id = 'DSI';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DSI', 'IT Service', 'IT Service', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGS', 'Service');
DELETE FROM listmodels WHERE object_id = 'DSI' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('DSI', 'entity_id', 0, 'ssissoko', 'user_id', 'dest', 'IT Service','IT Service', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'DSI', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'IT Service','IT Service', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'DSI', 'entity_id', 0, 'ccordy', 'user_id', 'cc', 'IT Service','IT Service', '', 'Y');
DELETE FROM entities WHERE entity_id = 'FIN';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('FIN', 'Finance and Supply chain Department', 'Finance and Supply chain Department', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'DGS', 'Service');
DELETE FROM listmodels WHERE object_id = 'FIN' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('FIN', 'entity_id', 0, 'sstar', 'user_id', 'dest', 'Finance and Supply chain Department','Finance and Supply chain Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'FIN', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Finance and Supply chain Department','Finance and Supply chain Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'FIN', 'entity_id', 0, 'jjane', 'user_id', 'cc', 'Finance and Supply chain Department','Finance and Supply chain Department', '', 'Y');
DELETE FROM entities WHERE entity_id = 'PJU';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('PJU', 'Legal Department', 'Legal Department', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'ACME', 'Service');
DELETE FROM listmodels WHERE object_id = 'PJU' AND object_type = 'entity_id';
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ('PJU', 'entity_id', 0, 'jjonasz', 'user_id', 'dest', 'Legal Department','Legal Department', '', 'Y');
INSERT INTO listmodels (object_id, object_type, "sequence", item_id, item_type, item_mode, title, description, process_comment, visible) VALUES ( 'PJU', 'entity_id', 0, 'DSG', 'entity_id', 'cc', 'Legal Department','Legal Department', '', 'Y');
DELETE FROM entities WHERE entity_id = 'ELUS';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ELUS', 'Board', 'Board', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', 'ACME', 'Direction');
DELETE FROM listmodels WHERE object_id = 'ELUS' AND object_type = 'entity_id';
DELETE FROM entities WHERE entity_id = 'ACME_FC';
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ACME_FC', 'ACME Football Club', 'ACME Football Club', 'Y', '', '', '', '', '', '', 'info@maarch.org', '', '', 'Direction');
DELETE FROM listmodels WHERE object_id = 'ACME_FC' AND object_type = 'entity_id';

-- Create BASKETS
TRUNCATE TABLE baskets;
DELETE FROM baskets WHERE basket_id = 'QualificationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'QualificationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'QualificationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('QualificationBasket', 'Correspondence to qualify', 'Qualification basket', 'status=''INIT''', 'letterbox_coll', 'Y', 'Y',10);
DELETE FROM baskets WHERE basket_id = 'CopyMailBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'CopyMailBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'CopyMailBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('CopyMailBasket', 'Correspondence in copy', 'Current correspondence in copy for me', '(res_id in (select res_id from listinstance WHERE coll_id = ''letterbox_coll'' and item_type = ''user_id'' and item_id = @user and item_mode = ''cc'') or res_id in (select res_id from listinstance WHERE coll_id = ''letterbox_coll'' and item_type = ''entity_id'' and item_mode = ''cc'' and item_id in (@my_entities))) and status not in ( ''DEL'', ''END'', ''SSUITE'') and res_id not in (select res_id from res_mark_as_read WHERE user_id = @user)', 'letterbox_coll', 'Y', 'Y',30);
DELETE FROM baskets WHERE basket_id = 'RetourCourrier';
DELETE FROM actions_groupbaskets WHERE basket_id = 'RetourCourrier';
DELETE FROM groupbasket_redirect WHERE basket_id = 'RetourCourrier';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('RetourCourrier', 'Returned correspondence', 'Correspondence returned to mailroom', 'STATUS=''RET''', 'letterbox_coll', 'Y', 'Y',40);
DELETE FROM baskets WHERE basket_id = 'DdeAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'DdeAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'DdeAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('DdeAvisBasket', 'Instructions : Requests', 'Correspondence needing instruction from me', 'status = ''EAVIS'' AND res_id IN (SELECT res_id FROM listinstance WHERE coll_id = ''letterbox_coll'' AND item_type = ''user_id'' AND item_id = @user AND item_mode = ''avis'' and process_date is NULL)', 'letterbox_coll', 'Y', 'Y',50);
DELETE FROM baskets WHERE basket_id = 'SupAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SupAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SupAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('SupAvisBasket', 'Instructions : In progress', 'Correspondence waiting for instructions from colleagues', 'status=''EAVIS'' and ((DEST_USER = @user) OR (DEST_USER IN (select user_id from users_entities WHERE entity_id IN( @my_entities)) or DESTINATION in (@subentities[@my_entities]))) and res_id NOT IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is not NULL and res_view_letterbox.res_id = res_id group by res_id) AND res_id IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is NULL and res_view_letterbox.res_id = res_id group by res_id)', 'letterbox_coll', 'Y', 'Y',60);
DELETE FROM baskets WHERE basket_id = 'RetAvisBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'RetAvisBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'RetAvisBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('RetAvisBasket', 'Instructions : Partiel answers', 'Correspondences having obtained instructions', 'status=''EAVIS'' and ((DEST_USER = @user) OR (DEST_USER IN (select user_id from users_entities WHERE entity_id IN( @my_entities)) or DESTINATION in (@subentities[@my_entities]))) and res_id IN (SELECT res_id FROM listinstance WHERE item_mode = ''avis'' and difflist_type = ''entity_id'' and process_date is not NULL and res_view_letterbox.res_id = res_id group by res_id)', 'letterbox_coll', 'Y', 'Y',70);
DELETE FROM baskets WHERE basket_id = 'ValidationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ValidationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ValidationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('ValidationBasket', 'Incoming correspondance to review', 'Important correspondence to review', 'status=''VAL''', 'letterbox_coll', 'Y', 'Y',80);
DELETE FROM baskets WHERE basket_id = 'InValidationBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'InValidationBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'InValidationBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('InValidationBasket', 'Incoming correspondance to review – Direction', 'Important correspondence for my Direction to review', 'destination in (@my_entities, @subentities[@my_entities]) and status=''VAL''', 'letterbox_coll', 'Y', 'Y',90);
DELETE FROM baskets WHERE basket_id = 'MyBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'MyBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'MyBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('MyBasket', 'Correspondence IN', 'Correspondence that I personaly have to process', 'status in (''NEW'', ''COU'', ''STDBY'', ''ENVDONE'') and dest_user = @user', 'letterbox_coll', 'Y', 'Y',100);
DELETE FROM baskets WHERE basket_id = 'LateMailBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'LateMailBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'LateMailBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('LateMailBasket', 'Late correspondence', 'Late correspondence', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'') and (now() > process_limit_date)', 'letterbox_coll', 'Y', 'Y',110);
DELETE FROM baskets WHERE basket_id = 'DepartmentBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'DepartmentBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'DepartmentBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('DepartmentBasket', 'My entities correspondence', 'My entities correspondence – supervision', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'' and status <> ''VAL'')', 'letterbox_coll', 'Y', 'Y',120);
DELETE FROM baskets WHERE basket_id = 'ParafBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ParafBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ParafBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('ParafBasket', 'Signature book', 'Correspondence to review or sign in my signature book', 'status in (''ESIG'', ''EVIS'') AND ((res_id, @user) IN (SELECT res_id, item_id FROM listinstance WHERE difflist_type = ''VISA_CIRCUIT'' and process_date ISNULL and res_view_letterbox.res_id = res_id order by listinstance_id asc limit 1))', 'letterbox_coll', 'Y', 'Y',130);
DELETE FROM baskets WHERE basket_id = 'SuiviParafBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SuiviParafBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SuiviParafBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('SuiviParafBasket', 'Correspondence in signature workflow', 'Correspondence circulating in visa/signature workflow', 'status in (''ESIG'', ''EVIS'') AND dest_user = @user', 'letterbox_coll', 'Y', 'Y',140);
DELETE FROM baskets WHERE basket_id = 'EsigARBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'EsigARBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'EsigARBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('EsigARBasket', 'Receipt to sign', 'Receipt to sign', 'status=''ESIGAR'' and (res_id,@user) IN (SELECT res_id, item_id FROM listinstance WHERE item_mode = ''sign'' and process_date ISNULL and res_view_letterbox.res_id = res_id order by listinstance_id asc limit 1)', 'letterbox_coll', 'Y', 'Y',150);
DELETE FROM baskets WHERE basket_id = 'EenvBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'EenvBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'EenvBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('EenvBasket', 'Correspondence OUT', 'Signed correspondence ready for sending', 'status=''EENV'' and dest_user = @user', 'letterbox_coll', 'Y', 'Y',160);
DELETE FROM baskets WHERE basket_id = 'EenvARBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'EenvARBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'EenvARBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('EenvARBasket', 'Receipt to send', 'Receipt to send', 'status=''EENVAR'' and dest_user = @user', 'letterbox_coll', 'Y', 'Y',170);
DELETE FROM baskets WHERE basket_id = 'ToArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ToArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ToArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('ToArcBasket', 'Cases ready for archiving', 'Cold cases ready for archiving', 'status = ''EXP_SEDA'' OR status = ''END'' OR status = ''SEND_SEDA''', 'letterbox_coll', 'Y', 'Y',180);
DELETE FROM baskets WHERE basket_id = 'SentArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'SentArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'SentArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('SentArcBasket', 'Archiving in progress', 'Archiving in progress', 'status=''ACK_SEDA''', 'letterbox_coll', 'Y', 'Y',190);
DELETE FROM baskets WHERE basket_id = 'AckArcBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'AckArcBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'AckArcBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('AckArcBasket', 'Archived cases', 'Archived cases', 'status=''REPLY_SEDA''', 'letterbox_coll', 'Y', 'Y',200);
DELETE FROM baskets WHERE basket_id = 'ReconcilBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'ReconcilBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'ReconcilBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('ReconcilBasket', '--Not to be used anymore', '--Not to be used anymore', 'status=''PJQUAL''', 'letterbox_coll', 'Y', 'Y',210);
DELETE FROM baskets WHERE basket_id = 'NumericBasket';
DELETE FROM actions_groupbaskets WHERE basket_id = 'NumericBasket';
DELETE FROM groupbasket_redirect WHERE basket_id = 'NumericBasket';
INSERT INTO baskets (basket_id, basket_name, basket_desc, basket_clause, coll_id, is_visible, enabled, basket_order) VALUES ('NumericBasket', 'E-Correspondence to qualify', 'E-Correspondence to qualify', 'status = ''NUMQUAL''', 'letterbox_coll', 'Y', 'Y',220);

-- Create GROUPBASKET
TRUNCATE TABLE groupbasket;
DELETE FROM groupbasket WHERE basket_id = 'QualificationBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('COURRIER', 'QualificationBasket');
DELETE FROM groupbasket WHERE basket_id = 'CopyMailBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'CopyMailBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'CopyMailBasket');
DELETE FROM groupbasket WHERE basket_id = 'RetourCourrier';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('COURRIER', 'RetourCourrier';
DELETE FROM groupbasket WHERE basket_id = 'DdeAvisBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'DdeAvisBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'DdeAvisBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('ELU', 'DdeAvisBasket');
DELETE FROM groupbasket WHERE basket_id = 'SupAvisBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'SupAvisBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'SupAvisBasket');
DELETE FROM groupbasket WHERE basket_id = 'RetAvisBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'RetAvisBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'RetAvisBasket');
DELETE FROM groupbasket WHERE basket_id = 'ValidationBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESP_COURRIER', 'ValidationBasket');
DELETE FROM groupbasket WHERE basket_id = 'InValidationBasket';
DELETE FROM groupbasket WHERE basket_id = 'MyBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'MyBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'MyBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('ELU', 'MyBasket');
DELETE FROM groupbasket WHERE basket_id = 'LateMailBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'LateMailBasket');
DELETE FROM groupbasket WHERE basket_id = 'DepartmentBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'DepartmentBasket');
DELETE FROM groupbasket WHERE basket_id = 'ParafBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'ParafBasket');
DELETE FROM groupbasket WHERE basket_id = 'SuiviParafBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'SuiviParafBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'SuiviParafBasket');
DELETE FROM groupbasket WHERE basket_id = 'EsigARBasket';
DELETE FROM groupbasket WHERE basket_id = 'EenvBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('AGENT', 'EenvBasket');
INSERT INTO groupbasket (group_id, basket_id) VALUES ('RESPONSABLE', 'EenvBasket');
DELETE FROM groupbasket WHERE basket_id = 'EenvARBasket';
DELETE FROM groupbasket WHERE basket_id = 'ToArcBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('ARCHIVISTE', 'ToArcBasket');
DELETE FROM groupbasket WHERE basket_id = 'SentArcBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('ARCHIVISTE', 'SentArcBasket');
DELETE FROM groupbasket WHERE basket_id = 'AckArcBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('ARCHIVISTE', 'AckArcBasket');
DELETE FROM groupbasket WHERE basket_id = 'ReconcilBasket';
DELETE FROM groupbasket WHERE basket_id = 'NumericBasket';
INSERT INTO groupbasket (group_id, basket_id) VALUES ('COURRIER', 'NumericBasket');


-- Create Security
TRUNCATE TABLE security;
DELETE FROM security WHERE group_id = 'COURRIER';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('COURRIER', 'letterbox_coll', '1=1', 'All docs');
DELETE FROM security WHERE group_id = 'AGENT';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('AGENT', 'letterbox_coll', 'destination in (@my_entities, @subentities[@my_primary_entity])', 'My services and under docs');
DELETE FROM security WHERE group_id = 'RESP_COURRIER';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('RESP_COURRIER', 'letterbox_coll', '1=1', 'All docs');
DELETE FROM security WHERE group_id = 'RESPONSABLE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('RESPONSABLE', 'letterbox_coll', 'destination in (@my_entities, @subentities[@my_primary_entity])', 'My services and under docs');
DELETE FROM security WHERE group_id = 'ADMINISTRATEUR_N1';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ADMINISTRATEUR_N1', 'letterbox_coll', '1=1', 'All docs');
DELETE FROM security WHERE group_id = 'ADMINISTRATEUR_N2';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ADMINISTRATEUR_N2', 'letterbox_coll', '1=0', 'No docs');
DELETE FROM security WHERE group_id = 'DIRECTEUR';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('DIRECTEUR', 'letterbox_coll', '1=0', 'No docs');
DELETE FROM security WHERE group_id = 'ELU';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ELU', 'letterbox_coll', '1=0', 'No docs');
DELETE FROM security WHERE group_id = 'CABINET';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('CABINET', 'letterbox_coll', '1=0', 'No docs');
DELETE FROM security WHERE group_id = 'ARCHIVISTE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('ARCHIVISTE', 'letterbox_coll', '1=1', 'All docs');
DELETE FROM security WHERE group_id = 'MAARCHTOGEC';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('MAARCHTOGEC', 'letterbox_coll', '1=0', 'No docs');
DELETE FROM security WHERE group_id = 'SERVICE';
INSERT INTO security (group_id, coll_id, where_clause, maarch_comment) VALUES ('SERVICE', 'letterbox_coll', '1=0', 'No docs');

-- Donnees manuelles
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
VALUES ('ACKNOWLEDGEMENT_RECEIPTS', 'Acknowledgement Receipts', 'Y');

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
INSERT INTO users (user_id, password, firstname, lastname, phone, mail, custom_t2, custom_t3, enabled, change_password, status) VALUES ('superadmin', '$2y$10$Vq244c5s2zmldjblmMXEN./Q2qZrqtGVgrbz/l1WfsUJbLco4E.e.', 'Super', 'ADMIN', '0147245159', 'info@maarch.org', NULL, NULL, 'Y', 'N', 'OK');
--MAARCH2GEC USER
DELETE FROM users WHERE user_id = 'cchaplin';
INSERT INTO users (user_id, password, firstname, lastname, mail, enabled, change_password, status, mode) VALUES ('cchaplin', '$2y$10$C.QSslBKD3yNMfRPuZfcaubFwPKiCkqqOUyAdOr5FSGKPaePwuEjG', 'Charlie', 'CHAPLIN', 'info@maarch.org', 'Y', 'N', 'OK', 'rest');
DELETE FROM usergroup_content WHERE user_id = 24;
INSERT INTO usergroup_content (user_id, group_id, role) VALUES (24, 11,'');

------------
--STATUS-
------------
TRUNCATE TABLE status;
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ATT', 'Stand-by', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('COU', 'In progress', 'Y', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('DEL', 'Deleted', 'Y', 'fm-letter-del', 'apps', 'N', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('END', 'Closed', 'Y', 'fm-letter-status-end', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NEW', 'New correspondence for the service', 'Y', 'fm-letter-status-new', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('RET', 'Returned correspondence', 'N', 'fm-letter-status-rejected', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('VAL', 'Important correspondence', 'Y', 'fm-letter-status-aval', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('INIT', 'New correspondence to qualify', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('VALSG', 'New correspondence to validate', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('VALDGS', 'New correspondence to validate N2', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EAVIS', 'Instruction requested', 'N', 'fa-lightbulb', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EENV', 'Ready to send', 'N', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ESIG', 'To e-sign', 'N', 'fm-file-fingerprint', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EVIS', 'To e-approve', 'N', 'fm-letter-status-aval', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ESIGAR', 'ACK to e-sign', 'N', 'fm-file-fingerprint', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EENVAR', 'ACK to e-send', 'N', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SVX', 'Not used', 'N', 'fm-letter-status-wait', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SSUITE', 'No following', 'Y', 'fm-letter-del', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('A_TRA', 'Attachment to process', 'Y', 'fa-question', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('FRZ', 'Attachment : freezed', 'Y', 'fa-pause', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('TRA', 'Attachment : processed', 'Y', 'fa-check', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('OBS', 'Attachment : out-of-date', 'Y', 'fa-pause', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('TMP', 'Attachment : draft', 'Y', 'fm-letter-status-inprogress', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EXP_SEDA', 'To archive', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SEND_SEDA', 'Sent to archiving', 'Y', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ACK_SEDA', 'SEDA Ack', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('REPLY_SEDA', 'SEDA reply', 'Y', 'fm-letter-status-acla', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC', 'Sent to GRC', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC_TRT', 'Processed by GRC', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('GRC_ALERT', 'GRC Alert', 'N', 'fm-letter-status-inprogress', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('RETRN', 'Returned', 'Y', 'fm-letter-outgoing', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NO_RETRN', 'No return', 'Y', 'fm-letter-status-rejected', 'apps', 'N', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('PJQUAL', 'Not used', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('NUMQUAL', 'Not used', 'Y', 'fm-letter-status-attr', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SEND_MASS', 'Mass mailing', 'Y', 'fa-mail-bulk', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('SIGN', 'Attachment : signed', 'Y', 'fa-check', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('STDBY', 'Closed with follow-up', 'Y', 'fm-letter-status-wait', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ENVDONE', 'Mail sent', 'Y', 'fm-letter-status-aenv', 'apps', 'Y', 'Y');

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
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('apa_reservation_batch', NULL, 1, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('workbatch_rec', '', 1, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('folder_id_increment', '', 200, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('work_batch_autoimport_id', NULL, 1, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('postindexing_workbatch', NULL, 1, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('database_version', '20.10.2', NULL, NULL);
INSERT INTO parameters (id, param_value_string, param_value_int, param_value_date) VALUES ('user_quota', '', 0, NULL);
INSERT INTO parameters (id, description, param_value_string, param_value_int, param_value_date) VALUES ('defaultDepartment', 'Département par défaut sélectionné dans le formulaire des adresses', NULL, 75, NULL);
INSERT INTO parameters (id, description, param_value_string) VALUES ('homepage_message', 'Texte apparaissant dans la bannière sur la page d''accueil, mettre un espace pour supprimer la bannière.', 'Bienvenue dans votre <b>G</b>estion <b>E</b>lectronique du <b>C</b>ourrier.');
INSERT INTO parameters (id, description, param_value_string) VALUES ('thumbnailsSize', 'Résolution des imagettes', '750x900');
INSERT INTO parameters (id, description, param_value_int) VALUES ('keepDestForRedirection', 'If enabled (1), put recipient in copy for diffusion list when redirecting', 0);
INSERT INTO parameters (id, description, param_value_int) VALUES ('QrCodePrefix', 'If enabled (1), add "Maarch_" before the content in QrCode. (Can be use with MaarchCapture >= 1.4)', 0);
INSERT INTO parameters (id, description, param_value_int) VALUES ('workingDays', 'If enabled (1), processing time is calculated in working days (Monday to Friday). Otherwise, in calendar days', 1);
INSERT INTO parameters (id, param_value_int) VALUES ('last_deposit_id', 0);
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailNotDistributedStatus', 'PND');
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailDistributedStatus', 'DSTRIBUTED');
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailImportedStatus', 'NEW');
INSERT INTO parameters (id, description, param_value_int) VALUES ('keepDiffusionRoleInOutgoingIndexation', 'Si activé (1), prend en compte les roles du modèle de diffusion de l''entité.', 1);


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
TRUNCATE TABLE actions_categories;
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (1, 'redirect', 'Redirect', 'NEW', 'Y', 'redirect', 'Y', 'redirectAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (2, '', 'Send to service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (3, '', 'Send back to Mail Room', 'RET', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (4, '', 'Save', '_NOSTATUS_', 'N', 'process', 'N', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (5, '', 'Send back to processing', 'COU', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (6, '', 'Delete correspondence', 'DEL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (18, 'indexing', 'Qualify mail', '_NOSTATUS', 'N', 'validate_mail', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (19, '', 'Process mail', 'COU', 'N', 'process', 'N', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (20, '', 'Close mail', 'END', 'N', 'close_mail', 'Y', 'closeMailAction');
INSERT INTO actions (id, label_action, id_status, is_system, history, component) VALUES (21, 'Save the mail', 'INIT', 'N', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (22, '', 'Send to service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (23, 'indexing', 'Send to service(s)', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (24, 'indexing', 'Send back in validation', 'CTRLCAB', 'N', 'validate_mail', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (36, '', 'Ask for instruction', 'EAVIS', 'N','send_docs_to_recommendation', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (37, '', 'Give an instruction', '_NOSTATUS_', 'N','avis_workflow_simple', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (100, '', 'View document', '', 'N', 'view', 'N', 'viewDoc');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (101, '', 'Send for visa', 'VIS', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (114, '', 'Mark as read', '', 'N', 'mark_as_read', 'N', 'resMarkAsReadAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (122, '', 'Send to service', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (123, 'indexing', 'Send to service(s)', 'NEW', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (210, '', 'Send signed ACK', 'EENVAR', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (400, '', 'Send an ACK', '_NOSTATUS_', 'N', 'send_attachments_to_contact', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (405, '', 'Give a visa', '_NOSTATUS_', 'N', 'visa_mail', 'Y', 'signatureBookAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (407, '', 'Send back to processing', 'COU', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (408, '', 'Refuse visa and go back to previous validator', '_NOSTATUS_', 'N', 'rejection_visa_previous', 'N', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (410, '', 'Transmit signed answer', 'EENV', 'N', 'interrupt_visa', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (414, '', 'Send to signbook', '_NOSTATUS_', 'N', 'send_to_visa', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (416, '', 'Approve and proceed', '_NOSTATUS_', 'N', 'visa_workflow', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (417, '', 'Send ACK', 'SVX', 'N', 'send_to_contact_with_mandatory_attachment', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (420, '', 'Stop following', 'SSUITE', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (421, '', 'Send back to mailroom', 'RET', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (431, '', 'Send to GRC', 'GRC', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (500, '', 'Send to archiving system', 'SEND_SEDA', 'N', 'export_seda', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (501, '', 'Acknowledge archiving', 'ACK_SEDA', 'N', 'check_acknowledgement', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (502, '', 'Send archiving acknowledge', 'REPLY_SEDA', 'N', 'check_reply', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (503, '', 'Delete mail', 'DEL', 'N', 'purge_letter', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (504, '', 'Reset mail', 'END', 'N', 'reset_letter', 'Y', 'v1Action');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (505, '', 'Close with follow-up', 'STDBY', 'N', 'close_mail', 'Y', 'closeMailAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (506, '', 'End follow-up', 'END', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (507, '', 'Validate posting', 'ENVDONE', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (522, '', 'Send to manager validation', 'VAL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (523, 'indexing', 'Send to manager validation', 'VAL', 'N', 'confirm_status', 'Y', 'confirmAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (524, '', 'Set persistent mode on', '_NOSTATUS_', 'N', 'set_persistent_mode_on', 'N', 'enabledBasketPersistenceAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (525, '', 'Set persistant mode off', '_NOSTATUS_', 'N', 'set_persistent_mode_off', 'N', 'disabledBasketPersistenceAction');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, action_page, history, component) VALUES (526, '', 'Release mails', 'VAL', 'Y', 'confirm_status', 'N', 'confirmAction');
Select setval('actions_id_seq', (select max(id)+1 from actions), false);
------------
-- BANNETTES SECONDAIRES
TRUNCATE TABLE users_baskets_preferences;
INSERT INTO users_baskets_preferences (user_serial_id, group_serial_id, basket_id, display)
SELECT usergroup_content.user_id, usergroups.id, groupbasket.basket_id, TRUE FROM usergroups, groupbasket, usergroup_content
WHERE groupbasket.group_id = usergroups.group_id AND usergroups.id = usergroup_content.group_id
ORDER BY users.id;

------------
--ACTIONS_GROUPBASKETS
------------
TRUNCATE TABLE actions_groupbaskets;
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (24, '', 'COURRIER', 'RetourCourrier', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (22, '', 'COURRIER', 'RetourCourrier', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (18, '', 'COURRIER', 'QualificationBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (18, '', 'COURRIER', 'NumericBasket', 'Y', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (18, '', 'RESP_COURRIER', 'ValidationBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (22, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (23, '', 'RESP_COURRIER', 'ValidationBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (420, '', 'RESP_COURRIER', 'ValidationBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'AGENT', 'CopyMailBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (114, '', 'AGENT', 'CopyMailBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'AGENT', 'MyBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (414, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (36, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, 'closing_date IS NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (505, 'closing_date IS NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (506, 'closing_date IS NOT NULL', 'AGENT', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (400, '', 'AGENT', 'MyBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'AGENT', 'DepartmentBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'AGENT', 'DepartmentBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'RetAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'AGENT', 'RetAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'AGENT', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'DdeAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'AGENT', 'SupAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'AGENT', 'SupAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'AGENT', 'EenvBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (507, '', 'AGENT', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'AGENT', 'SuiviParafBasket', 'Y', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'MyBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (414, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (36, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, 'closing_date IS NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (505, 'closing_date IS NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (506, 'closing_date IS NOT NULL', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (400, '', 'RESPONSABLE', 'MyBasket', 'N', 'Y', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'RESPONSABLE', 'CopyMailBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (114, '', 'RESPONSABLE', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'RESPONSABLE', 'ValidAnswerBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (3, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'RESPONSABLE', 'DepartmentBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'RESPONSABLE', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'DdeAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'SupAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'RESPONSABLE', 'SupAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'RESPONSABLE', 'RetAvisBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (5, '', 'RESPONSABLE', 'RetAvisBasket', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (405, '', 'RESPONSABLE', 'ParafBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (416, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (407, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (408, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (410, '', 'RESPONSABLE', 'ParafBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'RESPONSABLE', 'EenvBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (507, '', 'RESPONSABLE', 'EenvBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'RESPONSABLE', 'SuiviParafBasket', 'Y', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'ELU', 'MyBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (37, '', 'ELU', 'DdeAvisBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (4, '', 'ELU', 'DdeAvisBasket', 'Y', 'Y', 'Y');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (500, '', 'ARCHIVISTE', 'ToArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (501, '', 'ARCHIVISTE', 'ToArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (502, '', 'ARCHIVISTE', 'SentArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (503, '', 'ARCHIVISTE', 'AckArcBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (504, '', 'ARCHIVISTE', 'AckArcBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'CABINET', 'SuiviBasket', 'Y', 'Y', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (524, '', 'CABINET', 'SuiviBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (525, '', 'CABINET', 'SuiviBasket', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'SERVICE', 'ValidationBasket', 'Y', 'Y', 'Y');

------------
--GROUPBASKET_REDIRECT
------------
TRUNCATE TABLE groupbasket_redirect;
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
--TEMPLATES_DOCTYPE_EXT--
------------
TRUNCATE TABLE templates_doctype_ext;
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (3, 1201, 'Y');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (9, 1202, 'Y');

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

TRUNCATE TABLE tags_entities;
INSERT INTO tags_entities (tag_id, entity_id) VALUES (1, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (2, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (3, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (4, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (5, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (6, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (7, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (8, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (9, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (10, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (11, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (12, 'COU');
INSERT INTO tags_entities (tag_id, entity_id) VALUES (13, 'COU');

------------
------------
--TEMPLATES
------------
TRUNCATE TABLE templates;
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (3, 'AppelTel', 'Appel téléphonique', '<h2><span style="color: #000000;"><strong>Appel t&eacute;l&eacute;phonique</strong></span></h2>
<hr />
<p>&nbsp;</p>
<p>Bonjour,</p>
<p>Vous avez re&ccedil;u un appel t&eacute;l&eacute;phonique dont voici les informations :</p>
<table style="height: 61px; border-color: #f0f0f0;" border="1" width="597"><caption>&nbsp;</caption>
<tbody>
<tr>
<td style="text-align: center;"><strong>Date</strong></td>
<td style="text-align: center;"><strong>Heure</strong></td>
<td style="text-align: center;"><strong>Soci&eacute;t&eacute;</strong></td>
<td style="text-align: center;"><strong>Contact</strong></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<h4>Notes :</h4>
<p>&nbsp;</p>', 'HTML', NULL, NULL, '', '', 'doctypes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target)
VALUES (2, '[notification] Notifications événement', 'Notifications des événements système',
'<p><font face="verdana,geneva" size="1">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
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
</table>',
'HTML', NULL, NULL, '', 'notif_events', 'notifications');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target)
VALUES (5, '[notification courrier] Alerte 2', '[notification] Alerte 2', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
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
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society] [res_letterbox.contact_firstname] [res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.tag_label]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktoprocess]" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target)
VALUES (6, '[notification courrier] Alerte 1', '[notification] Alerte 1', '<p><font face="arial,helvetica,sans-serif" size="2">Bonjour [recipient.firstname] [recipient.lastname],</font></p>
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
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.typist]</font></td>
<td>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.contact_society] [res_letterbox.contact_firstname] [res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</font></p>
<p><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.tag_label]</font></p>
</td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</font></td>
<td><font face="arial,helvetica,sans-serif" color="#FF0000"><strong><font size="2">[res_letterbox.subject]</font></strong></font></td>
<td><font face="arial,helvetica,sans-serif" size="2">[res_letterbox.type_label]</font></td>
<td><font face="arial,helvetica,sans-serif"><a href="[res_letterbox.linktoprocess]" name="traiter">traiter</a> <a href="[res_letterbox.linktodoc]" name="doc">Afficher</a></font></td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target)
VALUES (7, '[notification courrier] Diffusion de courrier', 'Alerte de courriers présents dans les bannettes', '<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Bonjour <strong>[recipient.firstname] [recipient.lastname]</strong>,</p>
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
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.typist]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.contact_society] [res_letterbox.contact_firstname] [res_letterbox.contact_lastname][res_letterbox.function][res_letterbox.address_num][res_letterbox.address_street][res_letterbox.address_postal_code][res_letterbox.address_town]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.doc_date;block=tr;frm=dd/mm/yyyy]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.subject]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[res_letterbox.type_label]</td>
<td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodetail]" name="detail">D&eacute;tail</a> <a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodoc]" name="doc">Afficher</a></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; width: 100%; text-align: center; font-size: 9px; font-style: italic; opacity: 0.5;">Message g&eacute;n&eacute;r&eacute; via l''application MaarchCourrier</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_events', 'notifications');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target)
VALUES (8, '[notification courrier] Nouvelle annotation', '[notification] Nouvelle annotation', '<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Bonjour <strong>[recipient.firstname] [recipient.lastname]</strong>,</p>
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
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Ajout&eacute; par</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">Contact</th>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #135f7f; color: white;">&nbsp;</th>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.identifier]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.# ;frm=0000]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.doc_date;block=tr;frm=dd/mm/yyyy]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.subject]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.note_text]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.user_id]</td>
<td style="border: 1px solid #ddd; padding: 8px;">[notes.contact_society] [notes.contact_firstname] [notes.contact_lastname]</td>
<td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodetail]" name="detail">D&eacute;tail</a> <a style="text-decoration: none; background: #135f7f; padding: 5px; color: white; -webkit-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75); box-shadow: 6px 4px 5px 0px rgba(0,0,0,0.75);" href="[res_letterbox.linktodoc]" name="doc">Afficher</a></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; width: 100%; text-align: center; font-size: 9px; font-style: italic; opacity: 0.5;">Message g&eacute;n&eacute;r&eacute; via l''application MaarchCourrier</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'notes', 'notifications');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (9, 'Demande - Voirie', 'Demande - Voirie', '<h2>Demande Intervention VOIRIE</h2>
<hr />
<table style="border: 1pt solid #000000; width: 597px; background-color: #f0f0f0; height: 172px;" border="1" cellspacing="1" cellpadding="5"><caption>&nbsp;</caption>
<tbody>
<tr>
<td style="width: 200px; background-color: #ffffff;"><strong>NOM, PRENOM demandeur</strong></td>
<td style="width: 200px; background-color: #ffffff;">&nbsp;</td>
</tr>
<tr style="background-color: #ffffff;">
<td style="width: 200px;">Adresse</td>
<td>&nbsp;</td>
</tr>
<tr style="background-color: #ffffff;">
<td style="width: 200px;"><strong>Contact</strong></td>
<td>&nbsp;</td>
</tr>
<tr style="background-color: #ffffff;">
<td style="width: 200px;"><strong>Intitul&eacute; demande</strong></td>
<td>&nbsp;</td>
</tr>
<tr style="background-color: #ffffff;">
<td style="width: 200px;">Compl&eacute;ment</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'DOCX: demo_document_msoffice', '', 'doctypes', 'all');
INSERT INTO templates VALUES (10, '[maarch mairie] Clôture de demande', '[maarch mairie] Clôture de demande', '<p style="text-align: left;"><span style="font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="text-decoration: underline;"><span style="font-size: small;">CLOTURE DEMANDE Maarch Mairie - [res_letterbox.type_label] - [res_letterbox.res_id] </span></span></p>
<p style="text-align: center;">&nbsp;</p>
<table style="background-color: #a8c33c; width: 800px; border: #000000 1pt solid;" border="1" cellspacing="1" cellpadding="5">
<tbody>
<tr>
<td style="width: 200px;">CLOTURE&nbsp;DE LA DEMANDE</td>
<td>DATE: [dates]</td>
<td>HEURE: [time]</td>
</tr>
</tbody>
</table>
<table style="width: 800px; border: #000000 1pt solid;" border="1" cellspacing="1" cellpadding="5">
<tbody>
<tr>
<td style="width: 200px; background-color: #a8c33c;">OBJET</td>
<td style="background-color: #e1f787;">&nbsp;[res_letterbox.subject]</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">ACTIONS CONDUITES</td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">DATE DE REMISE EN ETAT / SERVICE</td>
<td style="background-color: #e1f787;">&nbsp;</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">CONSIGNES COMPLEMENTAIRES</td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">AUTRES OBSERVATIONS</td>
<td style="background-color: #e1f787;">&nbsp;</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width: 200px; background-color: #a8c33c;">&nbsp;</td>
<td style="background-color: #e1f787;">&nbsp;</td>
</tr>
</tbody>
</table>', 'HTML', NULL, NULL, 'DOCX: demo_document_msoffice', '', 'doctypes');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (20, 'Accompagnement courriel', 'Modèle de courriel d''''accompagnement', '<p>Bonjour,</p>
<p>En r&eacute;ponse &agrave; votre courrier en date du [res_letterbox.doc_date], veuillez trouver notre r&eacute;ponse en pi&egrave;ce-jointe.</p>
<p>Cordialement,</p>
<p><strong>Ville de Maarch-les-Bains</strong><br /><em>[user.firstname] [user.lastname]</em><br /><em>[user.phone]</em></p>', 'HTML', NULL, NULL, 'DOCX: standard_nosign', 'letterbox_attachment', 'sendmail', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (21, 'AR dérogation LO', 'AR derogation carte scolaire', '', 'OFFICE', '0000#', 'ar_derogation.odt', 'ODT: ARderogation', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (22, 'Réponse crèche LO', 'Réponse à une demande de place en crèche', '', 'OFFICE', '0000#', 'rep_creche.odt', 'ODT: Repddeplacecreche', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (23, 'Réponse générique LO', 'Modèle de réponse générique', '', 'OFFICE', '0000#', 'rep_standard.odt', 'ODT: standard_sign', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (24, 'Réponse générique MS', 'Modèle de réponse MS Office', '', 'OFFICE', '0000#', 'rep_standard.docx', 'DOCX: standard_sign', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (25, 'AR SVA LO', 'AR SVA LO', '', 'OFFICE', '0000#', 'ar_sva.odt', 'ODT: ar_sva', 'letterbox_attachment', 'attachments', 'sva');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (26, 'AR SVR LO', 'AR SVR LO', '', 'OFFICE', '0000#', 'ar_svr.odt', 'ODT: ar_svr', 'letterbox_attachment', 'attachments', 'svr');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (27, 'Réponse avec transmission LO', 'Réponse avec transmission LO', '', 'OFFICE', '0000#', 'rep_transmission.odt', 'ODT: rep_transmission', 'letterbox_attachment', 'attachments', 'response_project');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (28, 'Transmission LO', 'Transmission LO', '', 'OFFICE', '0000#', 'transmission.odt', 'ODT: transmission', 'letterbox_attachment', 'attachments', 'transmission');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (29, 'Courrier invitation PME LO', 'Courrier invitation PME LO', '', 'OFFICE', '0000#', 'invitation.odt', 'ODT: invitation', 'letterbox_attachment', 'attachments', 'outgoing_mail');
------------
--TEMPLATES réponses mail SVE
--
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (900, '[TRT] Passer me voir', 'Passer me voir', 'Passer me voir à mon bureau, merci.', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (901, '[TRT] Compléter', 'Compléter', 'Le projet de réponse doit être complété/révisé sur les points suivants : \n\n- ', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (902, '[AVIS] Demande avis', 'Demande avis', 'Merci de me fournir les éléments de langage pour répondre à ce courrier.', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (904, '[AVIS] Avis favorable', 'Avis favorable', 'Merci de répondre favorablement à la demande inscrite dans ce courrier', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (905, '[CLOTURE] Clôture pour REJET', 'Clôture pour REJET', 'Clôture pour REJET', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (906, '[CLOTURE] Clôture pour ABANDON', 'Clôture pour ABANDON', 'Clôture pour ABANDON', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (907, '[CLOTURE] Clôture RAS', 'Clôture RAS', 'Clôture NORMALE', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (908, '[CLOTURE] Clôture AUTRE', 'Clôture AUTRE', 'Clôture pour ce motif : ', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (909, '[REJET] Erreur affectation', 'Erreur affectation', 'Ce courrier ne semble pas concerner mon service', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
INSERT INTO templates (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (910, '[REJET] Anomalie de numérisation', 'Anomalie de numérisation', 'Le courrier présente des anomalies de numérisation', 'TXT', NULL, NULL, 'XLSX: demo_spreadsheet_msoffice', '', 'notes', 'all');
--
INSERT INTO templates  (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1000, '[MAIL] AR TYPE Réorientation d’une saisine électronique vers l’autorité compétente', '[MAIL] AR TYPE Réorientation d’une saisine électronique vers l’autorité compétente', '<h2>Ville de Maarch-les-Bains</h2>
<p><em>[entities.adrs_1]</em><br /><em>[entities.adrs_2]</em><br /><em>[entities.zipcode] [entities.city]<br /></em></p>
<p>(Veuillez renseigner le numero de telephone de votre service)</p>
<p>Le [res_letterbox.doc_date], vous avez transmis par voie &eacute;lectronique &agrave; la Ville une demande qui ne rel&egrave;ve pas de sa comp&eacute;tence.</p>
<p>Votre demande concerne [res_letterbox.subject].</p>
<p>Cette demande a &eacute;t&eacute; transmise &agrave; (veuillez renseigner le nom de l''AUTORITE COMPETENTE).</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_attachment', 'sendmail', 'all');
--
INSERT INTO templates  (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1020, '[MAIL] AR TYPE dans le cas d’une décision implicite de rejet', '[MAIL] AR TYPE dans le cas d’une décision implicite de rejet', '<h2>Ville de Maarch-les-Bains</h2>
<p><em>[entities.adrs_1]</em><br /><em>[entities.adrs_2]</em><br /><em>[entities.zipcode] [entities.city]<br /></em></p>
<p>(Veuillez renseigner le numero de telephone de votre service)</p>
<p>Le [res_letterbox.doc_date], vous avez transmis par voie &eacute;lectronique &agrave; la Ville une demande qui rel&egrave;ve de sa comp&eacute;tence.</p>
<p>Votre demande concerne : [res_letterbox.subject].</p>
<p>Le pr&eacute;sent accus&eacute; r&eacute;ception atteste la r&eacute;ception de votre demande, il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''&eacute;tude des pi&egrave;ces fournies. Pour tout renseignement concernant votre dossier, vous pouvez contacter le service charg&eacute; du dossier par t&eacute;l&eacute;phone [users.phone] ou par messagerie [users.mail].</p>
<p>Votre demande est susceptible de faire l''objet d''une d&eacute;cision implicite de rejet en l''absence de r&eacute;ponse dans les (XX) jours suivant sa r&eacute;ception, soit le [res_letterbox.process_limit_date].</p>
<p>Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.</p>
<p>Dans ce cas, le d&eacute;lai de d&eacute;cision implicite de rejet serait alors suspendu le temps de produire les pi&egrave;ces demand&eacute;es.</p>
<p>Si vous estimez que la d&eacute;cision qui sera prise par l''administration est contestable, vous pourrez formuler :</p>
<p>- Soit un recours gracieux devant l''auteur de la d&eacute;cision</p>
<p>- Soit un recours hi&eacute;rarchique devant le Maire</p>
<p>- Soit un recours contentieux devant le Tribunal Administratif territorialement comp&eacute;tent.</p>
<p>Le recours gracieux ou le recours hi&eacute;rarchique peuvent &ecirc;tre faits sans condition de d&eacute;lais.</p>
<p>Le recours contentieux doit intervenir dans un d&eacute;lai de deux mois &agrave; compter de la notification de la d&eacute;cision.</p>
<p>Toutefois, si vous souhaitez en cas de rejet du recours gracieux ou du recours hi&eacute;rarchique former un recours contentieux, ce recours gracieux ou hi&eacute;rarchique devra avoir &eacute;t&eacute; introduit dans le d&eacute;lai sus-indiqu&eacute; du recours contentieux.</p>
<p>Vous conserverez ainsi la possibilit&eacute; de former un recours contentieux, dans un d&eacute;lai de deux mois &agrave; compter de la d&eacute;cision intervenue sur ledit recours gracieux ou hi&eacute;rarchique.</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_attachment', 'sendmail', 'all');
--
INSERT INTO templates  (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1010, '[MAIL] AR TYPE dans le cas d’une décision implicite d’acceptation', '[MAIL] AR TYPE dans le cas d’une décision implicite d’acceptation', '<h2>Ville de Maarch-les-Bains</h2>
<p><em>[entities.adrs_1]</em><br /><em>[entities.adrs_2]</em><br /><em>[entities.zipcode] [entities.city]<br /></em></p>
<p>(Veuillez renseigner le numero de telephone de votre service)</p>
<p>Le [res_letterbox.doc_date], vous avez transmis par voie &eacute;lectronique &agrave; la Ville une demande qui rel&egrave;ve de sa comp&eacute;tence.</p>
<p>Votre demande concerne [res_letterbox.subject].</p>
<p>Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''''&eacute;tude des pi&egrave;ces fournies.</p>
<p>Pour tout renseignement concernant votre dossier, vous pouvez contacter le service charg&eacute; du dossier par t&eacute;l&eacute;phone [users.phone] ou par messagerie [users.mail].</p>
<p>Votre demande est susceptible de faire l''objet d''''une d&eacute;cision implicite d''''acceptation en l''absence de r&eacute;ponse dans les (XX) jours suivant sa r&eacute;ception, soit le [res_letterbox.process_limit_date].</p>
<p>Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.</p>
<p>Le cas &eacute;ch&eacute;ant, le d&eacute;lai de d&eacute;cision implicite d''acceptation ne d&eacute;butera qu''''apr&egrave;s la production des pi&egrave;ces demand&eacute;es.</p>
<p>En cas de d&eacute;cision implicite d''''acceptation vous avez la possibilit&eacute; de demander au service charg&eacute; du dossier une attestation conform&eacute;ment aux dispositions de l''article 22 de la loi n&deg; 2000-321 du 12 avril 2000 relative aux droits des citoyens dans leurs relations avec les administrations modifi&eacute;e.</p>', 'HTML', NULL, NULL, 'ODP: open_office_presentation', 'letterbox_attachment', 'sendmail', 'all');
--
INSERT INTO templates  (template_id, template_label, template_comment, template_content, template_type, template_path, template_file_name, template_style, template_datasource, template_target, template_attachment_type) VALUES (1030, '[MAIL] AR TYPE dans le cas d’une demande n’impliquant pas de décision implicite de l’administration', '[MAIL] AR TYPE dans le cas d’une demande n’impliquant pas de décision implicite de l’administration', '<h2>Ville de Maarch-les-Bains</h2>
<p><em>[entities.adrs_1]</em><br /><em>[entities.adrs_2]</em><br /><em>[entities.zipcode] [entities.city]<br /></em></p>
<p>(Veuillez renseigner le numero de telephone de votre service)</p>
<p>Le [res_letterbox.doc_date], vous avez transmis par voie &eacute;lectronique &agrave; la Ville une demande qui rel&egrave;ve de sa comp&eacute;tence.</p>
<p>Votre demande concerne [res_letterbox.subject].</p>
<p>Le pr&eacute;sent accus&eacute; de r&eacute;ception atteste de la r&eacute;ception de votre demande. Il ne pr&eacute;juge pas de la conformit&eacute; de son contenu qui d&eacute;pend entre autres de l''&eacute;tude des pi&egrave;ces fournies.</p>
<p>Si l''instruction de votre demande n&eacute;cessite des informations ou pi&egrave;ces compl&eacute;mentaires, la Ville vous contactera afin de les fournir, dans un d&eacute;lai de production qui sera fix&eacute;.</p>
<p>Pour tout renseignement concernant votre dossier, vous pouvez contacter le service charg&eacute; du dossier par t&eacute;l&eacute;phone [users.phone] ou par messagerie [users.mail].</p>', 'HTML', NULL, NULL, 'TXT: document_texte', 'letterbox_attachment', 'sendmail', 'all');

INSERT INTO templates (template_label, template_comment, template_content, template_type, template_style, template_target, template_attachment_type) VALUES ('Quota d''utilisateur', 'Modèle de notification pour le quota utilisateur', '<p>Quota utilisateur atteint</p>', 'HTML', 'ODT: rep_standard', 'notifications', 'all');
------------
Select setval('templates_seq', (select max(template_id)+1 from templates), false);

------------
--PRIORITES
------------
TRUNCATE TABLE priorities;
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1357nbvc', 'Normal', '#009dc5', 0, 1);
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1379nbvc', 'Urgent', '#ffa500', 8, 2);
INSERT INTO priorities (id, label, color, delays, "order") VALUES ('poiuytre1391nbvc', 'Very urgent', '#ff0000', 4, 3);

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
WHERE template_type in ('OFFICE','TXT');
Select setval('templates_association_id_seq', (select max(id)+1 from templates_association), false);
-----
-- Archive identifiers
-----
UPDATE entities SET business_id = 'org_987654321_DGS_SF';
UPDATE entities SET archival_agency = 'org_123456789_Archives';
UPDATE entities SET archival_agreement = 'MAARCH_LES_BAINS_ACTES_V2';
--UPDATE entities SET business_id = 'org_987654321_DGS_SF' WHERE entity_id = 'COU';
--UPDATE entities SET business_id = 'org_123456789_CAB_SF' WHERE entity_id = 'CAB';

UPDATE doctypes SET retention_final_disposition = 'destruction';
UPDATE doctypes SET retention_rule = 'compta_3_03';
UPDATE doctypes SET duration_current_use = 12;

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
INSERT INTO contacts_filling (enable, rating_columns, first_threshold, second_threshold) VALUES (true, '["address_street","address_postal_code","address_town","lastname","firstname","phone","email"]', 33, 66);

-----
-- Folders
-----
TRUNCATE TABLE folders;
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('HR', FALSE, 1, 0, 0);
INSERT INTO folders (label, public, user_id, parent_id, level) VALUES ('BUSINESS', FALSE, 1, 0, 0);

TRUNCATE TABLE indexing_models;
INSERT INTO indexing_models (id, category, label, "default", owner, private) VALUES (1, 'incoming', 'Courrier arrivée', TRUE, 23, FALSE);
INSERT INTO indexing_models (id, category, label, "default", owner, private) VALUES (2, 'outgoing', 'Courrier départ', FALSE, 23, FALSE);
INSERT INTO indexing_models (id, category, label, "default", owner, private) VALUES (3, 'internal', 'Courrier interne', FALSE, 23, FALSE);
INSERT INTO indexing_models (id, category, label, "default", owner, private) VALUES (4, 'ged_doc', 'Document ged', FALSE, 23, FALSE);
Select setval('indexing_models_id_seq', (select max(id)+1 from indexing_models), false);

TRUNCATE TABLE indexing_models_fields;
/* Arrivée */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'doctype', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'priority', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'confidentiality', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'docDate', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'arrivalDate', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'subject', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'senders', TRUE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'recipients', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'initiator', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'destination', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'processLimitDate', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'folder', FALSE, '""', 'classement');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (1, 'tags', FALSE, '""', 'classement');

/* Départ */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'doctype', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'priority', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'confidentiality', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'docDate', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'subject', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'senders', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'recipients', TRUE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'initiator', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'destination', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'processLimitDate', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'folder', FALSE, '""', 'classement');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (2, 'tags', FALSE, '""', 'classement');

/* Interne */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'doctype', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'priority', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'confidentiality', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'docDate', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'subject', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'senders', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'recipients', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'initiator', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'destination', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'processLimitDate', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'folder', FALSE, '""', 'classement');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (3, 'tags', FALSE, '""', 'classement');

/* GED */
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'doctype', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'confidentiality', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'docDate', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'subject', TRUE, '""', 'mail');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'senders', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'recipients', FALSE, '""', 'contact');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'initiator', TRUE, '""', 'process');
INSERT INTO indexing_models_fields (model_id, identifier, mandatory, default_value, unit) VALUES (4, 'destination', TRUE, '""', 'process');

INSERT INTO parameters (id, description, param_value_string) VALUES ('siret', 'SIRET company number', '45239273100025');

--Inscrire ici les clauses de conversion spécifiques en cas de reprise
--Update res_letterbox set status='VAL' where res_id=108;
--Update res_letterbox set status='INIT', subject='', destination='COU', type_id=001;
--delete from res_attachments;
--delete from listinstance;
--delete from listinstance_history;
--delete from listinstance_history_details;
--delete from notes;
-- END
