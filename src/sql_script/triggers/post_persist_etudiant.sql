DELIMITER //
DROP TRIGGER IF EXISTS projet_ent_universitaire.post_persist_etudiant //

CREATE TRIGGER post_persist_etudiant AFTER INSERT ON etudiant
    FOR EACH ROW
BEGIN
    DECLARE groupFound INT DEFAULT -1;
  	DECLARE groupType VARCHAR(2);
    
    -- CM
    SET groupType = 'CM';
    -- Trouver si un groupe de CM possède un étudiant avec la meme formation que le nouvel étudiant
    SELECT groupe_id INTO groupFound FROM groupe_etudiant ge
         LEFT JOIN groupe g ON ge.groupe_id = g.id -- join goupe
         LEFT JOIN etudiant e ON ge.etudiant_id = e.id -- join etudiant
         LEFT JOIN formation f ON e.formation_id = f.id -- join formation
    		WHERE g.type = groupType AND f.id = NEW.formation_id -- formation = nouvel étudiant et groupe type = CM
    		GROUP BY groupe_id LIMIT 1;	 -- Groupe par groupes et compare l'éffectif de celui-ci avec la limite
    IF groupFound = -1 THEN -- AUCUN GROUPES
    	INSERT INTO groupe(type) VALUES ('CM');
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (LAST_INSERT_ID(), NEW.id);
    ELSE -- Il y a un groupe
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (groupFound, NEW.id);
    END IF;
   
   -- TP
    SET groupType = 'TP';
    SET groupFound = -1;
    -- Trouver si un groupe de CM possède un étudiant avec la meme formation que le nouvel étudiant
    SELECT groupe_id INTO groupFound FROM groupe_etudiant ge
         LEFT JOIN groupe g ON ge.groupe_id = g.id -- join goupe
         LEFT JOIN etudiant e ON ge.etudiant_id = e.id -- join etudiant
         LEFT JOIN formation f ON e.formation_id = f.id -- join formation
    		WHERE g.type = groupType AND f.id = NEW.formation_id -- formation = nouvel étudiant et groupe type
    		GROUP BY groupe_id HAVING COUNT(*) < 20 LIMIT 1;	 -- Groupe par groupes et compare l'éffectif de celui-ci avec la limite
    IF groupFound = -1 THEN -- AUCUN GROUPES
    	INSERT INTO groupe(type) VALUES ('TP');
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (LAST_INSERT_ID(), NEW.id);
    ELSE -- Il y a un groupe,
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (groupFound, NEW.id);
    END IF;
   
   
      -- TD
    SET groupType = 'TD';
    SET groupFound = -1;
    SELECT groupe_id INTO groupFound FROM groupe_etudiant ge
         LEFT JOIN groupe g ON ge.groupe_id = g.id -- join goupe
         LEFT JOIN etudiant e ON ge.etudiant_id = e.id -- join etudiant
         LEFT JOIN formation f ON e.formation_id = f.id -- join formation
    		WHERE g.type = groupType AND f.id = NEW.formation_id -- formation = nouvel étudiant et groupe type
    		GROUP BY groupe_id HAVING COUNT(*) < 40 LIMIT 1;	 -- Groupe par groupes et compare l'éffectif de celui-ci avec la limite
    IF groupFound = -1 THEN -- AUCUN GROUPES
    	INSERT INTO groupe(type) VALUES ('TD');
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (LAST_INSERT_ID(), NEW.id);
    ELSE -- Il y a un groupe,
		INSERT INTO groupe_etudiant(groupe_id, etudiant_id) VALUES (groupFound, NEW.id);
    END IF;
    
END; //
DELIMITER ;
