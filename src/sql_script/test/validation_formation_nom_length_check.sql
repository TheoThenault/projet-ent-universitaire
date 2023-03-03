DROP PROCEDURE IF EXISTS validation_formation_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_formation_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test formation_nom_length_check';
            END;         
		INSERT INTO formation(cursus_id, nom, annee)
			VALUES(1, _nom, 1);

    END;

    -- Vérifier que le nom d'une formation est compris entre 2 et 255 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_groupe_type', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_formation_nom_length_check('t');
CALL validation_formation_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');
