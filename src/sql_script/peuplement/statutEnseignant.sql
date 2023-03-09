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