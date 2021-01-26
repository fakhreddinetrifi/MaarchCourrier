-- *************************************************************************--
--                                                                          --
--                                                                          --
-- Model migration script - 20.10.1 to 20.10.2                              --
--                                                                          --
--                                                                          --
-- *************************************************************************--
--DATABASE_BACKUP|parameters

DELETE FROM parameters WHERE id = 'minimumVisaRole';
INSERT INTO parameters (id, description, param_value_int) VALUES ('minimumVisaRole', 'Nombre minimum de viseur dans les circuits de visa (0 pour désactiver)', 0);

DELETE FROM parameters WHERE id = 'maximumSignRole';
INSERT INTO parameters (id, description, param_value_int) VALUES ('maximumSignRole', 'Nombre maximum de signataires dans les circuits de visa (0 pour désactiver)', 0);

UPDATE parameters SET param_value_string = '20.10.2' WHERE id = 'database_version';
