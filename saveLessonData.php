<?php

//TODO refer translation mode when I get the translation = true by the translating.php page 
if (!isset($_SESSION)) {
  session_start();
}
require_once("environment.php");
require_once("files/utils/arrayUtil.php");
sleep(3);


//Getting User Info
$user = "Unknown";
$username   =   "username";
if (isset($_SESSION[$username]))
{
    $user = $_SESSION[$username] ;
}
  //$return['session'] = Print_r($_SESSION);

if (empty($_POST['steps'])) {
    $return['error'] = true;
    $return['msg'] = 'No steps found'; 
} else {
    $return['error'] = false;
    $steps = $_POST['steps'];

    $decodedStepValue           = json_decode($steps);
    $return['decodedSteps']     = $decodedStepValue;
    $return['msg']              = 'You\'ve entered: ' . $steps . '.';
    //$return['firstElem']        = $decodedStepValue[1];


    //TODO case of removing singel step 
    /// Saving the lesson data into lessonSteps
    for ($i = 1; $i <= $_POST['numOfSteps']; $i += 1) {
        $stepsArray = $decodedStepValue[$i];
        // $translatArray = array("title" => $stepsArray[0], "explanation" => $stepsArray[1], "action" => $stepsArray[2],
        //     "solution" => $stepsArray[3], "hint" => $stepsArray[4]);
        $translatArray = array("title" => $stepsArray[0], "action" => $stepsArray[1], "solution" => $stepsArray[2],
            "hint" => $stepsArray[3], "explanation" => $stepsArray[4]);
        $lessonSteps[$i] = $translatArray;
    }
    $return['lessonSteps'] =  $lessonSteps;
    
    $m = new MongoClient();
    // select a database
    $db = $m->$db_name;
    // select a collection (analogous to a relational database's table)
    $precedence = $_POST['precedence'];
    $turtleId = $_POST['turtleId'];
    $dbCollection   =   "lessons";
    if (isset($_POST['collection']))
       $dbCollection    =   $_POST['collection'] ;
    $lessons = $db->$dbCollection;
    $localefullpath = "locale_en_US";
    $locale         = "en_US";
    if (isset($_POST['locale']))
    {
        $locale         =   $_POST['locale'];
        $localefullpath = "locale_" . $locale;       
    }
    
    
    //Case we are inserting a new lesson
    if (!isset($_POST["ObjId"]) OR $_POST["ObjId"] == null OR strlen($_POST["ObjId"]) < 2) {
        $titles = array('locale_en_US' => $_POST['lessonTitle']);
        for ($i = 1; $i <= $_POST['numOfSteps']; $i += 1) {
            $lessonStep["$localefullpath"] = $lessonSteps[$i];
            $finalArrAfterTranslation[$i] = $lessonStep;
        }
        $structure = array("steps" => $finalArrAfterTranslation, "title" => $titles, "pending" => "true" , "username" => $user , "localeCreated" => $locale , "register_only" => false );
        $result = $lessons->insert($structure);
        $return['objID'] = $structure['_id'];
    } 
    else 
    { //updating existing lesson

        $return['objID'] = $_POST["ObjId"];
        $return['isExistingLesson'] = "true";

        $the_object_id = new MongoId($_POST['ObjId']);
        $criteria = $lessons->findOne(array("_id" => $the_object_id));
        
        $isStepRemove = $_POST["isStepRemove"];
        $stepToRemove = $_POST["stepToRemove"];

        //Case we want to remove object 
        if (isset($_POST["formDelete"])) {
            $result = $lessons->remove(array('_id' => $the_object_id), true);
            
        } else { //Case we don't want to remove object but updating existing one
            
            $originLanguageStepsArr = $criteria["steps"]; 
            $return['originLanguageStepsArrBeforeSet'] = $originLanguageStepsArr;
            $originalTitle = $criteria["title"];
            $i = 1;
            //$finalArrAfterTranslation = array();
            $numberOfSteps  =  $_POST['numOfSteps']; 
            for ($i = 1; $i <= $numberOfSteps; $i++) {
                
                $return['$i'] = $i;
                $originLanguageStepsArr[$i]["$localefullpath"] = $lessonSteps[$i];
            }
            $return['originLanguageStepsAfterSettingLessonSteps'] = $originLanguageStepsArr;
            //Will delete step by step not in a list
            if ($isStepRemove)
            {
               $return['isStepRemove']          = true ; 
               $return['stepToRemove']         = $stepToRemove;
               //$lenStepToRemove                 = sizeof($stepToRemove);
               //$return['stepToRemoveLen']      = $lenStepToRemove;
               //for ($i = 0; $i < $lenStepToRemove; $i += 1) {
                   //unset($originLanguageStepsArr[$stepToRemove[$i]]);   
               $return['originLanguageStepsArrBeforeSet'] = $originLanguageStepsArr;
                unset($originLanguageStepsArr[$stepToRemove]);        
                   //Return index array from 0 ( need from 1 will create a function to fix)
                $originLanguageStepsArr = arrayUtil::reindexArray($originLanguageStepsArr);
                for ($i = 1; $i <= $numberOfSteps; $i += 1) {
                    $originLanguageStepsArr[$i]["$localefullpath"] = $lessonSteps[$i];
                } 
               //} 
            }
            $return['originLanguageStepsArrAfterSet'] = $originLanguageStepsArr;
            $lessonsTitle = $originalTitle;
            $postLessonTitle = "";
            if (isset($_POST['lessonTitle']))
                $postLessonTitle = $_POST['lessonTitle'];
            $lessonsTitle["$localefullpath"] = $postLessonTitle;
            //$return['finalArrAfterTranslation'] = $finalArrAfterTranslation;
            $result = $lessons->update($criteria, array('$set' => array("steps" => $originLanguageStepsArr, "title" => $lessonsTitle, "precedence" => $precedence, "username" => $user , "lesson_turtle_id" => $turtleId)));
            $return['isExistingLesson'] = "If We got Any result";
        }
    }
    
    //$return['keys'] = array_keys($_POST['steps'] );
}
echo json_encode($return);
?>