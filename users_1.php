
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">  
  <?php
    if (session_id() == '')
        session_start();
    //If the user is not logged in yet redirect
    require_once("environment.php");
    $is_public_user_page = false ;
    if (isset($_GET['username']))
        $is_public_user_page = true ;
    $display_page = true;
    //Will be redirected only if is not log in and didn't try to get to public page
    if (!isset($_SESSION['username']) && (!$is_public_user_page))
    {
        $_SESSION['redirectBack'] = "users.php";
      //  header('Location: '.$sitePath . "registration.php");
         header('refresh:3; url='.$site_path . "registration.php");
        $display_page = false;
        echo "<center><h1 id='redirect'> You will be redirected in order to log in </h1></center>";
    }
    
    require_once("localization.php");
    require_once("files/footer.php");
    require_once("files/cssUtils.php");
    require_once("files/utils/languageUtil.php");
    require_once('files/utils/topbarUtil.php');
    require_once('files/utils/badgesUtil.php'); 
    require_once('files/utils/userUtil.php');
    if (isset($_SESSION['username'])) 
    {
        $username           = $_SESSION['username'];
        $display_username    =   $username;
    }
    else if ($is_public_user_page)
    {
        $username = $_GET['username'];
        $display_username        =   $username;
    }
    else {
        $display_username        =   "";
    }
    
    if (isset ($_SESSION['username']))
    {
        $display_username = $_SESSION['username'];   
        // update the user badgse
        badgesUtil :: update_user_badges($display_username);
        // get the new user badges

        $lessonsNamesArray = Array("", "Logo's turtle", "Controlling the Turtle and Pen", "Turtle world", "The turtle answer", "Cool labels", "Loops",
            "Polygons", "The pen width", "The turtle is learning", "Colors and Printing", "Variables", "Procedure",
            "The for loop", "Recursion", "Lists", "Accessing the list");
    }
?>


<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8">
        <title>Account 1</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <?php  
            require_once("files/utils/includeCssAndJsFiles.php"); 
            includeCssAndJsFiles::include_all_page_files("users");
        ?>

    </head>
    <body>
        <?php
        //Will display the page only if user is register ,, if not will be redirected
            if ($display_page)
            {
            //Printing the topbar menu
            topbarUtil::print_topbar("users"); 
            
        ?>
        <div class="container span16" id="mainContainer">
            <?php 
                if ($is_public_user_page) {
                    ?>
                
            <div> <h2> <?php echo $display_username . " Public Page"; ?></h2> </div>
            <?php }; //Close div condition ?>
            <div class='cleaner_h40'></div>

            <div class='row'>
                <div class="well span4 sidebar" id="user_menu" lang="<?php echo $lang ?>">
                    <h4>
                    <?php
                        echo $display_username;
                    ?>
                    </h4>
                    <div class='cleaner_h10'></div>
                    <?php
                    if (!$is_public_user_page)
                    {
                    ?>
                    <p>
                        <a href='#'>
                        <?php echo _("Account Settings");
                            echo "(";echo _("coming soon");echo ")"; 
                        ?>
                        </a>
                    </p>
                    <?php
                    }
                    if (isset($_SESSION['institute']))
                    {
                    ?>
                        <p>
                            <a href='<?php echo $root_dir; ?>files/institute/addInstituteUser.php?l=<?php echo $locale_domain; ?>'>
                                <?php echo _("Add a new user"); ?>
                            </a>
                        </p>
                    <?php
                    
                    }
                    if (!$is_public_user_page)
                    {
                    ?>
                    <p>
                        <a href='<?php echo $root_dir; ?>files/newProgram.php?l=<?php echo $locale_domain; ?>'>
                            <?php echo _("Create a new program"); ?>
                        </a>
                    </p>
                    <!--
                    <p>
                        <a href='lesson.php?l=<?php echo $locale_domain; ?>'>
                            <?php echo _("Add a new lesson"); ?>
                        </a>
                    </p>
                    -->
                    <?php
                    }
                    ?>
                    <p> 
                        <a href='#myProgress'>
                            <?php 
                                if ($is_public_user_page)
                                    echo _("User progress");
                                else
                                    echo _("My progress"); 
                            ?>
                        </a>
                    </p>
                    <p> 
                        <a href='<?php  echo $root_dir."users/".$display_username; ?>'>
                            <?php 
                             if ($is_public_user_page)
                                     echo _("User Public Profile"); 
                                else
                                     echo _("My Public Profile"); 
                           
                            ?>
                        </a>
                    </p>
                    <p>
                        <a href='<?php  echo $root_dir."project/doc/".$lang; ?>'>
                            <?php echo _("Help"); ?>
                        </a>
                    </p>
                </div><!-- end of user_menu -->
                <div class=" span10 tab-pane active" id="myProgress">
                    <h2>
                        <?php 
                                if ($is_public_user_page)
                                    echo _("User progress");
                                else
                                    echo _("My progress"); 
                        ?>  
                    </h2>
                    <div class='cleaner_h20'></div>
                    <!-- Display User badges--->
                    <div class="badges">
                    <?php

                        $badges = badgesUtil::get_user_badges($username);
                        // Should use foreatch loop for all badges
                        //echo $badges;
                        $badgesArr           =   explode(",",$badges);
                        if (in_array("1", $badgesArr)) 
                        { 
                            echo "<div class='badge' title='finish lesson number 1' >";      
                                echo "<p> Green shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='".$site_path ."/Images/badges/lightshield.jpg' />";
                            echo "</div>";
                        }
                        if (in_array("2", $badgesArr)) 
                        { 
                            echo "<div class='badge' title='Finish the first 2 lessons' >";                       
                                echo "<p> Brown shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='".$site_path ."/Images/badges/brownshield.jpg' />";
                            echo "</div>";
                        }
                        if (in_array("3", $badgesArr)) 
                        { 
                            echo "<div class='badge' title='Familar with the Turtle World' >";                       
                                echo "<p> Gold shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='".$site_path ."/Images/badges/goldenshield.jpg' />";
                            echo "</div>";
                        }

                    ?>
                    </div> 
                    
                    <!--
                    <p>
                        <?php
                        echo _("Steps that I have done :) ");


                            $m = new Mongo();
                            $db = $m->turtleTestDb;
                            $userProgress = $db->user_progress;
                            $numberOfLessons = 20;
                            //check if the key is in the database
                            //$check_key = mysql_query("SELECT * FROM `confirm` WHERE `email` = '$email' AND `key` = '$key' LIMIT 1") or die(mysql_error());
                            $userQuery = array('username' => $username);
                            $check_key = $userProgress->findOne($userQuery);
                            $resultcount = $userProgress->count($userQuery);
                            if ($resultcount != 0) {
                                $UserProgressData = $check_key['stepCompleted'];
                                $steps = explode(",", $UserProgressData);
                                $numOfSteps = count($steps);
                                $numOfSteps = $numOfSteps - 1;
                                echo " So far you have " . $numOfSteps . " " . "Points " . "</br>";
                                $NumberOfStepsDoneInlesson = array_fill(0, $numberOfLessons, 0);
                                $lessonNumber = 0;
                                for ($i = 0; $i <= $numOfSteps; $i++) {
                                    //echo $steps[$i];
                                    if ($steps[$i] != "" && $steps[$i] != null) {


                                        if (strlen($steps[$i]) == 6) {
                                            //echo "equal 6";
                                            $lessonNumber = substr($steps[$i], 2, 1);
                                        } else {
                                            //echo "equal" . strlen($steps[$i]);
                                            $lessonNumber = substr($steps[$i], 2, 2);
                                        }
                                        //echo "**lesson number is " . $lessonNumber . "";  // bcd 
                                        //$NumberOfStepsDoneInlesson[$lessonNumber]++;
                                        //echo " THE STEP IS " . $steps[$i] . "END OF STEP";
                                    }
                                }
                                $count = 0;
                                $numStepsInLesson = 10;
                                //print_r($NumberOfStepsDoneInlesson);

                                for ($i = 0; $i < $numberOfLessons; $i++) {
                                    if ($NumberOfStepsDoneInlesson[$i] > 0) {
                                        echo "<b>" . "At Lesson  '" . $lessonsNamesArray[$i] . "' you have done" . " " . $NumberOfStepsDoneInlesson[$i] . " " . " steps so far which are" . ": </br></b>";
                                        for ($j = 0; $j < $NumberOfStepsDoneInlesson[$i]; $j++) {
                                            if ($steps[$count] != null && $steps[$count] != "") {
                                                if ($i < 10)
                                                    echo substr($steps[$count], 4, 1) . "  ";
                                                else
                                                    echo substr($steps[$count], 5, 1) . "  ";
                                                $count++;
                                            }
                                        }
                                        echo "</br>";
                                    }
                                }
                            }
                            else {  //No progress was detected by user
                                echo " No Pregress was made yet";
                            }
                        
                        ?>
                    </p>    
                    -->
                </div>
                <div class='span16'id="usrLessonDiv" lang="<?php echo $lang ?>"> 

                    <h2><?php 
                            if ($is_public_user_page)
                                echo _("User Programs");
                            else
                                echo _("Your Programs"); 
                        ?>
                    </h2>
                    <table class='zebra-striped ads' id="my_lessons" lang="<?php echo $lang ?>">
                        <thead>
                            <tr>
                                <th class='span4'><?php echo _("Name"); ?></th>
                                <th class='span4'><?php echo _("Date Created"); ?></th>
                                <th class='span4'><?php echo _("Last updated"); ?></th>
                                <th class='span4'><?php echo _("Actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $user_programs = userUtil::find_user_programs($username);
                            foreach ($user_programs as $program) {
                        ?>
                                <tr>
                                    <td><?php echo $program['programName'] ?></td>
                                    <td><?php echo $program['dateCreated'] ?></td>
                                    <td><?php echo $program['lastUpdated'] ?></td>
                                    <td>
                                        <!--<div class='btn small success disabled'>Renewed</div> 
                                        ?programid=527115cea51ffb9d25000000&username=lucio-->
                                        <a class='btn small info' href="<?php
                                            if ($is_public_user_page)
                                               echo $root_dir . "users/programs/"; 
                                            else   
                                                echo $root_dir . "files/updateProgram.php?programid=";
                                            echo $program['_id'];
                                            if (!$is_public_user_page)
                                            {
                                                echo"&username="; 
                                                echo $username;
                                            }
                                        ?> 

                                           ">  <?php 
                                                    if ($is_public_user_page)
                                                        echo _("View");
                                                    else
                                                        echo _("Edit"); 
                                                ?>
                                        </a>
                                        <!--<div class='btn small danger'>Remove</div> -->
                                    </td>
                                </tr>
                            <?php
                        } // End of foreach loop
                        ?> 
                        </tbody>  
                    </table>
                </div><!-- end of center content -->
            </div>
            <?php
                if (isset($footer))
                    echo $footer;
            ?>
        </div>
    <script> 
          $(document).ready(function() {
            selectLanguage("<?php echo $_SESSION['locale']; ?>" ,  "<?php echo $root_dir; ?>users/", "users.php" ,"en" ); 
        });
    </script>
        <?php
            } // End of rull Checking for session.username exists
        ?>
    </body>
</html>
