DROP PROCEDURE IF EXISTS test_VerifSalleQuandCreationCour;

DELIMITER //
CREATE PROCEDURE test_VerifSalleQuandCreationCour()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur VerifSalleQuandCreationCour';
            END;

        INSERT INTO salle(id, nom, batiment, equipement, capacite)
        VALUES(777777,'salleForTests', 'batimentTest', null, 0 );
        -- Tenter d'insérer un cours avec une salle pouvant acceuillir pas plus de 0 personne
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (1, 777777, 1, 1, 599);
    END;

    -- Vérifier si un message d'erreur a été capturé avec un cours dans une salle déjà pleine
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur VerifSalleQuandCreationCour', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_VerifSalleQuandCreationCour();