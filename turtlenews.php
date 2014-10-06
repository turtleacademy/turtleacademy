<?php
if (session_id() == '')
    session_start();
require_once("environment.php");
require_once("localization.php");
require_once("localization_js.php");
require_once("files/footer.php");
require_once("files/cssUtils.php");
require_once ('files/utils/topbarUtil.php');
require_once('files/utils/timeUtil.php');
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('America/Los_Angeles');
$m = new MongoClient();
$db = $m->turtleTestDb;
$newscol = $db->news;

$newsQuery = array('approve' => true);
$newsItems = $newscol->find($newsQuery);
$newsItems->sort(array('date' => -1));
?>
<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo _("TurtleAcademy news"); ?></title> 
        <?php
            require_once("files/utils/includeCssAndJsFiles.php"); 
            includeCssAndJsFiles::include_all_page_files("news");
        ?>
        
    </head>
    <body align="center"> 
    <?php 
        //Printing the topbar menu
        topbarUtil::print_topbar("news");
    ?>

        <div id="turtleNewsBody" class="span16 columns" lang="<?php echo $lang ?>"> 
            <h1><?php echo _("The Turtle news"); ?></h1>
            <!-- End of News Items topbar menu nav -->
            <?php
                $i = 1;
                foreach ($newsItems as $newsItem) {
                    $i++;
                    $headline = $newsItem['headline'];
                    $context = $newsItem['context'];
                    $itemid = $newsItem['itemid'];
                    $date = $newsItem['date'];
                    $newdate =  FormatTime(strtotime($date));
                    //$dateTime = new DateTime($date);
                    //$newdate    =    date_format($dateTime, 'Y-m-d');
                    if ($locale_domain != "en_US") {
                        $localestr = "locale_" . $locale_domain;
                        if (isset($newsItem['headline_translate'][$localestr]) )
                        {
                            if (strlen($newsItem['headline_translate'][$localestr]) > 3)
                                $headline = $newsItem['headline_translate'][$localestr];
                            else
                                $headline = $headline . " <span class='nottranslated'>(Hasn't been translated yet)</span>";
                        }
                        if (isset($newsItem['context_translate'][$localestr]) && strlen($newsItem['context_translate'][$localestr]) > 3)
                        {
                            $context = $newsItem['context_translate'][$localestr];
                        }
                        //echo $headline . $context;
                    }
                    if (strlen($headline) > 3) { //Check if the header is valid for this language
                        ?> 
                        <div data-spy="scroll" id="divScroll" data-target="#navbarExample" data-offset="50" class="scrollspy-example" lang="<?php echo $lang ?>">
                            <h2 class="newsItemTitle" id="<?php echo $itemid ?>"> <span><?php echo $headline ?> </span> <span id="newsDate" lang="<?php echo $lang ?>"><?php echo $newdate; ?> </span></h2>
                            <p><?php echo $context; ?></p>
                        </div>
                        <?php
                    } //End of checking if headline is really exist
                }
            ?>
        <?php
            if (isset($footer))
                echo $footer;
        ?> 
        </div>
    </body>
    <script>
        // Select language in main page
        $(document).ready(function() {
            selectLanguage("<?php echo $_SESSION['locale']; ?>" , "<?php echo $root_dir; echo $news_page;?>" , "turtlenews.php" ,"en_US" );
        })
        
    </script>
</html>