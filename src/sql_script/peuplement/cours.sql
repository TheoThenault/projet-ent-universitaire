DELIMITER //
DROP PROCEDURE IF EXISTS peupleCours; //

CREATE PROCEDURE peupleCours()
BEGIN
	DECLARE nombreCours  INT DEFAULT  1000;
	DECLARE counterCours INT DEFAULT  0;
	DECLARE ueID 		 INT DEFAULT -1;
	DECLARE salleID      INT DEFAULT -1;
	DECLARE profID       INT DEFAULT -1;
	DECLARE groupeID 	 INT DEFAULT -1;
	DECLARE creneau 	 INT DEFAULT -1;

	DECLARE groupeFormationID INT DEFAULT -1;
	DECLARE groupeCoursAtCreneau INT DEFAULT 1;
	DECLARE groupeEtudiantsNombreCoursAtCreneau INT DEFAULT 1;
	DECLARE groupeNombreEleve INT DEFAULT 1;

	DECLARE profCoursAtCreneau INT DEFAULT 1;
	DECLARE profHeuresMax      INT DEFAULT 0;
	DECLARE profHeuresAffecter INT DEFAULT 0;

	DECLARE salleCoursAtCreneau INT DEFAULT 1;
	DECLARE salleTaille			INT DEFAULT 0;

	DECLARE tries INT DEFAULT 0;
	DECLARE maxTries INT DEFAULT 15;

    WHILE counterCours < nombreCours DO
		-- Créneau au hasard
    	SET creneau = RAND()*600;	
    
    	SET groupeCoursAtCreneau = 1;
		SET profCoursAtCreneau   = 1;
		SET salleCoursAtCreneau  = 1;
		SET groupeEtudiantsNombreCoursAtCreneau  = 1;
    	SET profHeuresMax = 1;
		SET profHeuresAffecter   = 1;
		SET tries = 0;
		SET groupeNombreEleve = 1;
		SET salleTaille = 0;
		SET groupeFormationID = -1;
	
	
    	-- Tant qu'on a pas trouver un groupe qui est libre a ce créneau
    	`findGroup`: WHILE groupeCoursAtCreneau > 0 OR groupeEtudiantsNombreCoursAtCreneau > 0 DO
    		-- Groupe au hasard
			SELECT id INTO groupeID FROM groupe ORDER BY RAND() LIMIT 1;
			-- Compte le nombre de cours de ce groupe a ce créneau
			SELECT COUNT(*) INTO groupeCoursAtCreneau FROM cour c WHERE c.groupe_id = groupeID AND c.creneau = creneau;

			SELECT COUNT(*) INTO groupeEtudiantsNombreCoursAtCreneau FROM cour c 
				WHERE c.groupe_id IN 
					(SELECT groupe_id  FROM groupe_etudiant ge 
						WHERE ge.etudiant_id IN 
							(SELECT etudiant_id FROM cour c LEFT JOIN groupe_etudiant ge2 on ge2.groupe_id = groupeID) 
						GROUP BY groupe_id) 
				AND c.creneau = creneau;
		
			-- INSERT INTO res(creneau, groupeID, nbCgrEaCR) VALUES (creneau, groupeID, groupeEtudiantsNombreCoursAtCreneau);
			
			SET tries = tries + 1;
			IF tries >= maxTries THEN
				-- SELECT 'findGroup' AS '';
				LEAVE `findGroup`;
			END IF;
		END WHILE `findGroup`;

		IF tries < maxTries THEN
			SET tries = 0;
		
			-- On récupère la formation à laquelle appartient ce groupe via un étudiant
			-- (Les groupes ne peuvent pas etre vide, et tout les étudiant d'un groupe sont dans la même formation) 
            -- SELECT groupeID;
			SELECT formation_id INTO groupeFormationID FROM etudiant e 
				LEFT JOIN groupe_etudiant ge ON ge.etudiant_id = e.id 
				WHERE ge.groupe_id = groupeID LIMIT 1;
			
			SELECT COUNT(*) INTO groupeNombreEleve FROM groupe_etudiant ge WHERE ge.groupe_id = groupeID;
			
	    	-- Pareil pour prof
	    	`findProf`: WHILE profCoursAtCreneau > 0 OR profHeuresAffecter > (profHeuresMax-2) DO
	    		-- prof au hasard
				SELECT id INTO profID FROM enseignant ORDER BY RAND() LIMIT 1;
				-- Compte le nombre de cours de ce prof a ce créneau
				SELECT COUNT(*) INTO profCoursAtCreneau FROM cour c WHERE c.enseignant_id  = profID AND c.creneau = creneau;
				SELECT nb_heure_max INTO profHeuresMax FROM statut_enseignant se LEFT JOIN enseignant e ON se.id = e.statut_enseignant_id WHERE e.id = profID;
		    	SELECT COUNT(*)*2 INTO profHeuresAffecter FROM cour WHERE enseignant_id = profID;
		    	SET tries = tries + 1;
    			IF tries >= maxTries THEN
	    			-- SELECT 'findProf' AS '';
					LEAVE `findProf`;
				END IF;
			END WHILE `findProf`;
	
			IF tries < maxTries THEN
				SET tries = 0;
		
		    	-- Pareil pour salle
		    	`findSalle`: WHILE salleCoursAtCreneau > 0 OR salleTaille < groupeNombreEleve DO
		    		-- prof au hasard
					SELECT id INTO salleID FROM salle ORDER BY RAND() LIMIT 1;
					-- Compte le nombre de cours de ce salle a ce créneau
					SELECT COUNT(*) INTO salleCoursAtCreneau FROM cour c WHERE c.salle_id = salleID AND c.creneau = creneau;
					SELECT capacite INTO salleTaille FROM salle s WHERE s.id = salleID;
					SET tries = tries + 1;
					IF tries >= maxTries THEN
						-- SELECT 'findSalle' AS '';
						LEAVE `findSalle`;
					END IF;
				END WHILE `findSalle`;
				
				IF tries < maxTries THEN
					-- prends un UE au hasard de la formation
					SELECT id INTO ueID FROM ue u WHERE u.formation_id = groupeFormationID ORDER BY RAND() LIMIT 1;
					
					-- SELECT * FROM groupe ORDER BY RAND() LIMIT 1;
					INSERT INTO cour(ue_id, salle_id, enseignant_id, groupe_id, creneau)VALUES(ueID, salleID, profID, groupeID, creneau);
				END IF;
			END IF;
		END IF;
		-- SELECT creneau, groupeID, groupeCoursAtCreneau, groupeEtudiantsNombreCoursAtCreneau, groupeFormationID, profID, salleID, ueID;
        -- Increment the counter
        SET counterCours = counterCours + 1;
    END WHILE;
END; //

CALL peupleCours();//


DELIMITER ;
