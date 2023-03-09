DROP PROCEDURE IF EXISTS peupleCursus;
DROP TEMPORARY TABLE IF EXISTS nomCursus;

DELIMITER //
CREATE PROCEDURE peupleCursus()
BEGIN
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    -- DECLARE nom VARCHAR(255);
    -- Temporary table creation for cursus name (Medecine", "Droit", "Informatique" ...)
    CREATE TEMPORARY TABLE nomCursus(nom VARCHAR(255));
    -- Insert rows to the temp nomCursus table
    INSERT INTO nomCursus(nom)
    VALUES("Medecine"),
          ("Droit"),
          ("Informatique"),
          ("Geo"),
          ("Histoire");
    -- Insert rows to the cursus table
    SELECT COUNT(*) FROM nomCursus INTO length;
    WHILE counter<length DO
        -- Insert 'Master'
        INSERT INTO cursus(nom, niveau)
        SELECT nom, 'Master' AS niveau FROM nomCursus LIMIT counter, 1;
        -- Insert 'License'
        INSERT INTO cursus(nom, niveau)
        SELECT nom, 'Licence' AS niveau FROM nomCursus LIMIT counter, 1;
        SET counter = counter + 1;
    END WHILE;
END; //

DELIMITER ;
CALL peupleCursus();

