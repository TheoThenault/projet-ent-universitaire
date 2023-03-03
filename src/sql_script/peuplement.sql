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
    VALUES  ('Mattieu'),   ('Jean'),    ('Pierre'),  ('Michel'),   ('Sasha'),    ('André'),   ('Philippe'), ('Olivier'), ('Bernard'),
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

				INSERT INTO projet_ent_universitaire.personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
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
    VALUES ("certifié et agrégé", 384, 384 * 2),
           ("attaché temporaire", 192, 192 * 2),
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
    VALUES  ("I",    1, "Droit privé et sciences criminelles"),
            ("I",    2, "Droit public"),
            ("I",    3, "Histoire du droit et des institutions"),
            ("I",    4, "Science politique"),
            ("II",   5, "Sciences économiques"),
            ("II",   6, "Sciences de gestion"),
            ("III",  7, "Sciences de language : linguistique et phonétique générales"),
            ("III",  8, "Langue et littératures anciennes"),
            ("III",  9, "Langue et littérature françaises"),
            ("III", 10, "Littératures comparées"),
            ("III", 11, "Langues et littératires anglaises et anglo-saxonnes"),
            ("III", 12, "Langues et littératures germanique et scandinaves"),
            ("III", 13, "Langues et littérature slaves"),
            ("III", 14, "Langues et littératures romanes : espagnol, italien, portugais, autres langues romanes"),
            ("III", 15, "Langues et littératures arabes, chinoises, japonaises, hébraïques, d\'autres domaines linguistiques"),
            ("IV",  16, "Psychologie, psychologie clinique, psychologie sociale"),
            ("IV",  17, "Philosophie"),
            ("IV",  18, "Architecture (ses théories et ses pratiques), art appliqués, arts plastiques, arts du spectacle, épistémologie des enseignements artistiques, esthétique, musicologie, musique, science de l\'art"),
            ("IV",  19, "Sociologie, démographie"),
            ("IV",  20, "Anthropologie biologique, ethnologie, préhistoire"),
            ("IV",  21, "Histoire, civilisation, archéologie et art des mondes anciens et médiévaux"),
            ("IV",  22, "Histoire et civilisations : histoire des mondes modernes, histoire des mondes modernes, histoire du monde contemporain, de l\'art, de la musique"),
            ("IV",  23, "Géographie physique, humaine, économique et régionale"),
            ("IV",  24, "Aménagement de l\'espace, urbanisme"),
            ("V",   25, "Mathématiques"),
            ("V",   26, "Mathématiques appliqués et applications des mathématiques"),
            ("V",   27, "Informatique"),
            ("VI",  28, "Millieux denses et matériaux"),
            ("VI",  29, "Constituants élémentaires"),
            ("VI",  30, "Millieurx dilués et optique"),
            ("VII", 31, "Chimie théorique, physique, analytique"),
            ("VII", 32, "Chimie organique, minérale, industrielle"),
            ("VII", 33, "Chimie des matériaux"),
            ("VIII",34, "Astronomie, astrophysique"),
            ("VIII",35, "Structure et évolution de la Terre et des autres planètes"),
            ("VIII",36, "Terre solide : géodynamique des enveloppes supérieures, paléo-blosphère"),
            ("VIII",37, "Méteorologie, océanographie physique et physique de l\'environnement"),
            ("IX",  60, "Mécanique, génie mécanique, génie civil"),
            ("IX",  61, "Génie informatique, automatique et traitement du signal"),
            ("IX",  62, "Energétique, génie des procédés"),
            ("IX",  63, "Génie électrique, électronique, photonique et systèmes"),
            ("X",   64, "Biochimie et biologie moléculaire"),
            ("X",   65, "Biologie cellulaire"),
            ("X",   66, "Physiologie"),
            ("X",   67, "Biologie des populations et écologie"),
            ("X",   68, "Biologie des organismes"),
            ("X",   69, "Neurosciences"),
            ("XII", 70, "Sciences de l\'éducation"),
            ("XII", 71, "Sciences de l\'information et de la communication"),
            ("XII", 72, "Épistémologie, histoire des sciences et des techniques"),
            ("XII", 73, "Cultures et langues régionales"),
            ("XII", 74, "Sciences et technique des activités physiques et sportives"),
            ("théologie", 76, "théologie catholique"),
            ("théologie", 77, "théologie protestante"),
            ("pharmacie", 85, "Sciences physico-chimiques et technologies pharmaceutiques"),
            ("pharmacie", 86, "Sciences du médicament"),
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
            roles = 'a:1:{i:0;s:9:"ROLE_ETUDIANT";}'
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
          ("Web avancé"),
          ("Math"),
          ("Geo"),
          ("Histoire"),
          ("physique"),
          ("Enseignement civique"),
          ("Arts plastiques"),
          ("Histoire des arts"),
          ("Education physique et sportive"),
          ("Bases de données et web"),
          ("Programmation des systèmes mobiles"),
          ("Connaissance de l\'entreprise"),
          ("Anglais pour les métiers de l\'informatique"),
          ("Génie mécanique"),
          ("Génie électrique"),
          ("Robotique"),
          ("Systèmes automatisés"),
          ("Systèmes automatisés"),
          ("Capteurs"),
          ("Industrie 4.0"),
          ("Electricité industrielle"),
          ("Economie de filière"),
          ("Oenologie - Analyse sensorielle"),
          ("Logistique"),
          ("Méthodologie"),
          ("Géographie viticole"),
          ("Marketing stratégique"),
          ("Marketing digital"),
          ("Etudes de marché"),
          ("Négociation"),
          ("Prospection internationale"),
          ("Anglais technique"),
          ("Projets tutoré"),
          ("Droit"),
          ("Sécurité des système"),
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


-- =============================
-- 11) peupleCours
-- =============================