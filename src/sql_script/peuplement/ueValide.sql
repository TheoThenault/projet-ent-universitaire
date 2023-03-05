DROP PROCEDURE IF EXISTS peupleUeValide;
DROP TEMPORARY TABLE IF EXISTS ueTmp;

DELIMITER //
CREATE PROCEDURE peupleUeValide()
BEGIN
    DECLARE counter INT DEFAULT 0;
    DECLARE etuId INT;
    DECLARE ueId INT;

    WHILE counter < 20 DO -- 20 est le nombre d'ue valide

        -- prend 1 random dans la liste des étudiants
        SELECT id INTO etuId FROM etudiant ORDER BY RAND() LIMIT 1;

        -- réccup les ue de l'étudiant pour les mettre dans une table temporaire
        CREATE TEMPORARY TABLE ueTmp(id INT) AS
        SELECT u.id
        FROM ue u
             LEFT JOIN formation f
                       ON f.id = u.formation_id
             LEFT JOIN etudiant e
                       ON e.formation_id = f.id
        WHERE e.id = etuId;

        -- prend 1 ue random
        SELECT id INTO ueId FROM ueTmp ORDER BY RAND() LIMIT 1;

        -- valide cette eu(donc mettre id etudiant et id de l'ue)
        INSERT IGNORE INTO etudiant_ue(etudiant_id, ue_id) -- Le IGNORE permet d'ignorer un insertion si elle exsite deja
            VALUES(etuId, ueId);

        DROP TABLE ueTMP;

        SET counter = counter + 1;
    END WHILE;

END; //

DELIMITER ;
CALL peupleUeValide();