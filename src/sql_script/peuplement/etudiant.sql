DELIMITER //
DROP PROCEDURE IF EXISTS peupleEtudiant; //

CREATE PROCEDURE peupleEtudiant()
BEGIN
    DECLARE length  INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    DECLARE current_personne_id INT DEFAULT 0;
    DECLARE current_formation_id INT DEFAULT 0;

    -- Insert into a temp table all the Personne who are not Enseignant
    CREATE TEMPORARY TABLE temp_personne_not_enseignant AS
    SELECT * FROM personne WHERE enseignant_id IS NULL;

    SELECT COUNT(*) INTO length FROM temp_personne_not_enseignant ;
    -- ADD the students
    WHILE counter < length DO
        -- ===== ADD ETUDIANT =====
        -- Get the formation_id for the current student
        SELECT id INTO current_formation_id FROM formation ORDER BY RAND() LIMIT 1;

        -- Insert the current student into the etudiant table
        INSERT INTO etudiant(formation_id) VALUES(current_formation_id);

        -- ===== UPDATE PERSONNE =====
        -- Get a personne_id for the current student ( offset = counter, limit = 1 )
        SELECT id INTO current_personne_id FROM temp_personne_not_enseignant LIMIT counter, 1;

        -- Update personne
        UPDATE personne
        SET etudiant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:9:"ROLE_ETUDIANT";}'
        WHERE id = current_personne_id;

        -- Increment the counter
        SET counter = counter + 1;
    END WHILE;
END; //

CALL peupleEtudiant();//


DELIMITER ;