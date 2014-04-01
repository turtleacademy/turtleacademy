<?php

require_once 'utils/badgesUtil.php';

if (!isset($_SESSION)) {
    session_start();
}
$user = "Unknown";
$username = "username";
if (isset($_SESSION[$username])) {
    $user = $_SESSION[$username];
} else {
    echo "";
    exit();
}
//If we tought the turtle a new TO command it will be save seperately 
$cmd = "";
$tocmd = "";
$addNewToCmd = false; // If we will have to save a new user TO command
if (isset($_POST['command'])) {
    $cmd = $_POST['command'];
    $trimmedcmd = trim($cmd);
    if (strcasecmp(_(substr($trimmedcmd, 0, 2)), "to") == 0) { //Case the TO command learned in other language
        $tocmd = $trimmedcmd;
        $addNewToCmd = true;
    }
}
$return['username'] = $user;
$stepsComletedData = "";
$userActions = ""; // Will represent the user history already save for the user
$userActionsUpdate = ""; // The new user actions that should be save to history
$isLessonStep = false;
$isHistory = false;
$date = date('Y-m-d H:i:s');

if (isset($_POST['lclStoragevalues'])) {
    $stepsComletedData = $_POST['lclStoragevalues'];
    $return['lclsValues'] = $stepsComletedData;
    $isLessonStep = true;
}
if (isset($_POST['userHistory'])) {
    $userActionsUpdate = $date . " ->" . $_POST['userHistory'];
    $return['userActions'] = $userActionsUpdate;
    $isHistory = true;
}
$m = new Mongo();
$db = $m->turtleTestDb;
$userProgress = "user_progress";
$userProgressCol = $db->$userProgress;
//Checking if the user already has some data
$userQuery = array('username' => $user);
$userDataExist = $userProgressCol->findOne($userQuery);

$resultcount = $userProgressCol->count($userQuery);
$return['numberOfMathingUsers'] = $resultcount;
//Case we need to add a new record to db
if (!$resultcount > 0) {
    $return['isNewUser'] = true;
    $structure = array("username" => $user, "stepCompleted" => $stepsComletedData
        , "userHistory" => $userActionsUpdate, "lastUpdate" => $date, "tocmd" => $tocmd);
    $result = $userProgressCol->insert($structure, array('safe' => true));
    $newDocID = $structure['_id'];
    $return['programID'] = $newDocID;
} else { //Updating existing user
    $return['isNewUser'] = false;
    // if ($isLessonStep)
    $userActions = $userDataExist['userHistory'];
    if ($isHistory) {
        $stepsComletedData = $userDataExist['stepCompleted'];
    }
    //If The user has a privouse To command saved
    $toCommands = array();
    $new_cmd = array();
    $pos            =   strpos($tocmd , 'to');
    $return['position'] = $pos;
    if ($pos == 0)
    {
        $the_cmd        =   substr($tocmd ,$pos + 2 , -3 );
        $the_cmd_parts  =   explode(' ' , $the_cmd ) ;
        $to_cmd_title   =   $the_cmd_parts[1];
        $new_cmd = array('command' => $tocmd , "title" => $to_cmd_title );
    }
    if (isset($userDataExist['tocmd'])) {
        $toCommands         = $userDataExist['tocmd']; 
        $num_of_commands    =   sizeof($toCommands);
        for ($i =0 ; $i < $num_of_commands; $i++)
        {
            if ($toCommands[$i]["title"] == $to_cmd_title)
            {
               $toCommands[$i]           =   $new_cmd; 
               $addNewToCmd = false;
               break;
            }
        }
        if ($addNewToCmd) { 
            $toCommands[]           =   $new_cmd;
        }
    } 
    else {
        $toCommands[] = $new_cmd;
    } 

    $userActions = $userActions . " , " . $userActionsUpdate;
    $return['userActions'] = $userActions;

    $result = $userProgressCol->update($userDataExist, array("username" => $user, "lastUpdate" => $date,
        "stepCompleted" => $stepsComletedData, "userHistory" => $userActions, "tocmd" => $toCommands));
}
$return['badge'] = "no";
if (isset($_SESSION[$username])) {
    $return['badge'] = badgesUtil :: update_user_badges($user);
    $return['user'] = $user;
}

echo json_encode($return);
?>
