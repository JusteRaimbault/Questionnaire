<?php
session_start();
//file export of data -- for now user restriction ?

header('Content-type: application/octet-stream');

if(!isset($_SESSION['login'])){
    include '../../html/onedoesnotsimply.html';
}
else{
    require 'bdd.php';
    $questionnaire = getCurrentQuestionnaire();
    
    /*
     * read model file : specifies which variables and values associated to which output var
     *  (especially for dummy vars)
     *
     * Format is ::
     *   (out_name ; dbvar_name ; dbvar_value == NULL if valued outvar ; outvar_value)
     */
    
    $formatfile = file("../../data/filtreBiogeme.txt");
    $outvars = array();
    foreach($formatfile as $line){
        array_push($outvars,explode(";",$line));
    }

    //echo header
    $n=count($outvars);
    //echo individu_id
    echo "id\t";
    for($i=0;$i<$n;$i++){
        echo $outvars[$i][0];if($i<$n-1) echo "\t";
        
    };echo "\n";
    
    //echo records
    $id=1;$currentLogin="";$currentTime="";
    foreach(returnQuery("SELECT * FROM $questionnaire;",array()) as $line){
        //check if id changed
        if($line['login']!=$currentLogin||$line['time']!=$currentTime){$currentLogin=$line['login'];$currentTime=$line['time'];$id++;}
        echo $id."\t";
        
        for($i=0;$i<$n;$i++){
            //var_dump($outvars[$i]);
            if(strlen(strstr($outvars[$i][2],"NULL"))>0){
                //test if association of values
                if(strlen(strstr($outvars[$i][2],":"))>0){
                    //modify var by mapping
                    $map=explode(":",$outvars[$i][2]);
                    $echoed=FALSE;
                    for($j=1;$j<count($map);$j++){
                        $mapping=explode("-",$map[$j]);
                        if(intval($mapping[0])==intval($line[$outvars[$i][1]])){
                            echo intval($mapping[1]);
                            $echoed=TRUE;
                        }
                    }
                    if($echoed==FALSE){echo 0;}
                }
                else{
                    //var in state
                    echo $line[$outvars[$i][1]];
                }
            }
            else{
                //echo $outvars[$i][2]."  --  ".$line[$outvars[$i][1]]." - bool :: ".()."<br/>";
                if(intval($outvars[$i][2])-intval($line[$outvars[$i][1]])==0){echo 1;}
                else{echo 0;}
            }
            if($i<$n-1) echo "\t";
        }
        echo "\n";
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}

