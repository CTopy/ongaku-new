<?php
session_start();
if (empty($_POST)) {
    header("Location: index.php");}

if (!empty($_SESSION['idJoueur'])) {
    
    //Connexion à la base de données
    require("param.inc.php");
    $pdo = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
    $pdo->query("SET NAMES utf8");
    $pdo->query("SET CHARACTER SET 'utf-8'");
    
    //récupération de l'id du joueur
    $query=$pdo->prepare('SELECT PseudoJoueur, SexeJoueur, URLAvatarJoueur FROM JOUEURS WHERE idJoueur=:id');
    $query->bindValue(':id',$_SESSION["idJoueur"],PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    
    //Récupération du score de l'utilisateur
    $requete = "SELECT ScoreJoueur FROM JOUEURS WHERE IdJoueur = '".$_SESSION['idJoueur']."'";
    $resultat = $pdo->query($requete);
    $resultat = $resultat->fetch(PDO::FETCH_ASSOC);
    
    //Ajout du score de l'utilisateur au score de la partie
    $newscore = (int)$resultat['ScoreJoueur'] + (int)$_POST['score'];
    $requete = "UPDATE JOUEURS SET ScoreJoueur = ".$newscore." WHERE IdJoueur = '".$_SESSION['idJoueur']."'";
    $resultat = $pdo->query($requete);
    
    $requete = "SELECT ScoreJoueur FROM JOUEURS WHERE IdJoueur = '".$_SESSION['idJoueur']."'";
    $resultat = $pdo->query($requete);
    $resultat = $resultat->fetch(PDO::FETCH_ASSOC);
    
    $pdo = null;
    
    
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ongaku - Score</title>
    <meta name="description" content="Affichage des scores après la fin de la partie">
    <link rel="stylesheet" href="./css/score.css">
    <link rel="stylesheet" href="./css/styleGeneral.css">
    <link rel="stylesheet" href="./css/jquery-ui.css">
    <link rel="shortcut icon" href="medias/images/boutonVert.png">


</head>

<body>
     

    <main>

        <h1>Score</h1>

        <div class="logoPseudo">
            
<?php
            
            /** test **/
            if (!empty($_SESSION['idJoueur'])) {
?>
            
<?php if(!empty($data['URLAvatarJoueur'])){ ?>
        <img class="iconeMembre" src="<?php echo($data['URLAvatarJoueur']); ?>" alt="Icône du profil" />
        <?php } else if ($data['SexeJoueur'] == "MASCULIN") { ?>
        <img class="iconeMembre" src="medias/images/logoMasculin.png" alt="Icône du profil" />
        <?php } else if ($data['SexeJoueur'] == "FEMININ") { ?>
        <img class="iconeMembre" src="medias/images/logoFeminin.png" alt="Icône du profil" />
        <?php } else if ($data['SexeJoueur'] == "AUTRE") { ?>
        <img class="iconeMembre" src="medias/images/logoInvite.png" alt="Icône du profil" />
        <?php } ?>
            <h2 class="pseudoJoueur"><?php print($data['PseudoJoueur']) ?></h2>
            </div>
<?php
}else {
?>
        <img class="iconeMembre" src="medias/images/logoInvite.png" alt ="Icône du profil"/>
        <h2 class="pseudoJoueur">invite</h2>
        </div>
<?php
}
?>
        <div class="scoreBarre">

            <div class="ombreBarre">

                <div id="barre"></div>

            </div>

            <p class="scoreJoueur">
                <?php echo($_POST['score']); ?>
            </p>

        </div>

        <div class="buttonFinPartie">
            
            <button id="rejourQuitter" class="rejouer">Rejouer</button>
            <button id="rejourQuitter" class="quitter">Quitter</button>

        </div>

    </main>


    <footer>
    </footer>

    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/barreScore.js"></script>

</body>

</html>