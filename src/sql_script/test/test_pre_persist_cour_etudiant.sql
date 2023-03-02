DROP PROCEDURE IF EXISTS test_trigger_cour_etudiant;

DELIMITER //
CREATE PROCEDURE test_trigger_cour_etudiant()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur pre_persist_cour_etudiant';
            END;

        -- Tenter d'insérer un enregistrement dans la table cour
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (1, 19, 1, 1, 555);
        -- Tenter d'insérer un enregistrement dans la table cour avec le meme creneau et groupe
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (2, 20, 2, 1, 555);
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme groupe (donc meme etudiant)
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur pre_persist_cour_etudiant', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_trigger_cour_etudiant();