DELIMITER //
DROP TRIGGER IF EXISTS VerifUEQuandValidation //

CREATE TRIGGER VerifUEQuandValidation BEFORE INSERT ON etudiant_ue
    FOR EACH ROW
BEGIN
	DECLARE ueFound INT DEFAULT -1;
	SELECT COUNT(*) INTO ueFound FROM ue u 
		LEFT JOIN formation f ON u.formation_id = f.id 
		LEFT JOIN etudiant e ON f.id = e.formation_id 
			WHERE e.id = NEW.etudiant_id AND u.id = NEW.ue_id;
	IF ueFound < 1 THEN -- l'ue n'a pas été trouver dans la liste d'ue de la formation de l'élève
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'Cet élève n\'est pas dans cet UE! pas de validation possible!';
	END IF;		
END; //
DELIMITER ;