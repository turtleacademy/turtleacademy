<!DOCTYPE html>
    <?php
    if (session_id() == '')
        session_start();
    //If the user is not logged in yet redirect
    require_once("environment.php");
    $is_public_user_page = false;
    if (isset($_GET['username']))
        $is_public_user_page = true;
    $display_page = true;
    //Will be redirected only if is not log in and didn't try to get to public page
    if (!isset($_SESSION['username']) && (!$is_public_user_page)) {
        $_SESSION['redirectBack'] = "users.php";
        header('refresh:3; url=' . $site_path . "registration.php");
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
    require_once('files/utils/timeUtil.php');
    require_once('files/utils/pagination.php');

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $display_username = $username;
        if (strpos($display_username, '@') !== false) {
            $name_before_adding_mail_address = explode('@', $display_username);
            $display_username = $name_before_adding_mail_address[0];
            $email_server   =   $name_before_adding_mail_address[1]; 
            $_SESSION['completeEmail'] = "@" . $email_server;
        }
    }
    if ($is_public_user_page) {
        $username       = $_GET['username'];
        $is_mail_user     = false;
        if (strpos($username, '_email') !== false)
        {
            $is_mail_user     = true;
        }
        if ($is_mail_user)
        {
            $username = userUtil :: find_mail_user($username , "_email");
        }
        $display_username = $username;
        if (strpos($display_username, '@') !== false) {
            $name_before_adding_mail_address = explode('@', $display_username);
            $display_username = $name_before_adding_mail_address[0];
            $email_server   =   $name_before_adding_mail_address[1]; 
        }
    } else {
        $display_username = "";
        if (isset($_SESSION['username'])) {
            $display_username = $_SESSION['username'];
            if (strpos($display_username, '@') !== false) {
                $name_before_adding_mail_address = explode('@', $display_username);
                $display_username = $name_before_adding_mail_address[0];
             }
            // update the user badgse
            badgesUtil :: update_user_badges($display_username);
        }
    }
    ?>


<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8">
        <title> <?php $display_username ?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <?php
        echo "<link rel='stylesheet' href='".$root_dir."files/css/pagination.css' type='text/css' media='all'/>"; 
        require_once("localization_js.php");
        require_once("files/utils/includeCssAndJsFiles.php");
        includeCssAndJsFiles::include_all_page_files("users");
        ?>
    </head>
    <body>
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
        //Will display the page only if user is register ,, if not will be redirected
        if ($display_page) {
            //Printing the topbar menu
            topbarUtil::print_topbar("users");
            ?>
            <div class="container span16" id="mainContainer">
            <?php
            if ($is_public_user_page) {
                ?>

                    <div> <h2> <?php echo $display_username . " Public Page" ; ?></h2> </div>
                    
                <?php 
                //echo $emailAdd;
                }; //Close div condition 
                ?>
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
                            if (!$is_public_user_page) {
                                ?>
                            <p>
                                <a href='#'>
                            <?php
                            echo _("Account Settings");
                            echo "(";
                            echo _("coming soon");
                            echo ")";
                            ?>
                                </a>
                            </p>
                            <?php
                            } // End $isPublicUserPage
                            if (isset($_SESSION['institute'])) {
                            ?>
                                <?php
                                if (!$is_public_user_page) {
                                    ?>
                                    <p>
                                        <a href='<?php echo $root_dir; ?>files/institute/addInstituteUser.php?l=<?php echo $locale_domain; ?>'>
                                    <?php echo _("Manage my classes"); ?>
                                        </a>
                                    </p>

                                <?php
                                } // End Add user <p>
                            }
                            if (!$is_public_user_page) {
                            ?>
                            <p>
                                <a href='<?php echo $root_dir; ?>program/new/<?php echo  substr($locale_domain, 0, 2); ?>'>
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
                            <?php
                            if (!$is_public_user_page) {
                            ?>
                            <p> 
                            <?php
                                $m = new MongoClient();
                                $db = $m->turtleTestDb;
                                $strcol = $db->messages;
                                $username = trim($username);
                                $messages_recieve_query = array('sendto' => $username);
                                $messages_general = array('sendto' => 'all');
                                
                                $messages_all = array('$or' => array(array('sendto' => $username ), array('sendto' => 'all')));

                                $new_messages_query =  array('$or' => array(array('sendto' => $username , 'read'=>false ), array('sendto' => 'all', 'read'=>false)));
                                $message_sent_query = array('sendfrom' => $username);
                                //$messagesRecieve = $strcol->find($messagesRecieveQuery);
                                $messages_recieve = $strcol->find($messages_all);
                                $messages_recieve->sort(array('date' => -1));
                                $messages_sent = $strcol->findOne($message_sent_query);
                                $msg_recieve_count = $strcol->count($messages_recieve_query);
                                $num_of_new_msg = $strcol->count($new_messages_query);
                                
                            ?>
                                <a href='#myMessages' id="myMessageslink">
                                <?php
                                echo _("My Messages");
                                if ($num_of_new_msg > 0) {
                                    ?>
                                        <i class="icon-envelope innerIcon" lang="en"></i>
                                        <?php
                                        echo $num_of_new_msg;
                                    } // End if numofMsg >0
                                    ?>
                                </a>

                            </p>
                            <?php
                            } // End of if (!$isPublicUserPage) {
                            ?>
                            <p> 
                                <a href='#myProgress' id="myProgresslink">
                                <?php
                                if ($is_public_user_page)
                                    echo _("User progress");
                                else
                                    echo _("My progress");
                                ?>
                                </a>
                            </p>
                            <p> 
                            <?php
                            if ($is_public_user_page) {
                                if (isset ($_SESSION['username']))
                                {
                                    $private_user_name =  $_SESSION['username'];
                                    if (strpos($private_user_name, '@') !== false) {
                                        $name_before_adding_mail_address = explode('@', $private_user_name);
                                        $private_user_name = $name_before_adding_mail_address[0];
                                    }
                                    echo "<a href='$root_dir" . $user_profile . $private_user_name. "'" . ">";
                                    echo _("My private profile");
                                }
                            } else {
                                echo "<a href='$root_dir" . $user_profile . $display_username . "'" . ">";
                                echo _("My public profile");
                            }
                            ?>
                                </a>
                            </p>
                            <p>
                                <a href='<?php echo $root_dir . $documentation_page . $lang; ?>'>
                                <?php echo _("Help"); ?>
                                </a>
                            </p>
                            <?php
                            if (!$is_public_user_page) {
                            ?>
                            <p>
                                <a href='<?php echo $root_dir . "mylessons.php" ; ?>'>
                                <?php echo _("My lessons"); ?>
                                </a>
                            </p>
                            <?php } ?>
                            
                    </div><!-- end of user_menu -->
                    <div class=" span10 tab-pane " id="myMessages" >
                        <h2>
                            My messages
                        </h2>   

                        <table>
                            <thead>
                                <tr>
                                    <th class='span2'><?php echo _("From"); ?></th>
                                    <th class='span2'><?php echo _("Date"); ?></th>
                                    <th class='span4'><?php echo _("Subject"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            
                            foreach ($messages_recieve as $message) {
                            //foreach ($messagesRecieveQuery as $message) {
                                $class = '';
                                if ($message['read'])
                                    $class = "read";
                            ?>
                                <tr>
                                    <td> <?php echo $message['sendfrom'] ?></td>
                                    <td> <?php echo $message['date'] ?></td>
                                    <td> <a id ="<?php echo $message['_id']; ?>"  title ="<?php echo $message['subject']; ?>" class="openMessage <?php echo $class; ?>"> <?php echo $message['subject'] ?> </a></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>    

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
                            $badgesArr = explode(",", $badges);
                            if (in_array("1", $badgesArr)) {
                                echo "<div class='badge' title='finish lesson number 1' >";
                                echo "<p> Green shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='" . $site_path . "/Images/badges/lightshield.jpg'  />";
                                echo "</div>";
                            }
                            if (in_array("2", $badgesArr)) {
                                echo "<div class='badge' title='Finish the first 2 lessons' >";
                                echo "<p> Brown shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='" . $site_path . "/Images/badges/brownshield.jpg' />";
                                echo "</div>";
                            }
                            if (in_array("3", $badgesArr)) {
                                echo "<div class='badge' title='Familar with the Turtle World' >";
                                echo "<p> Gold shield </p>";
                                echo "<img class='badgeImg' id='turtleimg' src='" . $site_path . "/Images/badges/goldenshield.jpg' />";
                                echo "</div>";
                            }
                            ?>
                        </div> 

                    </div> <!-- End of myProgress div -->
                    
                    <!-- Viewing user programs -->
                    <div class='span16'id="usrLessonDiv" lang="<?php echo $lang ?>"> 

                        <h2><?php
                        if ($is_public_user_page)
                            echo _("User Programs");
                        else
                            echo _("Your Programs");
                            ?>
                        </h2>
                        <?php
                            if ($is_public_user_page)
                            {
                                $user_programs = userUtil::find_user_public_programs($username);
                            }
                            else
                            {
                                $user_programs = userUtil::find_user_programs($username);
                            }
                            $i = 0 ;
                            $display_program_in_page = false;
                            //Adding pagination
                            $limit = 4;
                            $adjacents = 2;
                            if (isset($_GET['page']))
                            {
                                $page = $_GET['page'];
                                $start = ($page - 1) * $limit +1; 
                            }
                            else
                            {
                                $start = 1;
                                $page = 1; //if no page var is given, default to 1.
                            };
                            $num_of_programs = $user_programs->count();
                            $targetpage = curPageURL();
                            if (isset($_GET['page']))
                            {
                                if ($page >=1 && $page < 10)
                                    $targetpage = substr ($targetpage, 0,  strlen ($targetpage) -2);
                                elseif ($page >=10 && $page < 100)
                                    $targetpage = substr ($targetpage, 0,  strlen ($targetpage) -3);
                                else {
                                  $targetpage = substr ($targetpage, 0,  strlen ($targetpage) -4);  
                                }
                            }
                           // echo "limit is " . $limit . " adjacents is " . $adjacents . " page is " . $page . " start is " . $start . " num prog is " . $num_of_programs
                           //         . " target page " . $targetpage;
                            $pagination = pagination($limit,$adjacents,$page,$start,$num_of_programs , $targetpage);
                            ?>
                            <div id="all-user-programs">
                            <?php
                            foreach ($user_programs as $program) { 
                                $i++;
                                if ($i == $start)
                                    $display_program_in_page = true;
                                if ($i == $start + $limit )
                                    $display_program_in_page = false;
                                if ($display_program_in_page)
                                {
                            ?>
                                <div id="user-singel-program" >
                                    <div id ="user-singel-program-image" > 
                                           <a  href="<?php
                                            if ($is_public_user_page)
                                                echo $root_dir . "view/programs/";
                                            else
                                                echo $root_dir . "program/update/";
                                            echo $program['_id'];
                                            if (!$is_public_user_page) {
                                                echo"/" . $username . "/".$lang;                                       
                                            }
                                            ?> 
                                            ">  
                                               <?php
                                               echo '<img src= ' .$site_path .'images/medium/'.$program['_id'].'.png>';
                                                   ?>
                                               <!-- <img  src="<?php echo $program['img'] ?>"/>; -->
                                            </a>
                                    </div>   
                                    <div id ="user-singel-program-description" >
                                        <p><?php echo $program['programName'] ?>
                                        <?php
                                            if ($program['numOfRanks'] > 0)
                                            {
                                        ?>    
                                        <div>
                                            <span> <?php echo _("Vote") ?> </span>
                                            <span><?php echo $program['numOfRanks'] ?> </span>                            
                                        </div>  
                                        <?php
                                            }
                                            if ($program['numOfSpinOffs'] > 0)
                                            {
                                        ?> 
                                        <div>
                                            <span> <?php echo _("Spin-Offs"); ?> </span> 
                                            <span><?php echo $program['numOfSpinOffs'] ?> </span>                          
                                        </div>  
                                        <?php
                                            }
                                            ?>
                                        </p>
                                        
                                    </div>
    
                                </div>
                            <?php
                                } 
                             }  // End of foreach loop
                            echo "</div>"; // close <div id="all-user-programs">
                            echo $pagination;
                            ?> 
                       
                    </div><!-- end of center content -->
                </div>
            <?php
            if (isset($footer))
                echo $footer;
            ?>
            </div>

            <script> 
                $(document).ready(function() {
                    $('.openMessage').each(function() {
                        var id = $(this).attr('id');
                        var title = $(this).attr('title');
                        var $dialog = $('<p></p>');
                        var $link = $(this).live('click', function() {
                            var locale      = $.Storage.get('locale');
                          

                            $dialog                           
                            .load(sitePath + 'message.php?id=' + id ) /* When opening the message will sign it as read message*/
                            .dialog({
                                title: $link.attr('title'),
                                width: 500 
                            }); 
                            $link.click(function() {
                                $dialog.dialog('open');
                                return false;
                            });
                            return false;
                                
                        });                                           
                    });
                        
                    selectLanguage("<?php echo $_SESSION['locale']; ?>" ,  "<?php echo $root_dir . $user_profile; ?><?php if ($is_public_user_page) echo $user_profile. $username; ?>", "users.php" ,"en" ); 
                    $('#myMessages').hide();
                    $('#myMessageslink').click(function() {
                        $('#myProgress').hide();
                        $('#myMessages').show();
                    });
                    $('#myProgresslink').click(function() {
                        $('#myMessages').hide();
                        $('#myProgress').show();
                        
                    });
                    
                            
                });
            </script>
    <?php
} // End of rull Checking for session.username exists
?>
    </body>
</html>
