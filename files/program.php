
<!DOCTYPE html>
<?php
if (session_id() == '')
    session_start();
require_once("../environment.php");
require_once("../localization.php");
require_once("../localization_js.php");
require_once("cssUtils.php");
require_once("utils/languageUtil.php");
require_once('utils/topbarUtil.php'); 
require_once('progdoc.php');
$logged_in_user = false;
if (isset($_SESSION['username']))
{
    $logged_in_user = true;
    $sendTo = $_SESSION['username'];
}

?>    
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        <?php
        echo _("Turtle Academy - learn logo programming in your browser");
        echo _(" free programming materials for kids");
        ?>  
    </title>     

    <?php
    require_once("utils/includeCssAndJsFiles.php"); 
    includeCssAndJsFiles::include_all_page_files("user-program"); 
    echo "<script type='application/javascript' src='" . $root_dir . "files/jquery.Storage.js' ></script>";
    ?>      
    <?php
        $file_path = "../locale/" . $locale_domain . "/LC_MESSAGES/messages.po";
    $po_file = "<link   rel='gettext' type='application/x-po' href='$site_path/locale/" . $locale_domain . "/LC_MESSAGES/messages.po'" . " />";
    
    if (file_exists($file_path))
       echo $po_file;
    else {
        echo "<script> var translationNotLoaded = 5; </script>";      
      } 
    ?>  
</head>
<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
<body>
        <?php
            //Printing the topbar menu
            topbarUtil::print_topbar("program");
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
            //echo $curr_url;
            $is_new     = strpos($curr_url,'/new/') !== false;
            $is_update  = strpos($curr_url,'/update/') !== false;
            $is_view    = strpos($curr_url,'/view/') !== false;
            
            $program_name = _("program 1");
            if ($is_update || $is_view)
            {
                $program_id = $_GET['programid'];
                $m = new Mongo();
                $db = $m->turtleTestDb;
                $programs = "programs";
                $programs_collection = $db->$programs;
                $the_object_id = new MongoId($program_id);
                $criteria = $programs_collection->findOne(array("_id" => $the_object_id));
                $program_name = $criteria['programName'];
                $bodytag = str_replace("\n", "↵", $criteria['code']);
            }
        ?>
    <div class="container" style="width:1200px;">
        <h2 id="program-info-header"><?php echo $program_name; ?></h2>
        <div id="command-to-draw">
            <div id="cm-side">          
                <form id="txtarea-container-form"><textarea id="code" name="code" placeholder="Write your code here .. e.g fd 50"></textarea></form>        
            </div>

            <div id="logoerplain"> 
                <div id="displayplain"> 
                    <canvas id="sandbox" width="600" height="400px" class="ui-corner-all ui-widget-content">   
                        <span style="color: red; background-color: yellow; font-weight: bold;">
                            <?php
                            echo _("TurtleAcademy learn programming for free");
                            echo _("Your browser is not supporting canvas");
                            echo _("We recoomnd you to use Chrome or Firefox browsers");
                            ?>                                      
                        </span> 
                    </canvas>
                    <canvas id="turtle" width="600" height="400px" >    
                    </canvas>
                </div>
            </div>
        </div> <!-- Close div command to drawing -->
        <div id="instructions"> 
            <div id="code-error-div">
                <input id="err-msg" type="text" placeholder="An error message will appear here"> </input>    
                <textarea id="console-output" name="code" style="width: 100px;" placeholder="print output..."></textarea>
            </div>
            <div id="action-buttons" > 
                <form> 
                    <?php
                        if ($is_new)
                        {
                    ?>
                    <input id="btn_save_program" type="button" value="<?php echo _("Save");?>" class="btn small info pressed"></input>
                    <input id="btn_clear" type="button" value="<?php echo _("Clear");?>" class="btn small info pressed"></input>
                    <input id="runbtn" type="button" value="<?php echo _("Run");?>" class="btn small info pressed"></input>
                    <?php
                        }
                        if ($is_update)
                        {
                    ?>   
                    <input id="btn_update_program" type="button" value="<?php echo _("Update"); ?>" class="btn small info pressed"></input>
                    <input id="btn_clear" type="button" value="<?php echo _("Clear"); ?>" class="btn small info pressed"></input>
                    <input id="btn_delete" type="button" value="<?php echo _("Delete Program"); ?>" class="btn small info pressed"></input>
                    <input id="btn_create" type="button" value="<?php echo _("Create a new Program"); ?>" class="btn small info pressed"></input>
                    <input id="runbtn" type="button" value="<?php echo _("Run"); ?>" class="btn small info pressed"></input>
                    <input id="btn_public_page" type="button" value="<?php echo _("Program Public page"); ?>" class="btn small info pressed"></input>
                    <input type='checkbox' id='publicProgramsCheckbox' name='publicProgramsCheckbox' value='is public' <?php if ($criteria['displayInProgramPage'] && $criteria['displayInProgramPage'] != "false" ) echo "checked='true'";?>><?php echo _("public"); ?></input>
                     <?php
                        }
                        if ($is_view)
                        {
                        ?>
                        <div>
                            <form> 
                                <input id="runbtn" type="button" value="<?php echo _('run'); ?>" class="btn small info pressed"></input>
                                <?php
                                    if (isset($_SESSION['username'] ) && $_SESSION['username'] == "admin")
                                    {
                                ?>   
                                <input id="btn_update_pic" type="button" value="<?php echo _("Update pic"); ?>" class="btn small info pressed"></input>
                                <?php
                                    }
                                    if ($logged_in_user)
                                    {
                                ?>
                                <input id="btn-spin-off" type="button" value="<?php echo _('Save as spin-off'); ?>" class="btn small info pressed"></input>
                                <?php
                                    }
                                ?>
                            </form>
                        </div>
                        <div id="rank">

                        </div>
                    <?php
                        }
                        ?>
                        
                </form>
            </div>
            <div id ="tab-row">
            </div>
            <?php
                if ($is_view)
                {
            ?>
            <div id="comments"> <!-- Comments load dynamically -->
            </div>
            <?php
                }
                ?>
            <?php
                echo $program_documentation;
            ?>
        </div> <!-- Close of container div --> 

</body>
<script>
 
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true
    });
    <?php
        if ($is_update || $is_view)
        {
            echo "var programid = '$program_id'" ;
            ?>
            
            editor.setValue('<?php echo $bodytag; ?>'); 
    <?php
            if ($is_view)
            {
                $program_user_name  =   $criteria['username'] ;
                echo "var programCreator = '$program_user_name'";
            }
        } else 
        {
            echo " var programid = null ;";
        }
        ?>
        
    <?php 
        if (isset($_SESSION['username']))
            echo "var username = '" . $_SESSION['username'] . "';";
        else
            echo "var username = null;";
    ?> 
    selectLanguage("<?php echo $_SESSION['locale']; ?>" , "<?php echo  substr($curr_url, 0, -2); ?>" , "program.php" ,"<?php echo substr($_SESSION['locale'], 0, 2) ?>" );
    <?php
    //Load function only on update mode
    if ($is_update)
    {
    ?>
    
    $("#btn_public_page").click(function() {    
        jConfirm('Are you sure you want to go to your public page?'  , 'Public Page', function(r) {
            if (r)
            {
                location.href = "<?php echo $site_path; ?>" + "/view/programs/" + "<?php  echo $_GET['programid'];?>" + "/" + "<?php echo substr($_SESSION['locale'], 0, 2); ?>";           
            }
           
        });
    });
    <?php } ?>
</script>
</html>