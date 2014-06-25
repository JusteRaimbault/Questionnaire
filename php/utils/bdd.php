<?php

class Database {

    public static function connect() {
        $dsn = 'mysql:dbname=QuestionnaireInterface;host=127.0.0.1';
        $user = /*ENTER SQL USER HERE*/"";$password=/*ENTER SQL PASSWORD HERE*/"";
        $dbh = null;
        try {
            
            $dbh = new PDO($dsn, $user,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;
    }
}

function returnQuery($query,$values){
    $dbh = Database::connect();
    $sth = $dbh->prepare($query);
    $sth->execute($values);
    return  $sth->fetchAll();  
}

function alterQuery($query,$values){
    $dbh = Database::connect();
    $sth = $dbh->prepare($query);
    $sth->execute($values);
}




function getUser(){
    if(isset($_SESSION['login'])){
        $dbh = Database::connect();
        $query = "SELECT * FROM `Utilisateurs` WHERE `login`=?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($_SESSION['login']));
        $u = $sth->fetch();//aboutit forcément
        return $u;
    }
    else return null;
}

function countMaps(){
    /*
    $dbh = Database::connect();
    $query = "SELECT * FROM `Cartes`;";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Carte');
    $sth->execute();
    return $sth->rowCount();
     * 
     */
}

function getMaps(){
    /*
    $dbh = Database::connect();
    $query = "SELECT * FROM `Cartes`;";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Carte');
    $sth->execute();
    return $sth->fetchAll(); 
     * 
     */
}

function getMapsFromUser($login){//return Array
    /*
    $dbh = Database::connect();
    $query = "SELECT * FROM `Cartes` WHERE `owner`=?;";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Carte');
    $sth->execute(array($login));
    return $sth->fetchAll();
     * 
     */
}


function freeLogin($login){
    $dbh = Database::connect();
    $query = "SELECT * FROM `Utilisateurs` WHERE `login`=?;";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Carte');
    $sth->execute(array($login));
    return ($sth->rowCount()==0);
}

function putUser($login,$nom,$password,$mail){
    $dbh = Database::connect();
    $query = "INSERT INTO `Utilisateurs` (`login`, `nom`, `password`, `mail`, `valid` , `isroot`) VALUES (?,?,SHA1(?),?,?,?);";
    $sth = $dbh->prepare($query);
    $sth->execute(array($login,$nom,$password,$mail,"0","0"));
}

function getUnvalidUsers(){
    $dbh = Database::connect();
    $query = "SELECT * FROM `Utilisateurs` WHERE `valid`='0';";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
    $sth->execute();
    return $sth->fetchAll();
}

function updateMail($mail,$login){
    $dbh = Database::connect();
    $query = "UPDATE `Utilisateurs` SET `mail` = ? WHERE `Utilisateurs`.`login` =?;";
    $sth = $dbh->prepare($query);
    $sth->execute(array($mail,$login));
    return $sth->rowCount();
}

function updatePsswd($newpsswd,$login){
    $dbh = Database::connect();
    $query = "UPDATE `Utilisateurs` SET `password` = SHA1(?) WHERE `Utilisateurs`.`login` =?;";
    $sth = $dbh->prepare($query);
    $sth->execute(array($newpsswd,$login));
    return $sth->rowCount();
}


/**
 * 
 * @param type $questName
 * @param type $ownerId
 * @param type $contributorsId
 * @param type $variables 2D array of variables names and their types -- VARCHAR is normalised to 20 ?
 * 
 * ONLY ROOT WILL CREATE TABLE SO NO PB WITH INJECTION -- NOT SECURED FUNCTION !!!
 * 
 * 
 * 
 */
function createQuestionnaire($questName,$ownerId,$contributorsId,$caracs,$attrs,$choices,$scenarii_number){
    $dbh = Database::connect();
    
    //new questionnaire will be current as default
    alterQuery("UPDATE  `QuestionnaireInterface`.`Questionnaires` SET  `isCurrent` =  '0'", array());
    
    //insert owner
    alterQuery("INSERT INTO `Questionnaires` ( `questionnaire`, `owner`, `isCurrent`,`scenarii_number`) VALUES (?,?,?,?);",array($questName,$ownerId,1,$scenarii_number));
    
    //insert contributors
    foreach($contributorsId as $contributor){
        addContributor($questName, $contributor,$dbh);
    }
    
    //create associated table
     $query = "CREATE TABLE  `QuestionnaireInterface`.`$questName` (" ;
     $number=1;
     foreach($caracs as $variable){
         $query = $query.'`'.$variable['name'].'` '.$variable['type'].' , ' ;
         
         //insert corresponding entry in table caracs
         //var_dump(array($variable['name'],$variable['type'],$variable['question'],$questName,$number));echo "<br/>";
         alterQuery("INSERT INTO `Caracs` ( `name`, `type`,`question`,`questionnaire`,`number`) VALUES (?,?,?,?,?);",array($variable['name'],$variable['type'],$variable['question'],$questName,$number));         
         
         $number++;
     }
     
     //idem for attrs
     $number=1;$attrnames=array();
     foreach($attrs as $attr){
         if(!in_array($attr['name'],$attrnames)){array_push($attrnames,$attr['name']);};
         alterQuery("INSERT INTO `Attributes` ( `name`,`questionnaire`,`level`,`level_description`,`number`) VALUES (?,?,?,?,?);",array($attr['name'],$questName,$attr['level'],$attr['level_description'],$number));         
         $number++;
     }
     var_dump($attrnames);
     foreach($attrnames as $name){$query = $query.'`'.$name.'` INT , ' ;}
     
     //and choices
     $number=1;
     foreach($choices as $choice){
         alterQuery("INSERT INTO `Choices` ( `name`,`questionnaire`,`number`) VALUES (?,?,?);",array($choice['name'],$questName,$number));         
         $number++;
     }
     $query = $query." `choice` INT,`login` VARCHAR(20),`time` VARCHAR(20),`id` INT )";
     $sth = $dbh->prepare($query);
     $sth->execute();
     //echo $query;
    
}

//TEST :: OK
//createQuestionnaire("Test", "root", array("user1","user2"), array(array("name"=>"name","type"=> "VARCHAR(20)"),array("name"=>"age","type"=>"INT")));


/**
 * 
 * Add a contributor to a questionnaire
 * 
 * @param type $questName
 * @param type $contributor
 * @param type $dbh
 */
function addContributor($questName,$contributor,$dbh){
    $query = "INSERT INTO `Questionnaires` ( `questionnaire`, `contributor`) VALUES (?,?);";
    $sth = $dbh->prepare($query);
    $sth->execute(array($questName,$contributor));
}




/**
 * 
 * User functions
 * 
 */

/**
 * Get contributed
 * 
 * called only in user page -> $SESSINO[login] should not be null
 * 
 * No injection, called on user id !
 */
function getContributedQuestionnaire(){
    $login = $_SESSION['login'];
    if($login != null){
        $dbh = Database::connect();
        $query = "SELECT `questionnaire` FROM `Questionnaires` WHERE `contributor`=?;";
        $sth = $dbh->prepare($query);
        $sth->execute(array($login));
        //let return a decent array !
        $res = array();
        foreach($sth->fetchAll() as $line){
            array_push($res, $line[0]);
        }
        return $res ;
    }
}

//Test :: OK --> and it was not, bitch :(
/*foreach(getContributedQuestionnaire() as $line){
    echo $line[0]."<br/>";
}*/

function getCurrentQuestionnaire(){
    $dbh = Database::connect();
    $query = "SELECT `questionnaire` FROM `Questionnaires` WHERE `isCurrent`=?;";
    $sth = $dbh->prepare($query);
    $sth->execute(array(1));
    $res =  $sth->fetch();//aboutit forcément
    return $res[0];
}


/**
 * returns in sorted order array of caracs corresponding to currentQuestionnaire
 */
function getCurrentCaracs(){
    return returnQuery("SELECT `name`,`question` FROM `Caracs` WHERE `questionnaire`='".getCurrentQuestionnaire()."' ORDER BY  `Caracs`.`number` ASC;",array());
}

function getCurrentDescription(){
    return returnQuery("SELECT `description` FROM `Questionnaires` WHERE `questionnaire`='".getCurrentQuestionnaire()."';",array());
}

//var_dump(getCurrentCaracs());

/**
 * Do same with attributes ? special way since need to build random combination
 */


/**
 * Beware, returns attributes in a special way [iterate other keys to get names]
 * 
 * @return array :: name => level_number
 */
function getCurrentAttributes(){
    $attrsquery = returnQuery("SELECT `name`,`level` FROM `Attributes` WHERE `questionnaire`='".getCurrentQuestionnaire()."' ORDER BY  `Attributes`.`number` ASC;",array());
    $attrs=array();
    foreach($attrsquery as $attr){
        if(!isset($attrs[$attr['name']])){$attrs[$attr['name']]=intval($attr['level']);}
        else{$attrs[$attr['name']]=max(intval($attr['level']),$attrs[$attr['name']]);}
    }
    return($attrs);
}


function getCurrentChoices(){
    return returnQuery("SELECT `name` FROM `Choices` WHERE `questionnaire`='".getCurrentQuestionnaire()."' ORDER BY  `Choices`.`number` ASC;",array());
}



/**
 * Returns a fixed number [in hard for now] of scenarii, satisfying non-dominance ?
 * 
 * Returns an array of scenario, which is each an array of arrays (attr_name, attr_level, attr_level_description)
 * 
 * Shall we return reference scenario first ?
 * 
 */
function getScenarii($scenarii_number){
    $res=array();
    $currentQuestionnaire=getCurrentQuestionnaire();
    
    //get total number of attributes and corresponding levels for the questionnaire :
    
    //Q : how to directly compute a dominated scenario
    //and how simply not give two times the same ? -> code as a string and store in array :(
    
    $attrs = getCurrentAttributes();
    
    $scenarii_hash = array();
    //beware with that while :: function shall be called carefully, or shall put a check here ? ok -- no time
    while(count($res)<$scenarii_number){
        $hash="";$scenario=array();
        foreach(array_keys($attrs) as $attr_name){
            //for each attribte, draw a random value
            $row=array();
            $val=rand(1,$attrs[$attr_name]);
            $q = returnQuery("SELECT `level_description` FROM `Attributes` WHERE `questionnaire`='$currentQuestionnaire' AND `name`='$attr_name' AND `level`='$val' ORDER BY  `Attributes`.`number` ASC;",array());
            $descr = $q[0]['level_description'];
            $row['name']=$attr_name;$row['level']=$val;$row['level_description']=$descr;
            $hash=$hash.$val;
            array_push($scenario,$row);
        }
        if(!in_array($hash, $scenarii_hash)){
            array_push($scenarii_hash,$hash);
            array_push($res, $scenario);
        }
    }
    //var_dump($res);
    return($res);
}

//getScenarii(3);

/*
 * //DISTGUSTING WAY OF DEALING WITH ARRAYS !!!
$a=array(1,2);
$b=$a;
array_push($b, 3);
var_dump($a);echo "<br/>";
var_dump($b);
*/


?>
