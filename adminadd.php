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

//EMPECHER LES NON ADMINS DE SE CONNECTER A LA PAGE
$requete = "SELECT IdAdministrateur FROM ADMINISTRATEURS WHERE IdJoueur = ".$_SESSION['idJoueur'];
$statement = $pdo->query($requete);
$resultat = $statement->fetch(PDO::FETCH_ASSOC);

if ($resultat == false) {
    header("Location: index.php");
}

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta name="robots" content="noindex, nofollow">
        <title>Ongaku - Admin : Ajouter une musique</title>
        <meta charset="utf-8">
        <meta name="description" content="Interface administrateur">
        <link rel="stylesheet" href="css/styleGeneral.css" />
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
            <form method="post" action="admin_process.php">

                <fieldset>
                    <legend>Ajouter une musique et des paroles</legend>

                    <label>Id de la musique sur Deezer</label>
                    <input required placeholder="ex : 137261720" pattern="\d{1,999}" type="text" name="IdMusique" />

                    <label>Langue</label>
                    <select required id="langues" name="IdLangue">
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
                    
                    <br />
                    
                    <label>Styles</label>
                    <select required id="styles" name="IdStyle">
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
                        $pdo = null;
                ?>
                    </select>
                    <button type="button" id="addstyle">Ajouter un style</button>

                </fieldset>
                <fieldset>
                    <legend>Ajouter les paroles de la musique</legend>
                    <p style="font-family: 'Courier New'">Relisez bien les paroles que vous retranscrivez, si possible vérifiez à l'aide de sites répertoriant déjà les paroles de musique.</p>
                    
                    <label>Paroles</label>
                    <textarea name="Paroles" required cols="50" rows="5" placeholder="Entrez un morceau des paroles que l'on entend dans l'extrait Deezer. Environ 150 caractères maximum, vous pouvez ajouter plusieurs paroles par musique."></textarea>
                    <button type="button" id="addparoles">Ajouter d'autres paroles</button>
                </fieldset>
                <input type="hidden" name="action" value="add" />
                <button type="submit">Valider</button>
            </form>
            <section style="font-family: 'Courier New';">
                <h3>Regles de retranscription</h3>
                <ul>
                    <li>Aucune faute de frappe ou d'orthographe des mots.</li>
                    <li>Ne pas contracter les mots que l'on entend contractés dans la musique (Ex : "je fais" plutôt que "j'fais"), sauf pour les contractions usuelles (Ex : "j'ai" plutôt que "je ai").</li>
                    <li>La ponctuation n'a pas d'importance, à l'exception des traits d'union qui doivent être utilisés à bon escient.</li>
                </ul>
            </section>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/admin.js"></script>
    </body>

    </html>