<?php
error_reporting(E_ERROR | E_PARSE);
require_once("../environment.php");
require_once ("utils/badgesUtil.php");
//require_once("../environment.php");

if (!isset($_SESSION)) {
    session_start();
}
$user = "Unknown";
$username = "username";
$return['a'] = "a";
if (isset($_SESSION[$username])) {
    $user = $_SESSION[$username];

//If we tought the turtle a new TO command it will be save seperately 
    $cmd = "";
    $tocmd = "";
    $addNewToCmd = false; // If we will have to save a new user TO command
    $date = date('Y-m-d H:i:s');
    $userActions = ""; // Will represent the user history already save for the user
    $userActionsUpdate = " "; // The new user actions that should be save to history

    $all_off_to_commands = array();
    if (isset($_POST['command'])) {

        $cmd = $_POST['command'];
        $trimmedcmd = trim($cmd);

        if (strcasecmp(_(substr($trimmedcmd, 0, 2)), "to") == 0) { //Case the TO command learned in other language
            $trimmedcmd = preg_replace("/\r|\n/", " ", $trimmedcmd);
            $trimmedcmd = preg_replace('/[ \t]+/', " ", $trimmedcmd);
            //remove redundant spaces to be only 1 space
            while (strpos($trimmedcmd, '  ') > 0) {
                $trimmedcmd = str_replace('  ', ' ', $trimmedcmd);
            }
            $all_off_to_new_commands = explode("end", $trimmedcmd);
            $num_of_new_TO_commands = sizeof($all_off_to_new_commands) - 1;
            for ($i = 0; $i < $num_of_new_TO_commands; $i++) {
                $all_off_to_commands[$i] = trim($all_off_to_new_commands[$i]) . " end"; //Only works for english now
            }
            $addNewToCmd = true;
        }
        $userActionsUpdate = $date . " ->" . $trimmedcmd; //Case of saving program 
    }
    $return['username'] = $user;
    $stepsComletedData = " ";

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
    $m = new MongoClient();
    $db = $m->turtleTestDb;
    $userProgress = $db_progress_collection;
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
            , "userHistory" => $userActionsUpdate, "lastUpdate" => $date, "tocmd" => $all_off_to_commands);
        $result = $userProgressCol->insert($structure);
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
        if ($addNewToCmd) {
            $toCommands = array();
            $new_to_command_object = array();

            //Running over the all new commands and creating objects of them
            for ($i = 0; $i < $num_of_new_TO_commands; $i++) {
                $the_cmd_parts = explode(' ', $all_off_to_commands[$i]);
                if (isset($the_cmd_parts[1])) {
                    $to_cmd_title = $the_cmd_parts[1];
                    $new_to_command_object[$i] = array('command' => $all_off_to_commands[$i], "title" => $to_cmd_title);
                }
            }
            //After created the objects we will check if the user already defined those commands before
            if (isset($userDataExist['tocmd'])) {
                $toCommands = $userDataExist['tocmd'];
                $already_saved_commands_size = sizeof($toCommands);
                $return["numofcommand"] = $already_saved_commands_size;
                //Check we have at least 1 command in the memory
                if ($already_saved_commands_size > 0) {
                    // Now for each 1 of the commands we need to check if the new command oveeride it
                    for ($i = 0; $i < $already_saved_commands_size; $i++) {

                        if ($addNewToCmd == false) {
                            break;
                        }
                        if (is_array($toCommands[$i])) {
                            if (!isset($to_cmd_title))
                                $to_cmd_title = " Fake";

                            //Need to check if one of the new TO commands are already in in the commands list
                            for ($j = 0; $j < $num_of_new_TO_commands; $j++) {
                                if ($toCommands[$i]["title"] == $new_to_command_object[$j]["title"]) {
                                    $toCommands[$i] = $new_to_command_object[$j];
                                    //We already worked on the command we can remove it
                                    array_splice($new_to_command_object, $j, 1);
                                    $num_of_new_TO_commands = sizeof($new_to_command_object);
                                    if ($num_of_new_TO_commands == 0)
                                        $addNewToCmd = false;
                                    break;
                                }
                            }
                        }
                    }
                    //If there are more command to add
                    if ($addNewToCmd) {
                        if (is_string($toCommands))
                            $toCommands = array($new_to_command_object);
                        else {
                            for ($j = 0; $j < $num_of_new_TO_commands; $j++) {
                                $toCommands[] = $new_to_command_object[$j];
                            }
                        }
                    }
                } else {
                    $toCommands = $new_to_command_object;
                }
            }
            //Not addingg a new to command
            else {
                if (isset($userDataExist['tocmd'])) {
                    $toCommands = $userDataExist['tocmd'];
                } else
                    $toCommands = array();
            }
        }
        //Not adding TO commmand
        else {
            if (isset($userDataExist['tocmd'])) {
                $toCommands = $userDataExist['tocmd'];
            } else
                $toCommands = array();
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
   
}
 echo json_encode($return);

?>