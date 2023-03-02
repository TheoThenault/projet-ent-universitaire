DELIMITER //
DROP PROCEDURE IF EXISTS peupleFormation; //

CREATE PROCEDURE peupleFormation()
BEGIN
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    DECLARE isMaster INT DEFAULT 0;
    DECLARE cursusId INT DEFAULT 0;

    -- Insert rows to the formation table for each cursus row
    SELECT COUNT(*) INTO length FROM cursus ;
    WHILE counter < length DO
        -- Find the cursus id for each row
        SELECT cursus.id INTO cursusId FROM cursus LIMIT counter, 1;
        -- Check if the current cursus is a master or not
        SELECT COUNT(*) INTO isMaster FROM cursus WHERE cursus.niveau = "Master" AND cursus.id = cursusId;
        IF isMaster > 0 THEN -- Master found
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Master 1', 1, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Master 2', 2, cursusId);
        ELSE -- Licence
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 1', 1, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 2', 2, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 3', 3, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence PRO', 3, cursusId);
        END IF;

        SET counter = counter + 1;
    END WHILE;
END; //

CALL peupleFormation();//

DELIMITER ;

