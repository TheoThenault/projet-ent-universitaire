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
