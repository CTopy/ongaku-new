<?php 

  ini_set('session.use_trans_sid', true);
  ini_set('session.use_cookies', true);        
  ini_set('session.use_only_cookies', false);

?>

<!DOCTYPE HTML>

<html lang="fr">

<head>
    <title>Ongaku - Se connecter</title>
    <meta charset="utf-8" />
    <meta name="description" content="Connectez-vous pour enregistrer votre score et devenir le meilleur de tous vos amis ! Pas encore inscrit ? Rejoignez-nous !" />
    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
    <link rel="stylesheet" href="css/styleGeneral.css" />
    <link rel="stylesheet" href="./css/header.css">
    <script type="text/javascript" src="js/header.js"></script>
    <link rel="shortcut icon" href="medias/images/boutonVert.png"> 
    
</head>

<body>
    <?php 
        include("header.php");
    ?>
    <main>
        <h1>SE CONNECTER</h1>
        <form action="connexion_process.php" method="post">
            <div>
                <label for="pseudo">Pseudo ou Email</label>
                <input type="text" id="email" name="pseudo" placeholder="PSEUDO OU EMAIL" autocomplete="off" required/>
                 <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" placeholder="MOT DE PASSE" autocomplete="off" required/>
                <input type="submit" name="envoi" class="btnValider">
            </div>
        </form>
        <a href="creer-un-compte.php">Envie de vous inscrire ?</a>
    </main>
</body>

</html> 