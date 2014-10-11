<?php
if (session_id() == '')
    session_start();
$phpDirPath = "../email/inc/php/";
require_once ('../../environment.php');
require_once ("../../localization.php");
require_once ('../utils/topbarUtil.php');
require_once ('../openid.php');
require_once ('../utils/userUtil.php');
?>
<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8">
        <title>Add Student</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <?php
            require_once("../utils/includeCssAndJsFiles.php");
            includeCssAndJsFiles::include_all_page_files("institute");
         echo "<link rel='stylesheet' href='../css/institute.css' type='text/css' media='all'/>";
         
        ?>     
                <link rel='stylesheet' href='<?php echo $root_dir; ?>files/css/zocial.css' type='text/css' media='all'/>   
 
        <script>
        $(document).ready(function(){
            $('#topbar').dropdown();
        });
        </script>
    </head>
    <body>
    <?php
    topbarUtil::print_topbar("institute");
    if (!isset($_SESSION['institute_email']))
        {
            echo _("You don't have institute admin permission Please contact site administrator") ;
        }
        else
        {
    ?>    
       <div class='span16'id="usrLessonDiv" lang="<?php echo $lang ?>">          
            <h1><?php echo $_SESSION['institute_name'];echo " ";echo _("Student List"); ?></h1>
            <h3><p> <?php echo $_SESSION['institute_description']; ?></p></h3>
            <table class='zebra-striped ads' id="my_lessons" lang="<?php echo $lang ?>">
                <thead>
                    <tr>
                        <th class='span4'><?php echo _("Username"); ?></th>
                        <th class='span4'><?php echo _("Join date"); ?></th>
                        <th class='span4'><?php echo _("Set new password"); ?></th>
                        <th class='span4'><?php echo _("action"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $instituteStudents = userUtil::get_institiute_users_by_institue_admin_email($_SESSION['institute_email']);
                    $i = 0;
                    foreach ($instituteStudents as $student) {
                        $i = $i++;
                        ?>
                        <tr>
                            <td>
                            <a class='' href="<?php
                                            echo $root_dir . $user_profile;
                                            $username    = $student['username'];
                                            echo $username . "/" . $lang; 
                                            ?>"> 
                                            <?php
                                                echo $username ;
                                            ?>  
                                        </a>
                            </td>
                            <td><?php if (isset($student['date'])) echo $student['date']; ?></td>
                            <td>
                                <textarea row="3" id='password<?php echo $i ?>'></textarea>
                            </td> 
                            <td>
                                <div class='btn small info pressed' id='<?php echo $i ?>' name='<?php echo $student['username'] ?>'>save</div>
                            </td>
                        </tr>
                        <?php
                    } // End of foreach loop
                    ?> 
                </tbody>  
            </table>
            <p><a href="addInstituteUser.php"> <?php echo _("Back to add student page"); ?> </a></p>
            <p><a href='<?php echo $root_dir; ?>users.php'> <?php echo _("Back to my account"); ?> </a></p>
        </div><!-- end of center content -->
        <?php
            }
        ?>
    </body>
     <script type="application/javascript">  
             $(document).ready(function() {
           
                    $(".pressed").click(function() {
                        var id              = $(this).attr('id');
                        var username        = $(this).attr('name');
                        var thepassword     = "password"+id;
                        var password         = $('#' + thepassword).val();
                        var passLen = password.length;
                        if (passLen < 6)
                            alert("<?php echo _("Password should contain at least 5 caracters"); ?>")
                        else
                        $.ajax({
                            type : 'POST',
                            url : 'setStudentPassword.php',
                            dataType : 'json',
                            data: {
                                username        :   username,
                                password       :   password
                            },
                            success: function(data) { 
                                alert('successfully save');
                            } ,
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert('en error occured');
                            }
                        });
                    });
            });
         </script>