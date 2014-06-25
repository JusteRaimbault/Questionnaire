<?php


    //utilisation de session
    session_start();
    session_regenerate_id();
    if (!isset($_SESSION['initiated'])) {
        session_name("OOWorldSession");
        $_SESSION['initiated'] = true;
    }

    //inclusion des fonctions
    require 'php/utils/bdd.php';
    require 'php/utils/classes.php';
    require 'php/utils/indexFonctions.php';
    

    //generation de la page

    generateHTMLHeader();
    generateContent();
    generateHTMLFooter();

?>