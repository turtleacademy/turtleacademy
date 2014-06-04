<?php
//TODO check why when translating .. the title is being changin
//TODO check why not all cache steps are being saved
if (session_id() == '')
    session_start();
require_once("environment.php");

$m = new MongoClient();
$db = $m->$db_name;

//updateLoclaStorageForLoggedUser($m , $db);

if (isset($_SESSION['username']))
{
 updateToCmd($m , $db);
} 

function updateToCmd($m , $db) 
{
    global $db_progress_collection;
        $userProgressCol    =   $db->$db_progress_collection;
        $userQuery = array('username' => $_SESSION['username']);
        $cursor = $userProgressCol->findone($userQuery);
        $user_to_command    = $cursor['tocmd'];   
        if ($cursor != null && isset($cursor['tocmd']))
        {         
            $user_to_command    = $cursor['tocmd'];
            //$user_to_command    = str_replace(array("\r", "\n"), " ", $user_to_command);
            $runcmd             =    $user_to_command;
            $num_of_to_cmds     =   sizeof($user_to_command);
            $storage_tocmd_value = "localStorage.setItem('tocmd' ,'" ;
            for ($i = 0 ; $i < $num_of_to_cmds ; $i++ ) 
            {
                if (isset ($user_to_command[$i]) && is_array($user_to_command[$i]))
                {
                    if(isset($user_to_command[$i]["command"]))
                    $storage_tocmd_value .= $user_to_command[$i]["command"] . " ,";
                }
            }
            $storage_tocmd_value .= "');";
            echo $storage_tocmd_value;
                 
        }
}
function updateLoclaStorageForLoggedUser($m , $db)
{
    global $db_progress_collection;
    if (isset ($_SESSION['username']))
    {
        //echo "; var username = " . $_SESSION['username'] ;
        $userProgressCol    =   $db->$db_progress_collection; 
        $userQuery = array('username' => $_SESSION['username']);
        $cursor = $userProgressCol->findone($userQuery);
        echo ";";
        if ($cursor != null && isset($cursor['stepCompleted']))
        {
            $data = explode(",", $cursor['stepCompleted']);         
            $datalen    = count($data);
            $value = "true";
            for ($i =0 ; $i < $datalen -1 ; $i++)
            {
               echo "localStorage.setItem('$data[$i]' ,'$value' );";
                 
            }
            if (isset($cursor['userHistory']))
            {
                $historyVal                       =   $cursor['userHistory'];
                $historyValNoSpecialCaracter      =   str_replace( "'", "" , $historyVal);
                echo "localStorage.setItem('logo-history' ,'$historyValNoSpecialCaracter' );";
            }
        }
    }
} 