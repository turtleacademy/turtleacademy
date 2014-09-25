<html>
    <head>
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel='stylesheet' href='../css/footer.css' type='text/css' media='all'/>  
         <script type="application/javascript"> <!-- Google Analytics Tracking -->

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-26588530-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
    </head>
</html>
<?php
    require_once("../../environment.php");
    require_once("../../localization.php");
    
    require_once("../footer.php");
    require_once("../cssUtils.php");
    $show = true ;
    $permission_num            = 1;
    if (isset ($_SESSION['permision']))
        $permission_num            =   $_SESSION['permision'] ;
    //echo $permissionNum;
    
    $permission_for_edit_lesson        = array(1,100);
    $permmision_translate_chinese           = array(1,2);
    $permission_translte_spanish           = array(1,2);
    $permission_translte_german            = array(1,103);
    $permission_translte_russain           = array(1,107);
    $permission_translte_arbic               = array(1,108);
    $permission_translte_thai              = array(1,109);
    $permission_approve_lesson           = array(1); 

    if (isset($_SESSION['username']))
    {
            echo "Hello ";
            echo $_SESSION['username'];
            if ($_SESSION['username'] == "translator" || $_SESSION['username'] == "admin" || $_SESSION['username'] == "eneditor" ||
                    $_SESSION['username'] == "gereditor" || $_SESSION['username'] == "rueditor" || $_SESSION['username'] == "areditor"
                    || $_SESSION['username'] == "theditor")
                $show = true ;
    }



    if ($show)
    {

        $locale = "en_US"; // Setting default
        $localePrefix = "locale_";
        $locale_get = 'locale';
        if (isset($_GET[$locale_get]))
            $locale = $_GET[$locale_get];


        cssUtils::loadcss($locale, "../css/lessons");

        $m = new MongoClient();

        // select a database
        $db = $m->$db_name;
        // select a collection (analogous to a relational database's table)
        $news = $db->news;

        // find everything in the collection
        $cursor = $news->find();
        $cursor->sort(array('itemid' => 1));

        echo "<div> <span class='title'> Edit one of the following news items </div>";
        foreach ($cursor as $newsItem) {
            $headline                         =    $newsItem['headline'] ;
            $context                          =    $newsItem['context'];
            $pending_status                    =    $newsItem['approve'];
            $objID                            =    $newsItem['_id'];
            
           
            echo "Lesson name is <b>" . $headline . "</b> " ;
            /*
            $editLessonHref    = "<a href='lesson.php?lesson=$objID&lfrom=$locale' > <span class='lessonh'> Edit Lesson <b>" . $headline . " </b></span> </a>";
            $translateLessonToChinese   = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=zh_CN' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to chinenese </span> </a>";
            $translateLessonToSpanish   = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=es_AR' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to Spanish </span> </a>";
            $translateLessonToGerman    = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=de_DE' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to German </span> </a>";
            $translateLessonToRussain   = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=ru_RU' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to Russain </span> </a>";
            $translateLessonToArb       = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=ar_SY' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to Arabic </span> </a>";
            $translateLessonToThai      = "<a href='translating.php?lesson=$objID&lfrom=$locale&ltranslate=th_TH' > <span class='lessonh'> Translate Lesson <b>" . $headline . " </b> to Thai </span> </a>";
            */
            
            $approveLesson ;
            if ($pending_status)
            {
                echo "Lesson is currently approved";
                $approveLesson = "<a href='approveNewsItem.php?item=$objID&pending=false&col=news' > <span class='lessonh'> Unapprove Lesson (lesson will appear in main page) </span> </a>";
            }
            else
            {
                echo "Lesson is curretnly unapproved";
                $approveLesson = "<a href='approveNewsItem.php?item=$objID&pending=true&col=news' > <span class='lessonh'> Approve (lesson won't appear in main page) </span> </a>";
            }

            echo "<div style='display:inline;height:60px;'>";    
            if (in_array($permission_num , $permission_approve_lesson)) 
                    echo $approveLesson;
                echo "<a href='insertNewsItem.php?itemid=$objID'> Edit </a>"; 
            echo   "</div>";   
            echo "</br>"; 
        } 

        echo "<div><a href='lesson.php' > <span> Create a new lesson  </span> </a></div>" ;

        echo "<span class='footer'>$footer</span>";
    }
    else
    {
        echo " User is not register ";
    }
?>
