DELIMITER //
DROP TRIGGER IF EXISTS pre_persist_cour_enseignant //

CREATE TRIGGER pre_persist_cour_enseignant BEFORE INSERT ON cour
    FOR EACH ROW
BEGIN
    DECLARE nbCoursSameEnseignantAndCrenneau INT DEFAULT 0;
    -- Compte les cours ayant le meme enseignant et créneau que le nouveau cour
    SELECT COUNT(*) INTO nbCoursSameEnseignantAndCrenneau FROM cour
    WHERE cour.enseignant_id = NEW.enseignant_id AND cour.creneau = NEW.creneau ;
    IF nbCoursSameEnseignantAndCrenneau > 0 THEN -- drop error
        SIGNAL SQLSTATE '46000'
        SET MESSAGE_TEXT = 'Impossible d''ajouter un cour avec le même enseignant et créneau qu''un autre cour existant.';
    END IF;
END; //
DELIMITER ;
