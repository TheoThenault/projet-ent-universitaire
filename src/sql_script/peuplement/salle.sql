DELIMITER //

DROP PROCEDURE IF EXISTS peupleSalle; //
DROP TEMPORARY TABLE IF EXISTS nomBatTemp;//
DROP TEMPORARY TABLE IF EXISTS equipementTemp;//
DROP TEMPORARY TABLE IF EXISTS capaciteTemp;//

CREATE PROCEDURE peupleSalle()
BEGIN
	DECLARE i INT default 1; -- index pour la boucle
	DECLARE nbSalle INT default 51; -- nombre de salle
	DECLARE nomBat VARCHAR(255);
	DECLARE nom VARCHAR(255);
	DECLARE equipement VARCHAR(255);
	DECLARE capacite INT;

    CREATE TEMPORARY TABLE nomBatTemp(nom VARCHAR(255));
        -- insert rows to the table nomBat
    INSERT INTO nomBatTemp(nom)
    VALUES ("H01"),
           ("B02"),
           ("G03"),
           ("A04"),
           ("J05");

    CREATE TEMPORARY TABLE equipementTemp(nom VARCHAR(255));
            -- insert rows to the table equipement
    INSERT INTO equipementTemp(nom)
    VALUES ("Informatique"),
           ("Langue"),
           ("Chimie"),
           ("Physique");

    CREATE TEMPORARY TABLE capaciteTemp(cap INT);
    INSERT INTO capaciteTemp(cap)
    VALUES (20),
           (30),
           (40),
           (50),
           (100);

    WHILE i < nbSalle DO
        SELECT * INTO nomBat FROM nomBatTemp ORDER BY RAND() LIMIT 1;

        SET nom = CONCAT(nomBat, " ", i);

        SELECT * INTO equipement FROM equipementTemp ORDER BY RAND() LIMIT 1;
        SELECT * INTO capacite FROM capaciteTemp ORDER BY RAND() LIMIT 1;

        INSERT INTO salle(nom, batiment, equipement, capacite)
        VALUES(nom, nomBat, equipement, capacite );

        SET i = i + 1;

    END WHILE;

    INSERT INTO salle(id, nom, batiment, equipement, capacite)
    VALUES(99999999,'salleForTests', 'batimentTest', null, 0 );
END; //


DELIMITER ;

CALL peupleSalle();