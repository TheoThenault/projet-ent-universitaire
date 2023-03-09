
-- Script de creation pour une base de données MYSQL utf8mb_unicode_ci

drop table if exists cour;
drop table if exists salle;
drop table if exists personne;
drop table if exists enseignant;
drop table if exists statut_enseignant;
drop table if exists groupe_etudiant;
drop table if exists etudiant_ue;
drop table if exists etudiant;
drop table if exists groupe;
drop table if exists ue;
drop table if exists specialite;
drop table if exists formation;
drop table if exists cursus;

create table cursus
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    niveau varchar(255) not null
);

create table formation
(
    id int auto_increment
        primary key,
    cursus_id int not null,
    nom varchar(255) not null,
    annee int not null,
    constraint FK_formation_cursus_id
        foreign key (cursus_id) references cursus (id)
);

create table etudiant
(
    id int auto_increment
        primary key,
    formation_id int null,
    constraint FK_etu_formation_id
        foreign key (formation_id) references formation (id)
);

create index IDX_etu_formation_id
    on etudiant (formation_id);

create index IDX_formation_cursus_id
    on formation (cursus_id);

create table groupe
(
    id int auto_increment
        primary key,
    type varchar(2) not null
);

create table groupe_etudiant
(
    groupe_id int not null,
    etudiant_id int not null,
    primary key (groupe_id, etudiant_id),
    constraint FK_ge_groupe_id
        foreign key (groupe_id) references groupe (id)
            on delete cascade,
    constraint FK_ge_etudiant_id
        foreign key (etudiant_id) references etudiant (id)
            on delete cascade
);

create table salle
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    batiment varchar(255) not null,
    equipement varchar(255) null,
    capacite int not null
);

create table specialite
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    section int not null,
    groupe varchar(255) not null
);

create table statut_enseignant
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    nb_heure_min int not null,
    nb_heure_max int not null
);

create table enseignant
(
    id int auto_increment
        primary key,
    statut_enseignant_id int null,
    section_id int null,
    responsable_formation_id int null,
    constraint UNIQ_enseignant_responsable_formation_id
        unique (responsable_formation_id),
    constraint FK_enseignant_statut_enseignant_id
        foreign key (statut_enseignant_id) references statut_enseignant (id),
    constraint FK_enseignant_responsable_formation_id
        foreign key (responsable_formation_id) references formation (id),
    constraint FK_section_id_section_id
        foreign key (section_id) references specialite (id)
);

create table personne
(
    id int auto_increment
        primary key,
    etudiant_id int null,
    enseignant_id int null,
    email varchar(255) not null,
    nom varchar(255) not null,
    prenom varchar(255) not null,
    password varchar(255) null,
    roles longtext null comment '(DC2Type:array)',
    constraint UNIQ_personne_etudiant_id
        unique (etudiant_id),
    constraint UNIQ_personne_enseignant_id
        unique (enseignant_id),
    constraint FK_personne_etudiant_id
        foreign key (etudiant_id) references etudiant (id),
    constraint FK_personne_enseignant_id
        foreign key (enseignant_id) references enseignant (id)
);

create table ue
(
    id int auto_increment
        primary key,
    specialite_id int not null,
    formation_id int null,
    nom varchar(255) not null,
    volume_horaire int null,
    constraint FK_ue_specialite_id
        foreign key (specialite_id) references specialite (id),
    constraint FK_ue_formation_id
        foreign key (formation_id) references formation (id)
);

create table cour
(
    id int auto_increment
        primary key,
    ue_id int not null,
    salle_id int not null,
    enseignant_id int not null,
    groupe_id int null,
    creneau int not null,
    constraint FK_cour_ue_id
        foreign key (ue_id) references ue (id),
    constraint FK_cour_groupe_id
        foreign key (groupe_id) references groupe (id),
    constraint FK_cour_salle_id
        foreign key (salle_id) references salle (id),
    constraint FK_cour_enseignant_id
        foreign key (enseignant_id) references enseignant (id)
);



create table etudiant_ue
(
    etudiant_id int not null,
    ue_id int not null,
    primary key (etudiant_id, ue_id),
    constraint FK_etudiant_ue_ue_id
        foreign key (ue_id) references ue (id)
            on delete cascade,
    constraint FK_etudiant_ue_etudiant_id
        foreign key (etudiant_id) references etudiant (id)
            on delete cascade
);

-- Index optionnelle servant à rechercher les données plus rapidement

-- Ralentie l’insertion de données
-- Accelère des clauses comme WHERE, GROUP BY ou ORDER BY pour un grand nombre d’enregistrements

create index IDX_etudiant_ue_ue_id
    on etudiant_ue (ue_id);

create index IDX_etudiant_ue_etudiant_id
    on etudiant_ue (etudiant_id);

create index IDX_ue_specialite_id
    on ue (specialite_id);

create index IDX_ue_formation_id
    on ue (formation_id);

create index IDX_ge_groupe_id
    on groupe_etudiant (groupe_id);

create index IDX_ge_etudiant_id
    on groupe_etudiant (etudiant_id);

create index IDX_enseignant_statut_enseignant_id
    on enseignant (statut_enseignant_id);

create index IDX_enseignant_section_id
    on enseignant (section_id);

create index IDX_cour_ue_id
    on cour (ue_id);

create index IDX_cour_groupe_id
    on cour (groupe_id);

create index IDX_cour_salle_id
    on cour (salle_id);

create index IDX_cour_enseignant_id
    on cour (enseignant_id);

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
DELIMITER //

DROP PROCEDURE IF EXISTS peupleEnseignant //

CREATE PROCEDURE peupleEnseignant()
BEGIN
    DECLARE counter INT DEFAULT 1; -- La premiere formation est pour un prof spécifique
    DECLARE nbFormation INT;
    DECLARE lengthListePersonne INT;
    DECLARE statutEnseignantId INT;
    DECLARE specialiteId INT;

    -- Création des enseignants responsable
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

    -- Création des enseignants

    SELECT COUNT(*) FROM personne INTO lengthListePersonne;

    WHILE counter < (lengthListePersonne / 4) DO -- Mettre 1/4 de prof pour 3/4 étudiants

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

DROP PROCEDURE IF EXISTS peupleUeValide;
DROP TEMPORARY TABLE IF EXISTS ueTmp;

DELIMITER //
CREATE PROCEDURE peupleUeValide()
BEGIN
    DECLARE counter INT DEFAULT 0;
    DECLARE etuId INT;
    DECLARE ueId INT;

    WHILE counter < 1250 DO -- 1250 est le nombre d'ue valide

    -- prend 1 random dans la liste des étudiants
            SELECT id INTO etuId FROM etudiant ORDER BY RAND() LIMIT 1;

            -- réccup les ue de l'étudiant pour les mettre dans une table temporaire
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


-- =============================
-- 12) Add specific Users
-- =============================

INSERT INTO personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
VALUES
    -- création scolarité
    (NULL, NULL, 'scolarite@univ-poitiers.fr', 'User', 'Scolarité', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:14:"ROLE_SCOLARITE";}'),
    -- création rh
    (NULL, NULL, 'rh@univ-poitiers.fr', 'User', 'Rh', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:7:"ROLE_RH";}'),
    -- création admin
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