INSERT INTO projet_ent_universitaire.personne (etudiant_id, enseignant_id, email, nom, prenom, password, roles)
VALUES
    -- enseignant responsable
    (NULL, NULL, 'enseignant.res@univ-poitiers.fr', 'User', 'EnseignantRes', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT_RES";}'),
    -- création étudiant
    (NULL, NULL, 'etudiant@univ-poitiers.fr', 'User', 'Etudiant', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_ETUDIANT";}'),
    -- création enseignant
    (NULL, NULL, 'enseignant@univ-poitiers.fr', 'User', 'Enseignant', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_ENSEIGNANT";}'),
    -- création scolarité
    (NULL, NULL, 'scolarite@univ-poitiers.fr', 'User', 'Scolarité', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_Scolarite";}'),
    -- création rh
    (NULL, NULL, 'rh@univ-poitiers.fr', 'User', 'Rh', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_RH";}'),
    -- création admin
    (NULL, NULL, 'admin@univ-poitiers.fr', 'User', 'Admin', '$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6', 'a:1:{i:0;s:9:"ROLE_Admin";}');