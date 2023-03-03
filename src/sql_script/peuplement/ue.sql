DELIMITER //
DROP PROCEDURE IF EXISTS peupleUe; //

CREATE PROCEDURE peupleUe()
BEGIN
    DECLARE lengthFormation  INT DEFAULT 0;
    DECLARE counterFormation  INT DEFAULT 0;
    DECLARE counterUe  INT DEFAULT 0;

    DECLARE current_specialite_id INT DEFAULT 0;
    DECLARE current_formation_id INT DEFAULT 0;
    DECLARE current_name VARCHAR(255);
    DECLARE current_volume_horaire INT DEFAULT 0;

    -- Temporary table creation for ue name
    CREATE TEMPORARY TABLE nomUe(nom VARCHAR(255));
    -- Insert rows to the temp nomUe table
    INSERT INTO nomUe(nom)
    VALUES("POO"),
          ("Algorithmes"),
          ("Anglais"),
          ("Droits"),
          ("Web"),
          ("Web avancé"),
          ("Math"),
          ("Geo"),
          ("Histoire"),
          ("physique"),
          ("Enseignement civique"),
          ("Arts plastiques"),
          ("Histoire des arts"),
          ("Education physique et sportive"),
          ("Bases de données et web"),
          ("Programmation des systèmes mobiles"),
          ("Connaissance de l\'entreprise"),
          ("Anglais pour les métiers de l\'informatique"),
          ("Génie mécanique"),
          ("Génie électrique"),
          ("Robotique"),
          ("Systèmes automatisés"),
          ("Systèmes automatisés"),
          ("Capteurs"),
          ("Industrie 4.0"),
          ("Electricité industrielle"),
          ("Economie de filière"),
          ("Oenologie - Analyse sensorielle"),
          ("Logistique"),
          ("Méthodologie"),
          ("Géographie viticole"),
          ("Marketing stratégique"),
          ("Marketing digital"),
          ("Etudes de marché"),
          ("Négociation"),
          ("Prospection internationale"),
          ("Anglais technique"),
          ("Projets tutoré"),
          ("Droit"),
          ("Sécurité des système"),
          ("Psychologie ");

    -- Temporary table creation for ue volume horaire
    CREATE TEMPORARY TABLE volumeHoraireUe(h INT);
    -- Insert rows to the temp volumeHoraireUe table
    INSERT INTO volumeHoraireUe(h)
    VALUES(16),
          (32),
          (64),
          (128),
          (256);

    SELECT COUNT(*) INTO lengthFormation FROM formation ;
    -- ADD 15ue per formation
    WHILE counterFormation < lengthFormation DO
        -- Get the current_formation_id for the current ue ( offset = counter, limit = 1 )
        SELECT id INTO current_formation_id FROM formation LIMIT counterFormation, 1;

        SET counterUe = 0;
        WHILE counterUe < 15 DO
            -- get a random specialite
            SELECT id INTO current_specialite_id FROM specialite ORDER BY RAND() LIMIT 1;
            -- get the current name
            SELECT nom INTO current_name FROM nomUe ORDER BY RAND() LIMIT 1;
            -- get a random volume horaire
            SELECT h INTO current_volume_horaire FROM volumeHoraireUe ORDER BY RAND() LIMIT 1;

            INSERT INTO ue(specialite_id, formation_id, nom, volume_horaire)
                VALUES(
                    current_specialite_id,
                    current_formation_id,
                    current_name,
                    current_volume_horaire
                );
            SET counterUe = counterUe + 1;
        END WHILE;
        SET counterFormation = counterFormation + 1;
    END WHILE;
END; //

CALL peupleUe();//


DELIMITER ;