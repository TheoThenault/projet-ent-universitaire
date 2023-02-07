ALTER TABLE cours
ADD CONSTRAINT check_creneau CHECK (creneau >= 0 AND creneau <= 600);

ALTER TABLE cursus
ADD CONSTRAINT cursus_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 128);

ALTER TABLE cursus
ADD CONSTRAINT chk_cursus_level
CHECK (niveau IN ('Master', 'License'));

ALTER TABLE formation
ADD CONSTRAINT formation_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE formation
ADD CONSTRAINT formation_annee_check
CHECK (annee >= 0 AND annee <= 10);

ALTER TABLE groupe
ADD CONSTRAINT chk_groupe_type
CHECK (type IN ('TD', 'TP', 'CM'));

ALTER TABLE personne
ADD CONSTRAINT email_format
CHECK (email REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$');

ALTER TABLE personne
ADD CONSTRAINT personne_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE personne
ADD CONSTRAINT personne_prenom_length_check CHECK (char_length(prenom) >= 2 AND char_length(prenom) <= 64);

ALTER TABLE salle
ADD CONSTRAINT salle_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE salle
ADD CONSTRAINT salle_batiment_length_check CHECK (char_length(batiment) >= 2 AND char_length(batiment) <= 64);

ALTER TABLE salle
ADD CONSTRAINT salle_equipement_length_check CHECK (char_length(equipement) >= 2 AND char_length(equipement) <= 128);

ALTER TABLE salle
ADD CONSTRAINT check_capacite CHECK (capacite >= 0 AND capacite <= 999);

ALTER TABLE specialite
ADD CONSTRAINT specialite_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE statut_enseignant
ADD CONSTRAINT statut_enseignant_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 64);

ALTER TABLE statut_enseignant
ADD CONSTRAINT check_nb_heure_min CHECK (nb_heure_min >= 0 AND nb_heure_min <= 9999);

ALTER TABLE statut_enseignant
ADD CONSTRAINT check_nb_heure_max CHECK (nb_heure_max >= 0 AND nb_heure_max <= 9999);

ALTER TABLE ue
ADD CONSTRAINT ue_nom_length_check CHECK (char_length(nom) >= 2 AND char_length(nom) <= 255);

ALTER TABLE ue
ADD CONSTRAINT check_volume_horaire CHECK (volume_horaire >= 0 AND volume_horaire <= 9999);
