DROP PROCEDURE IF EXISTS validation_chk_groupe_type;

DELIMITER //
CREATE PROCEDURE validation_chk_groupe_type(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test chk_groupe_type';
            END;         
           
		INSERT INTO projet_ent_universitaire.groupe(`type`)
			VALUES(_nom);

					
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_groupe_type', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_chk_groupe_type('PA');
CALL validation_chk_groupe_type('BO');
CALL validation_chk_groupe_type('N');
