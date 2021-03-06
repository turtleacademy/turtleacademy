<?php
    $fileDirectory = "../../";
    require_once("../../../environment.php");
    require_once("../../../localization.php");
    require_once("../../../localization_js.php");
    require_once($fileDirectory."utils/translationUtil.php");

    require_once($fileDirectory."utils/includeCssAndJsFiles.php");
    includeCssAndJsFiles::include_all_page_files("index");

    echo "<link rel='stylesheet' href=' " . $fileDirectory ."css/lessonReport.css' type='text/css' media='all'/>";
   
    if(session_id() == '') 
        session_start();

    $fullLocalePath     =   "locale_".$locale;
    
    $m = new MongoClient();
    $db                 = $m->turtleTestDb;
    $strcol             = $db->lessons_translate_status;
    $lessons            = $strcol->find();
    //Should be sorted by precedence
    $lessons->sort(array('precedence' => 1));
    $lang               = translationUtil::get_language($locale);
    
    echo "<div> <h1> Lesson translate to " . $lang . " report </h1></div>";
    foreach ($lessons as $lesson)
    {
        $progress       =   $lesson['in_progress'];
        $completed      =   $lesson['completed'];
        $title          =   $lesson['title'];
        $lessonId       =   $lesson['lesson_id']; 
        $translated     =   $completed[$fullLocalePath] == "true";
        $headerClass    =   "no_full_translate";
        if ($translated)
            $headerClass = "full_translate";
    ?>    
    <div class="span12" style="height: 77px;">
        <h2 class="<?php echo $headerClass; ?>"> 

            <?php echo $title;?>
        
        </h2>
        <div class=""> 
            <span>
                <?php
                    if ($translated)
                        echo " Lesson is completely translated"; 
                    else
                        echo " <b> Lesson is NOT completely translated </b>";
                ?>
            </span> 
        </div>
        <?php
            if ($completed[$fullLocalePath] != "true")
            { // Only if lesson is not completly translated we will check progress atatus
        ?>        
        
        <div class=""> 
            <span>
                <?php 
                    if ($progress[$fullLocalePath] == "true")
                        echo "Lesson is now under progress"; 
                    else
                        echo "Trnaslation of lesson haven't been started";
                ?>
            </span> 
        </div>
        <?php
            }
         ?>
        <div class=""> 
            <span>
                <?php 
                
                    echo "<a href='../../../translating.php?lesson=$lessonId&lfrom=en_US&ltranslate=$locale' > <span class='lessonh'> Translate Lesson " . $title . " to " .$lang ."  </span> </a>";
                
                ?>
            </span> 
        </div>
    </div>    

    <?php
    } // Ending foreach loop
    echo "</div>"; //Closing the header div
    ?>
    <div class=""> 
        <span>
            <?php 

                echo "<span><a href='../strings/showStrByLocale.php?locale=$locale' > <span class='lessonh'>Translate Strings</span> </a></span><br/>";
                echo "<span><a href='../../turtleNews/newsTrans.php?locale=$locale' > <span class='lessonh'>Translate News</span> </a></span>";
                echo "<span><a href='../../faq/faqTranslateReportPage.php?locale=$locale' > <span class='lessonh'>Translate Faq</span> </a></span>";
            ?>
        </span> 
    </div>
    <?php
    ?>

