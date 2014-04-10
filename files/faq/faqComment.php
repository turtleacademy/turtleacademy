<!DOCTYPE html>
<?php
if (session_id() == '')
    session_start();
$phpDirPath = "../registration/inc/php/";
include_once $phpDirPath . 'config.php';
include_once $phpDirPath . 'functions.php';
require_once ('../../environment.php');
require_once ("../../localization.php");
require_once ('../utils/topbarUtil.php');
require_once("faqSideNav.php");

?>
<html dir="<?php echo $dir ?>" lang="en">
    <head>
        <meta charset="utf-8">
        <title>Report a Problem</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <?php
        //echo "<link rel='stylesheet' type='text/css' href='" . $rootDir . "files/css/registration.css' /> ";

         require_once("../utils/includeCssAndJsFiles.php"); 
        includeCssAndJsFiles::include_all_page_files("faqadmin");         
        ?>     
        <!--
        <link rel='stylesheet' href='<?php echo $root_dir; ?>files/css/zocial.css' type='text/css' media='all'/> 
        <link rel='stylesheet' href='../css/faq.css' type='text/css' media='all'/>
        <script src="<?php echo $root_dir; ?>ajax/libs/jquery/validator/dist/jquery.validate.js" type="text/javascript"></script>
        --> 
        <script type='text/javascript'> 
            $(document).ready(function(){
          
                $('#topbar').dropdown();
                $('#username_in').focus();
                $('#username_in').focus();
                $("#submit-comment-form").validate({
                    
                    rules: {
                        reportername: {
                            required: true, 
                            minlength: 2
                        },
                        subject: {
                            required: true, 
                            minlength: 3
                        },
                        comment: {
                            required: true, 
                            minlength: 3
                        },
                        
                        email: {
                            required: true,
                            email: true
                        }
                        
                    },
                    messages: {
                        reportername: {
                            required: <?php echo '"'._("Please enter your username").'"'?>, 
                            minlength: <?php echo '"'._("Your name must contain at least 4 characters").'"'?>
                        },
                        subject: {
                            required: <?php echo '"'._("Please enter the comment subject").'"'?>, 
                            minlength: <?php echo '"'._("Subject should contain at least 3 Characters").'"'?>
                        },
                        comment: {
                            required: <?php echo '"'._("Please enter the comment content").'"'?>, 
                            minlength: <?php echo '"'._("Content should contain at least 3 Characters").'"'?>
                        },
                        email: {
                            required: <?php echo '"'._("Please enter your email address").'"'?>,
                            minlength: <?php echo '"'._("Please enter a valid email address").'"'?>
                        }
                    }
                });                   
            });

        </script>
        
    </head>
    <body>
        <?php
            topbarUtil::print_topbar("faq");
        ?>
        <div id="main" >
            <div id="container">
                <div id="faq-comment-main" class="span16">
                <h3>
                    Submit a comment
                </h3>
                <div id="form">
                    <form class='form-stacked' id='submit-comment-form' method="post" action="faqSaveComment.php">
                        <div class="form-block">
                            <span class="faq-form-label"> Your name </span>
                            <span class="faq-form-required"> (Required)</span>
                            <div class="input">
                                <input id="reportername" name="reportername" type="text"> </input>
                            </div>
                        </div>   
                        <div class="form-block">
                            <span class="faq-form-label"> Email Address </span>
                            <span class="faq-form-required"> (Required)</span>
                            <div class="input">
                                <input id="email" name="email" type="text"> </input>
                            </div>
                        </div>   
                        <div class="form-block">
                            <span class="faq-form-label"> Subject </span>
                            <span class="faq-form-required"> (Required)</span>
                            <div class="input">
                                <input id="subject" name="subject" type="text"> </input>
                            </div>
                        </div>   
                        <div class="form-block">
                            <span class="faq-form-label"> Message</span>
                            <span class="faq-form-required"> (Require)</span> 
                            <div class="input">
                                <textarea id="comment" name="comment" col="6" rows="6"></textarea>
                            </div>
                        </div>   
                        <input type='submit' value='<?php echo _("Submit"); ?>' id='signup' name='signup' class="btn primary"/>
                    </form>
                </div> <!-- Close form div -->
                </div>
                <?php
                    echo $sideNav;
                ?>
            </div> <!-- Close container div-->
        </div> <!-- Close main div-->    
    </body>