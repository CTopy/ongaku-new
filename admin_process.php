<?php

header("Content-type: text/html; charset=UTF-8");
session_start();
if ($_SESSION['idJoueur'] == false) {
     header("Location: index.php");
 }

//Connection à la base de données
require("param.inc.php");
$pdo = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf-8'");

//  EMPECHER LES NON ADMINS DE SE CONNECTER A LA PAGE
$requete = "SELECT IdAdministrateur FROM ADMINISTRATEURS WHERE IdJoueur = ".$_SESSION['idJoueur'];
$statement = $pdo->query($requete);
$resultat = $statement->fetch(PDO::FETCH_ASSOC);

if ($resultat == false) {
    header("Location: index.php");
}

if (!empty($_POST)) {

    // L'utilisateur a modifié les données d'une musique
    if ($_POST['action'] == "modify") {
        $requete = "UPDATE MUSIQUES SET ";
        $param = array();
        $requeteArray = array();

        if (!empty($_POST['IdMusique'])) {
            if (!isset($resultatDz['error'])) {
            $requeteArray[] ="IdMusique=?, ";
            $param[] = $_POST['IdMusique'];
            } else {
                echo("Il semble y avoir une erreur dans l'ID que vous avez saisie. <a href=\"adminadd.php\">Rééssayez</a>");
            }
        }

        if (!empty($_POST['NomMusique'])) {
            $requeteArray[] ="NomMusique=?, ";
            $param[] = $_POST['NomMusique'];
        }

        if (!empty($_POST['NomAuteur'])) {
            $requeteArray[] ="NomAuteur=?, ";
            $param[] = $_POST['NomAuteur'];
        }

        if ($_POST['IdLangue'] != "false") {
            $requeteArray[] ="IdLangue=?, ";
            $param[] = $_POST['IdLangue'];
        }

        foreach ($requeteArray as $elt) {
            $requete = $requete.$elt;
        }
        $requete = substr($requete, 0, -2);
        $requete = $requete." WHERE MUSIQUES.IdMusique = ?;";
        $param[] = $_POST['oldid'];

        $statement = $pdo->prepare($requete);  
        $statement->execute($param);
        // UPDATE les trucs dans la table MUSIQUES

        if ((isset($_POST['IdStyle']) && ($_POST['IdStyle'] != "false")) || 
            (isset($_POST['IdStyle2']) && ($_POST['IdStyle2'] != "false")) || 
            (isset($_POST['IdStyle3']) && ($_POST['IdStyle3'] != "false")) || 
            (isset($_POST['IdStyle4']) && ($_POST['IdStyle4'] != "false"))) {

            $requete = "DELETE FROM est_du_style WHERE IdMusique = ".addslashes($_POST['oldid']);
            $statement = $pdo->query($requete);
        }

        $requete = "INSERT INTO est_du_style(IdMusique, IdStyle) VALUES (?,?)";
        if (isset($_POST['IdStyle'])) {
            if ($_POST['IdStyle'] != "false") {

                $statement = $pdo->prepare($requete);
                $statement->execute(array($_POST['oldid'], $_POST['IdStyle']));

            }
        }

        if (isset($_POST['IdStyle2'])) {
            if ($_POST['IdStyle'] != "false") {

                $statement = $pdo->prepare($requete);
                $statement->execute(array($_POST['oldid'], $_POST['IdStyle2']));

            }
        }

        if (isset($_POST['IdStyle3'])) {
            if ($_POST['IdStyle'] != "false") {

                $statement = $pdo->prepare($requete);
                $statement->execute(array($_POST['oldid'], $_POST['IdStyle3']));

            }
        }

        if (isset($_POST['IdStyle4'])) {
            if ($_POST['IdStyle'] != "false") {

                $statement = $pdo->prepare($requete);
                $statement->execute(array($_POST['oldid'], $_POST['IdStyle4']));

            }
        }
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST['IdParoles'.$i])) {
                if (!empty($_POST['Paroles'.$i])) {

                    $requete = "UPDATE PAROLES SET Paroles=? WHERE IdParoles = ?";
                    $statement = $pdo->prepare($requete);
                    $statement->execute(array($_POST['Paroles'.$i], $_POST['IdParoles'.$i]));
                } else {
                    $requete = "DELETE FROM PAROLES WHERE IdParoles = ?";
                    $statement = $pdo->prepare($requete);
                    $statement->execute(array($_POST['IdParoles'.$i]));
                }
            } else if (!empty($_POST['Paroles'.$i])) {
                $requete = "INSERT INTO PAROLES (Paroles, IdMusique) VALUES (?, ?)";
                $statement = $pdo->prepare($requete);

                if (!empty($_POST['IdMusique'])) {
                    $statement->execute(array($_POST['Paroles'.$i], $_POST['IdMusique']));
                    echo('done');
                    echo('<br>'.$_POST['Paroles'.$i].$_POST['IdMusique']);
                } else {
                    $statement->execute(array($_POST['Paroles'.$i], $_POST['oldid']));
                    echo('done2');
                }
            }
        }

        echo("Les modifications ont été apportées avec succès. <a href=\"adminmodify.php\">Modifier une autre musique</a>, ou <a href=\"adminadd.php\">ajouter une musique</a>. ");

    } else if ($_POST['action'] == "delete") {
        $requete = "DELETE FROM MUSIQUES WHERE IdMusique = '".addslashes($_POST['IdMusique'])."'";
        $statement = $pdo->query($requete);
        echo("La musique a été supprimée avec succès. <a href=\"adminmodify.php\">Modifier une autre musique</a>, ou <a href=\"adminadd.php\">ajouter une musique</a>. ");
    } else if ($_POST['action'] == "add") {
        ////////////////////////////////////////////////
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
        $url = "https://api.deezer.com/track/".$_POST['IdMusique'];
        $json = file_get_contents($url,false,$context) ;
        $resultatDz = json_decode($json,true) ;
        /////////////////////////////////////////////////

        if (!isset($resultatDz['error'])) {

            $requete = "INSERT INTO MUSIQUES (IdMusique, NomAuteur, NomMusique, IdLangue) VALUES (?, ?, ?, ?)";
            $param = array($_POST['IdMusique'], $resultatDz['artist']['name'], $resultatDz['title'], $_POST['IdLangue']);
            $statement = $pdo->prepare($requete);
            $statement->execute($param);

            $requete = "INSERT INTO est_du_style(IdMusique, IdStyle) VALUES (?,?)";

            if (isset($_POST['IdStyle'])) {
                if ($_POST['IdStyle'] != "false") {

                    $statement = $pdo->prepare($requete);
                    $statement->execute(array($_POST['IdMusique'], $_POST['IdStyle']));

                }
            }

            for ($i = 2; $i <= 4; $i++) {
                if (isset($_POST['IdStyle'.$i])) {
                    if ($_POST['IdStyle'.$i] != "false") {

                        $statement = $pdo->prepare($requete);
                        $statement->execute(array($_POST['IdMusique'], $_POST['IdStyle'.$i]));

                    }
                }
            }
            
            if (!empty($_POST['Paroles'])) {

                    $requete = "INSERT INTO PAROLES (IdMusique, Paroles) VALUES (?,?)";
                    $statement = $pdo->prepare($requete);
                    $statement->execute(array($_POST['IdMusique'], $_POST['Paroles']));
                }

            for ($i = 2; $i <=4; $i++) {
                if (!empty($_POST['Paroles'.$i])) {

                    $requete = "INSERT INTO PAROLES (IdMusique, Paroles) VALUES (?,?)";
                    $statement = $pdo->prepare($requete);
                    $statement->execute(array($_POST['IdMusique'], $_POST['Paroles'.$i]));
                }    
            }
            
            echo("La musique a été ajoutée avec succès ! <a href=\"adminadd.php\">Ajouter une autre musique</a> ou <a href=\"adminmodify.php\">modifier une musique</a>.");
        } else {
            echo("L'ID que vous avez saisi semble ne pas être valide. <a href=\"adminadd.php\">Rééssayez</a>.");
        }
    }
}

$pdo = null;
?>