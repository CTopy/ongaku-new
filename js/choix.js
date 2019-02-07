(function () {
    "use strict";
    $("document").ready(init);

    function init(e) {
        $("#form").submit(function (e) {
            var langueCochees = $("#langue :checked").length,
                styleCoches = $("#style :checked").length,
                jeuCoches = $("#jeu :checked").length,
                erreur = "ERREUR :\n",
                canSubmit = true;
            if (langueCochees < 1) {
                erreur = erreur + "Vous devez sélectionner au moins une langue \n";
                canSubmit = false;
            }
            if (styleCoches < 2) {
                erreur = erreur + "Vous devez sélectionner au moins 2 styles de musique \n";
                canSubmit = false;
            }
            if (jeuCoches < 1) {
                erreur = erreur + "Vous devez sélectionner un mode de jeu \n";
                canSubmit = false;
            }
            if (canSubmit) {
                return true;
            } else {
                alert(erreur);
                         //Empecher la soumission du formulaire
            return false;   
            }
        });
    }
}());