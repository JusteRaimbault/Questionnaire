<?php
session_start();
//public page -- check captcha to be secure
//rq :: brute force attack with random values \in [0,200] ? server should block it

/**
 * More security :: html special chars on each Post statement ! :: nope because sql types make safe ?
 * and everything with PDO ! (Values)
 * 
 * OK seems safe !!!
 * 
 */


if(isset($_POST['captcha'])&&isset($_SESSION['captcha'])&&$_POST['captcha']==$_SESSION['captcha']){

    require 'bdd.php';
    
    if($_POST['action']=="submitQuestionnaire"){
        //insert line into Questionnaire table
        //add time, login at least

        $questionnaire = getCurrentQuestionnaire();
        //public :: record ip as login ? ~
        $login = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');
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
else{
    echo "Erreur : Mauvaise valeur du captcha !";
}
        
