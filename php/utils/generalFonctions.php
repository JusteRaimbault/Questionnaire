<?php

    function fromMapPdoObjectToHtml($object,$tableid){
       /*
        echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>Nom de la carte</th><th>Fichier vectoriel</th><th>Lieu</th><th>Coordonnées</th><th>Propriétaire</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $map) {
            $vecname = substr($map->filename, 0, strlen($map->filename) - strlen(end(explode(".",$map->filename)))-1).".ocd";
            if($map->processed=="1") $li = "<a href=\"cartes/$vecname\">$vecname</a>";
            else $li = "Non disponible";
            echo "<tr><td><p>$map->filename            <a href=\"cartes/$map->filename\" class=\"lightbox\" id=\"$map->filename\" title=\"$map->filename\">Voir: <img src=\"cartes/$map->filename\" height=\"20\" width=\"20\"/></a></p></td><td>$li</td><td>".$map->place."</td><td>lat:".$map->lat." lon:".$map->lon."</td><td>".$map->owner."</td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
        echo '<script type="text/javascript">$(document).ready(function(){$("a.lightbox").lightBox();})</script>';
        * 
        */
    }

    function fromMapPdoObjectToHtmlRoot($object,$tableid){
        /*echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>Nom de la carte</th><th>Lieu</th><th>Coordonnées</th><th>Propriétaire</th><th>Procéder</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $map) {
            echo "<tr><td>$map->filename</td><td>".$map->place."</td><td>lat:".$map->lat." lon:".$map->lon."</td><td>".$map->owner."</td><td id=\"caseconvert$map->filename\"><a href=\"#\" class=\"process\" id=\"$map->filename\">Lancer la conversion</a></td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
         * 
         */
    }

    function fromUserPdoObjectToHtml($object,$tableid){
        echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>login</th><th>Nom</th><th>Mail</th><th>Validation</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $user) {
            echo "<tr><td>$user->login</td><td>$user->nom</td><td>$user->mail</td><td id=\"case$user->login\"><a href=\"#\" class=\"valider\" id=\"$user->login\">Valider l'inscription</a></td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
    }

?>
