<?php
    session_start();
    require 'classes.php';
    require 'indexFonctions.php';
    $currentPage = getCurrentPage();
    
    //balise invisible permettant de récupérer le tire parès chargement (évite ajax compliqué)
    echo "<div id=\"titre\" name=\"".$currentPage->title."\"></div>";

    //check permissions
    if($currentPage->authorized=="ALL"){include "../contenu/$currentPage->name.php";}
    else if($currentPage->authorized=="USERS"){
        if(!isset($_SESSION['login'])) echo "<h1>Acces interdit</h1><h2>Connectez-vous ou créez un compte pour accéder à cette page</h2>";
        else{include "../contenu/$currentPage->name.php";}
    }
    else if($currentPage->authorized=="ROOT"){
        if(!isset($_SESSION['root'])) echo "<h1>Acces interdit</h1><h2>Vous n'êtes pas administrateur!</h2>";
        else{include "../contenu/$currentPage->name.php";}
    }
?>
