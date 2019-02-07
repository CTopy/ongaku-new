<?php
// Cette page sert uniquement à faire fonctionner la page jeu.php

header("Content-type: text/html; charset=UTF-8");
session_start();

$caracteresSpeciaux = 'àáâæçèéêëìíîïñòóôœùúûüýÿÀÁÂÆÇÈÉÊËÌÍÎÏÑÒÓÔŒÙÚÛÜÝŸ';        // Contient tous les caractères spéciaux, utile pour le str_word_count

//Connection à la base de données
require("param.inc.php");
$pdo = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf-8'");

//Cette fonction retourne un tableau qui contient un texte à trous
//ainsi qu'un tableau qui contient chaque mot caché
function cacherMots($phrase) {

    $caracteresSpeciaux = 'àáâæçèéêëìíîïñòóôœùúûüýÿÀÁÂÆÇÈÉÊËÌÍÎÏÑÒÓÔŒÙÚÛÜÝŸ';        // Contient tous les caractères spéciaux, utile pour le str_word_count

    $tabMots = str_word_count(strtolower($phrase), 1, $caracteresSpeciaux);
    $nbMotsCaches = (1/3) * count($tabMots);
    $index = 0;

    do {
        $motsCaches = array();
        foreach ($tabMots as $elt) {
            $alea = rand(0, 10);
            if ($alea == 1) {
                $motsCaches[$index] = $elt;
                $index++;
            }
        }
    } while (count($motsCaches) < 3);

    foreach ($motsCaches as $elt) {
        $strReplace = "";
        for ($i = 0; $i < strlen($elt); $i++) {
            $strReplace = $strReplace."_";
        }

        $phrase = preg_replace('/(?i)((?:(^|[\s,.]))'.$elt.'(?:$|[\s,.]))/', " ".$strReplace." ", $phrase);
        // Thanks to Banou and S3B4S, the bestest \o/

    }

    return array(
        "motsCaches" => $motsCaches,
        "phrase" => $phrase
    );
}

//Cette fonction permet de cacher les mots de $tabMots dans la $phrase,
//elle permet le traitement de la réponse du joueur
function remplacerMots($phrase, $tabMots) {

    $caracteresSpeciaux = 'àáâæçèéêëìíîïñòóôœùúûüýÿÀÁÂÆÇÈÉÊËÌÍÎÏÑÒÓÔŒÙÚÛÜÝŸ';        // Contient tous les caractères spéciaux, utile pour le str_word_count

    foreach ($tabMots as $elt) {
        $strReplace = "";
        for ($i = 0; $i < strlen($elt); $i++) {
            $strReplace = $strReplace."_";
        }

        $phrase = preg_replace('/(?i)((?:(^|[\s(,.]))'.$elt.'(?:$|[\s),.]))/', " ".$strReplace." ", $phrase);
        // Thanks Banou and S3B4S, the bestest \o/²

    }

    return $phrase;
}

//
//SI L'UTILISATEUR A ENVOYE DES DONNEES
//
if(isset($_POST['saisieUser'])) {

    //Récupérer les données envoyées par Javascript
    $saisieUser = strtolower($_POST['saisieUser']); //Saisie utilisateur
    $idParoles = $_POST['idParoles']; //Id de la phrase affichée

    //Eclater sa réponse en un tableau de mots
    $motsUser = str_word_count($saisieUser, 1, $caracteresSpeciaux);

    //PHP récupère les paroles complètes
    $requete = "SELECT Paroles FROM PAROLES WHERE IdParoles = :id";
    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(":id", $idParoles, PDO::PARAM_STR_CHAR);
    $stmt->execute();
    $paroles = $stmt->fetch(PDO::FETCH_ASSOC);
    $paroles = $paroles['Paroles'];

    //Récupérer la saisie sous forme de tableau
    $tabSaisie = str_word_count($saisieUser, 1, $caracteresSpeciaux);

    $motsCaches = array_diff($_SESSION['motsCaches'], $tabSaisie);

    if (!empty($motsCaches)) {
        //            Il reste encore des mots à trouver
        echo("false;".remplacerMots($paroles, $motsCaches).";");
        $_SESSION['motsCaches'] = $motsCaches;
    } else {
        //        AH OUAIS OUAIS OUAIS OUAIS OUAIS, C'EST GAGNE, C'EST OUI
        echo("true;".remplacerMots($paroles, $motsCaches).";");
    }

    //
    //SINON
    //
} else {
    //Choisir une musique au hasard selon un critère
    //A AMELIORER : Récupérer le critère de la page de sélection
    //Ajouter "WHERE" pour critère
    // Faire en sorte de ne pas pouvoir avoir la même musique 2 fois

    //Récupérer une musique dans la BDD
    $compteur = 0;
    do {
        do {
            $requete = "SELECT IdMusique, NomMusique, NomAuteur FROM MUSIQUES ";
            $styles = [];
            $musiques = [];
            $langues = [];

            if (!empty($_SESSION['styles'])) {
                $requete = $requete . "INNER JOIN est_du_style ON MUSIQUES.IdMusique = est_du_style.IdMusique INNER JOIN STYLES ON est_du_style.IdStyle = STYLES.IdStyle WHERE (";
                foreach ($_SESSION['styles'] as $style) {
                    $requete = $requete . "STYLES.IdStyle = ? OR ";
                    $styles[] = $style;
                }
                $requete = $requete . "1<>1) ";
            }

            if (!empty($_SESSION['musiques'])) {
                $requete = $requete . "AND (";
                foreach ($_SESSION['musiques'] as $elt) {
                    $requete = $requete."MUSIQUES.IdMusique <> ? AND ";
                    $musiques[] = $elt;
                }
                $requete = $requete . "1=1) ";

            }

            if (!empty($_SESSION["langues"])) {
                $requete = $requete . "AND (";
                foreach ($_SESSION["langues"] as $elt) {
                    $requete = $requete."IdLangue = ? OR ";
                    $langues[] = $elt;
                }
                $requete = $requete . "1<>1) ";
            }

            $requete = $requete."ORDER BY RAND() LIMIT 1";
            $requeteAfficher = $requete;    //Afficher la requête pour test
            $stmt = $pdo->prepare($requete);
            $stmt->execute(array_merge($styles, $musiques, $langues));
            $resultats = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultats === false) {
                unset($langues);
                $langues = [];

                $requete = "SELECT MUSIQUES.IdMusique, NomMusique, NomAuteur FROM MUSIQUES WHERE ";
                if (!empty($_SESSION["langues"])) {
                    foreach ($_SESSION["langues"] as $elt) {
                        $requete = $requete."IdLangue = ? OR ";
                        $langues[] = $elt;
                    }
                    $requete = $requete . "1<>1 ";
                }
                $requete = $requete . "ORDER BY RAND() LIMIT 1";
                unset($resultats);
                $stmt = $pdo->prepare($requete);
                $stmt->execute($langues);
                $resultats = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $idmusique = $resultats['IdMusique'];

            // Ajouter la musique aux musiques déjà passées
            $_SESSION['musiques'][] = $idmusique;

            //
            //Récupérer les données Deezer
            //
            $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => 1
                )
            ));
            $response = @file_get_contents("http://www.google.fr",0,$ctx);
            if ($response === false)
                $opts = array(
                "http" => array(
                    "proxy" => "tcp://proxy.univ-lemans.fr:3128" ,
                    "request_fulluri" => true
                ),
            );
            else
                $opts = array(
                "http" => array(
                    "request_fulluri" => true
                ),
            );;
            $context = stream_context_create($opts);
            $url = "https://api.deezer.com/track/".$idmusique;
            $json = file_get_contents($url,false,$context) ;
            $resultatDz = json_decode($json,true) ;
            //
            //Fin de la récupération des données Deezer
            //
        } while (empty($resultatDz['preview']));
        //Sélectionner des paroles au hasard parmis celles disponibles pour la chanson en cours
        $requete = "SELECT Paroles, IdParoles FROM PAROLES WHERE IdMusique = ? ORDER BY RAND() LIMIT 1";
        $stmt = $pdo->prepare($requete);
        $stmt->execute(array($idmusique));
        $paroles = $stmt->fetch(PDO::FETCH_ASSOC);

        if($compteur > 500) {
            echo("La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;La boucle a été executée 500 fois, erreur;");
            break;
        }
    } while ($paroles === false);

    $arrayText = cacherMots($paroles["Paroles"]);

    $_SESSION['motsCaches'] = $arrayText['motsCaches'];
    //Echo le résultat sous forme d'une string qui sera déchiffrée par le Javascript
    echo($resultatDz['preview'].
         ";".$resultats['NomMusique'].
         ";".$resultats['NomAuteur'].
         ";".$requeteAfficher.//Rien du tout
         ";".$arrayText['phrase'].
         ";".$paroles['IdParoles']);
}

$pdo = null;
