DROP PROCEDURE IF EXISTS test_post_persist_etudiant;

DELIMITER //
CREATE PROCEDURE test_post_persist_etudiant()
BEGIN
    DECLARE nbGroupe INT DEFAULT 0;
    DECLARE idEtu INT DEFAULT 9999;

    -- Inserer une nouvelle formation ( aucun groupe ne sera donc reliée)
    INSERT INTO formation (id, cursus_id, nom, annee)
    VALUES (9999, 1, 'FORMATIONTEST', 1);
    -- Inserer un nouvelle étudiant
    INSERT INTO etudiant (id, formation_id)
    VALUES (9999, 9999);
    -- Verifier que les tp td cm ont bien été créer
    SELECT COUNT(*) INTO nbGroupe FROM groupe_etudiant
        WHERE groupe_etudiant.etudiant_id = LAST_INSERT_ID();

    IF nbGroupe = 3 THEN
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur post_persist_etudiant', 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur post_persist_etudiant', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_post_persist_etudiant();