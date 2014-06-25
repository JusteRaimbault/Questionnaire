<?php
    session_start();
    unset($_SESSION['login']);
    if(isset($_SESSION['root'])) unset($_SESSION['root']);
?>
