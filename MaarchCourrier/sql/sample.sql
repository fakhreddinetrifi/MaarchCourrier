
TRUNCATE TABLE res_letterbox;
ALTER SEQUENCE res_id_mlb_seq restart WITH 1;
-- to sign documents
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date,  doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (1, 'Demande de dérogation carte scolaire', 305, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_derogation.pdf', '0', 24942, 'ATT_MP', 'PJS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 19, 'incoming', 'MAARCH/2020A/1', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date,  doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (2, 'Demande de travaux route 66', 918, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'sva_route_66.pdf', '0', 24877, 'ATT_MP', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/2', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (3, 'Plainte voisin chien bruyant', 503, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'svr_route_chien_bruyant.pdf', '0', 24877, 'ATT_MP', 'DGS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 1, 'incoming', 'MAARCH/2020A/3', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (4, 'Invitation pour échanges journées des sports', 110, 'pdf', 19, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'empty.pdf', '0', 111108, 'ATT_MP', 'PJS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'PJS', 19, 'outgoing', 'MAARCH/2020D/4', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (5, 'Demande de place en creche', 307, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_place_creche.pdf', '0', 24877, 'ATT_MP', 'PE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 15, 'incoming', 'MAARCH/2020A/4', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (6, 'Relance place en creche', 307, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'relance_place_creche.pdf', '0', 24877, 'ATT_MP', 'PE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 15, 'incoming', 'MAARCH/2020A/5', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);

-- to annotate documents
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (7, 'Pétition pour la survie du square Carré', 201, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'petition_square_carre.pdf', '0', 24877, 'ATT_MP', 'DGS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 1, '{"signatureBookId": "7"}', 'incoming', 'MAARCH/2020A/6', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (8, 'Félicitations élections', 205, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'felicitations.pdf', '0', 24877, 'ATT_MP', 'DGS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 1, '{"signatureBookId": "8"}', 'incoming', 'MAARCH/2020A/7', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (9, 'Demande place creche', 307, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'formulaire_place_creche.pdf', '0', 24877, 'ATT_MP', 'PE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 15, '{"signatureBookId": "9"}', 'incoming', 'MAARCH/2020A/8', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (10, 'Demande subvention jokkolabs', 406, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_subvention.pdf', '0', 24877, 'ATT_MP', 'DGS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 1, '{"signatureBookId": "10"}', 'incoming', 'MAARCH/2020A/9', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (11, 'Facture Maarch', 407, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'facture.pdf', '0', 24877, 'ATT_MP', 'FIN', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 14, '{"signatureBookId": "11"}', 'incoming', 'MAARCH/2020A/10', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (12, 'Demande état civil', 602, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'etat_civil.pdf', '0', 24877, 'ATT_MP', 'DGS', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 1, '{"signatureBookId": "12"}', 'incoming', 'MAARCH/2020A/11', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (13, 'Arret maladie vide', 701, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'arret_maladie.pdf', '0', 24877, 'ATT_MP', 'DRH', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 12, '{"signatureBookId": "13"}', 'incoming', 'MAARCH/2020A/12', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, external_id, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (14, 'Inscription école', 307, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'inscription_ecole.pdf', '0', 24877, 'ATT_MP', 'PE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 15, '{"signatureBookId": "14"}', 'incoming', 'MAARCH/2020A/13', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to qualify document
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (15, 'Demande intervention à qualifier', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'INIT', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/14', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to validate document
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (16, 'Demande intervention à valider', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'VAL', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/15', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to process document ccharles
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (17, 'Demande intervention à traiter', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'NEW', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/16', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to process document nnataly
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (18, 'Demande intervention à envoyer au parapheur', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'NEW', 'PSO', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 4, 'incoming', 'MAARCH/2020A/17', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to paraph document ppetit
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (19, 'Demande intervention à signer', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'ATT_MP', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/18', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);
-- to archive document ggrand
INSERT INTO res_letterbox (res_id, subject, type_id, format, typist, creation_date, modification_date, doc_date, docserver_id, path, filename, fingerprint, filesize, status, destination, work_batch, origin, priority, policy_id, cycle_id, initiator, dest_user, category_id, alt_identifier, admission_date, process_limit_date, closing_date, alarm1_date, alarm2_date, flag_alarm1, flag_alarm2, model_id, version)
VALUES (20, 'Demande intervention à archiver', 505, 'pdf', 21, NOW(), NOW(), CURRENT_DATE, 'FASTHD_MAN', 'tests#', 'demande_intervention.pdf', '0', 24877, 'END', 'PTE', NULL, NULL, 'poiuytre1357nbvc', NULL, NULL, 'COU', 16, 'incoming', 'MAARCH/2020A/19', CURRENT_DATE, CURRENT_DATE + 21, NULL, NULL, NULL, 'N', 'N', 1, 1);

Select setval('res_id_mlb_seq', (select max(res_id)+1 from res_letterbox), false);

TRUNCATE TABLE resource_contacts;
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (1, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (2, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (3, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (4, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (5, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (6, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (7, 5, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (8, 6, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (9, 7, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (10, 1, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (11, 1, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (12, 8, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (13, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (14, 7, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (15, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (16, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (17, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (18, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (19, 4, 'contact', 'sender');
INSERT INTO resource_contacts (res_id, item_id, type, mode) VALUES (20, 4, 'contact', 'sender');
Select setval('resource_contacts_id_seq', (select max(id)+1 from resource_contacts), false);

TRUNCATE TABLE adr_letterbox;
ALTER SEQUENCE adr_letterbox_id_seq restart WITH 1;
-- to sign documents
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (1, 1, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_derogation.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (2, 2, 'PDF', 'CONVERT_MLB', 'tests#', 'sva_route_66.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (3, 3, 'PDF', 'CONVERT_MLB', 'tests#', 'svr_route_chien_bruyant.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (4, 4, 'PDF', 'CONVERT_MLB', 'tests#', 'emtpy.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (5, 5, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_place_creche.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (6, 6, 'PDF', 'CONVERT_MLB', 'tests#', 'relance_place_creche.pdf', '0', 1);
-- to annotate documents
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (7, 7, 'PDF', 'CONVERT_MLB', 'tests#', 'petition_square_carre.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (8, 8, 'PDF', 'CONVERT_MLB', 'tests#', 'felicitations.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (9, 9, 'PDF', 'CONVERT_MLB', 'tests#', 'formulaire_place_creche.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (10, 10, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_subvention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (11, 11, 'PDF', 'CONVERT_MLB', 'tests#', 'facture.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (12, 12, 'PDF', 'CONVERT_MLB', 'tests#', 'etat_civil.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (13, 13, 'PDF', 'CONVERT_MLB', 'tests#', 'arret_maladie.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (14, 14, 'PDF', 'CONVERT_MLB', 'tests#', 'inscription_ecole.pdf', '0', 1);
-- thumbnails
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (15, 1, 'TNL', 'TNL_MLB', 'tests#', 'demande_derogation.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (16, 2, 'TNL', 'TNL_MLB', 'tests#', 'sva_route_66.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (17, 3, 'TNL', 'TNL_MLB', 'tests#', 'svr_route_chien_bruyant.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (18, 4, 'TNL', 'TNL_MLB', 'tests#', 'invitation.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (19, 5, 'TNL', 'TNL_MLB', 'tests#', 'demande_place_creche.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (20, 6, 'TNL', 'TNL_MLB', 'tests#', 'relance_place_creche.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (21, 7, 'TNL', 'TNL_MLB', 'tests#', 'petition_square_carre.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (22, 8, 'TNL', 'TNL_MLB', 'tests#', 'felicitations.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (23, 9, 'TNL', 'TNL_MLB', 'tests#', 'formulaire_place_creche.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (24, 10, 'TNL', 'TNL_MLB', 'tests#', 'demande_subvention.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (25, 11, 'TNL', 'TNL_MLB', 'tests#', 'facture.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (26, 12, 'TNL', 'TNL_MLB', 'tests#', 'etat_civil.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (27, 13, 'TNL', 'TNL_MLB', 'tests#', 'arret_maladie.png', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (28, 14, 'TNL', 'TNL_MLB', 'tests#', 'inscription_ecole.png', '0', 1);
-- to qualify documents
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (29, 15, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (30, 15, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);
-- to validate documents
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (31, 16, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (32, 16, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);
-- to process documents ccharles
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (33, 17, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (34, 17, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);
-- to process documents nnataly
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (35, 18, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (36, 18, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);
-- to paraph documents ppetit
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (37, 19, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (38, 19, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);
-- to archive documents ggrand
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (39, 20, 'PDF', 'CONVERT_MLB', 'tests#', 'demande_intervention.pdf', '0', 1);
INSERT INTO adr_letterbox (id, res_id, type, docserver_id, path, filename, fingerprint, version)
VALUES (40, 20, 'TNL', 'TNL_MLB', 'tests#', 'demande_intervention.png', '0', 1);

Select setval('adr_letterbox_id_seq', (select max(id)+1 from adr_letterbox), false);

TRUNCATE TABLE res_attachments;
ALTER SEQUENCE res_attachment_res_id_seq restart WITH 1;
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (1, 'ar_derogation', 'pdf', 19, NOW(), 'MAARCH/2020D/1', 1, 'FASTHD_MAN', 'tests#', 'ar_derogation.pdf', '0', 41682, 'FRZ', NULL, NULL, NULL, NULL, 1, 'response_project', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 1}');
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (2, 'ar_sva', 'pdf', 16, NOW(), 'MAARCH/2020D/2', 1, 'FASTHD_MAN', 'tests#', 'ar_sva.pdf', '0', 44907, 'FRZ', NULL, NULL, NULL, NULL, 2, 'response_project', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 2}');
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (3, 'ar_svr', 'pdf', 1, NOW(), 'MAARCH/2020D/3', 1, 'FASTHD_MAN', 'tests#', 'ar_svr.pdf', '0', 44907, 'FRZ', NULL, NULL, NULL, NULL, 3, 'response_project', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 3}');
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (4, 'invitation', 'pdf', 19, '2020-03-20 17:54:00.954235', 'MAARCH/2020D/4', 1, 'FASTHD_MAN', 'tests#', 'invitation.pdf', '0', 47379, 'FRZ', NULL, NULL, NULL, NULL, 4, 'outgoing_mail', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 4}');
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (5, 'rep_creche', 'pdf', 15, NOW(), 'MAARCH/2020D/5', 1, 'FASTHD_MAN', 'tests#', 'rep_creche.pdf', '0', 44907, 'FRZ', NULL, NULL, NULL, NULL, 5, 'response_project', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 5}');
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id, external_id)
VALUES (6, 'rep_standard', 'pdf', 15, NOW(), 'MAARCH/2020D/6', 1, 'FASTHD_MAN', 'tests#', 'rep_standard.pdf', '0', 44907, 'FRZ', NULL, NULL, NULL, NULL, 6, 'response_project', 4, 'contact', NULL, true, NULL, '{"signatureBookId": 6}');
-- to process documents nnataly
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id)
VALUES (7, 'rep_standard', 'pdf', 16, NOW(), 'MAARCH/2020D/7', 1, 'FASTHD_MAN', 'tests#', 'rep_standard_demande_intervention.pdf', '0', 44907, 'A_TRA', NULL, NULL, NULL, NULL, 18, 'response_project', 4, 'contact', NULL, true, NULL);
-- to paraph documents ppetit
INSERT INTO res_attachments (res_id, title, format, typist, creation_date, identifier, relation, docserver_id, path, filename, fingerprint, filesize, status, validation_date, effective_date, work_batch, origin, res_id_master, attachment_type, recipient_id, recipient_type, modified_by, in_signature_book, signatory_user_serial_id)
VALUES (8, 'rep_standard', 'pdf', 16, NOW(), 'MAARCH/2020D/8', 1, 'FASTHD_MAN', 'tests#', 'rep_standard_demande_intervention.pdf', '0', 44907, 'A_TRA', NULL, NULL, NULL, NULL, 19, 'response_project', 4, 'contact', NULL, true, NULL);

Select setval('res_attachment_res_id_seq', (select max(res_id)+1 from res_attachments), false);

TRUNCATE TABLE adr_attachments;
ALTER SEQUENCE adr_attachments_id_seq restart WITH 1;
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (1, 1, 'PDF', 'CONVERT_ATTACH', 'tests#', 'ar_derogation.pdf', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (2, 2, 'PDF', 'CONVERT_ATTACH', 'tests#', 'ar_sva.pdf', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (3, 3, 'PDF', 'CONVERT_ATTACH', 'tests#', 'ar_svr.pdf', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (4, 4, 'PDF', 'CONVERT_ATTACH', 'tests#', 'invitation.pdf', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (5, 4, 'TNL', 'TNL_MLB', 'tests#', 'invitation.png', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (6, 5, 'PDF', 'CONVERT_ATTACH', 'tests#', 'rep_creche.pdf', '0');
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (7, 6, 'PDF', 'CONVERT_ATTACH', 'tests#', 'rep_standard.pdf', '0');
-- to process documents nnataly
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (8, 7, 'PDF', 'CONVERT_ATTACH', 'tests#', 'rep_standard_demande_intervention.pdf', '0');
-- to paraph documents ppetit
INSERT INTO adr_attachments (id, res_id, type, docserver_id, path, filename, fingerprint)
VALUES (9, 8, 'PDF', 'CONVERT_ATTACH', 'tests#', 'rep_standard_demande_intervention.pdf', '0');
Select setval('adr_attachments_id_seq', (select max(res_id)+2 from adr_attachments), false);

TRUNCATE TABLE listinstance;
ALTER SEQUENCE listinstance_id_seq restart WITH 1;
-- to sign documents
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (1, 1, 0, 19, 'user_id', 'dest', 19, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (2, 1, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (3, 2, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (4, 2, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (5, 3, 0, 1, 'user_id', 'dest', 1, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (6, 4, 0, 19, 'user_id', 'dest', 19, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (7, 5, 0, 15, 'user_id', 'dest', 15, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (8, 5, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (9, 6, 0, 15, 'user_id', 'dest', 15, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (10, 6, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
-- to annotate documents
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (11, 7, 0, 1, 'user_id', 'dest', 1, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (12, 8, 0, 1, 'user_id', 'dest', 1, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (13, 9, 0, 15, 'user_id', 'dest', 15, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (14, 9, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (15, 10, 0, 1, 'user_id', 'dest', 1, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (16, 11, 0, 14, 'user_id', 'dest', 14, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (17, 11, 0, 12, 'entity_id', 'cc', 14, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (18, 11, 0, 6, 'user_id', 'cc', 14, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (19, 12, 0, 1, 'user_id', 'dest', 1, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (20, 13, 0, 12, 'user_id', 'dest', 19, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (21, 13, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (22, 14, 0, 15, 'user_id', 'dest', 15, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (23, 14, 0, 12, 'entity_id', 'cc', 19, 0, 'entity_id', NULL, NULL, false, false);
-- to qualify document
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (24, 15, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (25, 15, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);
-- to validate document
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (26, 16, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (27, 16, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);
-- to process document ccharles
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (28, 17, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (29, 17, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);
-- to process document nnataly
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (30, 18, 0, 4, 'user_id', 'dest', 4, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (31, 18, 0, 12, 'entity_id', 'cc', 4, 0, 'entity_id', NULL, NULL, false, false);
-- to paraph document ppetit
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (32, 19, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (33, 19, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (34, 19, 0, 17, 'user_id', 'visa', 16, 1, 'VISA_CIRCUIT', CURRENT_DATE, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (35, 19, 0, 10, 'user_id', 'sign', 16, 0, 'VISA_CIRCUIT', NULL, '', false, true);
-- to archive document ggrand
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (36, 20, 0, 16, 'user_id', 'dest', 16, 0, 'entity_id', NULL, '', false, false);
INSERT INTO listinstance (listinstance_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, viewed, difflist_type, process_date, process_comment, signatory, requested_signature)
VALUES (37, 20, 0, 12, 'entity_id', 'cc', 16, 0, 'entity_id', NULL, NULL, false, false);

Select setval('listinstance_id_seq', (select max(listinstance_id)+1 from listinstance), false);

--signature of ppetit
TRUNCATE TABLE user_signatures;
INSERT INTO user_signatures (id, user_serial_id, signature_label, signature_path, signature_file_name, fingerprint) 
VALUES (1, 10, 'ppetit.jpeg', '0000#', 'ppetit.jpeg', NULL);
Select setval('user_signatures_id_seq', (select max(id)+1 from user_signatures), false);

--update parameters for chrono
DELETE FROM parameters WHERE id = 'chrono_incoming_2020' OR  id = 'chrono_outgoing_2020';
INSERT INTO parameters (id, description, param_value_string, param_value_int, param_value_date) 
VALUES ('chrono_incoming_2020', NULL, NULL, 100, NULL);
INSERT INTO parameters (id, description, param_value_string, param_value_int, param_value_date) 
VALUES ('chrono_outgoing_2020', NULL, NULL, 100, NULL);

