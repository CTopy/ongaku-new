<?php

header("Content-type: text/html; charset=UTF-8");
session_start();

if ($_SESSION['idJoueur'] === false)
     header("Location: index.php");

//Connection à la base de données
require("param.inc.php");
$pdo = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf-8'");

//EMPECHER LES NON ADMINS DE SE CONNECTER A LA PAGE
$requete = "SELECT IdAdministrateur FROM ADMINISTRATEURS WHERE IdJoueur = ?";
$statement = $pdo->prepare($requete);
$statement->execute(array($_SESSION['idJoueur']));
$resultat = $statement->fetch(PDO::FETCH_ASSOC);

if ($resultat === false)
    header("Location: index.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="robots" content="noindex, nofollow">
        <title>Ongaku - Admin : Modifier une musique</title>
        <meta charset="utf-8">
        <meta name="description" content="Interface administrateur">
        <link rel="stylesheet" href="css/styleGeneral.css" />
        <style>
            p {
                font-family: 'Courier New';
                font-size: 12pt;
            }
        </style>
    </head>

    <body>
        <header>
            <h1 style="margin : 0;">Ongaku - Interface administrateur</h1>
            <ul style="list-style: none;">
                <li><a href="adminadd.php">Ajouter une musique</a></li>
                <li><a href="adminmodify.php">Modifier une musique</a></li>
            </ul>
        </header>

        <main>

            <form action="adminmodify.php" method="post">
                <fieldset>
                    <legend>Chercher une musique (Remplir au moins un champ)</legend>

                    <label>Recherche par ID</label>
                    <input autocomplete="off" placeholder="ex : 137261720" pattern="\d{1,999}" type="text" name="IdMusique" />
                    <br />

                    <label>Recherche par mots cles</label>
                    <input autocomplete="off" size="40" style="font-family: 'Courier New'; font-size: 12pt;" placeholder="ex : les cowboys fringants" type="text" name="motscles" />
                    <br />

                    <label>Recherche par style</label>
                    <select name="IdStyle">
                        <option default value="false">Aucun</option>
                        <?php
                        $requete = "SELECT IdStyle, LibelleStyle FROM STYLES";
                        $statement = $pdo->query($requete);
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                        while ($ligne != false) {?>

                        <option value="<?php echo($ligne['IdStyle']) ?>">
                            <?php echo($ligne['LibelleStyle']) ?>
                        </option>

                        <?php
                            $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                                                }
                        ?>
                    </select>
                    <br />



                    <label>Recherche par langue</label>
                    <select id="langues" name="IdLangue">
                        <option default value="false">Aucun</option>
                        <?php
                        $requete = "SELECT IdLangue, LibelleLangue FROM LANGUES";
                        $statement = $pdo->query($requete);
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                        while ($ligne != false) {?>

                        <option value="<?php echo($ligne['IdLangue']) ?>">
                            <?php echo($ligne['LibelleLangue']) ?>
                        </option>

                        <?php
                            $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                                                }
                        ?>
                    </select>
                </fieldset>

                <input type="hidden" name="action" value="search" />
                <button type="submit">Recherche</button>
            </form>

            <?php

            //**********************************************************************************
            //**********************************************************************************
            //**********************************************************************************
            //*****************************************
            //***************************************** SI LA PAGE A RECU DES INFORMATIONS
            //*****************************************
            //**********************************************************************************
            //**********************************************************************************
            //**********************************************************************************

            if (!empty($_POST)) {
                $requete = "";


                //**********************************************************************************
                //**********************************************************************************
                //***************************************** SI L'UTILISATEUR A RECHERCHE UNE MUSIQUE
                //**********************************************************************************
                //**********************************************************************************

                if ($_POST['action'] == "search") {

                    // On crée une recherche en fonction des paramètres saisis

                    $requete = "SELECT NomMusique, LANGUES.IdLangue, NomAuteur, MUSIQUES.IdMusique AS IdDeMusique, LibelleLangue, GROUP_CONCAT(LibelleStyle) AS StylesMus FROM MUSIQUES LEFT OUTER JOIN LANGUES ON MUSIQUES.IdLangue = LANGUES.IdLangue LEFT OUTER JOIN est_du_style ON MUSIQUES.IdMusique = est_du_style.IdMusique LEFT OUTER JOIN STYLES ON est_du_style.IdStyle = STYLES.IdStyle WHERE 1=1";

                    if (!empty($_POST['motscles'])) {
                        $requete = $requete." AND NomMusique LIKE '%".addslashes($_POST["motscles"])."%' OR NomAuteur LIKE '%".addslashes($_POST["motscles"])."%'";
                    }

                    if (!empty($_POST['IdMusique'])) {
                        $requete = $requete." AND MUSIQUES.IdMusique = '".addslashes($_POST['IdMusique'])."'";
                    }

                    if ($_POST["IdStyle"] != "false") {
                        $requete = $requete." AND STYLES.IdStyle = '".addslashes($_POST['IdStyle'])."'";
                    }

                    if ($_POST['IdLangue'] != "false") {
                        $requete = $requete." AND MUSIQUES.IdLangue = '".addslashes($_POST['IdLangue'])."'";
                    }

                    $requete = $requete." GROUP BY MUSIQUES.IdMusique";
                    //Execution de la requête
                    $statement = $pdo->query($requete);

                    //
                    // Si aucun résultat n'a été trouvé
                    //
                    if ($statement === false) {
                        $ligne = false;
            ?>
            <p><strong>Aucun résultat n'a été trouvé pour votre recherche. Rééssayez avec des mots clés différents, ou des paramètres de recherche différents</strong></p>
            <?php
                    } else $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                    //Tant qu'on trouve des résultats, on les affiche
                    while ($ligne) {
            ?>

            <form id="modifierMusique" action="adminmodify.php" method="post">
                <fieldset>
                    <p>Titre :
                        <?php echo($ligne['NomMusique']) ?>
                    </p>
                    <input type="hidden" name="NomMusique" value="<?php echo($ligne['NomMusique']) ?>" />

                    <p>Auteur :
                        <?php echo($ligne['NomAuteur']) ?>
                    </p>
                    <input type="hidden" name="NomAuteur" value="<?php echo($ligne['NomAuteur']) ?>" />

                    <p>Id Deezer :
                        <?php echo($ligne['IdDeMusique']) ?>
                    </p>
                    <input type="hidden" name="IdDeezer" value="<?php echo($ligne['IdDeMusique']) ?>" />

                    <?php
                // Si la langue de la musique a été renseignée
                if ($ligne['LibelleLangue']) {
                    ?>

                    <p>Langue :
                        <?php echo($ligne['LibelleLangue']) ?>
                    </p>
                    <input type="hidden" name="IdLangue" value="<?php echo($ligne['IdLangue']) ?>" />

                    <?php
                        // Sinon, afficher une erreur
                } else { ?>

                    <p>Langue : Aucune enregistrée</p>

                    <?php }

                        //
                        // Si le style de la musique a été renseigné
                        //

                        if ($ligne['StylesMus']) {
                    ?>

                    <p>Styles :
                        <?php echo($ligne['StylesMus']) ?>
                    </p>

                    <?php
                        //SINON
                        } else {
                    ?>

                    <p>Styles : Aucun enregistré</p>

                    <?php
                        }

                        //
                        // On recherche les paroles liées à cette musique
                        //
                        $requete2 = "SELECT Paroles, IdParoles FROM PAROLES WHERE PAROLES.IdMusique = '".addslashes($ligne['IdDeMusique'])."'";
                        $statement2 = $pdo->query($requete2);

                        //Si aucune n'a été trouvée
                        if ($statement2 === false) {
                    ?>

                    <p>Aucunes paroles n'ont été trouvées pour cette chanson, cela semble être une anomalie. Ajoutez les paroles de l'extrait Deezer</p>

                    <?php
                        } else {
                        //Sinon on affiche toutes les paroles
                            $ligne2 = $statement2->fetch(PDO::FETCH_ASSOC);
                        while ($ligne2 != false) {

                    ?>

                    <div style="border : 1px solid blue;">
                        <p>
                            <?php echo($ligne2['Paroles']); ?>
                        </p>
                        <input type="hidden" name="IdParoles" value="<?php echo($ligne2['IdParoles']); ?>" />
                    </div>
                    <?php
                    $ligne2 = $statement2->fetch(PDO::FETCH_ASSOC);
                        }} // En boucle
                    ?>

                    <button name="action" value="modify" type="submit">Modifier cette musique</button>

                </fieldset>
            </form>
            <form action="admin_process.php" method="post" id="supprimerMusique">
                        <input type="hidden" name="IdMusique" value="<?php echo($ligne['IdDeMusique']) ?>" />
                        <button id="delete" name="action" value="delete" type="submit">^ Supprimer cette musique ^</button>
                    </form>

            <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }
                }

                //**********************************************************************************
                //**********************************************************************************
                //***************************************** SI L'UTILISATEUR MODIFIE UNE MUSIQUE
                //**********************************************************************************
                //**********************************************************************************
                if ($_POST['action'] == "modify") {
            ?>

            <form method="post" action="admin_process.php">
                <fieldset>
                    <style>
                        input {
                            font-family: 'Courier New';
                            font-size: 12pt;
                        }

                        label {
                            font-weight: bold;
                            font-family: 'Courier New';
                        }
                    </style>
                    <legend>Modifier une musique</legend>
                    <p>Id Deezer :
                        <?php echo($_POST["IdDeezer"]); ?>
                    </p>
                    <label>Entrez un nouvel ID. N'entrez rien pour garder lID actuelle.</label>
                    <input placeholder="Ex : 1234567" type="text" name="IdMusique" />

                    <p>Titre :
                        <?php echo($_POST["NomMusique"]); ?>
                    </p>
                    <label>Entrez un nouveau titre. N'entrez rien pour garder le titre actuel.</label>
                    <input placeholder="Ex : On m'appelle l'OVNI" type="text" name="NomMusique" />

                    <p>Auteur :
                        <?php echo($_POST["NomAuteur"]); ?>
                    </p>
                    <label>Entrez un nouvel auteur. N'entrez rien pour garder l'auteur actuel.</label>
                    <input placeholder="Ex : Jul" type="text" name="NomAuteur" />

                    <?php
                    // Si une langue a été trouvée pour la musique à modifier
                    if (isset($_POST["IdLangue"])) {
                    ?>

                    <p>Langue :
                        <?php echo($_POST["IdLangue"]); ?>
                    </p>
                    <label>Changer la langue.</label>

                    <select name="IdLangue">
                        <option value="false">Ne pas changer</option>
                        <?php
                    } else {

                        // Sinon, obliger l'utilisateur à renseigner une langue
                        ?>
                        <p>Aucune langue n'a été renseignée pour cette musique.</p>
                        <label>Ajouter une langue.</label>
                        <select name="IdLangue">
                            <?php
                    }

                    $requete = "SELECT IdLangue, LibelleLangue FROM LANGUES";
                    $statement = $pdo->query($requete);
                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    while ($ligne != false) {
                            ?>

                            <option value="<?php echo($ligne['IdLangue']) ?>">
                                <?php echo($ligne['LibelleLangue']) ?>
                            </option>

                            <?php
                                $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }
                            ?>
                        </select>

                        <?php
                    $requete = "SELECT STYLES.IdStyle AS IdStyle, STYLES.LibelleStyle AS LibelleStyle FROM STYLES LEFT OUTER JOIN est_du_style ON est_du_style.IdStyle = STYLES.IdStyle LEFT OUTER JOIN MUSIQUES ON MUSIQUES.IdMusique = est_du_style.IdMusique WHERE MUSIQUES.IdMusique = '".addslashes($_POST['IdDeezer'])."'";
                    $statement = $pdo->query($requete);
                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    if ($ligne == false) {
                        ?>
                        <p>Aucun style n'a été renseigné pour cette musique.</p>
                        <label>Ajouter un/des style(s)</label>

                        <?php
                    } else {?>
                        <p>Styles :</p>
                        <?php
                        $nbStyles = 0;
                        while ($ligne != false) {
                            $nbStyles++;
                        ?>

                        <p>
                            <?php echo($ligne['LibelleStyle']) ?>
                        </p>

                        <?php
                            $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                        }
                        ?>
                        <label>Modifier les styles</label>
                        <p>Attention ! Si vous ne voulez pas modifier les styles, ne touchez à rien, sinon, sélectionner TOUS les styles qui doivent être appliqués sur la musique, même ceux déjà présents. Lorsque vous validerez, les anciens styles seront supprimés, et les nouveaux seront appliqués.</p>

                        <?php
                    }
                        ?>
                        <select id="styles" class="styles2" name="IdStyle">
                            <option value="false">Ne pas modifier</option>
                            <?php
                    $requete = "SELECT IdStyle, LibelleStyle FROM STYLES";
                    $statement = $pdo->query($requete);
                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                    while ($ligne != false) {?>

                            <option value="<?php echo($ligne['IdStyle']) ?>">
                                <?php echo($ligne['LibelleStyle']) ?>
                            </option>

                            <?php
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                                            }
                            ?>
                        </select>
                        <button type="button" id="addstyle">Ajouter un style</button>
                        </fieldset>
                    <fieldset>
                        <legend>Modifier les paroles liées à cette musique</legend>

                        <?php
                    //
                    // On recherche les paroles liées à cette musique
                    //
                    $requete2 = "SELECT Paroles, IdParoles FROM PAROLES WHERE PAROLES.IdMusique = '".addslashes($_POST['IdDeezer'])."'";
                    $statement2 = $pdo->query($requete2);

                    //Si aucune n'a été trouvée
                    $nbParoles = 0;
                    if (!$statement2) {
                        ?>

                        <p>Aucunes paroles n'ont été trouvées pour cette chanson, cela semble être une anomalie. Ajoutez les paroles de l'extrait Deezer</p>

                        <?php
                    } else { ?>
                        <legend style="font-family : 'Courier New';">Modifier les paroles si besoin, sinon, laisser la zone de texte tel quel, ou vide.</legend>
                        <?php

                    //Sinon on affiche toutes les paroles
                    $ligne2 = $statement2->fetch(PDO::FETCH_ASSOC);
                    while ($ligne2 != false) {
                        $nbParoles++;
                        ?>
                        <br />
                        <textarea cols="50" rows="5" name="Paroles<?php echo($nbParoles); ?>"><?php echo($ligne2['Paroles']); ?></textarea>
                        <input type="hidden" name="IdParoles<?php echo($nbParoles); ?>" value="<?php echo($ligne2['IdParoles']);?>"/>

                        <?php
                        $ligne2 = $statement2->fetch(PDO::FETCH_ASSOC);
                    }} // En boucle
                    if ($nbParoles < 4) {
                        $nbParoles++;
                        for ($nbParoles; $nbParoles <= 4; $nbParoles++) {
                        ?>
                        <br />
                        <textarea cols="50" rows="5" name="Paroles<?php echo($nbParoles); ?>"></textarea>

                        <?php
                        }
                    }
                        ?>
                    </fieldset>
                    <input type="hidden" name="action" value="modify" />
                    <input type="hidden" name="oldid" value="<?php echo($_POST['IdDeezer']) ?>" />
                    <button type="submit">Valider</button>
                    </form>

                <?php

                }}

                ?>
                </main>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="js/admin.js"></script>
            </body>

        </html>
    <?php
    $pdo = null; ?>
