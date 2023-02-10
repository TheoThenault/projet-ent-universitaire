DROP PROCEDURE IF EXISTS validation_formation_annee_check;

DELIMITER //
CREATE PROCEDURE validation_formation_annee_check(_num INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test formation_annee_check';
            END;         
		INSERT INTO projet_ent_universitaire.formation(cursus_id, nom, annee)
			VALUES(1, 'test', _num);

    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test formation_annee_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_formation_annee_check(-1);
CALL validation_formation_annee_check(11);
