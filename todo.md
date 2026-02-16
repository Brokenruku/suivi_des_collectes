CONCEPTION BASE DE DONNE 
    postgress
        TABLE 
            users 
            region
            ville foreign key table region
            besoin_nature, besoin_materiaux, besoin_argent foreign key ville
            dons foreign key users
            dons_nature, dons_materiaux, dons_argent foreign key dons
        INSERTION 
            chaque table doit avoir des tonne test

INTEGRATION PHP 
    login.php  
        connection user
    register.php
        creer user
    connection.php
        pdo pour connection a la base
    
    DonsModel.php 
        FONCTION 
            getDons()
                prendre tout les effectuer
            insertDons()
                insert un ligne de dons
    VilleModel.php 
        prendre les ville existant pour chaque region
        FONCTION 
            getVilleAvecBesoin() 
                retourne tout les besoin d un ville