INSERT INTO personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
VALUES
    -- création scolarité
    (NULL, NULL, 'scolarite@univ-poitiers.fr', 'User', 'Scolarité', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_Scolarite";}'),
    -- création rh
    (NULL, NULL, 'rh@univ-poitiers.fr', 'User', 'Rh', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_RH";}'),
    -- création admin
    (NULL, NULL, 'admin@univ-poitiers.fr', 'User', 'Admin', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_Admin";}');

-- Update personne
UPDATE personne
SET email = 'etudiant@univ-poitiers.fr',
    prenom = 'User',
    nom = 'Etudiant'
WHERE roles = 'a:1:{i:0;s:9:"ROLE_ETUDIANT";}'
LIMIT 1;

UPDATE personne
SET email = 'enseignant@univ-poitiers.fr',
    prenom = 'User',
    nom = 'Enseignant'
WHERE roles = 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT";}'
LIMIT 1;

UPDATE personne
SET email = 'enseignant@univ-poitiers.fr',
    prenom = 'User',
    nom = 'EnseignantRes'
WHERE roles = 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT_RES";}'
LIMIT 1;