DELIMITER ;
-- =============================
-- 1) peupleSalle
-- =============================

DROP PROCEDURE IF EXISTS peupleSalle;
DROP TEMPORARY TABLE IF EXISTS nomBatTemp;
DROP TEMPORARY TABLE IF EXISTS equipementTemp;
DROP TEMPORARY TABLE IF EXISTS capaciteTemp;

DELIMITER //
CREATE PROCEDURE peupleSalle()
BEGIN
	DECLARE i INT default 1; -- index pour la boucle
	DECLARE nbSalle INT default 51; -- nombre de salle
	DECLARE nomBat VARCHAR(255);
	DECLARE nom VARCHAR(255);
	DECLARE equipement VARCHAR(255);
	DECLARE capacite INT;

    CREATE TEMPORARY TABLE nomBatTemp(nom VARCHAR(255));
        -- insert rows to the table nomBat
    INSERT INTO nomBatTemp(nom)
    VALUES ("H01"),
           ("B02"),
           ("G03"),
           ("A04"),
           ("J05");

    CREATE TEMPORARY TABLE equipementTemp(nom VARCHAR(255));
            -- insert rows to the table equipement
    INSERT INTO equipementTemp(nom)
    VALUES ("Informatique"),
           ("Langue"),
           ("Chimie"),
           ("Physique");

    CREATE TEMPORARY TABLE capaciteTemp(cap INT);
    INSERT INTO capaciteTemp(cap)
    VALUES (20),
           (30),
           (40),
           (50),
           (100);

    WHILE i < nbSalle DO
        SELECT * INTO nomBat FROM nomBatTemp ORDER BY RAND() LIMIT 1;

        SET nom = CONCAT(nomBat, " ", i);

        SELECT * INTO equipement FROM equipementTemp ORDER BY RAND() LIMIT 1;
        SELECT * INTO capacite FROM capaciteTemp ORDER BY RAND() LIMIT 1;

        INSERT INTO salle(nom, batiment, equipement, capacite)
        VALUES(nom, nomBat, equipement, capacite );

        SET i = i + 1;

    END WHILE;
END; //

DELIMITER ;
CALL peupleSalle();

-- =============================
-- 2) peuplePersonne
-- =============================

DROP PROCEDURE IF EXISTS peuplePersonne;
DROP TEMPORARY TABLE IF EXISTS tableNom;
DROP TEMPORARY TABLE IF EXISTS tablePrenom;

DELIMITER //
CREATE PROCEDURE peuplePersonne()
BEGIN
    DECLARE lengthNOMS     INT DEFAULT 0;
    DECLARE counterNOMS    INT DEFAULT 0;
   	DECLARE lengthPRENOMS  INT DEFAULT 0;
    DECLARE counterPRENOMS INT DEFAULT 0;

    DECLARE _nom 		   VARCHAR(255);
    DECLARE _prenom		   VARCHAR(255);


    CREATE TEMPORARY TABLE tableNom(nom VARCHAR(255));
    INSERT INTO tableNom(nom)
    VALUES  ('Martin'),  ('Simon'),    ('Morel'),    ('Legrand'),  ('Perrin'),  ('Bernard'), ('Laurent'),  ('Girard'),  ('Garnier'),
            ('Morin'),   ('Dubois'),   ('Lefebvre'), ('Andre'),    ('Faure'),   ('Dupont'),  ('Fontaine'), ('Lopez'),   ('Robin'),
            ('Leroy'),   ('Durand'),   ('Petit'),    ('Bertrand'), ('Richard'), ('Poirier'), ('Rideau'),   ('Merlu'),   ('Duval'),
            ('Brun'),    ('Noel'),     ('Sins'),     ('Gourdin'),  ('Rhoades'), ('Melon'),   ('Guerin'),   ('Nicolas'), ('Leclerc'),
            ('Laporte'), ('Lemaitre'), ('Langlois'), ('Breton'),   ('Leroux'),  ('Charles'), ('Bonnet'),   ('Dubois'),  ('Deschamps'),
            ('Kenobi'),  ('Potter'),   ('Fujiwara'), ('Usumaki'),  ('Willis'),  ('Cruise');
    SELECT COUNT(*) INTO lengthNOMS FROM tableNom;

    CREATE TEMPORARY TABLE tablePrenom(nom VARCHAR(255));
    INSERT INTO tablePrenom(nom)
    VALUES  ('Mattieu'),   ('Jean'),    ('Pierre'),  ('Michel'),   ('Sasha'),    ('Andr??'),   ('Philippe'), ('Olivier'), ('Bernard'),
            ('Marie'),     ('Jeanne'),  ('Monique'), ('Isabelle'), ('Nathalie'), ('Sylvie'),  ('Suzanne'),  ('Abella'),  ('Lana'),
            ('Johnny'),    ('Camille'), ('Roger'),   ('Paul'),     ('Daniel'),   ('Henri'),   ('Nicolas'),  ('Manuel'),  ('Jacques'),
            ('Mia'),       ('Sarah'),   ('Rose'),    ('Jade'),     ('Emma'),     ('Angele'),  ('Lea'),      ('Manon'),   ('Lucie'),   ('Clara'),
            ('Alexandre'), ('Hugo'),    ('Lucas'),   ('Theo'),     ('Simon'),    ('Quentin'), ('Mathis'),   ('Paul'),    ('Bastien'),
            ('Amelie'),    ('Alicia'),  ('Carla'),   ('Elisa'),    ('Margaux'),  ('Melissa'), ('Lena'),     ('Elise'),   ('Ambre'),
            ('Bruce'),     ('Takumi'),  ('Harry'),   ('Obiwan'),   ('Anakin'),   ('Qui-Gon'), ('Tom');
    SELECT COUNT(*) INTO lengthPRENOMS FROM tablePrenom;

    WHILE counterNOMS < lengthNOMS DO
    		SET counterPRENOMS = 0;
		    WHILE counterPRENOMS < lengthPRENOMS DO
		    	SELECT nom INTO _nom    FROM tableNom    LIMIT counterNOMS,    1;
		    	SELECT nom INTO _prenom FROM tablePrenom LIMIT counterPRENOMS, 1;

				INSERT INTO personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
					VALUES(
						NULL,
						NULL,
						CONCAT(_prenom, '.', _nom, '@univ-poitiers.fr'),
						_nom,
						_prenom,
						'$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6',
						'a:1:{i:0;s:9:"ROLE_USER";}'
					);
        		SET counterPRENOMS = counterPRENOMS + 1;
		    END WHILE;
        SET counterNOMS = counterNOMS + 1;
    END WHILE;
END; //

DELIMITER ;
CALL peuplePersonne();

-- =============================
-- 3) peupleStatutEnseignant
-- =============================

DROP PROCEDURE IF EXISTS peupleStatutEnseignant;
DELIMITER //

CREATE PROCEDURE peupleStatutEnseignant()
BEGIN
    INSERT INTO statut_enseignant(nom, nb_heure_min, nb_heure_max)
    VALUES ("certifi?? et agr??g??", 384, 384 * 2),
           ("attach?? temporaire", 192, 192 * 2),
           ("maitre de conf", 192, 192 * 2),
           ("doctorant enseignant", 64, 64 * 2);
END; //

DELIMITER ;
CALL peupleStatutEnseignant();

-- =============================
-- 4) peupleSpecialite
-- =============================

DROP PROCEDURE IF EXISTS peupleSpecialite;
DELIMITER //

CREATE PROCEDURE peupleSpecialite()
BEGIN
    INSERT INTO specialite(groupe, section, nom)
    VALUES  ("I",    1, "Droit priv?? et sciences criminelles"),
            ("I",    2, "Droit public"),
            ("I",    3, "Histoire du droit et des institutions"),
            ("I",    4, "Science politique"),
            ("II",   5, "Sciences ??conomiques"),
            ("II",   6, "Sciences de gestion"),
            ("III",  7, "Sciences de language : linguistique et phon??tique g??n??rales"),
            ("III",  8, "Langue et litt??ratures anciennes"),
            ("III",  9, "Langue et litt??rature fran??aises"),
            ("III", 10, "Litt??ratures compar??es"),
            ("III", 11, "Langues et litt??ratires anglaises et anglo-saxonnes"),
            ("III", 12, "Langues et litt??ratures germanique et scandinaves"),
            ("III", 13, "Langues et litt??rature slaves"),
            ("III", 14, "Langues et litt??ratures romanes : espagnol, italien, portugais, autres langues romanes"),
            ("III", 15, "Langues et litt??ratures arabes, chinoises, japonaises, h??bra??ques, d\'autres domaines linguistiques"),
            ("IV",  16, "Psychologie, psychologie clinique, psychologie sociale"),
            ("IV",  17, "Philosophie"),
            ("IV",  18, "Architecture (ses th??ories et ses pratiques), art appliqu??s, arts plastiques, arts du spectacle, ??pist??mologie des enseignements artistiques, esth??tique, musicologie, musique, science de l\'art"),
            ("IV",  19, "Sociologie, d??mographie"),
            ("IV",  20, "Anthropologie biologique, ethnologie, pr??histoire"),
            ("IV",  21, "Histoire, civilisation, arch??ologie et art des mondes anciens et m??di??vaux"),
            ("IV",  22, "Histoire et civilisations : histoire des mondes modernes, histoire des mondes modernes, histoire du monde contemporain, de l\'art, de la musique"),
            ("IV",  23, "G??ographie physique, humaine, ??conomique et r??gionale"),
            ("IV",  24, "Am??nagement de l\'espace, urbanisme"),
            ("V",   25, "Math??matiques"),
            ("V",   26, "Math??matiques appliqu??s et applications des math??matiques"),
            ("V",   27, "Informatique"),
            ("VI",  28, "Millieux denses et mat??riaux"),
            ("VI",  29, "Constituants ??l??mentaires"),
            ("VI",  30, "Millieurx dilu??s et optique"),
            ("VII", 31, "Chimie th??orique, physique, analytique"),
            ("VII", 32, "Chimie organique, min??rale, industrielle"),
            ("VII", 33, "Chimie des mat??riaux"),
            ("VIII",34, "Astronomie, astrophysique"),
            ("VIII",35, "Structure et ??volution de la Terre et des autres plan??tes"),
            ("VIII",36, "Terre solide : g??odynamique des enveloppes sup??rieures, pal??o-blosph??re"),
            ("VIII",37, "M??teorologie, oc??anographie physique et physique de l\'environnement"),
            ("IX",  60, "M??canique, g??nie m??canique, g??nie civil"),
            ("IX",  61, "G??nie informatique, automatique et traitement du signal"),
            ("IX",  62, "Energ??tique, g??nie des proc??d??s"),
            ("IX",  63, "G??nie ??lectrique, ??lectronique, photonique et syst??mes"),
            ("X",   64, "Biochimie et biologie mol??culaire"),
            ("X",   65, "Biologie cellulaire"),
            ("X",   66, "Physiologie"),
            ("X",   67, "Biologie des populations et ??cologie"),
            ("X",   68, "Biologie des organismes"),
            ("X",   69, "Neurosciences"),
            ("XII", 70, "Sciences de l\'??ducation"),
            ("XII", 71, "Sciences de l\'information et de la communication"),
            ("XII", 72, "??pist??mologie, histoire des sciences et des techniques"),
            ("XII", 73, "Cultures et langues r??gionales"),
            ("XII", 74, "Sciences et technique des activit??s physiques et sportives"),
            ("th??ologie", 76, "th??ologie catholique"),
            ("th??ologie", 77, "th??ologie protestante"),
            ("pharmacie", 85, "Sciences physico-chimiques et technologies pharmaceutiques"),
            ("pharmacie", 86, "Sciences du m??dicament"),
            ("pharmacie", 87, "Sciences biologiques pharmaceutiques");
END; //

DELIMITER ;
CALL peupleSpecialite();

-- =============================
-- 5) peupleCursus
-- =============================

DROP PROCEDURE IF EXISTS peupleCursus;
DROP TEMPORARY TABLE IF EXISTS nomCursus;

DELIMITER //
CREATE PROCEDURE peupleCursus()
BEGIN
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    -- DECLARE nom VARCHAR(255);
    -- Temporary table creation for cursus name (Medecine", "Droit", "Informatique" ...)
    CREATE TEMPORARY TABLE nomCursus(nom VARCHAR(255));
    -- Insert rows to the temp nomCursus table
    INSERT INTO nomCursus(nom)
    VALUES("Medecine"),
          ("Droit"),
          ("Informatique"),
          ("Geo"),
          ("Histoire");
    -- Insert rows to the cursus table
    SELECT COUNT(*) FROM nomCursus INTO length;
    WHILE counter<length DO
        -- Insert 'Master'
        INSERT INTO cursus(nom, niveau)
        SELECT nom, 'Master' AS niveau FROM nomCursus LIMIT counter, 1;
        -- Insert 'License'
        INSERT INTO cursus(nom, niveau)
        SELECT nom, 'Licence' AS niveau FROM nomCursus LIMIT counter, 1;
        SET counter = counter + 1;
    END WHILE;
END; //

DELIMITER ;
CALL peupleCursus();

-- =============================
-- 6) peupleFormation
-- =============================

DROP PROCEDURE IF EXISTS peupleFormation;

DELIMITER //
CREATE PROCEDURE peupleFormation()
BEGIN
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    DECLARE isMaster INT DEFAULT 0;
    DECLARE cursusId INT DEFAULT 0;

    -- Insert rows to the formation table for each cursus row
    SELECT COUNT(*) INTO length FROM cursus ;
    WHILE counter < length DO
        -- Find the cursus id for each row
        SELECT cursus.id INTO cursusId FROM cursus LIMIT counter, 1;
        -- Check if the current cursus is a master or not
        SELECT COUNT(*) INTO isMaster FROM cursus WHERE cursus.niveau = "Master" AND cursus.id = cursusId;
        IF isMaster > 0 THEN -- Master found
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Master 1', 1, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Master 2', 2, cursusId);
        ELSE -- Licence
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 1', 1, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 2', 2, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence 3', 3, cursusId);
            INSERT INTO formation(nom, annee, cursus_id) VALUES ('Licence PRO', 3, cursusId);
        END IF;

        SET counter = counter + 1;
    END WHILE;
END; //

DELIMITER ;
CALL peupleFormation();

-- =============================
-- 7) peupleEnseignant
-- =============================
DELIMITER //

DROP PROCEDURE IF EXISTS peupleEnseignant //

CREATE PROCEDURE peupleEnseignant()
BEGIN
    DECLARE counter INT DEFAULT 1; -- La premiere formation est pour un prof sp??cifique
    DECLARE nbFormation INT;
    DECLARE lengthListePersonne INT;
    DECLARE statutEnseignantId INT;
    DECLARE specialiteId INT;

    -- Cr??ation des enseignants responsable
    SELECT COUNT(*) INTO nbFormation FROM formation;
    WHILE counter < nbFormation + 1 DO

        SELECT id INTO statutEnseignantId FROM statut_enseignant ORDER BY RAND() LIMIT 1;
        SELECT id INTO specialiteId FROM specialite ORDER BY RAND() LIMIT 1;

        INSERT INTO enseignant(statut_enseignant_id, section_id, responsable_formation_id)
            VALUES (
                statutEnseignantId,
                specialiteId,
                counter
            );

        UPDATE personne
        SET enseignant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:19:"ROLE_ENSEIGNANT_RES";}'
        WHERE id = counter;

        SET counter = counter + 1;
    END WHILE;

    -- Cr??ation des enseignants

    SELECT COUNT(*) FROM personne INTO lengthListePersonne;

    WHILE counter < (lengthListePersonne / 4) DO -- Mettre 1/4 de prof pour 3/4 ??tudiants

        SELECT id INTO statutEnseignantId FROM statut_enseignant ORDER BY RAND() LIMIT 1;
        SELECT id INTO specialiteId FROM specialite ORDER BY RAND() LIMIT 1;

        INSERT INTO enseignant(statut_enseignant_id, section_id)
            VALUES (
                statutEnseignantId,
                specialiteId
            );

        UPDATE personne
        SET enseignant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:15:"ROLE_ENSEIGNANT";}'
        WHERE id = counter;

        SET counter = counter + 1;
    END WHILE;
END; //

DELIMITER ;

CALL peupleEnseignant();

-- =============================
-- 8) peupleEtudiant
-- =============================

DROP PROCEDURE IF EXISTS peupleEtudiant;
DROP TEMPORARY TABLE IF EXISTS temp_personne_not_enseignant;
DROP TEMPORARY TABLE IF EXISTS temp_personne_not_enseignant;
DELIMITER //
CREATE PROCEDURE peupleEtudiant()
BEGIN
    DECLARE length  INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;
    DECLARE current_personne_id INT DEFAULT 0;
    DECLARE current_formation_id INT DEFAULT 0;

    -- Insert into a temp table all the Personne who are not Enseignant
    CREATE TEMPORARY TABLE temp_personne_not_enseignant AS
    SELECT * FROM personne WHERE enseignant_id IS NULL;

    SELECT COUNT(*) INTO length FROM temp_personne_not_enseignant ;
    -- ADD the students
    WHILE counter < length DO
        -- ===== ADD ETUDIANT =====
        -- Get the formation_id for the current student
        SELECT id INTO current_formation_id FROM formation ORDER BY RAND() LIMIT 1;

        -- Insert the current student into the etudiant table
        INSERT INTO etudiant(formation_id) VALUES(current_formation_id);

        -- ===== UPDATE PERSONNE =====
        -- Get a personne_id for the current student ( offset = counter, limit = 1 )
        SELECT id INTO current_personne_id FROM temp_personne_not_enseignant LIMIT counter, 1;

        -- Update personne
        UPDATE personne
        SET etudiant_id = LAST_INSERT_ID(),
            roles = 'a:1:{i:0;s:13:"ROLE_ETUDIANT";}'
        WHERE id = current_personne_id;

        -- Increment the counter
        SET counter = counter + 1;
    END WHILE;
END; //
DELIMITER ;
CALL peupleEtudiant();

-- =============================
-- 9) peupleUE
-- =============================

DROP PROCEDURE IF EXISTS peupleUe;
DROP TEMPORARY TABLE IF EXISTS nomUe;
DROP TEMPORARY TABLE IF EXISTS volumeHoraireUe;

DELIMITER //
CREATE PROCEDURE peupleUe()
BEGIN
    DECLARE lengthFormation  INT DEFAULT 0;
    DECLARE counterFormation  INT DEFAULT 0;
    DECLARE counterUe  INT DEFAULT 0;

    DECLARE current_specialite_id INT DEFAULT 0;
    DECLARE current_formation_id INT DEFAULT 0;
    DECLARE current_name VARCHAR(255);
    DECLARE current_volume_horaire INT DEFAULT 0;

    -- Temporary table creation for ue name
    CREATE TEMPORARY TABLE nomUe(nom VARCHAR(255));
    -- Insert rows to the temp nomUe table
    INSERT INTO nomUe(nom)
    VALUES("POO"),
          ("Algorithmes"),
          ("Anglais"),
          ("Droits"),
          ("Web"),
          ("Web avanc??"),
          ("Math"),
          ("Geo"),
          ("Histoire"),
          ("physique"),
          ("Enseignement civique"),
          ("Arts plastiques"),
          ("Histoire des arts"),
          ("Education physique et sportive"),
          ("Bases de donn??es et web"),
          ("Programmation des syst??mes mobiles"),
          ("Connaissance de l\'entreprise"),
          ("Anglais pour les m??tiers de l\'informatique"),
          ("G??nie m??canique"),
          ("G??nie ??lectrique"),
          ("Robotique"),
          ("Syst??mes automatis??s"),
          ("Syst??mes automatis??s"),
          ("Capteurs"),
          ("Industrie 4.0"),
          ("Electricit?? industrielle"),
          ("Economie de fili??re"),
          ("Oenologie - Analyse sensorielle"),
          ("Logistique"),
          ("M??thodologie"),
          ("G??ographie viticole"),
          ("Marketing strat??gique"),
          ("Marketing digital"),
          ("Etudes de march??"),
          ("N??gociation"),
          ("Prospection internationale"),
          ("Anglais technique"),
          ("Projets tutor??"),
          ("Droit"),
          ("S??curit?? des syst??me"),
          ("Psychologie ");

    -- Temporary table creation for ue volume horaire
    CREATE TEMPORARY TABLE volumeHoraireUe(h INT);
    -- Insert rows to the temp volumeHoraireUe table
    INSERT INTO volumeHoraireUe(h)
    VALUES(16),
          (32),
          (64),
          (128),
          (256);

    SELECT COUNT(*) INTO lengthFormation FROM formation ;
    -- ADD 15ue per formation
    WHILE counterFormation < lengthFormation DO
        -- Get the current_formation_id for the current ue ( offset = counter, limit = 1 )
        SELECT id INTO current_formation_id FROM formation LIMIT counterFormation, 1;

        SET counterUe = 0;
        WHILE counterUe < 15 DO
            -- get a random specialite
            SELECT id INTO current_specialite_id FROM specialite ORDER BY RAND() LIMIT 1;
            -- get the current name
            SELECT nom INTO current_name FROM nomUe ORDER BY RAND() LIMIT 1;
            -- get a random volume horaire
            SELECT h INTO current_volume_horaire FROM volumeHoraireUe ORDER BY RAND() LIMIT 1;

            INSERT INTO ue(specialite_id, formation_id, nom, volume_horaire)
                VALUES(
                    current_specialite_id,
                    current_formation_id,
                    current_name,
                    current_volume_horaire
                );
            SET counterUe = counterUe + 1;
        END WHILE;
        SET counterFormation = counterFormation + 1;
    END WHILE;
END; //

DELIMITER ;
CALL peupleUe();

-- =============================
-- 10) peupleUeValide
-- =============================

DROP PROCEDURE IF EXISTS peupleUeValide;
DROP TEMPORARY TABLE IF EXISTS ueTmp;

DELIMITER //
CREATE PROCEDURE peupleUeValide()
BEGIN
    DECLARE counter INT DEFAULT 0;
    DECLARE etuId INT;
    DECLARE ueId INT;

    WHILE counter < 1250 DO -- 1250 est le nombre d'ue valide

        -- prend 1 random dans la liste des ??tudiants
        SELECT id INTO etuId FROM etudiant ORDER BY RAND() LIMIT 1;

        -- r??ccup les ue de l'??tudiant pour les mettre dans une table temporaire
        CREATE TEMPORARY TABLE ueTmp(id INT) AS
        SELECT u.id
        FROM ue u
            LEFT JOIN formation f
                ON f.id = u.formation_id
            LEFT JOIN etudiant e
                ON e.formation_id = f.id
        WHERE e.id = etuId;

        -- prend 1 ue random
        SELECT id INTO ueId FROM ueTmp ORDER BY RAND() LIMIT 1;

        -- valide cette eu(donc mettre id etudiant et id de l'ue)
        INSERT IGNORE INTO etudiant_ue(etudiant_id, ue_id) -- Le IGNORE permet d'ignorer un insertion si elle exsite deja
            VALUES(etuId, ueId);

        DROP TABLE ueTmp;

        SET counter = counter + 1;
    END WHILE;

END; //

DELIMITER ;
CALL peupleUeValide();

-- =============================
-- 11) peupleCours
-- =============================
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
		-- Cr??neau au hasard
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


    	-- Tant qu'on a pas trouver un groupe qui est libre a ce cr??neau
    	`findGroup`: WHILE groupeCoursAtCreneau > 0 OR groupeEtudiantsNombreCoursAtCreneau > 0 DO
    		-- Groupe au hasard
			SELECT id INTO groupeID FROM groupe ORDER BY RAND() LIMIT 1;
			-- Compte le nombre de cours de ce groupe a ce cr??neau
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

			-- On r??cup??re la formation ?? laquelle appartient ce groupe via un ??tudiant
			-- (Les groupes ne peuvent pas etre vide, et tout les ??tudiant d'un groupe sont dans la m??me formation)
            -- SELECT groupeID;
			SELECT formation_id INTO groupeFormationID FROM etudiant e
				LEFT JOIN groupe_etudiant ge ON ge.etudiant_id = e.id
				WHERE ge.groupe_id = groupeID LIMIT 1;

			SELECT COUNT(*) INTO groupeNombreEleve FROM groupe_etudiant ge WHERE ge.groupe_id = groupeID;

	    	-- Pareil pour prof
	    	`findProf`: WHILE profCoursAtCreneau > 0 OR profHeuresAffecter > (profHeuresMax-2) DO
	    		-- prof au hasard
				SELECT id INTO profID FROM enseignant ORDER BY RAND() LIMIT 1;
				-- Compte le nombre de cours de ce prof a ce cr??neau
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
					-- Compte le nombre de cours de ce salle a ce cr??neau
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


-- =============================
-- 12) Add specific Users
-- =============================

INSERT INTO personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
VALUES
    -- cr??ation scolarit??
    (NULL, NULL, 'scolarite@univ-poitiers.fr', 'User', 'Scolarit??', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:14:"ROLE_SCOLARITE";}'),
    -- cr??ation rh
    (NULL, NULL, 'rh@univ-poitiers.fr', 'User', 'Rh', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:7:"ROLE_RH";}'),
    -- cr??ation admin
    (NULL, NULL, 'admin@univ-poitiers.fr', 'User', 'Admin', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:10:"ROLE_ADMIN";}');

-- Update personne
UPDATE personne
SET email = 'etudiant@univ-poitiers.fr',
    prenom = 'Etudiant',
    nom = 'User'
WHERE roles = 'a:1:{i:0;s:13:"ROLE_ETUDIANT";}'
LIMIT 1;

UPDATE personne
SET email = 'enseignant@univ-poitiers.fr',
    prenom = 'Enseignant',
    nom = 'User'
WHERE roles = 'a:1:{i:0;s:15:"ROLE_ENSEIGNANT";}'
LIMIT 1;

UPDATE personne
SET email = 'enseignant.res@univ-poitiers.fr',
    prenom = 'EnseignantRes',
    nom = 'User'
WHERE roles = 'a:1:{i:0;s:19:"ROLE_ENSEIGNANT_RES";}'
LIMIT 1;