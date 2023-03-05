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

            CREATE TEMPORARY TABLE ueTmp(id INT) AS
            SELECT u.id
            FROM ue u
                     LEFT JOIN formation f
                               ON f.id = u.formation_id
                     LEFT JOIN etudiant e
                               ON e.formation_id = f.id
            WHERE e.id = etuId;

            -- réccup les ue de l'étudiants
            SELECT id INTO ueId FROM ueTmp ORDER BY RAND() LIMIT 1;


            -- prend au hasard 1 eu
            INSERT IGNORE INTO etudiant_ue(etudiant_id, ue_id) -- Le IGNORE permet d'ignorer un insertion si elle exsite deja
                VALUES(etuId, ueId);
                -- valide cette eu( donc mettre id etudiant et id de l'ue)

            DROP TABLE ueTMP;

            SET counter = counter + 1;
        END WHILE;

END; //

DELIMITER ;
CALL peupleUeValide();