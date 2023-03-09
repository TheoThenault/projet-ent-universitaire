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
