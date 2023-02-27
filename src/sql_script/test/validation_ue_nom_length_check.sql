DROP PROCEDURE IF EXISTS validation_ue_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_ue_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test ue_nom_length_check';
            END;         
        INSERT INTO projet_ent_universitaire.ue (specialite_id, formation_id, nom, volume_horaire)
			VALUES(1, 1, _nom, 10);
    END;

    -- Vérifier que le nom d'une Ue est compris entre 2 et 255 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test ue_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_ue_nom_length_check('t');
CALL validation_ue_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');