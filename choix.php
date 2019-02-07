<?php
header("Content-type: text/html; charset=UTF-8");

//Connection à la base de données
require("param.inc.php");
$pdo = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf-8'");
?>
<!DOCTYPE HTML>

<html lang="fr">

    <head>
        <title>Ongaku - Choix</title>
        <meta charset="utf-8">
        <meta name="description" content="Choisir son Jeu, son style de musique et son language ">
        <link rel="stylesheet" href="css/choix.css" type="text/css" />
        <link rel="stylesheet" href="css/styleGeneral.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="shortcut icon" href="medias/images/boutonVert.png">
    </head>

    <body>
        <?php
        include("header.php");
        ?>
        <main>
            <form id="form" method="post" action="jeu.php">
                <h1>Choisissez votre mode de jeu</h1>
                <div>
                    <div class="fieldset" id="jeu">
                        <h2 id="jeux">JEU</h2>

                        <div class="checkbox">
                            <input id="classique" type="radio" name="jeu" value="classique" />
                            <label for="classique">CLASSIQUE

                            </label>
                        </div>


                        <div class="checkbox">
                           <!-- <input id="chrono" type="radio" name="jeu" value="classique" /> -->
                            <label>CHRONO</label><sup>Prochainement disponible</sup>
                        </div>
                    </div>

                    <div class="fieldset" id="style">

                        <h2 id="style">STYLES</h2>


                        <div id="choix">
                            <?php

                            //Demander la liste des styles à la base de donnée
                            $requete = "SELECT IdStyle, LibelleStyle FROM STYLES";
                            $resultats = $pdo->query($requete);
                            $ligne = $resultats->fetch(PDO::FETCH_ASSOC); 

                            //Les afficher
                            while ($ligne != false) {
                            ?>
                            <input id="<?php echo($ligne['IdStyle']) ?>" type="checkbox" name="style[]" value="<?php echo($ligne['IdStyle']) ?>" />
                            <label for="<?php echo($ligne['IdStyle']) ?>" data-numero="<?php echo($ligne['IdStyle']) ?>">
                                <?php echo($ligne['LibelleStyle']) ?>
                            </label>

                            <?php
                                $ligne = $resultats->fetch(PDO::FETCH_ASSOC); 
                            }
                            ?>
                        </div>
                    </div>
                    <!-- <button type="button" class="down"></button> -->
                </div>



                <div>

                    <div class="fieldset" id="langue">
                        <!-- <button type="button" class="up"></button> -->
                        <h2 id="langues">LANGUEs</h2>

                        <?php

                        //Demander la liste des styles à la base de donnée
                        $requete = "SELECT IdLangue, LibelleLangue FROM LANGUES";
                        $resultats = $pdo->query($requete);
                        $ligne = $resultats->fetch(PDO::FETCH_ASSOC); 


                        //Les afficher
                        while ($ligne != false) {
                        ?>
                        <input id="<?php echo($ligne['IdLangue']) ?>" type="checkbox" name="langue[]" value="<?php echo($ligne['IdLangue']) ?>" />
                        <label for="<?php echo($ligne['IdLangue']) ?>">
                            <?php echo($ligne['LibelleLangue']) ?>
                        </label>

                        <?php
                            $ligne = $resultats->fetch(PDO::FETCH_ASSOC); 
                        }
                        $pdo = null;
                        ?>
                        <!-- <button type="button" class="down"></button> -->
                    </div>
                    <div class="fieldset" id="valider">
                        <input type="image" src="medias/images/Elements/boutonChoix.png" height="300" width="300" alt="Valider" />
                        <svg height="0" xmlns="http://www.w3.org/2000/svg">
                            <filter id="drop-shadow">
                                <feGaussianBlur in="SourceAlpha" stdDeviation="4" />
                                <feOffset dx="12" dy="12" result="offsetblur" />
                                <feFlood flood-color="rgba(0,0,0,0.5)" />
                                <feComposite in2="offsetblur" operator="in" />
                                <feMerge>
                                    <feMergeNode/>
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                        </svg>
                    </div>
                </div>

            </form>

        </main>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/header.js"></script>
        <script src="js/choix.js"></script>
    </body>

</html>