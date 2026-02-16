INSERT INTO objet_nature (nom, prix_unitaire) VALUES
('Riz', 2500),
('Huile', 8000),
('Sucre', 3500),
('Haricots', 4000),
('Eau potable', 1000);

INSERT INTO objet_materiaux (nom, prix_unitaire) VALUES
('Tôle', 30000),
('Clou', 200),
('Ciment', 45000),
('Bois', 25000),
('Briques', 1500);

INSERT INTO besoin_nature (id_objet_nature, id_ville, qte) VALUES
(1, 1, 1),
(2, 1, 1), 
(3, 2, 1), 
(4, 2, 1), 
(5, 3, 1); 

INSERT INTO besoin_materiaux (id_objet_materiaux, id_ville, qte) VALUES
(1, 1, 1), 
(2, 1, 1), 
(3, 2, 1), 
(4, 3, 1); 

INSERT INTO besoin_argent (vola, id_ville) VALUES
(500000, 1),
(300000, 2),
(200000, 3);