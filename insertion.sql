INSERT INTO region (nom) VALUES
('Analamanga'),
('Atsinanana'),
('Haute Matsiatra');

INSERT INTO ville (nom, id_region) VALUES
('Antananarivo', 1),
('Toamasina', 2),
('Fianarantsoa', 3),
('Ambohidratrimo', 1),
('Vohipeno', 3);

INSERT INTO besoin_nature (nom, id_ville) VALUES
('Riz', 1),
('Huile', 1),
('Eau potable', 2),
('Légumes', 3),
('Farine', 4),
('Sel', 5);

INSERT INTO besoin_materiaux (nom, id_ville) VALUES
('Tôles', 1),
('Briques', 1),
('Ciment', 2),
('Bois', 3),
('Couvertures', 4),
('Bâches', 5);

INSERT INTO besoin_argent (vola, id_ville) VALUES
(1500000, 1),
(800000, 2),
(500000, 3),
(300000, 4),
(200000, 5);
