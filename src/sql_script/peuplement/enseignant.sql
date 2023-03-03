DELIMITER //

DROP PROCEDURE IF EXISTS peupleEnseignant //

CREATE PROCEDURE peupleEnseignant()
BEGIN
    DECLARE counter INT DEFAULT 1; -- La premiere formation est pour un prof spécifique
    DECLARE nbFormation INT;
    DECLARE lengthListePersonne INT;
    DECLARE statutEnseignantId INT;
    DECLARE specialiteId INT;

    -- Création des enseignants responsable
    SELECT COUNT(*) INTO nbFormation FROM formation;
    WHILE counter < nbFormation + 1 DO

        SELECT id INTO statutEnseignantId FROM statut_enseignant ORDER BY RAND() LIMIT 1;
        SELECT id INTO specialiteId FROM specialite ORDER BY RAND() LIMIT 1;

        INSERT INTO enseignant(statut_enseignant_id, section_id, responsable_formation_id)
            VALUES (
                statutEnseignantId,
                specialiteId,
                counter
            );

        UPDATE personne
        SET enseignant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT_RES";}'
        WHERE id = counter;

        SET counter = counter + 1;
    END WHILE;

    -- Création des enseignants

    SELECT COUNT(*) FROM personne INTO lengthListePersonne;

    WHILE counter < (lengthListePersonne / 4) DO -- Mettre 1/4 de prof pour 3/4 étudiants

        SELECT id INTO statutEnseignantId FROM statut_enseignant ORDER BY RAND() LIMIT 1;
        SELECT id INTO specialiteId FROM specialite ORDER BY RAND() LIMIT 1;

        INSERT INTO enseignant(statut_enseignant_id, section_id)
            VALUES (
                statutEnseignantId,
                specialiteId
            );

        UPDATE personne
        SET enseignant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT";}'
        WHERE id = counter;

        SET counter = counter + 1;
    END WHILE;
END; //

DELIMITER ;

CALL peupleEnseignant();