<?php
  ini_set('session.use_trans_sid', true);
  ini_set('session.use_cookies', true);        
  ini_set('session.use_only_cookies', false);
  

?>

    <!DOCTYPE HTML>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <title>Ongaku – Rejoignez nous !</title>
        <meta name="description" content="Vous adorez Ongaku ? Créez un compte et rejoignez nous ! Votre score sera comptabilisé et vous serez le meilleur de tous vos amis ! C’est simple et gratuit !" />
        <link rel="stylesheet" href="css/creer-un-compte.css" type="text/css" />
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
            <h1>CREER UN COMPTE</h1>
            <form method="post" action="signup_process.php" enctype="multipart/form-data">
            <div id="fieldset">
                <input type="text" name="pseudo" placeholder="PSEUDO" autocomplete="off" required/>
                <input type="email" name="email" placeholder="EMAIL" autocomplete="off" required/>
                <input type="email" name="confirmeremail" placeholder="CONFIRMER VOTRE EMAIL" autocomplete="off" required/>

                <div class="liste">
                    <input id="masculin" type="radio" name="sexe" value="Masculin"/>
                    <label for="masculin">Masculin</label>

                    <input id="feminin" type="radio" name="sexe" value="Feminin"/>
                    <label for="feminin">Feminin</label>
                    
                    <input id="autre" type="radio" name="sexe" value="Autre"/>
                    <label for="autre">Autre</label>
                 </div>

                <input type="password" name="mdp" placeholder="MOT DE PASSE" autocomplete="off" required/>
                <input type="password" name="confirmermdp" placeholder="CONFIRMER LE MOT DE PASSE" autocomplete="off" required/>
                

                <input type="file" name="avatar" class="Avatar" />
                
                <input type="submit" name="confirm" value="GO" class="boutonGo" /> 
            </div>
                </form>            
        </main>
    </body>

    </html>
    