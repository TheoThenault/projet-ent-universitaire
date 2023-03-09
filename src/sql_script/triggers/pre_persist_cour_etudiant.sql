DELIMITER //
DROP TRIGGER IF EXISTS pre_persist_cour_etudiant //

CREATE TRIGGER pre_persist_cour_etudiant BEFORE INSERT ON cour
    FOR EACH ROW
BEGIN
    DECLARE etuFound INT DEFAULT -1;

    -- récupérer tout les cours des étudiant qui ont le meme créneau que le nouveau cour
    SELECT COUNT(*) INTO etuFound FROM etudiant e
        LEFT JOIN groupe_etudiant ge ON e.id = ge.etudiant_id
        LEFT JOIN cour c ON ge.groupe_id = c.groupe_id
    WHERE c.creneau = NEW.creneau;

    IF etuFound > 0 THEN -- drop error
        SIGNAL SQLSTATE '46001'
        SET MESSAGE_TEXT = 'Impossible d''ajouter un etudiant qui a déja un cour sur ce créneau.';
    END IF;
END; //
DELIMITER ;
