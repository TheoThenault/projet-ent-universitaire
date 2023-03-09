DELIMITER //
DROP TRIGGER IF EXISTS verifAffectationMaxProf //

CREATE TRIGGER verifAffectationMaxProf BEFORE INSERT ON cour
    FOR EACH ROW
BEGIN
	DECLARE maxHeureProf INT;
	DECLARE nbHeures     INT;

	SELECT nb_heure_max INTO maxHeureProf FROM statut_enseignant se 
		LEFT JOIN enseignant e ON se.id = e.statut_enseignant_id 
		WHERE e.id = NEW.enseignant_id;

	SELECT COUNT(*)*2 INTO nbHeures FROM cour WHERE enseignant_id = NEW.enseignant_id;

	IF nbHeures >= maxHeureProf THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ce prof à un edt rempli! plus de place!';
	END IF;
END; //
DELIMITER ;