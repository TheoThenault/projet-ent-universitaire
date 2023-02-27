DROP PROCEDURE IF EXISTS validation_check_capacite;

DELIMITER //
CREATE PROCEDURE validation_check_capacite(_cap INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_capacite';
            END;         

		INSERT INTO projet_ent_universitaire.salle (nom, batiment, equipement, capacite)
			VALUES('test', 'test', 'langue', _cap);

    END;

    -- Vérifier si un message d'erreur a été capturé quand on insere une salle avec une capacité non comprise entre 0 et 999
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_capacite', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_capacite(-1);
CALL validation_check_capacite(1000);