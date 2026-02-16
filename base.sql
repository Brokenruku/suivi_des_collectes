CREATE DATABASE IF NOT EXISTS 4064_4078_4107
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE 4064_4078_4107;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  mdp VARCHAR(100),
  mail VARCHAR(100),
  numero VARCHAR(100)
);

CREATE TABLE region (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50)
);

CREATE TABLE ville (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50),
  id_region INT,
  FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE objet_nature (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100)
);

CREATE TABLE objet_materiaux (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100)
);

ALTER TABLE objet_nature
ADD prix_unitaire DECIMAL(12,2) NOT NULL DEFAULT 0;

ALTER TABLE objet_materiaux
ADD prix_unitaire DECIMAL(12,2) NOT NULL DEFAULT 0;

CREATE TABLE besoin_nature (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_objet_nature INT,
  id_ville INT,
  FOREIGN KEY (id_ville) REFERENCES ville(id),
  FOREIGN KEY (id_objet_nature) REFERENCES objet_nature(id)
);

CREATE TABLE besoin_materiaux (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_objet_materiaux INT,
  id_ville INT,
  FOREIGN KEY (id_ville) REFERENCES ville(id),
  FOREIGN KEY (id_objet_materiaux) REFERENCES objet_materiaux(id)
);

CREATE TABLE besoin_argent (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vola DOUBLE,
  id_ville INT UNIQUE,
  FOREIGN KEY (id_ville) REFERENCES ville(id)
);

ALTER TABLE besoin_nature ADD qte INT NOT NULL DEFAULT 1;
ALTER TABLE besoin_materiaux ADD qte INT NOT NULL DEFAULT 1;

CREATE TABLE dons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_users INT,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_users) REFERENCES users(id)
);

CREATE TABLE dons_nature (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_objet_nature INT,
  id_dons INT,
  FOREIGN KEY (id_dons) REFERENCES dons(id),
  FOREIGN KEY (id_objet_nature) REFERENCES objet_nature(id)
);

CREATE TABLE dons_materiaux (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_objet_materiaux INT,
  id_dons INT,
  FOREIGN KEY (id_dons) REFERENCES dons(id),
  FOREIGN KEY (id_objet_materiaux) REFERENCES objet_materiaux(id)
);

CREATE TABLE dons_argent (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vola DOUBLE,
  id_dons INT UNIQUE,
  FOREIGN KEY (id_dons) REFERENCES dons(id)
);

ALTER TABLE dons_nature ADD qte INT NOT NULL DEFAULT 1;
ALTER TABLE dons_materiaux ADD qte INT NOT NULL DEFAULT 1;

CREATE TABLE achats (
  id SERIAL PRIMARY KEY,
  id_users INT NOT NULL,
  id_ville INT NOT NULL,
  total_argent DOUBLE PRECISION NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_users) REFERENCES users(id),
  FOREIGN KEY (id_ville) REFERENCES ville(id)
);

CREATE TABLE achat_lignes (
  id SERIAL PRIMARY KEY,
  id_achat BIGINT UNSIGNED NOT NULL,
  type_objet VARCHAR(20) NOT NULL,
  id_objet INT NOT NULL,
  qte INT NOT NULL,
  pu DOUBLE PRECISION NOT NULL,
  montant DOUBLE PRECISION NOT NULL,
  FOREIGN KEY (id_achat) REFERENCES achats(id) ON DELETE CASCADE
);

