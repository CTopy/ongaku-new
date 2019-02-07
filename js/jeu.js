
//    Fonction anonyme
"use strict";
document.addEventListener("DOMContentLoaded", init);

var tempsRestant = 45;
var IdMusique,
    NomMusique,
    NomAuteur,
    TexteATrous,
    IdParoles,
    nbPoints = 0,
    nbMusiques = 0,
    audio = $("audio"),
    bouton = $("button.passer");;       // Détecter si l'initialisation est déjà finie;

function demanderMusique() {
    var donneesPHPloc;

    //
    //Récupérer les données de la page PHP
    //
    //
    var oReq = new XMLHttpRequest(); //New request object
    oReq.onload = function () {

//            console.log(this.responseText);
        //This is where you handle what to do with the response.
        //The actual data is found on this.responseText

        //Tableau :
        //0 : IdMusique
        //1 : TitreMusique
        //2 : NomAuteur
        //3 : Requete pour le debug
        //4 : Phrase
        //5 : IdParoles
        donneesPHPloc = this.responseText.split([";"]);
        for(const e of donneesPHPloc) {
          console.log(e);
        }

        //Préparer variable
        var lienMusique = donneesPHPloc[0];

        //Afficher
        document.getElementById("titre").textContent = donneesPHPloc[1];
        document.getElementById("auteur").textContent = donneesPHPloc[2];
        document.getElementById("paroles").textContent = donneesPHPloc[4];
        //            document.getElementsByTagName("audio")[0].setAttribute("src", lienMusique);
        window.setTimeout(function () {
            audio.play;
        }, 1000)
        audio.attr("src", lienMusique);

        //Récupérer les données PHP dans la variable globale
        donneesPHP = donneesPHPloc;

        // Permettre à l'utilisateur de passer
        window.setTimeout(function () {
            $("button.passer").on("click", passer);
        }, 1000);
    };
    oReq.open("post", "jeu_process.php", true);
    //                               ^ Don't block the rest of the execution.
    //                                 Don't wait until the request finishes to
    //                                 continue.
    oReq.send();
}

//Variable globale qui servira à récupérer les données PHP
var donneesPHP;

function init(evt) {

    document.querySelector("button.passer").style.cursor = "pointer";

    $("#saisieUser").focus();

    //Quand on clique sur le bouton
    $("#envoyerReponse").click(buttonCode);

    //Quand on valide le formulaire
    $("#formulaire").submit(function () {
        return false;
    });

    demanderMusique();

    //Ajouter un écouteur qui vérifie si on presse une touche
    document.getElementById("saisieUser").addEventListener("keyup", testEnter);

    //Toutes les 1 sec, on appelle la fonction timer
    window.setInterval(timer, 1000);

}

function passer(e) {
    bouton.off("click", passer);

    $("#saisieUser").focus();
    bouton.css("box-shadow", "inset 0 0 10px black");
    window.setTimeout(function () {
        bouton.css("box-shadow", "0 0 20px black");
    }, 80);
    tempsRestant = 45;
    nbMusiques++;
    audio.attr("src", "");
    testerFin();
    demanderMusique();

}

//
//Cette fonction permet d'attendre n millisecondes
//
function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

//
//Cette fonction teste si la touche actuellement pressée est entrée
//
function testEnter(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("envoyerReponse").click();
    }
}

//
//Cette fonction s'active lorsqu'on appuie sur entrée. La valeur saisie est stockée,
//et la valeur du champ effacée.
//
function buttonCode(e) {

    e.preventDefault();
    var saisieUtilisateur = $('#saisieUser').val();
    document.getElementById("saisieUser").value = "";

    $.ajax({
        type: 'post',
        url: 'jeu_process.php',
        data: {
            saisieUser: saisieUtilisateur,
            phrase: donneesPHP[4],
            idParoles: donneesPHP[5],
            motsCaches: donneesPHP[3],
        },
        success: function (data) {
            //Récupérer les données traitées par PHP
            var donneesPHPloc = data.split([";"]);

            //1 : Booléen indiquant la victoire ou non
            //2 : Phrase completée

            $("#paroles").text(donneesPHPloc[1]);

            for (var elt of donneesPHPloc) {
                //                    alert(elt+"\n\n");
            }

            console.log(nbMusiques);
            console.log(nbPoints);
            console.log(donneesPHPloc[0]);
            console.log(donneesPHPloc[1]);

            if (donneesPHPloc[0] == "true") {
                bouton.off("click", passer);
                tempsRestant = 45;
                nbPoints++;
                nbMusiques++;
                audio.attr("src", "");
                testerFin();
                demanderMusique();
            }
        }
    });

    return false;
}

//
//Cette fonction met à jour la variable globale tempsRestant et affiche le timer
//
function timer() {

    //On met le temps restant sous forme de XX:XX
    document.getElementById("timer").textContent = stringTemps(tempsRestant);

    //Si il n'y a plus de temps
    if (tempsRestant == 0) {
        bouton.off("click", passer);
        //Cette ligne empêche que le timer descende en dessous de 0, par soucis esthétique
        tempsRestant++;
        audio.attr("src", "");
        tempsRestant = 45;
        nbMusiques++;
        testerFin();
        demanderMusique();
    }

    //On retire une seconde au timer chaque seconde
    tempsRestant = tempsRestant - 1;

}

function testerFin() {
    if (nbMusiques == 10) {
        $("main").append("<form id=\"envoyerScore\" action=\"score.php\" method=\"post\"><input type=\"hidden\" name=\"score\" value=\"" + nbPoints + "\" /></form>")
        $("#envoyerScore").submit();
    }
}

//
//Cette fonction permet de mettre en forme un entier en secondes (ex : 60), sous la forme d'un timer (ex : 1:00)
//
function stringTemps(sec_num) {
    var minutes = Math.floor(sec_num / 60);
    var seconds = sec_num - (minutes * 60);

    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    return minutes + ':' + seconds;
}
