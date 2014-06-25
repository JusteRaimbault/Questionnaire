<?php

    session_start();
    require 'bdd.php';
    $dbh = Database::connect();
    $query = "SELECT * FROM `Utilisateurs` WHERE `login`=? AND `password`=SHA1(?);";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
    $sth->execute(array($_POST['login'],$_POST['password']));
    if($sth->rowCount()>0){//connexion
       $q=$sth->fetchAll();
       $u=$q[0];
       if($u["valid"]=="1"){
          $_SESSION['login']=$_POST['login'];
          if($u["isroot"]=="1") $_SESSION['root'] = 1;
       }
    }
    $dbh=null;

    
?>
