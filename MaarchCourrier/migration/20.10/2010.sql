-- *************************************************************************--
--                                                                          --
--                                                                          --
-- Model migration script - 20.03 to 20.10                                  --
--                                                                          --
--                                                                          --
-- *************************************************************************--
UPDATE parameters SET param_value_string = '20.10.2' WHERE id = 'database_version';

DROP VIEW IF EXISTS res_view_letterbox;

CREATE EXTENSION IF NOT EXISTS unaccent;

/* SENDMAIL */
DROP TABLE IF EXISTS sendmail;

/* REPORTS */
DROP TABLE IF EXISTS usergroups_reports;
DELETE FROM usergroups_services WHERE service_id IN ('reports', 'admin_reports');

/*NOTIF_EMAIL_STACK*/
ALTER TABLE notif_email_stack DROP COLUMN IF EXISTS sender;
ALTER TABLE notif_email_stack DROP COLUMN IF EXISTS charset;
ALTER TABLE notif_email_stack DROP COLUMN IF EXISTS text_body;
ALTER TABLE notif_email_stack DROP COLUMN IF EXISTS module;

/* USERS */
ALTER TABLE users DROP COLUMN IF EXISTS cookie_key;
ALTER TABLE users DROP COLUMN IF EXISTS cookie_date;
ALTER TABLE users DROP COLUMN IF EXISTS refresh_token;
ALTER TABLE users ADD COLUMN refresh_token jsonb NOT NULL DEFAULT '[]';
ALTER TABLE users DROP COLUMN IF EXISTS mode;
DROP TYPE IF EXISTS users_modes;
CREATE TYPE users_modes AS ENUM ('standard', 'rest', 'root_visible', 'root_invisible');
ALTER TABLE users ADD COLUMN mode users_modes NOT NULL DEFAULT 'standard';
UPDATE users set mode = 'root_invisible' WHERE user_id = 'superadmin';
ALTER TABLE users DROP COLUMN IF EXISTS authorized_api;
ALTER TABLE users ADD COLUMN authorized_api jsonb NOT NULL DEFAULT '[]';
ALTER TABLE users DROP COLUMN IF EXISTS feature_tour;
ALTER TABLE users ADD COLUMN feature_tour jsonb NOT NULL DEFAULT '[]';

DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'users' and column_name = 'loginmode') THEN
        UPDATE users set mode = 'rest' WHERE loginmode = 'restMode';
        ALTER TABLE users DROP COLUMN IF EXISTS loginmode;
    END IF;
END$$;

/*INDEXING_MODELS_FIELDS*/
ALTER TABLE indexing_models_fields DROP COLUMN IF EXISTS enabled;
ALTER TABLE indexing_models_fields ADD COLUMN enabled BOOLEAN NOT NULL DEFAULT TRUE;

/* CONTACTS GROUPS */
ALTER TABLE contacts_groups DROP COLUMN IF EXISTS entity_owner;

DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'notif_event_stack' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE notif_event_stack ADD COLUMN user_id_tmp INTEGER;
        UPDATE notif_event_stack set user_id_tmp = (select id FROM users where users.user_id = notif_event_stack.user_id);
        DELETE FROM notif_event_stack WHERE user_id_tmp IS NULL;
        ALTER TABLE notif_event_stack ALTER COLUMN user_id_tmp set not null;
        ALTER TABLE notif_event_stack DROP COLUMN IF EXISTS user_id;
        ALTER TABLE notif_event_stack RENAME COLUMN user_id_tmp TO user_id;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'users_email_signatures' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE users_email_signatures ADD COLUMN user_id_tmp INTEGER;
        UPDATE users_email_signatures set user_id_tmp = (select id FROM users where users.user_id = users_email_signatures.user_id);
        DELETE FROM users_email_signatures WHERE user_id_tmp IS NULL;
        ALTER TABLE users_email_signatures ALTER COLUMN user_id_tmp set not null;
        ALTER TABLE users_email_signatures DROP COLUMN IF EXISTS user_id;
        ALTER TABLE users_email_signatures RENAME COLUMN user_id_tmp TO user_id;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'users_entities' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE users_entities ADD COLUMN user_id_tmp INTEGER;
        UPDATE users_entities set user_id_tmp = (select id FROM users where users.user_id = users_entities.user_id);
        DELETE FROM users_entities WHERE user_id_tmp IS NULL;
        ALTER TABLE users_entities ALTER COLUMN user_id_tmp set not null;
        ALTER TABLE users_entities DROP COLUMN IF EXISTS user_id;
        ALTER TABLE users_entities RENAME COLUMN user_id_tmp TO user_id;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'res_letterbox' and column_name = 'dest_user' and data_type != 'integer') THEN
        ALTER TABLE res_letterbox ADD COLUMN dest_user_tmp INTEGER;
        UPDATE res_letterbox set dest_user_tmp = (select id FROM users where users.user_id = res_letterbox.dest_user);
        ALTER TABLE res_letterbox DROP COLUMN IF EXISTS dest_user;
        ALTER TABLE res_letterbox RENAME COLUMN dest_user_tmp TO dest_user;
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'dest_user(\s*)=(\s*)@user', 'dest_user = @user_id', 'gmi');
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'dest_user(\s*)=(\s*)''', 'dest_user is null', 'gmi');
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'dest_user(\s*)=(\s*)""', 'dest_user is null', 'gmi');
        UPDATE security SET where_clause = REGEXP_REPLACE(where_clause, 'dest_user(\s*)=(\s*)@user', 'dest_user = @user_id', 'gmi');
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'basket_persistent_mode' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE basket_persistent_mode ADD COLUMN user_id_tmp INTEGER;
        UPDATE basket_persistent_mode set user_id_tmp = (select id FROM users where users.user_id = basket_persistent_mode.user_id);
        DELETE FROM basket_persistent_mode WHERE user_id_tmp IS NULL;
        ALTER TABLE basket_persistent_mode ALTER COLUMN user_id_tmp set not null;
        ALTER TABLE basket_persistent_mode DROP COLUMN IF EXISTS user_id;
        ALTER TABLE basket_persistent_mode RENAME COLUMN user_id_tmp TO user_id;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'res_mark_as_read' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE res_mark_as_read ADD COLUMN user_id_tmp INTEGER;
        UPDATE res_mark_as_read set user_id_tmp = (select id FROM users where users.user_id = res_mark_as_read.user_id);
        DELETE FROM res_mark_as_read WHERE user_id_tmp IS NULL;
        ALTER TABLE res_mark_as_read ALTER COLUMN user_id_tmp set not null;
        ALTER TABLE res_mark_as_read DROP COLUMN IF EXISTS user_id;
        ALTER TABLE res_mark_as_read RENAME COLUMN user_id_tmp TO user_id;
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'from res_mark_as_read WHERE user_id(\s*)=(\s*)@user', 'from res_mark_as_read WHERE user_id = @user_id', 'gmi');
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'res_attachments' and column_name = 'typist' and data_type != 'integer') THEN
        ALTER TABLE res_attachments ADD COLUMN typist_tmp INTEGER;
        UPDATE res_attachments set typist_tmp = (select id FROM users where users.user_id = res_attachments.typist);
        UPDATE res_attachments set typist_tmp = (SELECT
                                                     CASE
                                                         WHEN (SELECT count(id) FROM users WHERE user_id = 'superadmin') > 0 THEN
                                                             (SELECT id FROM users WHERE user_id = 'superadmin')
                                                         ELSE
                                                             (SELECT id FROM users WHERE status = 'OK' ORDER BY id LIMIT 1)
                                                         END) WHERE typist_tmp IS NULL;
        ALTER TABLE res_attachments DROP COLUMN IF EXISTS typist;
        ALTER TABLE res_attachments RENAME COLUMN typist_tmp TO typist;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'listinstance_history_details' and column_name = 'item_id' and data_type != 'integer') THEN
        ALTER TABLE listinstance_history_details ADD COLUMN item_id_tmp INTEGER;
        UPDATE listinstance_history_details set item_id_tmp = (select id FROM users where users.user_id = listinstance_history_details.item_id) WHERE item_type = 'user_id';
        UPDATE listinstance_history_details set item_id_tmp = (select id FROM entities where entities.entity_id = listinstance_history_details.item_id) WHERE item_type = 'entity_id';
        ALTER TABLE listinstance_history_details DROP COLUMN IF EXISTS item_id;
        ALTER TABLE listinstance_history_details RENAME COLUMN item_id_tmp TO item_id;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'listinstance_history_details' and column_name = 'added_by_user' and data_type != 'integer') THEN
        ALTER TABLE listinstance_history_details ADD COLUMN added_by_user_tmp INTEGER;
        UPDATE listinstance_history_details set added_by_user_tmp = (select id FROM users where users.user_id = listinstance_history_details.added_by_user);
        ALTER TABLE listinstance_history_details DROP COLUMN IF EXISTS added_by_user;
        ALTER TABLE listinstance_history_details RENAME COLUMN added_by_user_tmp TO added_by_user;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'listinstance' and column_name = 'item_id' and data_type != 'integer') THEN
        ALTER TABLE listinstance ADD COLUMN item_id_tmp INTEGER;
        UPDATE listinstance set item_id_tmp = (select id FROM users where users.user_id = listinstance.item_id) WHERE item_type = 'user_id';
        UPDATE listinstance set item_id_tmp = (select id FROM entities where entities.entity_id = listinstance.item_id) WHERE item_type = 'entity_id';
        UPDATE listinstance set item_id_tmp = (SELECT
                                                     CASE
                                                         WHEN (SELECT count(id) FROM users WHERE user_id = 'superadmin') > 0 THEN
                                                             (SELECT id FROM users WHERE user_id = 'superadmin')
                                                         ELSE
                                                             (SELECT id FROM users WHERE status = 'OK' ORDER BY id LIMIT 1)
                                                         END) WHERE item_id_tmp IS NULL AND item_type = 'user_id';
        UPDATE listinstance set item_id_tmp = (SELECT id FROM entities WHERE enabled = 'Y' ORDER BY id LIMIT 1) WHERE item_id_tmp IS NULL AND item_type = 'entity_id';
        ALTER TABLE listinstance DROP COLUMN IF EXISTS item_id;
        ALTER TABLE listinstance RENAME COLUMN item_id_tmp TO item_id;
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'item_id(\s*)=(\s*)@user ', 'item_id = @user_id ', 'gmi');
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'item_id(\s*)in(\s*)\(@my_entities\)', 'item_id in (@my_entities_id)', 'gmi');
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, 'item_id(\s*)in(\s*)\(@my_primary_entity\)', 'item_id in (@my_primary_entity_id)', 'gmi');
        UPDATE baskets SET basket_clause = REGEXP_REPLACE(basket_clause, '\(res_id,(\s*)@user\)', '(res_id, @user_id)', 'gmi');
        UPDATE security SET where_clause = REGEXP_REPLACE(where_clause, 'item_id(\s*)=(\s*)@user ', 'item_id = @user_id ', 'gmi');
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'listinstance' and column_name = 'added_by_user' and data_type != 'integer') THEN
        ALTER TABLE listinstance ADD COLUMN added_by_user_tmp INTEGER;
        UPDATE listinstance set added_by_user_tmp = (select id FROM users where users.user_id = listinstance.added_by_user);
        UPDATE listinstance set added_by_user_tmp = (SELECT
                                                   CASE
                                                       WHEN (SELECT count(id) FROM users WHERE user_id = 'superadmin') > 0 THEN
                                                           (SELECT id FROM users WHERE user_id = 'superadmin')
                                                       ELSE
                                                           (SELECT id FROM users WHERE status = 'OK' ORDER BY id LIMIT 1)
                                                       END) WHERE added_by_user_tmp IS NULL;
        ALTER TABLE listinstance DROP COLUMN IF EXISTS added_by_user;
        ALTER TABLE listinstance RENAME COLUMN added_by_user_tmp TO added_by_user;
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'history' and column_name = 'user_id' and data_type != 'integer') THEN
        ALTER TABLE history ADD COLUMN user_id_tmp INTEGER;
        UPDATE history set user_id_tmp = (select id FROM users where lower(users.user_id) = lower(history.user_id) LIMIT 1);
        ALTER TABLE history DROP COLUMN IF EXISTS user_id;
        ALTER TABLE history RENAME COLUMN user_id_tmp TO user_id;
        UPDATE history set record_id = (select id FROM users where users.user_id = history.record_id) WHERE table_name = 'users';
    END IF;
END$$;
DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'message_exchange' and column_name = 'account_id' and data_type != 'integer') THEN
        ALTER TABLE message_exchange ADD COLUMN account_id_tmp INTEGER;
        UPDATE message_exchange set account_id_tmp = (select id FROM users where users.user_id = message_exchange.account_id);
        ALTER TABLE message_exchange DROP COLUMN IF EXISTS account_id;
        ALTER TABLE message_exchange RENAME COLUMN account_id_tmp TO account_id;
    END IF;
END$$;

/* GROUPBASKET */
UPDATE groupbasket SET list_event_data = jsonb_set(list_event_data, '{canUpdateData}', 'true') WHERE list_event_data->>'canUpdate' = 'true';
UPDATE groupbasket SET list_event_data = jsonb_set(list_event_data, '{canUpdateData}', 'false') WHERE list_event_data->>'canUpdate' = 'false';
UPDATE groupbasket SET list_event_data = list_event_data - 'canUpdate';

/* TEMPLATES */
ALTER TABLE templates DROP COLUMN IF EXISTS subject;
ALTER TABLE templates ADD COLUMN subject character varying(255);

UPDATE groupbasket SET list_event_data = '{"canUpdateDocuments":true}' WHERE list_event_data->'canUpdateDocument' = 'true';

/* REGISTERED MAIL */
DROP TABLE IF EXISTS registered_mail_issuing_sites;
CREATE TABLE IF NOT EXISTS registered_mail_issuing_sites (
   id SERIAL NOT NULL,
   label CHARACTER VARYING(256) NOT NULL,
   post_office_label CHARACTER VARYING(256),
   account_number INTEGER,
   address_number CHARACTER VARYING(256) NOT NULL,
   address_street CHARACTER VARYING(256) NOT NULL,
   address_additional1 CHARACTER VARYING(256),
   address_additional2 CHARACTER VARYING(256),
   address_postcode CHARACTER VARYING(256) NOT NULL,
   address_town CHARACTER VARYING(256) NOT NULL,
   address_country CHARACTER VARYING(256),
   CONSTRAINT registered_mail_issuing_sites_pkey PRIMARY KEY (id)
);
DROP TABLE IF EXISTS registered_mail_issuing_sites_entities;
CREATE TABLE IF NOT EXISTS registered_mail_issuing_sites_entities (
   id SERIAL NOT NULL,
   site_id INTEGER NOT NULL,
   entity_id INTEGER NOT NULL,
   CONSTRAINT registered_mail_issuing_sites_entities_pkey PRIMARY KEY (id),
   CONSTRAINT registered_mail_issuing_sites_entities_unique_key UNIQUE (site_id, entity_id)
);

DROP TABLE IF EXISTS registered_mail_number_range;
CREATE TABLE IF NOT EXISTS registered_mail_number_range (
    id SERIAL NOT NULL,
    type CHARACTER VARYING(15) NOT NULL,
    tracking_account_number CHARACTER VARYING(256) NOT NULL,
    range_start INTEGER NOT NULL,
    range_end INTEGER NOT NULL,
    creator INTEGER NOT NULL,
    creation_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    status CHARACTER VARYING(10) NOT NULL,
    current_number INTEGER,
    CONSTRAINT registered_mail_number_range_pkey PRIMARY KEY (id),
    CONSTRAINT registered_mail_number_range_unique_key UNIQUE (tracking_account_number)
);

DROP TABLE IF EXISTS registered_mail_resources;
CREATE TABLE IF NOT EXISTS registered_mail_resources (
    id SERIAL NOT NULL,
    res_id INTEGER NOT NULL,
    type CHARACTER VARYING(2) NOT NULL,
    issuing_site INTEGER NOT NULL,
    warranty CHARACTER VARYING(2) NOT NULL,
    letter BOOL NOT NULL DEFAULT FALSE,
    recipient jsonb NOT NULL,
    number INTEGER NOT NULL,
    reference TEXT,
    generated BOOL NOT NULL DEFAULT FALSE,
    deposit_id INTEGER,
    received_date TIMESTAMP WITHOUT TIME ZONE,
    return_reason CHARACTER VARYING(256),
    CONSTRAINT registered_mail_resources_pkey PRIMARY KEY (id),
    CONSTRAINT registered_mail_resources_unique_key UNIQUE (res_id)
);

DELETE FROM parameters WHERE id = 'last_deposit_id';
INSERT INTO parameters (id, param_value_int) VALUES ('last_deposit_id', 0);
DELETE FROM parameters WHERE id = 'registeredMailNotDistributedStatus';
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailNotDistributedStatus', 'PND');
DELETE FROM parameters WHERE id = 'registeredMailDistributedStatus';
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailDistributedStatus', 'DSTRIBUTED');
DELETE FROM status WHERE id = 'PND' OR id = 'DSTRIBUTED';
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('PND', 'AR Non distribué', 'Y', 'fm-letter-status-rejected', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('DSTRIBUTED', 'AR distribué', 'Y', 'fa-check', 'apps', 'Y', 'Y');
DELETE FROM parameters WHERE id = 'registeredMailImportedStatus';
INSERT INTO parameters (id, param_value_string) VALUES ('registeredMailImportedStatus', 'NEW');

DELETE FROM parameters WHERE id = 'traffic_record_summary_sheet';
INSERT INTO parameters (id, description, param_value_string) VALUES ('traffic_record_summary_sheet', 'Module circulation pour la fiche de liaison', '');

DO $$ BEGIN
    IF (SELECT count(column_name) from information_schema.columns where table_name = 'configurations' and column_name = 'service') THEN
        ALTER TABLE configurations RENAME COLUMN service TO privilege;
        DELETE FROM configurations WHERE privilege = 'admin_search';
        INSERT INTO configurations (privilege, value) VALUES ('admin_search', '{"listEvent": {"defaultTab": "dashboard"},"listDisplay":{"templateColumns":6,"subInfos":[{"value":"getPriority","cssClasses":["align_leftData"],"icon":"fa-traffic-light"},{"value":"getCreationAndProcessLimitDates","cssClasses":["align_leftData"],"icon":"fa-calendar"},{"value":"getAssignee","cssClasses":["align_leftData"],"icon":"fa-sitemap"},{"value":"getDoctype","cssClasses":["align_leftData"],"icon":"fa-suitcase"},{"value":"getRecipients","cssClasses":["align_leftData"],"icon":"fa-user"},{"value":"getSenders","cssClasses":["align_leftData"],"icon":"fa-book"}]}}');
    END IF;
END$$;

DROP TABLE IF EXISTS search_templates;
CREATE TABLE search_templates (
  id serial,
  user_id integer NOT NULL,
  label character varying(255) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  query json NOT NULL,
  CONSTRAINT search_templates_pkey PRIMARY KEY (id)
) WITH (OIDS=FALSE);

/*ARCHIVAL*/
ALTER TABLE doctypes DROP COLUMN IF EXISTS action_current_use;
ALTER TABLE doctypes ADD COLUMN action_current_use character varying(255) DEFAULT NULL;
UPDATE doctypes SET duration_current_use = duration_current_use * 30;

ALTER TABLE entities DROP COLUMN IF EXISTS archival_agency;
ALTER TABLE entities DROP COLUMN IF EXISTS archival_agreement;
ALTER TABLE entities DROP COLUMN IF EXISTS producer_service;
ALTER TABLE entities ADD COLUMN producer_service character varying(255);
UPDATE entities SET producer_service = entity_id;

UPDATE actions SET component = 'sendToRecordManagementAction' where action_page = 'export_seda';
UPDATE actions SET component = 'checkAcknowledgmentRecordManagementAction' where action_page = 'check_acknowledgement';
UPDATE actions SET component = 'checkReplyRecordManagementAction' where action_page = 'check_reply';
UPDATE actions SET component = 'resetRecordManagementAction' where action_page = 'reset_letter';
UPDATE actions SET component = 'confirmAction' where action_page = 'purge_letter';

UPDATE res_attachments SET attachment_type = 'acknowledgement_record_management' WHERE attachment_type = 'simple_attachment' AND format = 'xml' AND title = 'Accusé de réception' AND relation = 1 AND status = 'TRA';
UPDATE res_attachments SET attachment_type = 'reply_record_management' WHERE attachment_type = 'simple_attachment' AND format = 'xml' AND title = 'Réponse au transfert' AND relation = 1 AND status = 'TRA';

ALTER TABLE res_letterbox DROP COLUMN IF EXISTS retention_frozen;
ALTER TABLE res_letterbox ADD COLUMN retention_frozen boolean DEFAULT FALSE NOT NULL;
ALTER TABLE res_letterbox DROP COLUMN IF EXISTS binding;
ALTER TABLE res_letterbox ADD COLUMN binding boolean;

DELETE FROM parameters WHERE id = 'bindingDocumentFinalAction';
INSERT INTO parameters (id, param_value_string) VALUES ('bindingDocumentFinalAction', 'copy');
DELETE FROM parameters WHERE id = 'nonBindingDocumentFinalAction';
INSERT INTO parameters (id, param_value_string) VALUES ('nonBindingDocumentFinalAction', 'delete');

/* CUSTOM FIELDS */
ALTER TABLE custom_fields DROP COLUMN IF EXISTS mode;
DROP TYPE IF EXISTS custom_fields_modes;
CREATE TYPE custom_fields_modes AS ENUM ('form', 'technical');
ALTER TABLE custom_fields ADD COLUMN mode custom_fields_modes NOT NULL DEFAULT 'form';

ALTER TABLE listinstance DROP COLUMN IF EXISTS delegate;
ALTER TABLE listinstance ADD COLUMN delegate INTEGER;

/* Replace dest_user with the dest in listinstance */
UPDATE res_letterbox
SET dest_user = (
    SELECT item_id FROM listinstance
    WHERE item_mode = 'dest' AND item_type = 'user_id' AND listinstance.res_id = res_letterbox.res_id LIMIT 1
);

/* VISA CIRCUIT PARAMETERS */
DELETE FROM parameters WHERE id = 'minimumVisaRole';
INSERT INTO parameters (id, description, param_value_int) VALUES ('minimumVisaRole', 'Nombre minimum de viseur dans les circuits de visa (0 pour désactiver)', 0);
DELETE FROM parameters WHERE id = 'maximumSignRole';
INSERT INTO parameters (id, description, param_value_int) VALUES ('maximumSignRole', 'Nombre maximum de signataires dans les circuits de visa (0 pour désactiver)', 0);

/* ORDER ON CHRONO */
CREATE OR REPLACE FUNCTION order_alphanum(text) RETURNS text AS $$
declare
    tmp text;
begin
    tmp := $1;
    tmp := tmp || 'Z';
    tmp := regexp_replace(tmp, E'(\\D)', E'\\1/', 'g');

    IF count(regexp_match(tmp, E'(\\D(\\d{8})\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{8}\\D)', E'\\10\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{7}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{7}\\D)', E'\\100\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{6}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{6}\\D)', E'\\1000\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{5}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{5}\\D)', E'\\10000\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{4}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{4}\\D)', E'\\100000\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{3}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{3}\\D)', E'\\1000000\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{2}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{2}\\D)', E'\\10000000\\2', 'g');
    END IF;
    IF count(regexp_match(tmp, E'(\\D)(\\d{1}\\D)')) > 0 THEN
        tmp := regexp_replace(tmp, E'(\\D)(\\d{1}\\D)', E'\\100000000\\2', 'g');
    END IF;

    RETURN tmp;
end;
$$ LANGUAGE plpgsql;

/* RE CREATE VIEWS */
CREATE OR REPLACE VIEW res_view_letterbox AS
SELECT r.res_id,
       r.type_id,
       r.policy_id,
       r.cycle_id,
       d.description AS type_label,
       d.doctypes_first_level_id,
       dfl.doctypes_first_level_label,
       dfl.css_style AS doctype_first_level_style,
       d.doctypes_second_level_id,
       dsl.doctypes_second_level_label,
       dsl.css_style AS doctype_second_level_style,
       r.format,
       r.typist,
       r.creation_date,
       r.modification_date,
       r.docserver_id,
       r.path,
       r.filename,
       r.fingerprint,
       r.filesize,
       r.status,
       r.work_batch,
       r.doc_date,
       r.external_id,
       r.departure_date,
       r.opinion_limit_date,
       r.barcode,
       r.initiator,
       r.destination,
       r.dest_user,
       r.confidentiality,
       r.category_id,
       r.alt_identifier,
       r.admission_date,
       r.process_limit_date,
       r.closing_date,
       r.alarm1_date,
       r.alarm2_date,
       r.flag_alarm1,
       r.flag_alarm2,
       r.subject,
       r.priority,
       r.locker_user_id,
       r.locker_time,
       r.custom_fields,
       r.retention_frozen,
       r.binding,
       en.entity_label,
       en.entity_type AS entitytype
FROM doctypes d,
     doctypes_first_level dfl,
     doctypes_second_level dsl,
     res_letterbox r
    LEFT JOIN entities en ON r.destination::text = en.entity_id::text
WHERE r.type_id = d.type_id AND d.doctypes_first_level_id = dfl.doctypes_first_level_id AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;
