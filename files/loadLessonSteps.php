<?php
    require_once("../environment.php");
    require_once ("utils/lessonsUtil.php");
    if (isset($_GET['lesson'])) {
        $m = new MongoClient();
        $db = $m->$db_name;
        if (isset($_GET['col']))
            $db_lesson_collection = $_GET['col'];
        $lessons = $db->$db_lesson_collection;
        
        $languageGet = "l";
        $locale = $_GET[$languageGet];
        $lu = new lessonsUtil($locale, $lessons, $_GET['lesson']);
        $the_object_id = new MongoId($_GET['lesson']);
        $cursor = $lessons->findOne(array("_id" => $the_object_id));
        $localSteps = $lu->get_steps_by_locale("locale_" . $_GET[$languageGet]);
        $localtitle = $lu->get_title_by_locale("locale_" . $_GET[$languageGet]);

        $return_lesson_info['title'] = $localtitle;
        $return_lesson_info['steps'] = $localSteps;
        echo json_encode($return_lesson_info);        
    }
?> 