DROP PROCEDURE IF EXISTS validation_salle_equipement_length_check;

DELIMITER //
CREATE PROCEDURE validation_salle_equipement_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test salle_equipement_length_check';
            END;         

           INSERT INTO projet_ent_universitaire.salle (nom, batiment, equipement, capacite)
          		VALUES('test', 'test', _nom, 10);

           
    END;

    -- Vérifier que l'équipement d'une salle est compris entre 2 et 128 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test salle_equipement_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_salle_equipement_length_check('t');
CALL validation_salle_equipement_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');