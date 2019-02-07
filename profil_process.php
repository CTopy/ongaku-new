
<html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex" />
        <link rel="stylesheet" href="css/signup_process.css" type="text/css" />
        <link rel="stylesheet" href="css/styleGeneral.css" />
    </head>


    <?php
    session_start();

    if (isset($_SESSION['idJoueur']) && !empty($_POST)) {

        require("param.inc.php");
        $pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
        $pdo->query("SET NAMES utf8");
        $pdo->query("SET CHARACTER SET 'utf8'");
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        //Récuperaton variables
        $i = 0;
        $id=$_SESSION["idJoueur"];
        if (!empty($_POST["nvPseudo"])) {
            $pseudo= addslashes($_POST["nvPseudo"]);
        }

        if (!empty($_POST["nvMail"])) {
            $email=addslashes($_POST["nvMail"]);
        }

        if (!empty($_POST["confNvMail"])) {
            $confirmemail = addslashes($_POST['confNvMail']);
        }

        if (!empty($_POST["nvMdp"])) {
            $mdp=addslashes(sha1($_POST["nvMdp"]));
        }

        if (!empty($_POST["confNvMdp"])) {
            $confirmmdp = addslashes(sha1($_POST['confNvMdp']));
        }

        //Infos du membre
        $query=$pdo->prepare('SELECT PseudoJoueur, eMailJoueur, MdpJoueur, URLAvatarJoueur
    FROM JOUEURS WHERE idJoueur=:id');
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $data=$query->fetch(PDO::FETCH_ASSOC);

        $pseudo_erreur1 = NULL;
        $pseudo_erreur2 = NULL;
        $mdp_erreur = NULL;
        $email_erreur1 = NULL;
        $email_erreur2 = NULL;
        $email_erreur3 = NULL;
        $champ_vide = NULL;


        //Vérification du pseudo
        if (!empty($pseudo)){
            $query=$pdo->prepare('SELECT PseudoJoueur FROM JOUEURS WHERE idJoueur=:id'); 
            $query->bindValue(':id',$id,PDO::PARAM_INT);
            $query->execute();
            $data=$query->fetch();
            if (strtolower($data['PseudoJoueur']) != strtolower($pseudo))
            {

                $query=$pdo->prepare('SELECT COUNT(*) FROM JOUEURS WHERE PseudoJoueur =:pseudo');
                $query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
                $query->execute();
                $pseudo_used=($query->fetchColumn()==0)?1:0;
                $query->CloseCursor();
                if(!$pseudo_used)
                {
                    $pseudo_erreur1 = "Votre nouveau pseudo est déjà utilisé par un membre";
                    $i++;
                }

                if (strlen($pseudo) < 3 || strlen($pseudo) > 15)
                {
                    $pseudo_erreur2 = "Votre nouveau pseudo est soit trop grand, soit trop petit";
                    $i++;
                }
            }
        }
        //Vérification du mdp
        if (!empty($mdp)){
            if ($mdp != $confirmmdp || empty($confirmmdp))
            {
                $mdp_erreur = "Votre nouveau mot de passe et votre confirmation sont différents, ou sont vides";
                $i++;
            }
        }



        //Adresse email utilisée
        if (!empty($email)){
            $query=$pdo->prepare('SELECT COUNT(*) FROM JOUEURS WHERE eMailJoueur =:mail');
            $query->bindValue(':mail',$email, PDO::PARAM_STR);
            $query->execute();
            $mail_used=($query->fetchColumn()==0)?1:0;
            $query->CloseCursor();

            if(!$mail_used)
            {
                $email_erreur1 = "Votre nouvelle adresse email est déjà utilisée par un membre";
                $i++;
            }

            //Vérification de la forme
            if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email))
            {
                $email_erreur2 = "Le format de votre nouvelle adresse E-Mail n'est pas valide";
                $i++;
            }

            //Vérification de l'adresse email
            if ($email != $confirmemail)
            {
                $email_erreur3 = "Votre nouvelle adresse email et votre confirmation sont diffèrente";
                $i++;
            }
        }



        $avatar="";

        if(isset($avatar)) {


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

        //     if (isset ($_GET["image"])) {
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
        //        
        if(empty($_POST['nvPseudo']) && empty($_POST['nvMail']) && empty($_POST['nvMdp']) && empty($_FILES['avatar']['name']) ){

            $champ_vide = "Vous devez renseigner au moins un champ pour pouvoir valider";
            $i++;
        }
    ?>

    <body>
        <?php
        if ($i==0){
        ?>
        <main>
            <?php
            echo'<h1 class="titre">Changements réussis !</h1>';
            ?>
            <div class="error">
                <?php
            echo'<p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil</p>';
                ?>
            </div>
            <?php


            if (!empty($pseudo)){
                $query=$pdo->prepare('UPDATE JOUEURS SET PseudoJoueur=:pseudo WHERE idJoueur=:id');
                $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
            }

            if (!empty($email)){
                $query=$pdo->prepare('UPDATE JOUEURS SET eMailJoueur=:email WHERE idJoueur=:id');
                $query->bindValue(':email', $email, PDO::PARAM_STR);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
            }

            if (!empty($mdp)){
                $query=$pdo->prepare('UPDATE JOUEURS SET MdpJoueur=:mdp WHERE idJoueur=:id');
                $query->bindValue(':mdp', $mdp, PDO::PARAM_STR);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();
            }

            if (!empty($avatar)){
                $query=$pdo->prepare('UPDATE JOUEURS SET URLAvatarJoueur=:nomavatar WHERE idJoueur=:id');
                $query->bindValue(':nomavatar', $avatar, PDO::PARAM_STR);
                $query->bindValue(':id',$id,PDO::PARAM_INT);
                $query->execute();

            }


        }else {
            ?>
            <?php
            echo'<h1 class="titre">OUPS !</h1>';
            ?>
            <div class="error">
                <?php
            echo'<p>Une ou plusieurs erreurs se sont produites pendant vos changements...</p>';
            echo'<p>'.$i.' erreur(s)</p>';
            echo'<p>'.$pseudo_erreur1.'</p>';
            echo'<p>'.$pseudo_erreur2.'</p>';
            echo'<p>'.$email_erreur1.'</p>';
            echo'<p>'.$email_erreur2.'</p>';
            echo'<p>'.$email_erreur3.'</p>';
            echo'<p>'.$mdp_erreur.'</p>';
            echo'<p>'.$champ_vide.'</p>';

            echo'<p>Cliquez <a href="./profil.php">ici</a> pour recommencer</p>';
                ?>
            </div>
        </main>
        <?php        
        }} else {
        header('Location: index.php'); 
    }
        ?>
    </body>

</html>