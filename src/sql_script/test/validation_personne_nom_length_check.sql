DROP PROCEDURE IF EXISTS validation_personne_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_personne_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test personne_nom_length_check';
            END;         
           
		INSERT INTO projet_ent_universitaire.personne(etudiant_id, enseignant_id, email, nom, prenom, password, roles)
			VALUES(NULL, NULL, 'email@internet.com', _nom, 'prenom', 
			'$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 
						'a:1:{i:0;s:9:"ROLE_USER";}');

           
    END;

    -- Vérifier que le nom d'une personne est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test personne_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_personne_nom_length_check('t');
CALL validation_personne_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');