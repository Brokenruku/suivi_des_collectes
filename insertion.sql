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
