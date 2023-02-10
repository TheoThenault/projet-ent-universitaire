DROP PROCEDURE IF EXISTS validation_chk_cursus_level;

DELIMITER //
CREATE PROCEDURE validation_chk_cursus_level(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test chk_cursus_level';
            END;         
		INSERT INTO projet_ent_universitaire.cursus(nom, niveau)
			VALUES('test', _nom);
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_cursus_level', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_chk_cursus_level('NIlun');
CALL validation_chk_cursus_level('Nil\'autre');
CALL validation_chk_cursus_level('Seulemnt');
CALL validation_chk_cursus_level('M_aster');
CALL validation_chk_cursus_level('_liCe_nce');
CALL validation_chk_cursus_level('marche');
