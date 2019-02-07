<html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex" />
        <link rel="stylesheet" href="css/signup_process.css" type="text/css" />
        <link rel="stylesheet" href="css/styleGeneral.css" />
    </head>

<?php
session_start();


require("param.inc.php");
    $pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
    $pdo->query("SET NAMES utf8");
    $pdo->query("SET CHARACTER SET 'utf8'");
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

//    $message='';
//    if (empty($_POST['email']) || empty($_POST['mdp']) )
//    {
//        $message = '<p>Vous devez remplir tous les champs pour pouvoir continuer</p>
//	<p>Cliquez <a href="./seConnecter.php">ici</a> pour vous connecter</p>';
//    }
    

        $query=$pdo->prepare('SELECT PseudoJoueur, eMailJoueur, MdpJoueur, SexeJoueur, URLAvatarJoueur, IdJoueur
        FROM JOUEURS WHERE eMailJoueur = :email OR PseudoJoueur = :pseudo');
        $query->bindValue(':email',$_POST['pseudo'], PDO::PARAM_STR);
        $query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch(PDO::FETCH_ASSOC);
    
    // Acces OK !
	if ($data['MdpJoueur'] == sha1(($_POST['mdp']))) 
	{
	    $_SESSION['email'] = $data['eMailJoueur'];
        $_SESSION['pseudo'] = $data['PseudoJoueur'];
	    $_SESSION['idJoueur'] = $data['IdJoueur'];
	    header("Location: index.php"); 
        
        $expire = time() + 365*24*3600;
        setcookie('id', $_SESSION['idJoueur'], $expire);
	}
	else 
    // Acces pas OK !
	{
	    $message = '<h1 class="titre">OUPS !</h1><br /><div class="error"><p> Le mot de passe, l\'email ou le pseudo 
            entré n\'est pas correcte...</p><p>Cliquez <a href="./connexion.php">ici</a> 
	    pour revenir à la page précédente
	    <br /><br />Cliquez <a href="./index.php">ici</a> 
	    pour revenir à la page d accueil</p></div>';
	}
    $query->CloseCursor();
    
    echo $message;
    
    

?>
</body>
</html>