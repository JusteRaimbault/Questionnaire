<?php
session_start();

if(!isset($_SESSION['login'])){
    include '../../html/onedoesnotsimply.html';
}
else{

    require 'bdd.php';
    
    //echo directly brute form creation if not action
    if(!isset($_POST['action'])){
        
       
    }
    else{
        if($_POST['action']=="addQuestionnaire"){
            
            //good good we already defined creation function !            
           $n=1;$caracs=array();
           while($n<=intval($_POST['carac_number'])){
               array_push($caracs,array("name"=>$_POST['caracname_'.$n],"question"=>$_POST['caracquestion_'.$n],"type"=>$_POST['caractype_'.$n]));
               $n++;
           }
           $n=1;$attrs=array();
           while($n<=intval($_POST['attr_number'])){
               array_push($attrs,array("name"=>$_POST['attrname_'.$n],"level"=>$_POST['attrlevel_'.$n],"level_description"=>$_POST['attrleveldescr_'.$n]));
               $n++;
           }
           $n=1;$choices=array();
           while($n<=intval($_POST['choice_number'])){
               array_push($choices,array("name"=>$_POST['choicename_'.$n]));
               $n++;
           }
           
           //don't bother with contributors yet : everyone has access
           createQuestionnaire($_POST['questionnaireName'], "root", array(), $caracs,$attrs,$choices,$_POST['scenarii_number']);
            
            
        }
        
        if($_POST['action']=="submitQuestionnaire"){
            //insert line into Questionnaire table
            //add time, login at least
            
            $questionnaire = getCurrentQuestionnaire();
            $login = $_SESSION['login'];
            $time = date(DATE_ISO8601);
            
            
            $query = "INSERT INTO `$questionnaire` (";
            $valstring = ") VALUES(";
            $values = array();
            
            //get carac values
            foreach(getCurrentCaracs() as $carac){
                $query = $query."`".$carac['name']."`,";
                $valstring = $valstring."?,";
                array_push($values,$_POST[$carac['name']]);
            }
            
            $attrnames = array_keys(getCurrentAttributes());
            
            //Beware :: insert a line in base FOR EACH scenario
            //until here, query string stays the same, end has to be different
            
            for($i=1;$i<=intval($_POST['scenarii_number']);$i++){
                $linequery=$query;
                $linevalstring=$valstring;
                $linevalues=$values;
                
                //iterateon attributes
                //fuck dirty code, should have standardised way to deal with sql requests -- code is undoubtely unreadable and unstable :(
                foreach($attrnames as $attr){
                    $linequery=$linequery."`".$attr."`,";
                    $linevalstring=$linevalstring."?,";
                    array_push($linevalues, $_POST[$attr."_scenario".$i]);
                    //OK this fixes format for input name in form
                }
                
                //get choices and exec query
                $linequery=$linequery."`choice`,`login`,`time`";
                $linevalstring=$linevalstring."?,?,?);";
                array_push($linevalues,$_POST['choice_scenario'.$i]);
                array_push($linevalues,$login);
                array_push($linevalues, $time);
                
                alterQuery($linequery.$linevalstring, $linevalues);
                
                
            }
            
            
        }
        
    }




}

?>