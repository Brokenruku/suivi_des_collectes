CREATE DATABASE "4064_4078_4107";
\c "4064_4078_4107"

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    mdp VARCHAR(100),
    mail VARCHAR(100),
    numero VARCHAR(100)
);

CREATE TABLE region (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50)
);

CREATE TABLE ville (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50),
    id_region INTEGER REFERENCES region(id)
);

CREATE TABLE besoin_nature (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    id_ville INTEGER REFERENCES ville(id)
);

CREATE TABLE besoin_materiaux (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    id_ville INTEGER REFERENCES ville(id)
);

CREATE TABLE besoin_argent (
    id SERIAL PRIMARY KEY,
    vola DOUBLE PRECISION,
    id_ville INTEGER UNIQUE REFERENCES ville(id)
);
 
CREATE TABLE dons (
    id SERIAL PRIMARY KEY,
    id_users INTEGER REFERENCES users(id),
    id_ville INTEGER REFERENCES ville(id),
    date timestamp default current_timestamp
);

CREATE TABLE dons_nature (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    id_ville INTEGER REFERENCES dons(id)
);

CREATE TABLE dons_materiaux (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    id_ville INTEGER REFERENCES dons(id)
);

CREATE TABLE dons_argent (
    id SERIAL PRIMARY KEY,
    vola DOUBLE PRECISION,
    id_ville INTEGER REFERENCES dons(id)
);