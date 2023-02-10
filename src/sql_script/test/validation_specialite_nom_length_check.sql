DROP PROCEDURE IF EXISTS validation_specialite_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_specialite_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test specialite_nom_length_check';
            END;         

           INSERT INTO projet_ent_universitaire.specialite (nom, `section`, groupe)
			VALUES(_nom, 1, 'I');


    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test specialite_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_specialite_nom_length_check('t');
CALL validation_specialite_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');