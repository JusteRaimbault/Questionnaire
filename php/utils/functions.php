<?php



function echoQuestionnaire($public){
   
    //for public :: load captcha !!
    //add input "public"
    //security check is done in submission
    
    
    
    
    
    /**
     * First need a tool to select current Questionnaire
     * -> simple toggler ?
     */

    /**
     * User is connected
     */
    $login = $_SESSION['login'];
    $questionnaire=getCurrentQuestionnaire();
    $description=getCurrentDescription();
    //$contributed = getContributedQuestionnaire();
    if($public==0){echo "Le questionnaire courant est : ".$questionnaire."<br/>";}
    echo $description[0]["description"]."<br/><br/>";
    
    //change Questionnaire ? -> do that later !
    //Ajax request to change Session var from cursor value
    /*echo "<select id=\"chooseCurrentQ\"><option>Choisir...</option>";
        foreach($contributed as $q){echo '<option'; if($q==$SESSION['currentQ']) echo ' selected="selected"';echo '>'.$q.'</option>';}
        echo<<<END
            </select>
                    <script type="text/javascript">$(document).ready(function(){
                    $("#chooseCurrentQ").change(function(){
                        var n = $(this).val();
                        if(n=="Choisir..."){alert("Veuillez choisir un questionnaire!");}
                        else{
                            $.post("php/utils/manageQuestionnaires.php",{"action":"changeCurrentQ","newQuestionnaire":n},function(rep){
                                 alert(rep);
                             })
                        }
                    });})
                    </script>
    END;
    */


    //differentiate public submission from private (security constraint in manageQ page)
    //architecture pb by rassembling actions in same page ? quite messy
    if($public==0){$target="manageQuestionnaires.php";}else{$target="submitQuestionnaire_public.php";}
    echo "<form action=\"php/utils/$target\" method=\"post\" enctype=\"multipart/form-data\" target=\"submitQuestionnaire\" onsubmit=\"questionnaireSubmitted()\"><input type=\"text\" name=\"action\" id=\"action\" value=\"submitQuestionnaire\" hidden></input><div id=\"caracs\">";
    //echo caracs
    foreach(getCurrentCaracs() as $carac){
        echo "<p>".$carac['question']."<input type=\"text\" name=\"".$carac['name']."\" placeholder=\"".$carac['name']."\"></input></p>";
    }
    echo "<a href=\"#\" id=\"submitCaracs\">Suite >></a></div><div id=\"scenarii\"><a href=\"#\" id=\"backCaracs\"><< Retour</a>";

    $q=returnQuery("SELECT `scenarii_number` FROM `Questionnaires` WHERE `isCurrent`=?", array(1));
    $scenarii_number=$q[0]['scenarii_number'];
    $scenario_number=1;

    $choices=  getCurrentChoices();

    foreach(getScenarii($scenarii_number) as $scenario){
        echo "<p><b>Scenario $scenario_number </b><br/>";
        foreach($scenario as $attr){
            echo $attr['name']." : ".$attr['level_description']."<br/>";
            //needs hidden input to post attribute value
            echo "<input type=\"text\" name=\"".$attr['name']."_scenario$scenario_number\" class=\"hidden\" value=\"".$attr['level']."\">";

        }
        //echo choice radio button :: needs to get choices


        //Check out radio choice !
        echo "Choix : ";
        $choice_number=1;
        foreach($choices as $choice){echo "<input type= \"radio\" name=\"choice_scenario$scenario_number\" value=\"$choice_number\" required>".$choice['name']."</input>";$choice_number++;}


        $scenario_number++;
    }

    echo <<<END

        <br/>
        <input type="text" name="scenarii_number" class="hidden" value="$scenarii_number"></input>
        <input type="text" name="public" class="hidden" value="$public"></input>
        <br/> 
            
END;
    if($public==1){
        echo <<<END
    
        <br/>
        Pour vérifier que vous n'êtes pas un robot, veuillez rentrer la valeur du captcha :
        <img src="php/utils/captcha.php"/>
        <br/>
        Valeur de la somme : <input type="text" name="captcha" required/><br/>
   
        
END;
        
    }
    
    echo <<<END
            
        <input type="submit" value="Soumettre" id="uploadButton"></input>
        </div>
    </form>

    <iframe name="submitQuestionnaire" hidden></iframe>

    <script>
        $(document).ready(function(){
            $("#action").hide();$("#scenarii").hide();
            $(".hidden").hide();
            $("#submitCaracs").click(function(){
                $("#caracs").hide();$("#scenarii").toggle(500,function(){});
            });
            $("#backCaracs").click(function(){
                $("#scenarii").hide();$("#caracs").toggle(500,function(){});
            })
        })
        
    </script>
END;
    
    
    
    
    
}

