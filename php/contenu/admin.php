<?php
//session_start();

if(!isset($_SESSION['root'])){
    include '../../html/onedoesnotsimply.html';
}
else{


    require 'generalFonctions.php';
    require 'bdd.php';
    
    echo <<<END
            <p>Nouveau Questionnaire : </p>
            <form action="php/utils/manageQuestionnaires.php" method="post" enctype="multipart/form-data" target="addQuestionnaire">
                <input type="text" name="questionnaireName" placeholder="Name" ></input><br/>
    
                <input type="text" name="action" id="action" value="addQuestionnaire" hidden></input>
    
                Caracteristiques :
                <div id="caracs">
                    <p>Carac 1 : <input type="text" name="caracname_1" id="caracname_1" placeholder="name"></input>
                    <input type="text" name="caracquestion_1" id="caracquestion_1" placeholder="Question"></input>
                    Type : <select name="caractype_1"><option value="DOUBLE">DOUBLE</option><option value="INT">INT</option><option value="VARCHAR(20)">VARCHAR(20)</option></select>
                    </p>
                </div>
    
                <input type="text" name="carac_number" id="carac_number" value="1" hidden></input><br/>
                
                <a id="addcarac" href="#">Add carac</a><br/>
                
                Attributs :
                <div id="attrs">
                    <p>Attr 1 : <input type="text" name="attrname_1" placeholder="name"></input>
                    <input type="text" name="attrlevel_1" placeholder="Level"></input>
                    <input type="text" name="attrleveldescr_1" placeholder="Level Description"></input>
                    </p>
                </div>
    
                <input type="text" name="attr_number" id="attr_number" value="1" hidden></input><br/>
                
                <a id="addattr" href="#">Add attribute</a><br/>
                
                Choix :
                <div id="choices">
                    <p>Choix 1 : <input type="text" name="choicename_1" placeholder="name"></input></p>
                </div>
    
                <input type="text" name="choice_number" id="choice_number" value="1" hidden></input><br/>
                
                Scenarii Number : <input type="text" name="scenarii_number" id="scenarii_numer"></input><br/>
    
                <a id="addchoice" href="#">Add choice</a><br/>
    
               <input type="submit" value="Create" id="uploadButton"></input>
    
            </form>
            <iframe id="addQuestionnaire" hidden></iframe>
    
    
                <script>
                    var ncaracs = 1 ;
                    var nattrs = 1 ;
                    var nchoices = 1 ;
    
                    $(document).ready(function(){
                    //why does hidden attr does not directly work ?
                    $("#action").hide();
                    //$("#carac_number").hide();
                    //$("#attr_number").hide();
                    //$("#choice_number").hide();
                    
                    //add carac
                    $("#addcarac").click(function(){
                        ncaracs++;
                        var child = "<p>Carac "+ncaracs+" : <input type=\"text\" name=\"caracname_"+ncaracs+"\" id=\"caracname_"+ncaracs+"\" placeholder=\"name\"></input><input type=\"text\" name=\"caracquestion_"+ncaracs+"\" id=\"caracquestion_"+ncaracs+"\" placeholder=\"Question\"></input>Type : <select name=\"caractype_"+ncaracs+"\"><option value=\"DOUBLE\">DOUBLE</option><option value=\"INT\">INT</option><option value=\"VARCHAR(20)\">VARCHAR(20)</option></select></p>";
                        $("#caracs").append(child);
                        $("#carac_number").attr("value",ncaracs);
                    })
                    
                    //add attribute
                    $("#addattr").click(function(){
                        nattrs++;
                        var child = "<p>Attr "+nattrs+" : <input type=\"text\" name=\"attrname_"+nattrs+"\" placeholder=\"name\"></input><input type=\"text\" name=\"attrlevel_"+nattrs+"\" placeholder=\"Level\"></input><input type=\"text\" name=\"attrleveldescr_"+nattrs+"\" placeholder=\"Level Description\"></input></p>"
                        $("#attrs").append(child);
                        $("#attr_number").attr("value",nattrs);
                    })
    
                    //add choice
                    $("#addchoice").click(function(){
                        nchoices++;
                        var child = "<p>Choix "+nchoices+" : <input type=\"text\" name=\"choicename_"+nchoices+"\" placeholder=\"name\"></input></p>"
                        $("#choices").append(child);
                        $("#choice_number").attr("value",nchoices);
                    })
    
    
                    })
                </script>
    
    
    
    
    
        
        
END;
    
    
    
    
    
    
    
    
    
    
    
    echo "<!--<h1>Inscriptions à valider</h1>";
    fromUserPdoObjectToHtml(getUnvalidUsers(),"unvalids");
    echo '<script type="text/javascript">$(document).ready(function(){
        $(\'#unvalids\').dataTable({
        "oLanguage": {
            "sLengthMenu": "Afficher _MENU_ résultats par page",
            "sZeroRecords": "Aucun résultat",
            "sInfo": "Affichage de _START_ à _END_ de _TOTAL_ résultats",
            "sInfoEmpty": "Affichage de 0 à 0 de 0 résultats",
            "sInfoFiltered": "(filtré parmi _MAX_ résultats au total)",
            "sSearch":"Rechercher:"
        }
    });})</script>';

    echo '<script type="text/javascript">$(document).ready(function(){
        $(".valider").click(function(){valid($(this).attr("id"))});
        })</script>';

    echo "<br/><br/><br/><br/><h1>Cartes à convertir</h1>";
    fromMapPdoObjectToHtmlRoot(getUnprocessedMaps(),"toprocess");
    echo '<script type="text/javascript">$(document).ready(function(){
        $(\'#toprocess\').dataTable({
        "oLanguage": {
            "sLengthMenu": "Afficher _MENU_ résultats par page",
            "sZeroRecords": "Aucun résultat",
            "sInfo": "Affichage de _START_ à _END_ de _TOTAL_ résultats",
            "sInfoEmpty": "Affichage de 0 à 0 de 0 résultats",
            "sInfoFiltered": "(filtré parmi _MAX_ résultats au total)",
            "sSearch":"Rechercher:"
        }
    });})</script>';
    echo '<script type="text/javascript">$(document).ready(function(){
        $(".process").click(function(){process($(this).attr("id"))});
        })</script>-->';

}
    
?>
