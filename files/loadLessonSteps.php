<?php
    require_once("../environment.php");
    require_once ("utils/lessonsUtil.php");
    if (isset($_GET['lesson'])) {
        $m = new Mongo();
        $db = $m->$dbName;
        $lessons = $db->$dbLessonCollection;
        $localePrefix = "locale_";
        
        $languageGet = "l";
        $locale = $_GET[$languageGet];
        $lu = new lessonsUtil($locale, "locale_", $lessons, $_GET['lesson']);
        $theObjId = new MongoId($_GET['lesson']);
        $cursor = $lessons->findOne(array("_id" => $theObjId));
        $localSteps = $lu->getStepsByLocale($localePrefix . $_GET[$languageGet]);
        $localtitle = $lu->getTitleByLocale($localePrefix . $_GET[$languageGet]);
        //return $localSteps;
       // foreach ($localSteps as $step)
        //{
       //     echo json_encode($step);
        //}
        //TOOD return title
        
        $return['title'] = $localtitle;
        $return['steps'] = $localSteps;
        echo json_encode($return);        
    }
    //return null;
?> 
