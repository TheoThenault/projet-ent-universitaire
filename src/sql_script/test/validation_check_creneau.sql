DROP PROCEDURE IF EXISTS validation_check_creneau;

DELIMITER //
CREATE PROCEDURE validation_check_creneau(_num INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_creneau';
            END;         
		INSERT INTO projet_ent_universitaire.cour(ue_id, salle_id, enseignant_id, groupe_id, creneau)
			VALUES(1, 1, 1, NULL, _num);


    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_creneau', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_creneau(-1);
CALL validation_check_creneau(601);
