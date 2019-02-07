"use strict"
document.addEventListener("DOMContentLoaded",initialiser);
    
function initialiser(evt){
     var menuDeroulant = document.getElementById("iconeMenu");
     menuDeroulant.addEventListener("click", cliquer);
    menuDeroulant.style.cursor="pointer";
    
var regles = document.getElementById("iconeRegle");
    document.getElementById("nav2").style.display="none";
     regles.addEventListener("mouseenter", cliquer2);
      regles.addEventListener("mouseleave", cliquer3);

    
    var image = document.querySelector(".iconeMembre");
    if(image.getAttribute("src") != "medias/images/Invite.png" || image.getAttribute("src") != "medias/images/Masculin.png" || image.getAttribute("src") != "medias/images/Feminin.png" ){
       
        image.style.height="60px";
        
    }
    
    if (document.querySelector(".creerUnCompte") != null) {
var creer = document.getElementById("boutonCreer");
    document.querySelector(".creerUnCompte").style.display="none";
    boutonCreer.addEventListener("mouseenter", cliquer4);
    boutonCreer.addEventListener("mouseleave", cliquer5);
    }

}   
    
    
function cliquer(evt){
    document.querySelector(".menuDeroulant").classList.toggle("apparaitre");
    
    var image = document.getElementById("iconeMenu");
//    console.log(image.getAttribute("src"));
    
    if(image.getAttribute("src") == "medias/images/deroulant.png"){
        image.src = "medias/images/croixMenuDeroulant.png";
        
        
    }else{
         image.src = "medias/images/deroulant.png";
    }
}



function cliquer2(evt){
    document.querySelectorAll(".menuDeroulant")[1].classList.toggle("apparaitre") ;
    document.getElementById("nav2").style.display="block";
    this.style.cursor="pointer";
    
    var image = document.getElementById("iconeRegle");
    image.src = "medias/images/boutonLivreOuvert.png";
    
    }
        
      
    
function cliquer3(evt){
     document.querySelectorAll(".menuDeroulant")[1].classList.remove("apparaitre") ;
     document.getElementById("nav2").style.display="none";
    var image = document.getElementById("iconeRegle");
    image.src = "medias/images/boutonLivreFerme.png";
    
    
    }

function cliquer4(evt){
   var creerUnCompte = document.querySelector(".creerUnCompte");
    creerUnCompte.style.display="block";
    creerUnCompte.style.opacity=1;
    }
        
      
    
function cliquer5(evt){
    var creerUnCompte = document.querySelector(".creerUnCompte");
    creerUnCompte.style.display="none";
    creerUnCompte.style.opacity=0;
     
    
    }
