
-- Script de creation pour une base de données MYSQL

drop table if exists cour;
drop table if exists salle;
drop table if exists personne;
drop table if exists enseignant;
drop table if exists statut_enseignant;
drop table if exists groupe_etudiant;
drop table if exists etudiant_ue;
drop table if exists etudiant;
drop table if exists groupe;
drop table if exists ue;
drop table if exists specialite;
drop table if exists formation;
drop table if exists cursus;

create table cursus
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    niveau varchar(255) not null
);

create table formation
(
    id int auto_increment
        primary key,
    cursus_id int not null,
    nom varchar(255) not null,
    annee int not null,
    constraint FK_formation_cursus_id
        foreign key (cursus_id) references cursus (id)
);

create table etudiant
(
    id int auto_increment
        primary key,
    formation_id int null,
    constraint FK_etu_formation_id
        foreign key (formation_id) references formation (id)
);

create index IDX_etu_formation_id
    on etudiant (formation_id);

create index IDX_formation_cursus_id
    on formation (cursus_id);

create table groupe
(
    id int auto_increment
        primary key,
    type varchar(2) not null
);

create table groupe_etudiant
(
    groupe_id int not null,
    etudiant_id int not null,
    primary key (groupe_id, etudiant_id),
    constraint FK_ge_groupe_id
        foreign key (groupe_id) references groupe (id)
            on delete cascade,
    constraint FK_ge_etudiant_id
        foreign key (etudiant_id) references etudiant (id)
            on delete cascade
);

create table salle
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    batiment varchar(255) not null,
    equipement varchar(255) null,
    capacite int not null
);

create table specialite
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    section int not null,
    groupe varchar(255) not null
);

create table statut_enseignant
(
    id int auto_increment
        primary key,
    nom varchar(255) not null,
    nb_heure_min int not null,
    nb_heure_max int not null
);

create table enseignant
(
    id int auto_increment
        primary key,
    statut_enseignant_id int null,
    section_id int null,
    responsable_formation_id int null,
    constraint UNIQ_enseignant_responsable_formation_id
        unique (responsable_formation_id),
    constraint FK_enseignant_statut_enseignant_id
        foreign key (statut_enseignant_id) references statut_enseignant (id),
    constraint FK_enseignant_responsable_formation_id
        foreign key (responsable_formation_id) references formation (id),
    constraint FK_section_id_section_id
        foreign key (section_id) references specialite (id)
);

create table personne
(
    id int auto_increment
        primary key,
    etudiant_id int null,
    enseignant_id int null,
    email varchar(255) not null,
    nom varchar(255) not null,
    prenom varchar(255) not null,
    password varchar(255) null,
    roles longtext null comment '(DC2Type:array)',
    constraint UNIQ_personne_etudiant_id
        unique (etudiant_id),
    constraint UNIQ_personne_enseignant_id
        unique (enseignant_id),
    constraint FK_personne_etudiant_id
        foreign key (etudiant_id) references etudiant (id),
    constraint FK_personne_enseignant_id
        foreign key (enseignant_id) references enseignant (id)
);

create table ue
(
    id int auto_increment
        primary key,
    specialite_id int not null,
    formation_id int null,
    nom varchar(255) not null,
    volume_horaire int null,
    constraint FK_ue_specialite_id
        foreign key (specialite_id) references specialite (id),
    constraint FK_ue_formation_id
        foreign key (formation_id) references formation (id)
);

create table cour
(
    id int auto_increment
        primary key,
    ue_id int not null,
    salle_id int not null,
    enseignant_id int not null,
    groupe_id int null,
    creneau int not null,
    constraint FK_cour_ue_id
        foreign key (ue_id) references ue (id),
    constraint FK_cour_groupe_id
        foreign key (groupe_id) references groupe (id),
    constraint FK_cour_salle_id
        foreign key (salle_id) references salle (id),
    constraint FK_cour_enseignant_id
        foreign key (enseignant_id) references enseignant (id)
);



create table etudiant_ue
(
    etudiant_id int not null,
    ue_id int not null,
    primary key (etudiant_id, ue_id),
    constraint FK_etudiant_ue_ue_id
        foreign key (ue_id) references ue (id)
            on delete cascade,
    constraint FK_etudiant_ue_etudiant_id
        foreign key (etudiant_id) references etudiant (id)
            on delete cascade
);

-- Index optionnelle servant à rechercher les données plus rapidement

-- Ralentie l’insertion de données
-- Accelère des clauses comme WHERE, GROUP BY ou ORDER BY pour un grand nombre d’enregistrements

create index IDX_etudiant_ue_ue_id
    on etudiant_ue (ue_id);

create index IDX_etudiant_ue_etudiant_id
    on etudiant_ue (etudiant_id);

create index IDX_ue_specialite_id
    on ue (specialite_id);

create index IDX_ue_formation_id
    on ue (formation_id);

create index IDX_ge_groupe_id
    on groupe_etudiant (groupe_id);

create index IDX_ge_etudiant_id
    on groupe_etudiant (etudiant_id);

create index IDX_enseignant_statut_enseignant_id
    on enseignant (statut_enseignant_id);

create index IDX_enseignant_section_id
    on enseignant (section_id);

create index IDX_cour_ue_id
    on cour (ue_id);

create index IDX_cour_groupe_id
    on cour (groupe_id);

create index IDX_cour_salle_id
    on cour (salle_id);

create index IDX_cour_enseignant_id
    on cour (enseignant_id);