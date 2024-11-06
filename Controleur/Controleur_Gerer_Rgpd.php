<?php

use App\Modele\Modele_Entreprise;
use App\Modele\Modele_Salarie;
use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_ConsentementRGPD;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_Entete;

$Vue->setEntete(new Vue_Structure_Entete());
//$Vue->addToCorps(new Vue_AfficherMessage("<br>Controleur rgpd<br>"));
switch ($action) {

    case "validerRGPD":
        if (isset($_REQUEST["accepterRGPD"])) {
            if ($_REQUEST["accepterRGPD"] == 0) {
                session_destroy();
                unset($_SESSION);
                $Vue->setEntete(new Vue_Structure_Entete());
                $Vue->addToCorps(new Vue_Connexion_Formulaire_client());
            } else {


                $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_SESSION["idUtilisateur"]);
                if ($utilisateur != null) {
                    Modele_Utilisateur::Utilisateur_UpdateRgpd($utilisateur["idUtilisateur"], $_REQUEST["accepterRGPD"], $_SERVER['REMOTE_ADDR']);
                    switch ($utilisateur["idCategorie_utilisateur"]) {
                        case 1:
                            $_SESSION["typeConnexionBack"] = "administrateurLogiciel"; //Champ inutile, mais bien pour voir ce qu'il se passe avec des étudiants !
                            $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                            break;
                        case 2:
                            $_SESSION["typeConnexionBack"] = "gestionnaireCatalogue";
                            $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                            $Vue->addToCorps(new \App\Vue\Vue_AfficherMessage("Bienvenue " . $_REQUEST["compte"]));
                            break;
                        case 3:
                            $_SESSION["typeConnexionBack"] = "entrepriseCliente";
                            //error_log("idUtilisateur : " . $_SESSION["idUtilisateur"]);
                            $_SESSION["idEntreprise"] = Modele_Entreprise::Entreprise_Select_Par_IdUtilisateur($_SESSION["idUtilisateur"])["idEntreprise"];
                            include "./Controleur/Controleur_Gerer_Entreprise.php";
                            break;
                        case 4:
                            $_SESSION["typeConnexionBack"] = "salarieEntrepriseCliente";
                            $_SESSION["idSalarie"] = $utilisateur["idUtilisateur"];
                            $_SESSION["idEntreprise"] = Modele_Salarie::Salarie_Select_byId($_SESSION["idUtilisateur"])["idEntreprise"];
                            include "./Controleur/Controleur_Catalogue_client.php";
                            break;
                        case 5:
                            $_SESSION["typeConnexionBack"] = "commercialCafe";
                            $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                            break;
                    }
                } else {
                    $Vue->addToCorps(new \App\Vue\Vue_AfficherMessage("Erreur utilisateur non trouvé"));

                }
            }
        }
        else
            {
                $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_SESSION["idUtilisateur"]);

                $Vue->addToCorps(new Vue_ConsentementRGPD($utilisateur));
            }


        break;
}