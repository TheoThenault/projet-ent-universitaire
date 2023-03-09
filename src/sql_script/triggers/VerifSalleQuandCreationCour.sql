DELIMITER //
DROP TRIGGER IF EXISTS VerifSalleQuandCreationCour //

CREATE TRIGGER VerifSalleQuandCreationCour BEFORE INSERT ON cour
    FOR EACH ROW
BEGIN
	DECLARE nbEleveGrp  INT DEFAULT 10000;
	DECLARE placesSalle INT DEFAULT -1;

	SELECT COUNT(*) INTO nbEleveGrp FROM groupe_etudiant ge 
		WHERE ge.groupe_id = NEW.groupe_id;
	
	SELECT capacite INTO placesSalle FROM salle s 
		WHERE s.id = NEW.salle_id;
	
	IF placesSalle < nbEleveGrp THEN
		SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'La salle ne peut pas acceuilir autant d\'élèves!';
	END IF;	
END; //
DELIMITER ;