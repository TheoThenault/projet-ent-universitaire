-- CREATION TABLE TEST
-- Faire peuplement avant
DROP TABLE IF EXISTS test;

DELIMITER //
CREATE TABLE test (
      nom varchar(255) NOT NULL,
      resultat varchar(5) NOT NULL
); //

ALTER TABLE test DROP CONSTRAINT IF EXISTS ck_resultat_du_test; //
ALTER TABLE test
    ADD CONSTRAINT ck_resultat_du_test
    CHECK (resultat IN ('OK', 'FAIL'));
//
DELIMITER ;

-- =============================
-- 1) test_post_persist_etudiant
-- =============================

DROP PROCEDURE IF EXISTS test_post_persist_etudiant;

DELIMITER //
CREATE PROCEDURE test_post_persist_etudiant()
BEGIN
    DECLARE nbGroupe INT DEFAULT 0;

    -- Inserer une nouvelle formation ( aucun groupe ne sera donc reliée)
    INSERT INTO formation (cursus_id, nom, annee)
    VALUES (1, 'FORMATIONTEST', 1);
    -- Inserer un nouvelle étudiant
    INSERT INTO etudiant (formation_id)
    VALUES (LAST_INSERT_ID());
    -- Verifier que les tp td cm ont bien été créer
    SELECT COUNT(*) INTO nbGroupe FROM groupe_etudiant
        WHERE groupe_etudiant.etudiant_id = LAST_INSERT_ID();

    IF nbGroupe = 3 THEN
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur post_persist_etudiant', 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur post_persist_etudiant', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_post_persist_etudiant();

-- =============================
-- 2) test_trigger_cour_enseignant
-- =============================

DROP PROCEDURE IF EXISTS test_trigger_cour_enseignant;

DELIMITER //
CREATE PROCEDURE test_trigger_cour_enseignant()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur pre_persist_cour_enseignant';
            END;

        -- Tenter d'insérer un enregistrement dans la table cour
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (1, 19, 1, 1, 333);
        -- Tenter d'insérer un enregistrement dans la table cour avec le meme enseignant_id et creneau
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (2, 20, 1, 2, 333);
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme enseignant_id et creneau
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur pre_persist_cour_enseignant', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_trigger_cour_enseignant();

-- =============================
-- 3) test_trigger_cour_etudiant
-- =============================

DROP PROCEDURE IF EXISTS test_trigger_cour_etudiant;

DELIMITER //
CREATE PROCEDURE test_trigger_cour_etudiant()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur pre_persist_cour_etudiant';
            END;

        -- Tenter d'insérer un enregistrement dans la table cour
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (1, 19, 1, 1, 555);
        -- Tenter d'insérer un enregistrement dans la table cour avec le meme creneau et groupe
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (2, 20, 2, 1, 555);
    END;

    -- Vérifier si un message d'erreur a été capturé avec deux cours ayant le meme groupe (donc meme etudiant)
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur pre_persist_cour_etudiant', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_trigger_cour_etudiant();

-- =============================
-- 4) test_VerifSalleQuandCreationCour
-- =============================

DROP PROCEDURE IF EXISTS test_VerifSalleQuandCreationCour;

DELIMITER //
CREATE PROCEDURE test_VerifSalleQuandCreationCour()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur VerifSalleQuandCreationCour';
            END;

        INSERT INTO salle(id, nom, batiment, equipement, capacite)
        VALUES(777777,'salleForTests', 'batimentTest', null, 0 );
        -- Tenter d'insérer un cours avec une salle pouvant acceuillir pas plus de 0 personne
        INSERT INTO cour (ue_id, salle_id, enseignant_id, groupe_id, creneau)
        VALUES (1, 777777, 1, 1, 599);
    END;

    -- Vérifier si un message d'erreur a été capturé avec un cours dans une salle déjà pleine
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur VerifSalleQuandCreationCour', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_VerifSalleQuandCreationCour();

-- =============================
-- 5) test_VerifUEQuandValidation
-- =============================

DROP PROCEDURE IF EXISTS test_VerifUEQuandValidation;

DELIMITER //
CREATE PROCEDURE test_VerifUEQuandValidation()
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE last_etu_id INT;
        DECLARE last_ue_id INT;
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test du déclencheur VerifUEQuandValidation';
            END;

        INSERT INTO etudiant(formation_id)
        VALUEs (1);
        SET last_etu_id = LAST_INSERT_ID();

        INSERT INTO ue(specialite_id, formation_id, nom, volume_horaire)
        VALUEs (1, 2, 'TEST UE', 0);
        SET last_ue_id = LAST_INSERT_ID();

        -- Tenter de valider l'ue d'un etudiant qu'il ne possede pas ( à travers sa formation )
        INSERT INTO etudiant_ue (etudiant_id, ue_id)
        VALUES (last_etu_id, last_ue_id);
    END;

    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test du déclencheur VerifUEQuandValidation', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL test_VerifUEQuandValidation();

-- =============================
-- 6) validation_check_capacite
-- =============================

DROP PROCEDURE IF EXISTS validation_check_capacite;

DELIMITER //
CREATE PROCEDURE validation_check_capacite(_cap INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_capacite';
            END;

		INSERT INTO salle (nom, batiment, equipement, capacite)
			VALUES('test', 'test', 'langue', _cap);

    END;

    -- Vérifier si un message d'erreur a été capturé quand on insere une salle avec une capacité non comprise entre 0 et 999
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_capacite', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_capacite(-1);
CALL validation_check_capacite(1000);

-- =============================
-- 7) validation_check_creneau
-- =============================

DROP PROCEDURE IF EXISTS validation_check_creneau;

DELIMITER //
CREATE PROCEDURE validation_check_creneau(_num INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_creneau';
            END;
		INSERT INTO cour(ue_id, salle_id, enseignant_id, groupe_id, creneau)
			VALUES(1, 1, 1, NULL, _num);


    END;

    -- Vérifier si un message d'erreur a été capturé avec le créneau d'un cours non compris entre 0 et 600
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_creneau', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_creneau(-1);
CALL validation_check_creneau(601);

-- =============================
-- 9) validation_check_nb_heure_max
-- =============================

DROP PROCEDURE IF EXISTS validation_check_nb_heure_max;

DELIMITER //
CREATE PROCEDURE validation_check_nb_heure_max(_nbHeure INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_nb_heure_max';
            END;
        INSERT INTO statut_enseignant (nom, nb_heure_min, nb_heure_max)
			VALUES('test', 0, _nbHeure);

    END;

    -- Tester qu'on ne peux pas insérer un statut enseignant avec un nombre d'heures max non compris entre 0 et 9999
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_nb_heure_max', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_nb_heure_max(-1);
CALL validation_check_nb_heure_max(10000);

-- =============================
-- 10) validation_check_nb_heure_min
-- =============================

DROP PROCEDURE IF EXISTS validation_check_nb_heure_min;

DELIMITER //
CREATE PROCEDURE validation_check_nb_heure_min(_nbHeure INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_nb_heure_min';
            END;
        INSERT INTO statut_enseignant (nom, nb_heure_min, nb_heure_max)
			VALUES('test', 0, _nbHeure);

    END;

    -- Tester qu'on ne peux pas insérer un statut enseignant avec un nombre d'heures min non compris entre 0 et 9999
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_nb_heure_min', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_nb_heure_min(-1);
CALL validation_check_nb_heure_min(10000);

-- =============================
-- 11) validation_check_volume_horaire
-- =============================

DROP PROCEDURE IF EXISTS validation_check_volume_horaire;

DELIMITER //
CREATE PROCEDURE validation_check_volume_horaire(_vol INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test check_volume_horaire';
            END;
        INSERT INTO ue (specialite_id, formation_id, nom, volume_horaire)
			VALUES(1, 1, 'test', _vol);
    END;

    -- Un ue doit avoir un volume horaire entre 0 et 9999, erreur sinon
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test check_volume_horaire', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_check_volume_horaire(-1);
CALL validation_check_volume_horaire(10001);

-- =============================
-- 12) validation_chk_cursus_level
-- =============================

DROP PROCEDURE IF EXISTS validation_chk_cursus_level;

DELIMITER //
CREATE PROCEDURE validation_chk_cursus_level(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test chk_cursus_level';
            END;
        INSERT INTO cursus(nom, niveau)
        VALUES('test', _nom);
    END;

    -- Vérifier que le niveau d'un cursus vaut soit 'Master' soit 'Licence'
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_cursus_level', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_chk_cursus_level('NIlun');
CALL validation_chk_cursus_level('Nil\'autre');
CALL validation_chk_cursus_level('Seulemnt');
CALL validation_chk_cursus_level('M_aster');
CALL validation_chk_cursus_level('_liCe_nce');
CALL validation_chk_cursus_level('marche');

-- =============================
-- 13) validation_chk_groupe_type
-- =============================

DROP PROCEDURE IF EXISTS validation_chk_groupe_type;

DELIMITER //
CREATE PROCEDURE validation_chk_groupe_type(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test chk_groupe_type';
            END;

        INSERT INTO groupe(`type`)
        VALUES(_nom);


    END;

    -- Vérifier que le type d'un groupe puisse être seulement 'TD', 'TP' ou 'CM'
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_groupe_type', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_chk_groupe_type('PA');
CALL validation_chk_groupe_type('BO');
CALL validation_chk_groupe_type('N');

-- =============================
-- 14) validation_cursus_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_cursus_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_cursus_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test cursus_nom_length_check';
            END;
        INSERT INTO cursus(nom, niveau)
        VALUES(_nom, 'Licence');
    END;

    -- Vérifier que le nom d'un cursus est compris entre 2 et 128 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test cursus_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_cursus_nom_length_check('t');
CALL validation_cursus_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 15) validation_email_format
-- =============================

DROP PROCEDURE IF EXISTS validation_email_format;

DELIMITER //
CREATE PROCEDURE validation_email_format(_email VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test email_format';
            END;

		INSERT INTO personne(etudiant_id, enseignant_id, email, nom, prenom, password, roles)
			VALUES(NULL, NULL, _email, 'nom', 'prenom',
			'$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6',
						'a:1:{i:0;s:9:"ROLE_USER";}');

    END;

    -- Vérifier que l'email d'une personne respecte le format 'exemple@univ-poitiers.fr'
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test email_format', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_email_format('');
CALL validation_email_format('a');
CALL validation_email_format('@email');
CALL validation_email_format('test@email');
CALL validation_email_format('@');
CALL validation_email_format('.com');
CALL validation_email_format('test@.com');
CALL validation_email_format('@email.com');
CALL validation_email_format('user@pasbonEmail.com');

-- =============================
-- 16) validation_formation_annee_check
-- =============================

DROP PROCEDURE IF EXISTS validation_formation_annee_check;

DELIMITER //
CREATE PROCEDURE validation_formation_annee_check(_num INT)
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test formation_annee_check';
            END;
		INSERT INTO formation(cursus_id, nom, annee)
			VALUES(1, 'test', _num);

    END;

    -- Vérifier que l'année d'une formation est bien compris entre 0 et 10
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test formation_annee_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_formation_annee_check(-1);
CALL validation_formation_annee_check(11);

-- =============================
-- 17) validation_formation_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_formation_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_formation_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test formation_nom_length_check';
            END;
		INSERT INTO formation(cursus_id, nom, annee)
			VALUES(1, _nom, 1);

    END;

    -- Vérifier que le nom d'une formation est compris entre 2 et 255 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test chk_groupe_type', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_formation_nom_length_check('t');
CALL validation_formation_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 18) validation_personne_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_personne_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_personne_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test personne_nom_length_check';
            END;

		INSERT INTO personne(etudiant_id, enseignant_id, email, nom, prenom, password, roles)
			VALUES(NULL, NULL, 'email@internet.com', _nom, 'prenom',
			'$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6',
						'a:1:{i:0;s:9:"ROLE_USER";}');


    END;

    -- Vérifier que le nom d'une personne est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test personne_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_personne_nom_length_check('t');
CALL validation_personne_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 19) validation_personne_prenom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_personne_prenom_length_check;

DELIMITER //
CREATE PROCEDURE validation_personne_prenom_length_check(_prenom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test personne_prenom_length_check';
            END;

        INSERT INTO personne(etudiant_id, enseignant_id, email, nom, prenom, password, roles)
        VALUES(NULL, NULL, 'email@internet.com', 'nom', _prenom,
               '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6',
               'a:1:{i:0;s:9:"ROLE_USER";}');


    END;

    -- Vérifier que le prenom d'une personne est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test personne_prenom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_personne_prenom_length_check('t');
CALL validation_personne_prenom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 20) validation_salle_batiment_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_salle_batiment_length_check;

DELIMITER //
CREATE PROCEDURE validation_salle_batiment_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test salle_batiment_length_check';
            END;

           INSERT INTO salle (nom, batiment, equipement, capacite)
          		VALUES('test', _nom, 'langue', 10);


    END;

    -- Vérifier que le batiment d'une salle est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test salle_batiment_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_salle_batiment_length_check('t');
CALL validation_salle_batiment_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 21) validation_salle_equipement_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_salle_equipement_length_check;

DELIMITER //
CREATE PROCEDURE validation_salle_equipement_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test salle_equipement_length_check';
            END;

           INSERT INTO salle (nom, batiment, equipement, capacite)
          		VALUES('test', 'test', _nom, 10);


    END;

    -- Vérifier que l'équipement d'une salle est compris entre 2 et 128 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test salle_equipement_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_salle_equipement_length_check('t');
CALL validation_salle_equipement_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 22) validation_specialite_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_specialite_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_specialite_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test specialite_nom_length_check';
            END;

           INSERT INTO specialite (nom, `section`, groupe)
			VALUES(_nom, 1, 'I');


    END;

    -- Vérifier que le nom d'une spécialité est compris entre 2 et 255 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test specialite_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_specialite_nom_length_check('t');
CALL validation_specialite_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 23) validation_statut_enseignant_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_statut_enseignant_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_statut_enseignant_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test statut_enseignant_nom_length_check';
            END;
		INSERT INTO statut_enseignant(nom, nb_heure_min, nb_heure_max)
			VALUES(_nom, 1, 2);

    END;

    -- Vérifier que le nom d'un statut enseignant est compris entre 2 et 64 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test statut_enseignant_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_statut_enseignant_nom_length_check('t');
CALL validation_statut_enseignant_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');

-- =============================
-- 24) validation_ue_nom_length_check
-- =============================

DROP PROCEDURE IF EXISTS validation_ue_nom_length_check;

DELIMITER //
CREATE PROCEDURE validation_ue_nom_length_check(_nom VARCHAR(260))
BEGIN
    DECLARE errorMessage VARCHAR(255);
    -- Si une exception est levée, capturer le message d'erreur
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                SET errorMessage := 'Test ue_nom_length_check';
            END;
        INSERT INTO ue (specialite_id, formation_id, nom, volume_horaire)
			VALUES(1, 1, _nom, 10);g
    END;

    -- Vérifier que le nom d'une Ue est compris entre 2 et 255 caractères
    IF errorMessage IS NOT NULL THEN
        INSERT INTO test (nom, resultat)
        VALUES (errorMessage, 'OK');
    ELSE
        INSERT INTO test (nom, resultat)
        VALUES ('Test ue_nom_length_check', 'FAIL');
    END IF;
END; //
DELIMITER ;

CALL validation_ue_nom_length_check('t');
CALL validation_ue_nom_length_check('tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');