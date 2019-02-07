<?php

session_start();

require("param.inc.php");

//Créer l'objet PDO pour accéder à la BDD
$pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf8'");
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

//Si le joueur est connecté
if (!empty($_SESSION['idJoueur'])){

    //Vérifier si l'utilisateur est admin
    $requete = "SELECT IdAdministrateur FROM ADMINISTRATEURS WHERE IdJoueur = :id";
    $statement = $pdo->prepare($requete);
    $statement->bindParam(':id', $_SESSION['idJoueur'], PDO::PARAM_INT);
    $statement->execute();
    $resultat = $statement->fetch(PDO::FETCH_ASSOC);

    if ($resultat)
        $estAdmin = true;
    else $estAdmin = false;

    $query=$pdo->prepare('SELECT PseudoJoueur, SexeJoueur, URLAvatarJoueur FROM JOUEURS WHERE idJoueur=:id');
    $query->bindValue(':id',$_SESSION["idJoueur"],PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();

    if (!$estAdmin) {
?>

<header>
    <div>
        <img id="iconeMenu" src="medias/images/deroulant.png" alt="Menu déroulant" />
        <nav>
            <ul class="menuDeroulant" >
                <li><a href="deconnexion.php">Se deconnecter</a></li>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="jeu.php">Jouer</a></li>
                <li><a href="a-propos.php">A propos</a></li>
            </ul>
        </nav>

        <div class="reseaux">
            <a href="https://twitter.com/Ongaku76824917"> <img class="twitter" src="medias/images/twitter.png" alt="twitter" /></a>
            <a href="https://www.facebook.com/OngakuMMi/?modal=admin_todo_tour"> <img class="facebook" src="medias/images/facebook.png" alt="facebook" /></a>
            <img id="iconeRegle" src="medias/images/boutonLivreFerme.png" alt="Règles">
            <section id="nav2">
                <ul class="menuDeroulant">
                    <li>Vous pouvez remplir les mots de la musique pendant 45 secondes (30 secondes de musique et 15 secondes supplémentaires pour écrire)</li>
                    <li>Le seul moyen pour compléter les mots manquant c'est de les écrire dans la barre de réponse</li>
                    <li>Attention, les mots doivent être écrits sans aucune faute, accents compris !</li>
                    <li>Vous pouvez mettre tous les mots en même temps dans la barre de réponse même s'ils ne se suivent pas dans les paroles</li>
                    <li>Une partie est composée de 10 chansons différentes</li>
                </ul>
            </section>
        </div>
    </div>

    <p><a href="index.php" >ONGAKU</a></p>

    <div>
        <p id="pseudo"><?php print($data['PseudoJoueur']) ?></p>

            <?php if(!empty($data['URLAvatarJoueur'])){ ?>
        <a href="profil.php"> <img class="iconeMembre" src="<?php echo($data['URLAvatarJoueur']); ?>" alt="Icône du profil" /></a>
            <?php } else { ?>
        <a href="profil.php"> <img class="iconeMembre" src="medias/images/logoInvite.png" alt="Icône du profil" /></a>
            <?php } ?>
    </div>
</header>
<?php
    } else {
?>
<header>
    <div>
        <img id="iconeMenu" src="medias/images/deroulant.png" alt="Menu déroulant" />
        <nav>
            <ul class="menuDeroulant" >
                <li><a href="deconnexion.php">Se deconnecter</a></li>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="jeu.php">Jouer une partie</a></li>
                <li><a href="a-propos.php">A propos</a></li>
                <li><a href="adminadd.php">Interface admin</a></li>
            </ul>
        </nav>

        <div class="reseaux">
            <a href="https://twitter.com/Ongaku76824917"> <img class="twitter" src="medias/images/twitter.png" alt="twitter" /></a>
            <a href="https://www.facebook.com/OngakuMMi/?modal=admin_todo_tour"> <img class="facebook" src="medias/images/facebook.png" alt="facebook" /></a>
            <img id="iconeRegle" src="medias/images/boutonLivreFerme.png" alt="Règles">
            <section id="nav2">
                <ul class="menuDeroulant">
                    <li>Vous pouvez remplir les mots de la musique pendant 45 secondes (30 secondes de musique et 15 secondes supplémentaires pour écrire)
                    <li>Le seul moyen pour compléter les mots manquant c'est de les écrire dans la barre de réponse</li>
                     <li>Attention, les mots doivent être écrits sans aucune faute, accents compris !</li>
                    <li>Vous pouvez mettre tous les mots en même temps dans la barre de réponse même s'ils ne se suivent pas dans les paroles</li>
                    <li>Une partie est composée de 10 chansons différentes</li>
                </ul>
            </section>
        </div>
    </div>

    <p><a href="index.php" >ONGAKU</a></p>

    <div>
        <p id="pseudo"><?php print($data['PseudoJoueur']) ?></p>

        <?php if(!empty($data['URLAvatarJoueur'])){ ?>
        <a href="profil.php"> <img class="iconeMembre" src="<?php echo($data['URLAvatarJoueur']); ?>" alt="Icône du profil" /></a>
        <?php } else { ?>
        <a href="profil.php"> <img class="iconeMembre" src="medias/images/logoInvite.png" alt="Icône du profil" /></a>
        <?php } ?>
    </div>
</header>

<?php
    }
} else { //S'il n'est pas connecté
?>

<header>
    <div>
        <img id="iconeMenu" src="medias/images/deroulant.png" alt="Menu déroulant"/>
        <nav>
            <ul class="menuDeroulant">
                <li><a id="boutonCreer" href="creer-un-compte.php">creer un compte</a></li>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="jeu.php">Jouer une partie</a></li>
                <li><a href="a-propos.php">A propos</a></li>
            </ul>
        </nav>
        <div class="creerUnCompte">
            <p>Créez un compte pour suivre votre progression ! Tous vos scores seront enregistrés sur votre page profil !</p>
        </div>
        <div class="reseaux">
            <a href="https://twitter.com/Ongaku76824917"> <img class="twitter" src="medias/images/twitter.png" alt="twitter" /></a>
            <a href="https://www.facebook.com/OngakuMMi/?modal=admin_todo_tour"> <img class="facebook" src="medias/images/facebook.png" alt="facebook" /></a>
            <img id="iconeRegle" src="medias/images/boutonLivreFerme.png" alt="Règles">
            <section id="nav2">
                <ul class="menuDeroulant">
                    <li>Vous pouvez remplir les mots de la musique pendant 45 secondes (30 secondes de musique et 15 secondes supplémentaires pour écrire)
                    <li>Le seul moyen pour compléter les mots manquant c'est de les écrire dans la barre de réponse</li>
                    <li>Attention, les mots doivent être écrits sans aucune faute, accents compris !</li>
                    <li>Vous pouvez mettre tous les mots en même temps dans la barre de réponse même s'ils ne se suivent pas dans les paroles</li>
                    <li>Une partie est composée de 10 chansons différentes</li>
                </ul>
            </section>
        </div>
    </div>

    <p id="ongakuInvite"><a href="index.php" >ONGAKU</a></p>

    <div>
        <p id="pseudo">Invite</p>
        <a href="connexion.php" > <img class="iconeMembre" src="medias/images/logoInvite.png" alt ="Icône du profil"/></a>
    </div>
</header>

<?php
}
?>
