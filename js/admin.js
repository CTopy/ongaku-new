(function () {
    "use strict";
    $("document").ready(init);
    var nbstyles = 1,
        nbparoles = 1;

    function init(e) {
        $("#addstyle").click(ajouterStyle);
        $("#addparoles").click(ajouterParoles);
        $("#delete").click(confirmer);
    }

    function ajouterStyle (e) {
        if (nbstyles < 4) {
            nbstyles++;
            
            var content = $('#styles').html(),
                newselect = $("<select name=\"IdStyle"+nbstyles+"\">");
            if ($(".styles2").length == 0) {
            content = content + "<option value=\"false\">Aucun</option>";
            }
            newselect.html(content);
            $('#styles').after(newselect);
    }
    }
    
    function ajouterParoles (e) {
        if (nbparoles < 4) {
            nbparoles++
            $('[name="Paroles"]').after('<br /><textarea placeholder="Suite des paroles..." rows="5" cols="50" name="Paroles'+nbparoles+'"></textarea>');
        }
    }
    
    function confirmer (evt) {
        evt.preventDefault;
        var resultat = confirm("Êtes-vous sûr de vouloir supprimer cette musique ?");
        if (resultat) {
            $("#supprimerMusique").submit()
        } else {
            return false;
        }
    }
}());