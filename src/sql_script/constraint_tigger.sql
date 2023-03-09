-- ========== CONSTRAINT ==========

ALTER TABLE cour DROP CONSTRAINT IF EXISTS check_creneau;
ALTER TABLE cour
ADD CONSTRAINT check_creneau CHECK (creneau >= 0 AND creneau <= 600);

ALTER TABLE ue DROP CONSTRAINT IF EXISTS check_ue_volume_horaire;
ALTER TABLE ue
ADD CONSTRAINT check_ue_volume_horaire CHECK (volume_horaire >= 0 AND volume_horaire <= 600);

ALTER TABLE cursus DROP CONSTRAINT IF EXISTS cursus_nom_length_check;
ALTER TABLE cursus
ADD CONSTRAINT cursus_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 128);

ALTER TABLE cursus DROP CONSTRAINT IF EXISTS chk_cursus_level;
ALTER TABLE cursus
ADD CONSTRAINT chk_cursus_level CHECK (niveau IN ('Master', 'Licence'));

ALTER TABLE formation DROP CONSTRAINT IF EXISTS formation_nom_length_check;
ALTER TABLE formation
ADD CONSTRAINT formation_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE formation DROP CONSTRAINT IF EXISTS formation_annee_check;
ALTER TABLE formation
ADD CONSTRAINT formation_annee_check
CHECK (annee >= 0 AND annee <= 10);

ALTER TABLE groupe DROP CONSTRAINT IF EXISTS chk_groupe_type;
ALTER TABLE groupe
ADD CONSTRAINT chk_groupe_type
CHECK (type IN ('TD', 'TP', 'CM'));

ALTER TABLE personne DROP CONSTRAINT IF EXISTS email_format;
ALTER TABLE personne
ADD CONSTRAINT email_format CHECK (email LIKE '%@univ-poitiers.fr');

ALTER TABLE personne DROP CONSTRAINT IF EXISTS personne_nom_length_check;
ALTER TABLE personne
ADD CONSTRAINT personne_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE personne DROP CONSTRAINT IF EXISTS personne_prenom_length_check;
ALTER TABLE personne
ADD CONSTRAINT personne_prenom_length_check CHECK (char_length(prenom) >= 2 AND char_length(prenom) <= 64);

ALTER TABLE salle DROP CONSTRAINT IF EXISTS salle_nom_length_check;
ALTER TABLE salle
ADD CONSTRAINT salle_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE salle DROP CONSTRAINT IF EXISTS salle_batiment_length_check;
ALTER TABLE salle
ADD CONSTRAINT salle_batiment_length_check CHECK (char_length(batiment) >= 2 AND char_length(batiment) <= 64);

ALTER TABLE salle DROP CONSTRAINT IF EXISTS salle_equipement_length_check;
ALTER TABLE salle
ADD CONSTRAINT salle_equipement_length_check CHECK (char_length(equipement) >= 2 AND char_length(equipement) <= 128);

ALTER TABLE salle DROP CONSTRAINT IF EXISTS check_capacite;
ALTER TABLE salle
ADD CONSTRAINT check_capacite CHECK (capacite >= 0 AND capacite <= 999);

ALTER TABLE specialite DROP CONSTRAINT IF EXISTS specialite_nom_length_check;
ALTER TABLE specialite
ADD CONSTRAINT specialite_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE statut_enseignant DROP CONSTRAINT IF EXISTS statut_enseignant_nom_length_check;
ALTER TABLE statut_enseignant
ADD CONSTRAINT statut_enseignant_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE statut_enseignant DROP CONSTRAINT IF EXISTS check_nb_heure_min;
ALTER TABLE statut_enseignant
ADD CONSTRAINT check_nb_heure_min CHECK (nb_heure_min >= 0 AND nb_heure_min <= 9999);

ALTER TABLE statut_enseignant DROP CONSTRAINT IF EXISTS check_nb_heure_max;
ALTER TABLE statut_enseignant
ADD CONSTRAINT check_nb_heure_max CHECK (nb_heure_max >= 0 AND nb_heure_max <= 9999);

ALTER TABLE ue DROP CONSTRAINT IF EXISTS ue_nom_length_check;
ALTER TABLE ue
ADD CONSTRAINT ue_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE ue DROP CONSTRAINT IF EXISTS check_volume_horaire;
ALTER TABLE ue
ADD CONSTRAINT check_volume_horaire CHECK (volume_horaire >= 0 AND volume_horaire <= 9999);


-- ========== TRIGGER ==========

-- 1) trigger post_persist_etudiant
DELIMITER //
DROP TRIGGER IF EXISTS post_persist_etudiant //

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

-- 2) trigger pre_persist_cour_enseignant

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

-- 3) trigger pre_persist_cour_etudiant

DELIMITER //
DROP TRIGGER IF EXISTS pre_persist_cour_etudiant //

CREATE TRIGGER pre_persist_cour_etudiant BEFORE INSERT ON cour
    FOR EACH ROW
BEGIN
    DECLARE etuFound INT DEFAULT -1;

    -- récupérer tout les cours des étudiant qui sont dans le groupe du nouveau cour au même creneau
	SELECT count(*) into etuFound from cour c 
		where c.groupe_id IN 
			(SELECT groupe_id  from groupe_etudiant ge 
				where ge.etudiant_id IN 
					(SELECT etudiant_id from cour c left join groupe_etudiant ge2 on ge2.groupe_id = NEW.groupe_id) 
				group by groupe_id) 
		and c.creneau = NEW.creneau;

    IF etuFound > 0 THEN -- drop error
        SIGNAL SQLSTATE '46001'
        SET MESSAGE_TEXT = 'Impossible d''ajouter un etudiant qui a déja un cour sur ce créneau.';
    END IF;
END; //
DELIMITER ;

-- 4) trigger pre_persist_personne_mail

DELIMITER //
DROP TRIGGER IF EXISTS pre_persist_personne_mail //

CREATE TRIGGER pre_persist_personne_mail BEFORE INSERT ON personne
    FOR EACH ROW
BEGIN
    DECLARE nbPersonneSameMail INT DEFAULT 0;
    DECLARE myRegex VARCHAR(255);
    -- Compte les personnes ayant le meme mail que la nouvelle personne
    SELECT COUNT(*) INTO nbPersonneSameMail FROM personne
    WHERE personne.email REGEXP CONCAT('^',NEW.prenom,'\.',NEW.nom, '+[0-9]*@univ-poitiers\.fr$');
    IF nbPersonneSameMail > 0 THEN -- change mail
        SET NEW.email = (CONCAT(NEW.prenom,'.',NEW.nom, nbPersonneSameMail, '@univ-poitiers.fr' ));
    END IF;
END; //
DELIMITER ;

-- 5) pre_persist_salle_occupe

DELIMITER //
DROP TRIGGER IF EXISTS pre_persist_salle_occupe //

CREATE TRIGGER pre_persist_salle_occupe BEFORE INSERT ON cour -- verifier si deja un cour dans cette salle avant insert
    FOR EACH ROW
begin
    declare nbSalleCour int default 0;
	-- Compte le nombre de cour dans une salle sur le meme creneau
    select COUNT(*) into nbSalleCour from cour
    where cour.creneau = new.creneau AND cour.salle_id = new.salle_id;
    if nbSalleCour > 0 then -- drop une erreur car deja un cour dans la meme salle sur le meme creneau
		SIGNAL SQLSTATE '47000'
        SET MESSAGE_TEXT = 'Salle deja occupée.';
    end if;
end; //
DELIMITER ;

-- 6) trigger verifAffectationMaxProf

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

-- 7)trigger VerifSalleQuandCreationCour

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
		SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'La salle ne peut pas acceuilir autant d élèves!';
	END IF;
END; //
DELIMITER ;

-- 8) trigger VerifUEQuandValidation
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
		SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'Cet élève n est pas dans cet UE! pas de validation possible!';
	END IF;
END; //
DELIMITER ;