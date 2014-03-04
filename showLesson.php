<?php
require_once("environment.php");
require_once("localization.php");
require_once("files/cssUtils.php");
require_once("files/utils/languageUtil.php");
            cssUtils::loadcss($locale_domain, $root_dir . "files/css/interface"); 
?>
<script>
    var numOfActiveLessons = 1;
    var no_carousel = true ;
    var dont_show_first_lesson = true;
</script>    
<style>
.jqconsole {
    width : 460px !important;
}
.accordion
{
    width: 300px;
}
#logoer , #display {
    width :460px !important;
}
</style>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script>
        <?php  
            $m              = new Mongo();
            $db             = $m->$db_name;
            $lessons        =   $db->lessons_created_by_guest;
            //TODO if not editing mode and lesson was just created Get won't help need to take from local storage
            $the_object_id       =   new MongoId($_GET['objid']);
            $localPosted    =   $_GET['locale'];
            $cursor = $lessons->find(array("_id" => $the_object_id));
            // Putting all the new lesson input
            echo "var lessons = [";
                foreach ($cursor as $lessonStructure) {
                    $lessonStructure['id'] = '' . $lessonStructure['_id'];
                    unset($lessonStructure['_id']);

                    if (isset($lessonStructure['locale_' . $localPosted])) {
                        //  echo "isset ".$lessonStructure['locale_' . $_GET[$localPosted]];
                        $lessonStructure = $lessonStructure['locale_' . $localPosted];
                    }
                    if (isset($lessonStructure["steps"])) {
                        // echo "is set steps";
                        $lessonSteps = $lessonStructure["steps"];
                    }
                    $showItem = true;
                    foreach ($lessonSteps as $key => $value) {
                        "enterLessonSteps";
                        //echo "Key = " . $key ;
                        // If we have locale for the current step we will set him
                        if (isset($lessonSteps[$key]['locale_' . $localPosted])) {
                            $lessonSteps[$key] = $lessonSteps[$key]['locale_' . $localPosted];
                        } else {
                            $showItem = false;
                        }
                        // unsetting the other locale values
                        foreach ($value as $kkey => $vvalue) {
                            //echo "Key = " . $kkey ;
                            if (strpos($kkey, 'locale') === 0) {
                                unset($lessonSteps[$key][$kkey]);
                            }
                        }
                    }
                    $lessonStructure["steps"] = $lessonSteps;
                    $finalTitle = $lessonStructure["title"];
                    //Now handling the title

                    $lessonTitles = $lessonStructure["title"];
                    foreach ($lessonTitles as $key => $value) {
                        //echo "@@@".$key;
                        if ($key == 'locale_' . $localPosted) {
                            $finalTitle = $lessonTitles[$key];
                        }
                    }
                    $lessonStructure["title"] = $finalTitle;

                    // cleanup extra locales
                    foreach ($lessonStructure as $key => $value) {
                        if (strpos($key, 'locale') === 0) {
                            unset($lessonStructure[$key]);
                        }
                    }
                   
                        echo json_encode($lessonStructure);
                        echo ",";
                    
                }
                echo "]";
            ?>  
        </script>    
        <script type="application/javascript" src="files/interface.js?locale=<?php echo $locale ?>"></script> 
    </head>
    <body> 
        <div id='lesson_preview'>
            <div id="logoer"> 
                <div id="display"> 
                    <canvas id="sandbox" width="460" height="350" class="ui-corner-all ui-widget-content">   
                        <span style="color: red; background-color: yellow; font-weight: bold;">
                            <?php
                            echo _("Your browser does not support canvas - an updated browser is recommended");
                            ?>                                      
                        </span>
                    </canvas>
                    <canvas id="turtle" width="460" height="350">   
                    </canvas>
                </div>

                <div id="console" class="ui-corner-all ui-widget-content" style='width: 460px;'><!-- command box --></div>
            </div>

            <div id="accordion">
            </div>
            <div id="lessonnav">
                <?php
                    $lu = new languageUtil("turtleTestDb", "rtlLanguages");
                    $isRtlLocale = $lu->findIfLocaleExist($locale);
                ?>
            </div>
    </body>
</html>
