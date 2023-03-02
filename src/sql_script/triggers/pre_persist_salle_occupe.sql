DELIMITER //
DROP TRIGGER IF EXISTS projet_ent_universitaire.pre_persist_salle_occupe //

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