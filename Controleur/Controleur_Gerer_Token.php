<?php

use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_Mail_ChoisirNouveauMdp;
use App\Vue\Vue_Structure_Entete;
use function App\Fonctions\CalculComplexiteMdp;

$Vue->setEntete(new Vue_Structure_Entete());

switch ($action) {
    case "choixmdp":
        $Vue->setEntete(new Vue_Structure_Entete());
        if ($_REQUEST["mdp1"] != $_REQUEST["mdp2"]) {
            $Vue->addToCorps(new Vue_Mail_ChoisirNouveauMdp($tokenBDD["valeur"], "<label><b>Les mots de passe ne correspondent pas</b></label>"));

        }
        else {
            $complexite = CalculComplexiteMdp($_REQUEST["mdp1"]);
            if ($complexite < 90) {
                $Vue->addToCorps(new Vue_Mail_ChoisirNouveauMdp($tokenBDD["valeur"], "<label><b>Le mot de passe doit avoir une complexite d'au moins 90. Ici elle juste est de $complexite. Vous pouvez augmenter la longueur, le type de caractères (majuscule, miniscule, numérique, caractère spécial)</b></label>"));

            } else {
                Modele_Utilisateur::Utilisateur_Modifier_motDePasse($tokenBDD["idUtilisateur"], $_REQUEST["mdp1"]);
                $Vue->addToCorps(new \App\Vue\Vue_Connexion_Formulaire_client("Changement effectué. Veuillez vous connecter !"));
            }
        }
        break;
    default :
        switch ($tokenBDD["codeAction"]) {
            case 1:
                $Vue->addToCorps(new Vue_Mail_ChoisirNouveauMdp($tokenBDD["valeur"]));

                break;
            default:

                break;
        }
}
