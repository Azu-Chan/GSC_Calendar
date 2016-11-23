CREATE TABLE utilisateur(
prenom VARCHAR(109) NOT NULL,
nom VARCHAR(109) NOT NULL,
email VARCHAR(255) NOT NULL PRIMARY KEY,
mdp VARCHAR(32) NOT NULL
);

CREATE TABLE evenement(
id integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
utilisateur VARCHAR(255) NOT NULL,
categorie integer NOT NULL,
dateDebut DATETIME NOT NULL,
dateFin DATETIME NOT NULL,
nom VARCHAR(80) NOT NULL,
resume VARCHAR(500),
frequence integer,
CONSTRAINT fk_ue FOREIGN KEY (utilisateur) REFERENCES utilisateur(email),
CONSTRAINT fk_c FOREIGN KEY (categorie) REFERENCES categorie(id)
);

CREATE TABLE media(
evenement integer NOT NULL,
url VARCHAR(255) NOT NULL,
CONSTRAINT fk_e FOREIGN KEY (evenement) REFERENCES evenement(id),
CONSTRAINT pk_m PRIMARY KEY (evenement, url)
);

CREATE TABLE categorie(
id integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
utilisateur VARCHAR(255) NOT NULL,
nom VARCHAR(20) NOT NULL,
CONSTRAINT fk_u FOREIGN KEY (utilisateur) REFERENCES utilisateur(email)
);

CREATE TABLE connexion(
utilisateur VARCHAR(255) NOT NULL,
ip VARCHAR(39),
dateConnexion TIMESTAMP NOT NULL DEFAULT GETDATE() ,
CONSTRAINT fk_u_c FOREIGN KEY (utilisateur) REFERENCES utilisateur(email),
CONSTRAINT pk_c PRIMARY KEY (utilisateur,dateConnexion,ip)
);