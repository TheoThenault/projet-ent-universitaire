DELIMITER //
DROP TRIGGER IF EXISTS projet_ent_universitaire.pre_persist_personne_mail //

CREATE TRIGGER pre_persist_personne_mail BEFORE INSERT ON personne
    FOR EACH ROW
BEGIN
    DECLARE nbPersonneSameMail INT DEFAULT 0;
    -- Compte les personnes ayant le meme mail que la nouvelle personne
    SELECT COUNT(*) INTO nbPersonneSameMail FROM personne
        WHERE personne.email REGEXP '^[a-zA-Z]+\.[a-zA-Z]+[0-9]*@univ-poitiers\.fr$';

    IF nbPersonneSameMail > 0 THEN -- change mail
        SET NEW.email = (CONCAT(NEW.prenom,'.',NEW.nom, nbPersonneSameMail, '@univ-poitiers.fr' ));
    END IF;
END; //
DELIMITER ;
