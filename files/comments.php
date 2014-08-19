<?php
if (session_id() == '')
    session_start();
require_once("../environment.php");
require_once("../localization.php");
require_once("cssUtils.php");
require_once("utils/languageUtil.php");
require_once('utils/topbarUtil.php');
require_once('utils/userUtil.php');
require_once('utils/programUtil.php');
error_reporting(E_ERROR | E_PARSE);
?>
<link rel="stylesheet" href="<?php echo $site_path; ?>/files/codemirror/lib/codemirror_turtle.css">
<?php
    date_default_timezone_set('America/Los_Angeles');
    $logged_in_user = false;
    $sendTo = "";
    if (isset($_SESSION['username']))
    {
        $logged_in_user = true;
        $sendTo = $_SESSION['username'];
    }
    $sendTo = "";
    $program_id = $_GET['programid'];
    $m = new MongoClient();
    $db = $m->turtleTestDb;
    $programs = "programs";
    $programs_collection = $db->$programs;
    $the_object_id = new MongoId($program_id);
    $criteria = $programs_collection->findOne(array("_id" => $the_object_id));
    $program_name = $criteria['programName'];
    $bodytag = str_replace("\n", "↵", $criteria['code']);                    
?>
                <?php
                    $comment_place_holder = _('Add comment to the program') . "..";
                    if ($logged_in_user)
                    {
                ?>
                <form id="add_comment_form"> 
                        <textarea id="commentTxtArea" placeholder="<?php echo $comment_place_holder;?>"></textarea>
                        <input id="btn_comment" type="button" value="<?php echo _('submit comment'); ?>" class="btn small info pressed"></input>
                </form>
                <?php
                    }
                    else{
                ?>
<div><span> <?php echo _("You must"); ?> <a href="<?php echo $root_dir . "registration.php";?>"><?php echo _("login"); ?> </a> <?php echo _("to comment"); ?></span></div>
                <?php
                    }
                 ?> 
                </div>
                <div id="numOfComments">
                    <?php echo $criteria['numOfComments']; echo " " ; echo "Comments"?>
                </div>
                <div id ="user-comments">
                   <?php
                        $comments = programUtil::find_program_comments($the_object_id);
                        //print_r($comments); 
                        if (is_array($comments) )
                        {
                            foreach ($comments as $comment)
                            {
                                echo "<div class='comment-contain'>";
                                    echo "<div class='comment-title'>"; 
                                         echo "<span>";
                                         ?>
                                         <a class='' href="<?php
                                                echo $root_dir . "users/profile/";
                                                $user = userUtil::strip_user_email($comment['user']);
                                                echo $user['name'];
                                                if ($user['email'])
                                                    echo "_email";
                                                ?>">
                                                 <?php echo $user['name']; ?>
                                         </a>
                                         <?php
                                         echo "</span>";    
                                         echo "<span class='title-time'>"; 
                                            $date = new DateTime($comment['time']);
                                            echo $date->format('Y-m-d');
                                         echo "</span>"; 
                                    echo "</div>";
                                    echo "<div class='comment-content'>"; 
                                        echo "<p>";
                                            echo $comment['comment'];
                                        echo "</p>";
                                    echo "</div>";
                                echo "</div>";  // Closing of comment-contain
                            }
                        }
                   ?>
                </div>
                <script>      
        $("#btn_comment").click(function() {  
        //programid username
        if (username != null)
            {
                var saveCommentUrl  = sitePath + "files/saveProgramComment.php";
                var updateMsgUrl    = sitePath + "files/messages/saveNewMessage.php";
                var cmt = $("#commentTxtArea").val();
                $.ajax({
                    type : 'POST',
                    url : saveCommentUrl,
                    dataType : 'json',
                    data: {
                        comment         : cmt,
                        programid       : programid,
                        username        : username
                    },

                    success : function(data){
                        //alert('success');
                        $("#comments").load(sitePath + 'files/comments.php?programid=' + programid);
                    },       
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        alert('fail');  
                    }
                });
                $.ajax({
                    type : 'POST',
                    url : updateMsgUrl,
                    dataType : 'json',
                    data: {
                        comment         : cmt,
                        programid       : programid,
                        username        : username,
                        programCreator  : programCreator,
                        programSubject  : '<?php echo $criteria['programName'];?>'
                    },
                    success : function(data){
                        alert('success');
                        $("#comments").load(sitePath + 'files/comments.php?programid=' + programid);
                    },       
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.responseText);  
                    }
                });
            }
         else{
             alert('Only register users can comment');
         }
    });
    </script>