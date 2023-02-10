DROP PROCEDURE IF EXISTS validation_check_volume_horaire;

DELIMITER //
CREATE PROCEDURE validation_check_volume_horaire(_vol INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_volume_horaire';
            END;         
        INSERT INTO projet_ent_universitaire.ue (specialite_id, formation_id, nom, volume_horaire)
			VALUES(1, 1, 'test', _vol);
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_volume_horaire', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_volume_horaire(-1);
CALL validation_check_volume_horaire(10001);
