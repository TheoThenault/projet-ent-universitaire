DROP PROCEDURE IF EXISTS test_VerifUEQuandValidation;

DELIMITER //
CREATE PROCEDURE test_VerifUEQuandValidation()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur VerifUEQuandValidation';
            END;

        INSERT INTO etudiant(id, formation_id)
        VALUEs (9999999, 1);
        INSERT INTO ue(id, specialite_id, formation_id, nom, volume_horaire)
        VALUEs (9999999, 1, 2, 'TEST UE', 0);
        -- Tenter de valider l'ue d'un etudiant qu'il ne possede pas ( à travers sa formation )
        INSERT INTO etudiant_ue (etudiant_id, ue_id)
        VALUES (9999999, 9999999);
    END;

    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur VerifUEQuandValidation', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_VerifUEQuandValidation();