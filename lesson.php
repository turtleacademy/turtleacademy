<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php
if (session_id() == '')
    session_start();
(isset($_SESSION['Admin']) && $_SESSION['Admin'] == true) || (isset($_SESSION['Guest']) && $_SESSION['Guest'] == true) || (isset($_SESSION['translator']) && $_SESSION['translator'] == true) ? $show = true : $show = false;
(isset($_SESSION['username'])) ? $has_permission = true : $has_permission = false;
require_once ("files/utils/lessonsUtil.php");
require_once("environment.php");
require_once("localization.php");
require_once("files/utils/languageUtil.php");
require_once('files/utils/topbarUtil.php');
?>
<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php
        require_once('files/utils/includeCssAndJsFiles.php');
        includeCssAndJsFiles::include_all_page_files("learn");
        ?>
        <title>
            Create new lesson
        </title> 
        <?php
        $username = "Unknown";
        if (isset($_SESSION['username']))
            $username = $_SESSION['username'];
        ?>
        <link rel='stylesheet' type='text/css' href='<?php echo $root_dir ?>files/css/lessons.css' />
        <script type="application/javascript" src='<?php echo $root_dir ?>files/js/lesson.js'></script> <!-- lessonFunctions --> 

    </head>
    <?php
    function curPageURL() {
                $pageURL = 'http';
                if (isset($_SERVER["HTTPS"])) {$pageURL .= "s";}
                $pageURL .= "://";
                if ($_SERVER["SERVER_PORT"] != "80") {
                 $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
                } else {
                 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                }
                return $pageURL;
            }
    $curr_url = curPageURL();
    if ($has_permission) { // Show the page for register user
        ?>
        <body>
            <?php
            topbarUtil::print_topbar("index");
            $m = new Mongo();
            $db = $m->$db_name;
            $db_lesson_collection = "lessons_created_by_guest";
            if (isset($_SESSION['Admin'])) {
                $db_lesson_collection = "lessons";
            }
            $lessons = $db->$db_lesson_collection;

            $lessonFinalTitle = "";
            $lessonPrecedence = 100;
            $lessonTurtleId = 100;

            //If we are in existing lesson we will enter editing mode 
            if (isset($_GET['lessonid'])) {
                $lu = new lessonsUtil($locale, $lessons, $_GET['lessonid']); 
                $the_object_id = new MongoId($_GET['lessonid']);
                $cursor = $lessons->findOne(array("_id" => $the_object_id));
                $lesson_created_locale = $cursor['localeCreated']; 
                $localSteps = $lu->get_steps_by_locale("locale_" .$lesson_created_locale );
                $lessonFinalTitle = $lu->get_title_by_locale("locale_" .$lesson_created_locale);
                $lessonPrecedence = $lu->get_precedence();
                $lessonTurtleId = $lu->get_turtle_id(); 
                if (strlen($lessonFinalTitle) <= 1)
                    $lessonFinalTitle = "No Title";
                ?>
            <script>
                var editGuestLesson = true;
            </script>
            <?php
            }

            function printElement($i, $flag, $step) {
                global $dir;
                if ($flag) {
                    $action     = $step["action"];
                    $solution   = $step["solution"];
                    $hint       = $step["hint"];
                } else {
                    $action     = "";
                    $solution   = "";
                    $hint       = "";
                }
                // <label class='control-label' > %%a: </lable>
                $baseInputText          = "<div><label class='lesson-label' dir='$dir'> %%a </label><textarea class='lessonInfoElement  input-xlarge' type='text'  name='%%b' id='%%b' placeholder='Step %%a'>%%c</textarea></div>";
                $toReplace              = array("%%a", "%%b", "%%c");
                $replaceWithAction      = array(_("Action"), "action", $action);
                $replaceWithSolution    = array(_("Solution"), "solution", $solution);
                $replaceWithHint        = array(_("Hint"), "hint", $hint);
                $elementAction          = str_replace($toReplace, $replaceWithAction, $baseInputText);
                $elementSolution        = str_replace($toReplace, $replaceWithSolution, $baseInputText);
                $elementHint            = str_replace($toReplace, $replaceWithHint, $baseInputText);
                echo $elementAction;
                echo $elementSolution;
                echo $elementHint;

            }

            function printLeftLessonElemnt($i, $show, $lessonPrecedence, $lessonTurtleId) {
                global $cssleft , $dir;
                echo "<div class='leftLessonElem span7' dir='$dir' style='margin-top:10px; margin-left: 0px;  height:350px;'> 
                        <form class='form'>
                            <fieldset class='lesson-fieldset'> 
                                <div class=''> <label class='lesson-label' dir='$dir'>" . _("Title") . " </label><textarea type='text'  name='title' id='title' placeholder='Step Title' class='input-xlarge' ></textarea>
                                </div>";
                //<!--  Close div RightLessonElem --> 
                printElement($i, false, null);
                echo " <div class=''>
                                        <label class='lesson-label' dir='$dir'> " . _("Explanation") . "</label>
                                        <textarea rows='3' type='text'  name='explanation' id='explanation' class='dscText input-xlarge'></textarea>
                </div> ";
                if ($show) {
                    echo "<div class=''> 
                            <lable class='lesson-label' dir='$dir' > Precedence :</lable> 
                            <textarea type='text'  name='precedence' id='precedence' placeholder='precedence' class='input-xlarge'>";
                    echo $lessonPrecedence;
                    echo"</textarea>";
                    echo "<div class=''> 
                            <lable class='lesson-label' dir='$dir'> TurtleId : 
                            </lable>  
                                <textarea type='text'  name='turtleId' id='turtleId' placeholder='turtleId' class='input-xlarge'>";
                    echo $lessonTurtleId;
                    echo"</textarea>";
                    echo "</div> ";
                } //End of if show
                echo "<div class='divActionBtn'>
                            <a class='btn' id='btnSaveLesson'>" . _("Save Lesson") . "</a>
                            <a class='btn btn-danger' id='btnDeleteLesson'>" . _("Delete Lesson") . "</a>
                        </div>
                        </fieldset>                      
                      </form> 
                    </div>"; // Closing left lesson elements
            }

//End of print left element

            function printRightLessonElemnt() {
                echo "<div class='rightLessonElem ' style='margin-top:10px; margin-left: 0px; height:350px;width:780px;' >
                                 <div id = 'lesson_preview'> 
                                    After saving the lesson data will appear here Instead of the demo console
                                   <div id='console' class='ui-corner-all ui-widget-content' style='width:10px';><!-- command box --><pre class='jqconsole jqconsole-blurred' style='height: 200px;'><div style='position: relative; width: 1px; height: 0px; overflow: hidden; left: 242px; top: 32px;'><textarea wrap='off' style='position: absolute; width: 2px;'></textarea></div><span class='jqconsole-header'>Hi
                                               Welcome to the Turtle world</span><span class='jqconsole-prompt'><span></span><span><span>&gt; </span><span></span><span class='jqconsole-cursor' style='color: transparent; display: inline; z-index: 0; position: relative;'>&nbsp;</span><span style='position: relative;'></span></span><span></span></span></pre>
                                   </div>
                                   <canvas id='sandbox' width='10' height='10' class='ui-corner-all ui-widget-content'>   
                                       <span style='color: red; background-color: yellow; font-weight: bold;'>
                                           TurtleAcademy learn programming for freeYour browser is not supporting canvasWe recomand you to use Chrome or Firefox browsers                                      
                                       </span> 
                                   </canvas>
                                   <canvas id='turtle' width='10' height='10'>   
                                       <!-- drawing box -->
                                   </canvas>
                                </div>
                              </div>   ";
            }

            function printLessonSteps() {
                global $dir;
                echo "<div id='lessonStep'>";
                echo "<div id='stepNavBody'>";
                echo "<h3 class='muted'>" . _("lesson Steps");
                if (isset($_GET['lessonid'])) {
                    echo " ↓";
                    echo _('please choose step to edit');
                }
                echo "</h3>";
                echo "<div id='stepNav' dir='$dir'>";
                echo "<ul id='lessonStepUl' dir='$dir' class=' nav nav-pills'>";
                echo "</ul>";
                echo "</div>";
                //Inserting the step div 
                echo "<div class='actionButtonsStep btn-group' >";
                echo "<button class='btn btn-danger' id='removeStep'>" . _("Remove lesson step") . "</button>";
                echo "</div>"; //End of actionButtons div
                echo "</div>"; //End of stepNev div
                echo "</div>"; //End of lessonStep div
            }

            function printLessonTitle($hasTitle, $lessonFinalTitle, $cursor) {
                echo "<div>
                            <h3 class='muted'>" . _("Lesson Title") . "
                                <input type='text' name='lessonTitle'  id='lessonTitle' class='lessonInput' placeholder='" . _("Lesson Title") . "'
                                   value=\"";
                if ($hasTitle)
                    echo $lessonFinalTitle;
                else
                    echo "";
                echo "\"/>";
                if ($hasTitle)
                    echo "
                            <script type='text/javascript'>
                                $.Storage.set(\"lessonTitle\" , \"$lessonFinalTitle\");
                            </script>";
                echo "
                            <input type='text' name='ObjId' style='display:none' id='lessonObjectId' class='lessonInput' value=\"";
                if (isset($cursor["_id"]))
                    echo $cursor["_id"];
                else {
                    echo "";
                }
                echo "\"/> 
                    </h3> </div>";
            }
            ?>

            <script type='text/javascript'>
                selectLanguage("<?php echo $_SESSION['locale']; ?>" ,  "<?php echo  substr($curr_url, 0, -2); ?>" , "lesson.php" ,"en" ); 
                $.Storage.remove("active-step");
                $.Storage.remove("username");
                $.Storage.remove("turtleId");
                $.Storage.remove("locale");
                $.Storage.set("locale", "<?php echo $locale_domain ?>");
                $.Storage.set("dontClearLocale", "true"); // Prevent clearing locale on lesson.js
            </script>

            <?php
            $i = 1; //Set default value to 1 in case there are no steps
            if (isset($cursor["steps"]) && count($cursor["steps"]) > 0) {
                $i = 0;
                //Init the loacl Storage
                ?>

                <script type='text/javascript'>
                    $.Storage.remove("lessonStepsValues");
                    $.Storage.remove("active-step-num");
                    $.Storage.remove("lesson-total-number-of-steps");
                    $.Storage.remove("collection-name");
                    var lessonStepValuesStorage = new Array(new Array());
                    $.Storage.set('lessonStepsValues', JSON.stringify(lessonStepValuesStorage, null, 2))
                    $.Storage.set("active-step", "lesson_step1");
                    $.Storage.set("lesson-total-number-of-steps", "0");
                    $.Storage.set("collection-name", "<?php echo $db_lesson_collection ?>");
                    
                    $.Storage.set("username", "<?php echo $username; ?>");
                    $.Storage.set("turtleId", "<?php echo intval($lessonTurtleId) ?>");
                </script>

                <?php
                foreach ($localSteps as $step) {
                    $i++;
                    ?>
                    <script type='text/javascript'>
                        //Here I am parsing the response from the ajax request regarding loading an existing lesson
                        var stepNumber = parseInt($.Storage.get("lesson-total-number-of-steps")) + 1;
                        $.Storage.set("lesson-total-number-of-steps", stepNumber.toString());

                        var stepExplanation = <?php echo json_encode($step["explanation"]); ?>;
                        var stepTitle = <?php echo json_encode($step["title"]); ?>;
                        var stepAction = <?php echo json_encode($step["action"]); ?>;
                        var stepSolution = <?php echo json_encode($step["solution"]); ?>;
                        var stepHint = <?php echo json_encode($step["hint"]); ?>;

                        $.Storage.set("lesson-total-number-of-steps", stepNumber.toString());

                        var fullStep = new Array();
                        fullStep[0] = stepTitle;
                        fullStep[1] = stepAction;
                        fullStep[2] = stepSolution;
                        fullStep[3] = stepHint;
                        fullStep[4] = stepExplanation;
                        //adding the step
                        window.addStepVar(stepNumber, fullStep, false, "lessonStepsValues");

                    </script>
                    <?php
                } //End of for each loop
                ?>  
                <div class="container span22" style="float:none;" >
                    <div id="stepSection" style="margin-bottom:4px;" class="stepsSection">    
                        <?php
                        printLessonTitle(true, $lessonFinalTitle, $cursor);
                        printLessonSteps();
                        printLeftLessonElemnt($i, $show, $lessonPrecedence, $lessonTurtleId);
                        printRightLessonElemnt();
                        ?>
                    </div> <!-- End of stepSection -->
                </div> <!-- container -->

                <script type='text/javascript'>

                    //Print Nav  
                    window.createStepNavVar();
                    window.showFirstStepIfExist('lessonStepsValues');

                </script>
                <?php
            } //end of if lesson exist
            else { //Starting case of creating a new lesson
                ?>
                <script type='text/javascript'>
                    var lessonTitle = $('#lessonTitle').val();
                    $.Storage.remove("collection-name");
                    $.Storage.remove("lessonTitle");
                    $.Storage.set("collection-name", "<?php echo $db_lesson_collection ?>");
                    $.Storage.set("lessonTitle", lessonTitle);
                    $.Storage.set("active-step", "lesson_step1");
                    $.Storage.set("username", "<?php echo $username ?>");

                </script>
                <div class="container span22" style="float:none;" >
                    <div id="stepSection" style="margin-bottom:4px;" class="stepsSection ">                                
                        <?php
                        printLessonTitle(false, $lessonFinalTitle, false);
                        printLessonSteps();
                        printLeftLessonElemnt($i, $show, $lessonPrecedence, $lessonTurtleId);
                        printRightLessonElemnt();
                        ?>
                    </div>  <!-- Finish div stepSection -->  
                </div> <!-- Finish stepContainer div -->
                <?php
            } //end of else (New Lesson) 
            ?> 
            <div id="message" style="display: none;">
                <div id="waiting" style="display: none;">
                    Please wait<br />
                </div>
            </div>           
        </body>
    </html>
    <?php
} else { //Unregister user
    echo "Only registered users are allowed to add lessons";
    echo "<p><a class='btn primary large' href='/registration.php'>Register for free</a></p>";
}
?> 