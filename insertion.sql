INSERT INTO region (nom) VALUES
('Analamanga'),
('Atsinanana'),
('Haute Matsiatra'),
('Boeny'),
('Diana');

INSERT INTO ville (nom, id_region) VALUES
('Antananarivo', 1),
('Antsirabe', 1),
('Toamasina', 2),
('Fenoarivo Atsinanana', 2),
('Fianarantsoa', 3),
('Ambalavao', 3),
('Mahajanga', 4),
('Ambato Boeny', 4),
('Antsiranana', 5),
('Nosy Be', 5);

INSERT INTO objet_nature (nom, prix_unitaire) VALUES
('Riz (kg)', 3200.00),
('Haricots secs (kg)', 5800.00),
('Huile (L)', 9500.00),
('Sucre (kg)', 4500.00),
('Lait en poudre', 12000.00),
('Sel (kg)', 1200.00),
('Maïs (kg)', 2800.00),
('Farine (kg)', 3000.00);

INSERT INTO objet_materiaux (nom, prix_unitaire) VALUES
('Ciment (sac)', 38000.00),
('Tôle (unité)', 29000.00),
('Bois (planche)', 15000.00),
('Clous (kg)', 12000.00),
('Peinture (pot)', 52000.00),
('Briques (lot)', 45000.00);

INSERT INTO besoin_nature (id_objet_nature, id_ville, qte) VALUES
(1, 1, 600),
(2, 1, 120),
(3, 2, 60),
(4, 2, 220),
(5, 3, 45),
(1, 3, 800),
(6, 4, 150),
(7, 5, 300),
(8, 6, 240),
(2, 7, 90);

INSERT INTO besoin_materiaux (id_objet_materiaux, id_ville, qte) VALUES
(1, 7, 25),
(2, 7, 140),
(3, 8, 80),
(4, 8, 35),
(5, 9, 18),
(6, 10, 22),
(2, 10, 110);

INSERT INTO besoin_argent (vola, id_ville) VALUES
(2500000, 1),
(1800000, 2),
(3200000, 3),
(1400000, 4),
(2100000, 5),
(900000, 6),
(2600000, 7),
(1100000, 8),
(2300000, 9),
(1700000, 10);

INSERT INTO reduction_vente (pourcentage) VALUES
(10);
