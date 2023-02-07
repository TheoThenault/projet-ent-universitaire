CREATE TRIGGER post_persist_etudiant AFTER INSERT ON etudiant
    FOR EACH ROW
BEGIN
    DECLARE groupFound INT DEFAULT 0;

    DECLARE groupType VARCHAR(10);
    DECLARE groupCmFoundId VARCHAR(10);
    DECLARE etuFormationId INT;

    -- Formation de l'étudiant :
    SET etuFormationId = (SELECT formation_id FROM etudiant WHERE id = NEW.id);

    -- CM
    SET groupType = 'CM';
    -- Trouver si un groupe de CM possède un étudiant avec la meme formation que le nouvel étudiant
    SELECT groupe_id INTO groupCmFoundId
    FROM groupe_etudiant ge
         LEFT JOIN groupe g ON ge.groupe_id = g.id -- join goupe
         LEFT JOIN etudiant e ON ge.etudiant_id = e.id -- join etudiant
         LEFT JOIN formation f ON e.formation_id = f.id -- join formation
    WHERE g.type = groupType AND f.id = NEW.formation_id; --  where formation = nouvel étudiant et groupe type = CM
END



-- PHPMYADMIN TEST
DELIMITER //
CREATE PROCEDURE test()
BEGIN
    DECLARE groupType VARCHAR(10);
    DECLARE groupCmFoundId VARCHAR(10);
    DECLARE etuFormationId INT;

    SELECT groupType, groupCmFoundId, etuFormationId;

    -- Formation de l'étudiant :
    SET etuFormationId = (SELECT formation_id FROM etudiant WHERE id = NEW.id);

    -- CM
    SET groupType = 'CM';
    -- Trouver si un groupe de CM possède un étudiant avec la meme formation que le nouvel étudiant
    SELECT groupe_id INTO groupCmFoundId
    FROM groupe_etudiant ge
             LEFT JOIN groupe g ON ge.groupe_id = g.id -- join goupe
             LEFT JOIN etudiant e ON ge.etudiant_id = e.id -- join etudiant
             LEFT JOIN formation f ON e.formation_id = f.id -- join formation
    WHERE g.type = groupType AND f.id = NEW.formation_id; --  where formation = nouvel étudiant et groupe type = CM
END; //

DELIMITER ;

CALL test();