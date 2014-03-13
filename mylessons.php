
<?php
    if (session_id() == '')
        session_start();
    require_once("environment.php");
    require_once("localization.php");
    require_once("files/footer.php");
    require_once("files/cssUtils.php");

        cssUtils::loadcss($locale, "./files/css/lessons");

        $m = new Mongo();

        // select a database
        $db = $m->$db_name;

        // select a collection (analogous to a relational database's table)
        $lessons = $db->lessons_created_by_guest;

        $lessonTitle = "title";
        $lessonSteps = "steps";


        // find everything in the collection
        $cursor = $lessons->find(array('username' => $_SESSION['username']));
        $cursor->sort(array('precedence' => 1));
        ?>
        <table class='zebra-striped ads' id="my_lessons" lang="<?php echo $lang ?>">
            <thead>
                <tr>
                    <th class='span4'><?php echo _("Name"); ?></th>

                    <th class='span4'><?php echo _("Actions"); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($cursor as $lessonStructure) {
                echo "<tr>";
                $title                              =    $lessonStructure[$lessonTitle][$prefix_locale_domain] ;
                $objID                              =    $lessonStructure['_id'];
                $pending_status                     =    $lessonStructure['pending'];
                $lesson_created_locale              =    $lessonStructure['localeCreated'];
                //$translateToLanguage            
                echo "<td>" . $title . "</td> " ;
                $editLessonHref    = "<td><a href='lesson/$objID/$lesson_created_locale' > <span class='lessonh'> Edit </span> </a></td>";
                echo $editLessonHref;
            } 
            ?> 
            </tbody>  
        </table>
<?php
        echo "<div><a href='lesson/$locale_domain' > <span> Create a new lesson  </span> </a></div>" ;

        echo "<span class='footer'>$footer</span>";


?>
