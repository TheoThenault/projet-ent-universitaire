DROP PROCEDURE IF EXISTS validation_statut_enseignant_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_statut_enseignant_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test statut_enseignant_nom_length_check';
            END;         
		INSERT INTO projet_ent_universitaire.statut_enseignant(nom, nb_heure_min, nb_heure_max)
			VALUES(_nom, 1, 2);

    END;

    -- Vérifier que le nom d'un statut enseignant est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test statut_enseignant_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_statut_enseignant_nom_length_check('t');
CALL validation_statut_enseignant_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');