<?php
namespace App\Fonctions;
    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function GenereMDP($nbChar) :string{

    return "secret";
}

function CalculComplexiteMdp($mdp) :int
{
    //Fonction qui calcule la complexite binaire d'un mot de passe selon la nature des caractères qui le compose
    //Le mot de passe doit être composé de 8 caractères minimum
    //Le mot de passe doit contenir au moins un caractère de chaque type suivant : majuscule, minuscule, chiffre, caractère spécial
    //Le mot de passe ne doit pas contenir de caractère accentué

    $complexite = 0;
    $nbCaracteres = strlen($mdp);
    $nbMajuscules = 0;
    $nbMinuscules = 0;
    $nbChiffres = 0;
    $nbSpeciaux = 0;
    $nbAccentues = 0;
    $nbAutres = 0;

    for ($i = 0; $i < $nbCaracteres; $i++) {
        $caractere = $mdp[$i];
        if (ctype_upper($caractere)) {
            $nbMajuscules++;
        } elseif (ctype_lower($caractere)) {
            $nbMinuscules++;
        } elseif (ctype_digit($caractere)) {
            $nbChiffres++;
        } elseif (preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $caractere)) {
            $nbSpeciaux++;
        } elseif (preg_match('/[éèêàùâûîôëïüö]/', $caractere)) {
            $nbAccentues++;
        } else {
            $nbAutres++;
        }
    }

    if ($nbMajuscules > 0) {
        $complexite += 26;
    }

    if ($nbMinuscules > 0) {
        $complexite += 26;
    }

    if ($nbChiffres > 0) {
        $complexite += 10;
    }

    if ($nbSpeciaux > 0) {
        $complexite += 10;
    }

    if ($nbAccentues > 0) {
        $complexite += 10;
    }

    if ($nbAutres > 0) {
        $complexite += 10;
    }

    $calculComplexite = log(pow($complexite , $nbCaracteres)) / log(2);

    return $calculComplexite;
}


