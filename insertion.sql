INSERT INTO region (nom) VALUES 
('Atsinanana');

INSERT INTO ville (nom, id_region) VALUES
('Toamasina', 1),
('Mananjary', 1),
('Farafangana', 1),
('Nosy Be', 1),
('Morondava', 1);

INSERT INTO objet_nature (nom, prix_unitaire) VALUES
('Riz (kg)', 3000),
('Eau (L)', 1000),
('Huile (L)', 6000),
('Haricots (kg)', 4000);

INSERT INTO objet_materiaux (nom, prix_unitaire) VALUES
('Tôle', 25000),
('Bâche', 15000),
('Clous (kg)', 8000),
('Bois', 10000),
('groupe', 6750000);

INSERT INTO besoin_nature (id_objet_nature, id_ville, qte) VALUES
(1, 1, 800),   
(2, 1, 1500), 

(1, 2, 500),  
(3, 2, 120), 

(1, 3, 600),   
(2, 3, 1000),  

(1, 4, 300),  
(4, 4, 200), 

(1, 5, 700),  
(2, 5, 1200); 


INSERT INTO besoin_materiaux (id_objet_materiaux, id_ville, qte) VALUES
(1, 1, 120), 
(2, 1, 200),
(5, 1, 3),   

(1, 2, 80),  
(3, 2, 60),  

(2, 3, 150), 
(4, 3, 100), 

(1, 4, 40), 
(3, 4, 30), 

(2, 5, 180), 
(4, 5, 150); 


INSERT INTO besoin_argent (vola, id_ville) VALUES
(12000000, 1),
(6000000, 2),
(8000000, 3),
(4000000, 4),
(10000000, 5);


INSERT INTO reduction_vente (pourcentage) VALUES
(10);

INSERT INTO dons (id, id_users, date) VALUES
(1, 1, '2026-02-16'),
(2, 1, '2026-02-16'),
(3, 1, '2026-02-17'),
(4, 1, '2026-02-17'),
(5, 1, '2026-02-17'),
(6, 1, '2026-02-16'),
(7, 1, '2026-02-16'),
(8, 1, '2026-02-17'),
(9, 1, '2026-02-17'),
(10, 1, '2026-02-17'),
(11, 1, '2026-02-18'),
(12, 1, '2026-02-18'),
(13, 1, '2026-02-18'),
(14, 1, '2026-02-19'),
(15, 1, '2026-02-19'),
(16, 1, '2026-02-17');

INSERT INTO dons_argent (id_dons, vola) VALUES
(1, 5000000),
(2, 3000000),
(3, 4000000),
(4, 1500000),
(5, 6000000),
(14, 20000000);

INSERT INTO dons_nature (id_dons, id_objet_nature, qte) VALUES
(6,  (SELECT id FROM objet_nature WHERE nom='Riz (kg)' LIMIT 1), 400),
(7,  (SELECT id FROM objet_nature WHERE nom='Eau (L)' LIMIT 1), 600),
(10, (SELECT id FROM objet_nature WHERE nom='Haricots' LIMIT 1), 100),
(11, (SELECT id FROM objet_nature WHERE nom='Riz (kg)' LIMIT 1), 2000),
(13, (SELECT id FROM objet_nature WHERE nom='Eau (L)' LIMIT 1), 5000),
(16, (SELECT id FROM objet_nature WHERE nom='Haricots' LIMIT 1), 88);

INSERT INTO dons_materiaux (id_dons, id_objet_materiaux, qte) VALUES
(8,  (SELECT id FROM objet_materiaux WHERE nom='Tôle' LIMIT 1), 50),
(9,  (SELECT id FROM objet_materiaux WHERE nom='Bâche' LIMIT 1), 70),
(12, (SELECT id FROM objet_materiaux WHERE nom='Tôle' LIMIT 1), 300),
(15, (SELECT id FROM objet_materiaux WHERE nom='Bâche' LIMIT 1), 500);
