<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ongaku – Votre profil</title>
    <meta name="description" content="Modifiez les informations de votre profil.">
    <link rel="stylesheet" href="./css/profil.css">
    <link rel="stylesheet" href="./css/styleGeneral.css">
    <link rel="stylesheet" href="./css/header.css">
    <script type="text/javascript" src="js/header.js"></script>
    <link rel="shortcut icon" href="medias/images/boutonVert.png">

</head>

<body>
     <?php 
        include("header.php");
    
        require("param.inc.php");
        $pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
        $pdo->query("SET NAMES utf8");
        $pdo->query("SET CHARACTER SET 'utf8'");
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $query=$pdo->prepare('SELECT PseudoJoueur, ScoreJoueur FROM JOUEURS WHERE idJoueur=:id');
        $query->bindValue(':id',$_SESSION["idJoueur"],PDO::PARAM_INT);
        $query->execute();
        $data=$query->fetch();
    ?>

    <main>
        <div id="titres">
        <div class="profilPseudo">
            <h1>Profil</h1>
            <h2><?php print($data['PseudoJoueur']) ?></h2>
            
            </div>
            
            <div id="leScore">
                <p id="score">Score : <span id="chiffreScore"> <?php print($data['ScoreJoueur']) ?></span></p>
            </div>
        
        </div>
        
        <form method="post" action="profil_process.php" enctype="multipart/form-data">
            
            <div class="casesARemplir">

                <div class="changerPseudo">
                    <label for="pseudo">Changer de pseudo</label>
                    <input type="text" id="pseudo" name="nvPseudo" autocomplete="off">
                </div>

                <div class="mettrePhoto">
                    <label for="boutonPhoto" >mettre une photo (jpeg)</label>
                    <input type="file" id="boutonPhoto" name="avatar"/>
                </div>

                <div class="changerMail">
                    <label for="mail">changer de mail</label>
                    <input type="email" id="mail" name="nvMail" autocomplete="off">
                </div>

                <div class="changerMotDePasse">
                    <label for="mdp">changer de mot passe</label>
                    <input type="password" id="mdp" name="nvMdp" autocomplete="off">
                </div>

                <div class="changerMailConf">
                    <label class="confirmer" for="confMail">confirmer</label>
                    <input type="email" id="confMail" name="confNvMail" autocomplete="off">
                </div>

                <div class="changerMotDePasseConf">
                    <label class="confirmer" for="confMdp">confirmer</label>
                    <input type="password" id="confMdp" name="confNvMdp" autocomplete="off">
                </div>

            </div>

            <div class="caseACocher">

                <!--<div class="afficherLeMail">

                    <h2>afficher le mail</h2>

                    <div class="ouiNonMail">
                        <div>

                            <input type="radio" name="mail" value="yes" id="mailoui" />
                            <label for="mailoui">oui</label>
                        </div>
                        <div>
                            <input type="radio" name="mail" value="no" id="mailnon" />
                            <label for="mailnon">non</label>
                        </div>
                    </div>-->
                </div>
                <div class="boutonVerif">
                    <input type="image" src="./medias/images/boutonProfil.png" name="valider"/> <!--  src="./medias/images/boutonProfil.png" TODO à mettre en background dans le CSS -->
                </div>
            </form>
        </div>

    </main>

</body>

 