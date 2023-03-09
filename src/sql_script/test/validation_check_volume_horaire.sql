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
        INSERT INTO ue (specialite_id, formation_id, nom, volume_horaire)
			VALUES(1, 1, 'test', _vol);
    END;

    -- Un ue doit avoir un volume horaire entre 0 et 9999, erreur sinon
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
