CONCEPTION BASE DE DONNEES
    (OK) mysql
        (OK) TABLES
            users 4078
                identifiants et profils utilisateurs
            region 4064
                regions geographiques
            ville 4064
                villes avec reference region
            objet_nature, objet_materiaux 4107
                articles disponibles avec prix unitaire
            besoin_nature, besoin_materiaux, besoin_argent 4064
                besoins par ville references
            dons 4078
                enregistrements de dons utilisateur
            dons_nature, dons_materiaux, dons_argent 4078
                details des dons effectues
            achats 4107
                historique des achats par utilisateur et ville
            achat_lignes 4107
                lignes detaillees des achats
        (OK) DONNEES 4078
            donnees test pour chaque table

ARCHITECTURE PHP
    (OK) AUTHENTIFICATION 4078
        (OK) LoginController 4078
            showLogin() - affiche formulaire connexion
            login() - valide email/password et cree session utilisateur
        (OK) RegisterController/LoginController 4078
            showRegister() - affiche formulaire inscription
            register() - validation input et creation nouvel utilisateur en base
        (OK) UserModel 4078
            checkLogin() - requete WHERE mail/mdp et retour utilisateur
            checkUserExists() - verifie doublon email
            registerUser() - INSERT utilisateur avec donnees validees
    
    (OK) DONS 4064
        (OK) DonController 4064
            form() - affiche formulaire avec villes/besoins disponibles
            submit() - validation et enregistrement don en base
        (OK) DonModel 4064
            getDons() - SELECT historique dons avec details utilisateur
            insertDon() - INSERT dons + dons_nature/materiaux/argent selon type
            getBesoinsVille() - recupere besoins ville cible
    
    (OK) ACHATS 4107
        (OK) AchatController 4107
            index() - affiche liste achats par utilisateur
            create() - creation nouvel achat + lignes
            show() - detail achat specifique avec ses lignes
        (OK) AchatModel 4107
            getAchats() - SELECT achats joins ville/utilisateur
            createAchat() - INSERT achat + achat_lignes
            getAchatById() - recupere achat avec details

    (OK) VENTE 4064
        (OK) VenteController 4064
            form() - appelle les getters nature, materiaux, reduction
            store() - appelle la methode vendre
        (OK) VenteModel 4064
            les getters des objects natures et materiaux
            stockNature() - mamerina tableau nature disponible dans dons
            stockMateriau() - mamerina tableau materiaux disponible dans dons
            besoinRestantMateriaux() - calcul les besoins materiaux restants
            prixMateriaux() - prend le PU materiaux
            mergeLines() - retourne un tableau de total de qte des objets
            vendreMixte() - insertion dans vente et/ou venteligne les objets concernees
    
    (OK) VILLES ET BESOINS 4064
        (OK) VilleModel 4064
            getVilles() - SELECT villes groupees par region
            getBesoinsVille() - SELECT besoins nature/materiaux/argent pour ville
            getBesoinsTotal() - somme besoins par type et ville
    
    (OK) RECAPITULATIF 4064
        (OK) RecapController 4064 
            index() - affiche dashboard global
            data() - retourne JSON donnees pour graphiques
        (OK) RecapModel 4064 
            getBesoinsVsDons() - compare besoins satisfaits vs non satisfaits
            getStatistiques() - totaux dons/achats par type 
        (OK) correction : inserer meme si ca depasse les besoins 4078
        (OK) affichage liste des dons nature et materiaux 4078

    (OK) RESET 4078
        (OK) ResetController 4078
            reset() - vide les tableaux
    
    (OK) PRESENTATION ALL
        (OK) Layouts
            (OK) header 4078
                barre navigation avec liens accueil/achats/dons/recap
                affichage nom utilisateur connecte
                boutons login/register si non authentifie
                bouttons REINITIALISE 
            (OK) footer 4078
                scripts bootstrap
        (OK) Vues
            (OK) login.php 4078
                formulaire email/password
                lien vers register
                affichage erreurs validation
            
            (OK) register.php 4078
                formulaire nom/email/password/numero
                validation email format et password longueur
                message succes/erreur
                lien vers login
            
            (OK) accueil.php 4064
                titre et navigation
                tableau besoins par ville
                colonnes region/ville/besoins_nature/besoins_materiaux/besoins_argent
                boutons actions achats/dons/recap
                filtres par region
            
            (OK) faireDon.php 4064
                selecteur ville
                selecteur type don (nature/materiaux/argent)
                listage articles disponibles avec prix
                formulaire quantite/montant selon type
                recapitulatif don avant confirmation
            
            (OK) achats.php 4064
                tableau historique achats utilisateur
                colonnes date/ville/total/details
                lien vers detail achat
                bouton creation nouvel achat
            
            (OK) achat_show.php 4064
                infos achat (date/ville/total)
                tableau lignes achat avec articles/qte/pu/montant
                totaux et recapitulatif
            
            (OK) recap.php 4064
                dashboard globale
                graphiques besoins satisfaction par type
                statistiques dons vs achats
                tableau comparatif region/besoins/dons/achats

            (OK) vendre.php 4064
                formulaire de vente de materiaux

DESIGN
    (OK) ajout des icones dans les pages 4107
    