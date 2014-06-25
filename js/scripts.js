//scripts Js non spécifiques


//Gestion du Login

function updateLoginZone(){
    $("#loginarea").load("php/utils/loginform.php",manageLogin);
}

function manageLogin(){
    $("#logout").click(function(){
        $.post("php/utils/logout.php",function(){
            $("#loginarea").load("php/utils/loginform.php",manageLogin);
            loadCurrentPage("acceuil","");
        });
    })
    
    $("#inscription").click(function(){loadCurrentPage("moncompte");});

    $("#loginForm").submit(function(){
        $.post("php/utils/login.php", $("#loginForm").serialize(), function(){
            $("#loginarea").load("php/utils/loginform.php",manageLogin);
            loadCurrentPage("acceuil","");
        });
        return false;
    })
}


//Gestion du chargement des pages

function loadCurrentPage(name,mapname){//pagename in post
    $("#contenu").load("php/utils/pageload.php", {"page" : name,"mapname":mapname});
    $("#title").html($("#titre").attr("name"));
}

function loadFromMenu(){
    $(".menulink").click(function(){
        loadCurrentPage($(this).attr("id"),"");
    });
}


function loadFromPopup(arg){
        loadCurrentPage("cartes",arg);
}

function valid(arg){
    var sel = "#case"+arg ;
    $.post("php/utils/valid.php",{"login" : arg},function(){$(sel).html("Inscription validée");
        });
}

function process(arg){
    alert("Lancement du programme");
    $.post("php/utils/process.php",{"filename" : arg},function(rep){alert(rep);
        });
}

function updateMail(){
    $("#newmail").show();
    $("#newMailButton").show();
    $("#newMailButton").click(function(){
        $.post("php/utils/updateProfile.php",{"newmail":$("#newmail").attr("value")},function(){loadCurrentPage("moncompte","")});
    });
}

function updatePsswd(){
    $("#newpassword").show();
    $("#newpasswordbis").show();
    $("#newPsswdButton").show();
    $("#newPsswdButton").click(function(){
        $.post("php/utils/updateProfile.php",{"newpassword":$("#newpassword").attr("value"),"newpasswordbis":$("#newpasswordbis").attr("value")},function(rep){alert(rep);loadCurrentPage("moncompte","")});
    });
}


function main(){
    loadCurrentPage("acceuil","");
    updateLoginZone();
    loadFromMenu();
}

$(document).ready(main);




//additional functions for Questionnaire Interface

function questionnaireSubmitted(){
    alert("Merci pour votre participation !");
    loadCurrentPage("acceuil","");
}

