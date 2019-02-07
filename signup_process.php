<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Ongaku - Créer un compte</title>
    <meta name="description" content="Creer un compte sur Ongaku pour pouvoir profiter de toutes les fonctionnalitées du jeu !" />
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="css/signup_process.css" type="text/css" />
    <link rel="stylesheet" href="css/styleGeneral.css" />
    <link rel="shortcut icon" href="medias/images/boutonVert.png">
</head>


<?php
session_start();

    require("param.inc.php");
    $pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
    $pdo->query("SET NAMES utf8");
    $pdo->query("SET CHARACTER SET 'utf8'");
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $pseudo_erreur1 = NULL;
    $pseudo_erreur2 = NULL;
    $mdp_erreur = NULL;
    $email_erreur1 = NULL;
    $email_erreur2 = NULL;
    $email_erreur3 = NULL;

    //Récuperaton variables
    $i = 0;
    $pseudo= addslashes($_POST["pseudo"]);
    $email= addslashes($_POST["email"]);
    $confirmemail = addslashes($_POST['confirmeremail']);
    $sexe= addslashes($_POST["sexe"]);
    $mdp= addslashes(sha1($_POST["mdp"]));
    $confirmmdp = addslashes(sha1($_POST['confirmermdp']));
    

    //Vérification du pseudo
    $query=$pdo->prepare('SELECT COUNT(*) FROM JOUEURS WHERE PseudoJoueur =:pseudo');
    $query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
    $query->execute();
    $pseudo_used=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    if(!$pseudo_used)
    {
        $pseudo_erreur1 = "Votre pseudo est déjà utilisé par un membre";
        $i++;
    }

    if (strlen($pseudo) < 3 || strlen($pseudo) > 15)
    {
        $pseudo_erreur2 = "Votre pseudo est soit trop grand, soit trop petit";
        $i++;
    }

    //Vérification du mdp
    if ($mdp != $confirmmdp || empty($confirmmdp) || empty($mdp))
    {
        $mdp_erreur = "Votre mot de passe et votre confirmation sont différents, ou sont vides";
        $i++;
    }


    //Adresse email utilisée
    $query=$pdo->prepare('SELECT COUNT(*) FROM JOUEURS WHERE eMailJoueur =:mail');
    $query->bindValue(':mail',$email, PDO::PARAM_STR);
    $query->execute();
    $mail_used=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();

    if(!$mail_used)
    {
        $email_erreur1 = "Votre adresse email est déjà utilisée par un membre";
        $i++;
    }

    //Vérification de la forme
    if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $email_erreur2 = "Le format de votre adresse E-Mail n'est pas valide";
        $i++;
    }

    //Vérification de l'adresse email
    if ($email != $confirmemail || empty($confirmemail) || empty($email))
    {
        $email_erreur3 = "Votre adresse email et votre confirmation sont diffèrente, ou sont vides";
        $i++;
    }
        
    
    //Vérification de l'avatar :
    
$avatar="";

    if(isset($_FILES["avatar"])) {
        
        if($_FILES["avatar"] ["name"] !=".htaccess") {
		  if($_FILES["avatar"] ["type"] == "image/jpeg" || $_FILES["avatar"] ["type"] == "image/pjpeg"){
			copy($_FILES["avatar"]["tmp_name"],"./medias/images/avatars/".$_FILES["avatar"] ["name"]);
            $avatar="./medias/images/avatars/vignette_".$_FILES["avatar"] ["name"];
			require (__DIR__.'/vendor/autoload.php');
			$convertImage = new ManipulerImage\Convertir($_FILES["avatar"] ["tmp_name"],"./medias/images/avatars/vignette_".$_FILES["avatar"] ["name"]);
			$convertImage->convertirImage85x85();
			$convertImage->convertirImage595("./medias/images/avatars/vignette_".$_FILES["avatar"] ["name"]);
		}
	}
    }
    
        
    $query=$pdo->prepare('SELECT COUNT(*) FROM JOUEURS WHERE URLAvatarJoueur =:nomavatar');
    $query->bindValue(':nomavatar',$avatar, PDO::PARAM_STR);
    $query->execute();
    $mail_used=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    
//    if (isset ($_GET["image"])) {
//    $nomImage = $_GET["image"] ;
//    $im = imagecreatefromjpeg("./medias/images/avatars/reduction_".$nomImage) ;
//    $logo = imagecreate(300, 20) ;
//    $background_color = imagecolorallocate($logo, 0, 0, 0);
//    $text_color = imagecolorallocate($logo, 233, 14, 91);
//    imagestring($logo, 1, 5, 5,  "Votre adresse Ip est ".$adressIP, $text_color);
//    imagecopymerge($im, $logo, 10, 10, 0, 0, 200, 47, 75);
//    // Affichage de l'image
//    imagejpeg($im);
//    // Libération de la mémoire
//    imagedestroy($im);
//  }	

?>

    <body>
        <?php
    if ($i==0){
?>
            <main>
                <?php
        echo'<h1 class="titre">Inscription terminée !</h1>';
?>
                    <div class="error">
                        <?php
        echo'<p>Bienvenue '.stripslashes(htmlspecialchars($_POST['pseudo'])).' vous êtes maintenant inscrit sur le site !</p>
        <p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil</p>';
?>
                    </div>
                    <?php
    

        $query=$pdo->prepare('INSERT INTO JOUEURS (PseudoJoueur, eMailJoueur, MdpJoueur, SexeJoueur, URLAvatarJoueur)
        VALUES (:pseudo, :email, :mdp, :sexe, :nomavatar);');
        $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->bindValue(':mdp', $mdp, PDO::PARAM_INT);
        $query->bindValue(':sexe', $sexe, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':nomavatar', $avatar, PDO::PARAM_STR);
        $query->execute();

    //variables de sessions
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['email'] = $email;
        $_SESSION['idJoueur'] = $pdo->lastInsertId(); ;
        $query->CloseCursor();

        
        
    }else {
?>
                        <?php
        echo'<h1 class="titre">OUPS !</h1>';
?>
                            <div class="error">
                                <?php
        echo'<p>Une ou plusieurs erreurs se sont produites pendant l\'incription...</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$pseudo_erreur1.'</p>';
        echo'<p>'.$pseudo_erreur2.'</p>';
        echo'<p>'.$email_erreur1.'</p>';
        echo'<p>'.$email_erreur2.'</p>';
        echo'<p>'.$email_erreur3.'</p>';
        echo'<p>'.$mdp_erreur.'</p>';

        echo'<p>Cliquez <a href="./creer-un-compte.php">ici</a> pour recommencer</p>';
?>
                            </div>
            </main>
            <?php        
    }
?>
    </body>

</html>