<?php

if(!isset($_SESSION['login'])){
    include '../../html/onedoesnotsimply.html';
}
else{

/**
 * SECURITY CHECK !!!
 * 
 */


    require "../utils/bdd.php";
    require "../utils/functions.php";
    echoQuestionnaire(0);

}