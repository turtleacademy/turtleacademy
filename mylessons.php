 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
    if (session_id() == '')
        session_start();
    require_once("environment.php");
    require_once("localization.php");
    require_once("files/footer.php");
    require_once("files/cssUtils.php");
    require_once('files/utils/topbarUtil.php');
    require_once("files/utils/includeCssAndJsFiles.php");
    includeCssAndJsFiles::include_all_page_files("my-lessons");
        cssUtils::loadcss($locale, "./files/css/lessons");
        topbarUtil::print_topbar("users");
        
        $m = new Mongo();
        $db = $m->$db_name;
        $lessons = $db->lessons_created_by_guest;

        $lessonTitle = "title";
        $lessonSteps = "steps";
        
        // find all the user lessons
        $cursor = $lessons->find(array('username' => $_SESSION['username']));
        $cursor->sort(array('precedence' => 1));
        ?>
        <div class="row">
            <div class="span16" style="float:none;margin: auto;" dir="<?php echo $dir;?>">
                <table class='zebra-striped ads' id="my_lessons" lang="<?php echo $lang ?>">
                    <thead>
                        <tr>
                            <th class='span4'><?php echo _("Lesson name"); ?></th>

                            <th class='span4'><?php echo _("Actions"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($cursor as $lessonStructure) {
                        echo "<tr>";
                        if (isset ($lessonStructure[$lessonTitle][$prefix_locale_domain])) {
                            $title                              =    $lessonStructure[$lessonTitle][$prefix_locale_domain] ;
                        }
                        else
                        {
                             $titles = $lessonStructure[$lessonTitle];
                             $title                              =    reset($titles); 
                        }
                        $objID                              =    $lessonStructure['_id'];
                        $pending_status                     =    $lessonStructure['pending'];
                        $lesson_created_locale              =    substr($lessonStructure['localeCreated'],0,2);
                        //$translateToLanguage            
                        echo "<td>" . $title . "</td> " ;
                        $editLessonHref    = "<td><a class='btn small info'  href='lesson/$objID/$lesson_created_locale' >" . _('Edit') . "</a></td>";
                        echo $editLessonHref;
                    } 
                    ?> 
                    </tbody>  
                </table>
                    <div><a class='btn small info'  href='lesson/<?php echo substr($locale_domain,0,2);?>' >Create a new lesson</a></div>

            </div> <!-- Close span-->
        </div> <!-- Close Row-->
        <?php
         echo "<span class='footer'>$footer</span>";
        ?>
        <script>
                    selectLanguage("<?php echo $_SESSION['locale']; ?>" ,  "<?php echo $root_dir; ?>users/myLessons/", "users.php" ,"en" ); 
        </script>
            
