<?php
session_start();
    require "bdd.php";
    
    echo "<p>Bienvenue ".$_SESSION['login']."!</p><br/>";
    
?>
