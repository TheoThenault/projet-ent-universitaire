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
