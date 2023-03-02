DROP PROCEDURE IF EXISTS validation_email_format;

DELIMITER //
CREATE PROCEDURE validation_email_format(_email VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test email_format';
            END;         
           
		INSERT INTO projet_ent_universitaire.personne(etudiant_id, enseignant_id, email, nom, prenom, password, roles)
			VALUES(NULL, NULL, _email, 'nom', 'prenom',
			'$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 
						'a:1:{i:0;s:9:"ROLE_USER";}');
					
    END;

    -- Vérifier que l'email d'une personne respecte le format 'exemple@univ-poitiers.fr'
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test email_format', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_email_format('');
CALL validation_email_format('a');
CALL validation_email_format('@email');
CALL validation_email_format('test@email');
CALL validation_email_format('@');
CALL validation_email_format('.com');
CALL validation_email_format('test@.com');
CALL validation_email_format('@email.com');
CALL validation_email_format('user@pasbonEmail.com');
