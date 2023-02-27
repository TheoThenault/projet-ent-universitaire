DROP PROCEDURE IF EXISTS validation_check_nb_heure_max;

DELIMITER //
CREATE PROCEDURE validation_check_nb_heure_max(_nbHeure INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_nb_heure_max';
            END;         
        INSERT INTO projet_ent_universitaire.statut_enseignant (nom, nb_heure_min, nb_heure_max)
			VALUES('test', 0, _nbHeure);

    END;

    -- Tester qu'on ne peux pas insérer un statut enseignant avec un nombre d'heures max non compris entre 0 et 9999
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_nb_heure_max', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_nb_heure_max(-1);
CALL validation_check_nb_heure_max(10000);