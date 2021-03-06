<?php
    if(session_id() == '') 
        session_start();
    require_once("environment.php");
    require_once("localization.php");
    require_once("files/footer.php");
    require_once("files/cssUtils.php");
    include_once("files/inc/dropdowndef.php");
    include_once("files/inc/jquerydef.php");
    include_once("files/inc/boostrapdef.php");
 ?>
<!DOCTYPE html>
<html dir="ltr"> 
    <head> 
        <title> <?php  echo _("Project Documentation"); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
        <script type="application/javascript" src="<?php echo $root_dir; ?>files/logo.js"></script> <!-- Logo interpreter -->
        <script type="application/javascript" src="<?php echo $root_dir; ?>files/turtle.js"></script> <!-- Canvas turtle -->
        <script type="application/javascript" src="<?php echo $root_dir; ?>files/Gettext.js"></script> <!-- Using JS GetText -->
        <link rel='stylesheet' href='<?php echo $root_dir; ?>files/css/topbar.css' type='text/css' media='all'/> 

        <?php   
        
            $file_path = "locale/".$locale."/LC_MESSAGES/messages.po";
            $po_file =  "<link   rel='gettext' type='application/x-po' href='".$root_dir."locale/".$locale."/LC_MESSAGES/messages.po'"." />";       
            if ( file_exists($file_path))
                echo $po_file;            
         
            if (!isset ($root_dir))
                $root_dir = "/";
            if (isset($_SESSION['locale']))
                $locale =   $_SESSION['locale'];
            if (!isset($locale))
                if (isset($_GET['locale']))
                    $locale = $_GET['locale'];
                else
                     $locale = "en_US";
            $localePage =   substr($locale, 0, -3); 
            require_once("localization.php");
            $file_path = "locale/".$locale."/LC_MESSAGES/messages.po";
            $po_file =  "<link   rel='gettext' type='application/x-po' href='".$root_dir."locale/".$locale."/LC_MESSAGES/messages.po'"." />";             
            if ( file_exists($file_path))
                echo $po_file;      
             cssUtils::loadcss($locale, $root_dir . "files/css/doc"); 
             cssUtils::loadcss($locale, $root_dir . "files/css/topbar");
        ?>
                <script type="application/javascript"> 
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-26588530-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head>
    <body>
        <?php  
            $class = ($locale == "he_IL" ?  "pull-right" :  "pull-left");    
            $login = ($locale != "he_IL" ?  "pull-right" :  "pull-left");    
        ?> 
        <div class="topbar" id="topbarMainDiv"> 
            <div class="fill" id="topbarfill">
                <div class="container span16" id="topbarContainer"> 
                    <img class="brand" id="turtleimg" src="<?php echo $root_dir ?>files/turtles.png" alt="צב במשקפיים">

                    <ul class="nav" id="turtleHeaderUl"> 
                            <li><a href="<?php echo $root_dir."lang/".$localePage; ?>" style="color:gray;" ><?php echo _("TurtleAcademy");?></a></li> 
                            <!--<li class="active"><a href="index.html"><?php echo _("Sample");?></a></li> -->
                    </ul> 
                    <?php
                        if (isset($_SESSION['username']))
                        {
                    ?>                       
                            <!--  <p class="pull-right">Hello <a href="#"> -->
                                <nav class="<?php echo $login ?>" style="width:200px;" id="turtleHeaderLoggedUser">
                                    <ul class="nav nav-pills <?php echo $login ?>" id="loggedUserUl">

                                        <li style="padding: 10px 10px 11px;"> <?php echo _("Hello");?></li>
                                        <li class="cc-button-group btn-group"> 
                                            <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" style="color:#ffffff; background-color: rgba(0, 0, 0, 0.5);" >
                                            <?php
                                                echo $_SESSION['username'];
                                            ?>
                                                <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu" id="ddmenu"role="menu" aria-labelledby="dLabel">
                                                <li><a tabindex="-1" href="/docs"   class="innerLink" id="help-nav"><?php echo _("My account");?></a></li>
                                                <li><a tabindex="-1" href="/docs" class="innerLink" id="hel-nav"><?php echo _("Help");?></a></li>
                                                <li><a href="logout.php" class="innerLink"><?php echo _("Log out");?></a></li>
                                            </ul>


                                        </li>
                                    </ul> 
                                </nav>                                                                     
                                </a>

                        <?php
                            }
                            else
                            {
                        ?>       
                                <ul class="nav <?php echo $login ?>" id="turtleHeaderUl"> 
                                    <li><a href="<?php echo $root_dir; ?>registration.php" id="turtleHeaderUlLogin"><?php echo _("Login");?></a></li> 
                                    <li><a class='btn primary large' href="<?php echo $root_dir; ?>registration.php" ><?php echo _("Sign Up for free");?></a></li> 
                                </ul>                         
                         <?php
                            }
                         ?>
                </div>
            </div> <!-- Ending fill barf -->
        </div> <!-- Ending top bar -->
        <div class="container">
            <div class="content">
                <div class="page-header"> 
                    <h1>
                        <?php  echo _("About the project"); ?> 
                    </h1>  
                </div>
                <div class="row">
                    <div class="span10">
                        <!-- <div id="logo1"></div> -->
                        <h2>
                            <?php  echo _("About the project"); ?>  
                        </h2>
                        <div class='cleaner_h20'></div>
                        <p>
                            <?php
                                echo _("The project contains a client side learning environment and a compiler for the <a target='_bjlank' href='http://he.wikipedia.org/wiki/%D7%9C%D7%95%D7%92%D7%95_(%D7%A9%D7%A4%D7%AA_%D7%AA%D7%9B%D7%A0%D7%95%D7%AA)'>Logo</a> Programming language.");
                                echo _("The project enables to learn the Logo language and programming principles and can be used for programming logo");
                            ?>
                        </p>    
                        <div id="logo2"></div>
                        <h2>     
                            <?php  echo _("Why was it done"); ?>
                        </h2>
                        <div class='cleaner_h20'></div>
                        <p>
                            <?php
                                echo _("My brother has been volunteering in a primary school in Israel for the past few years.");
                                echo _("He was trying to instill the basic programming skills in the children and he discovered that there are three main problems.");
                                echo _("The first is the language barrier (all the programming languages are using English and the children's do not speak the language ),"); 
                                echo _("the second issue is that currently there are not many online learning programs which address children.");
                                echo _("The third issue is the installation requirement of an appropriate programming environment in order to be able to start programming.");
                                echo _("For this reason we thought it would be a good idea to join forces and create TurtleAcademy");
                                echo _("The inspiration for the project came from other projects like <a target='_blank' href='http://www.khanacademy.org/'> Khanacademy</a> And a javascript learning project named <a target='_blank' href='http://www.codecademy.com/'>Codecademy</a>");
                            ?>
                        </p>
                        <div id="logo3"></div>
                        <h2>       
                            <?php echo _("Who are we ?");?>
                        </h2>
                        <div class='cleaner_h20'></div>
                        <p>
                        
                            <?php
                                echo _("Lucio  - Webmaster  and  amateur ping pong player");  echo "</br>";          
                                echo _("Ofer   - Father of three , amateur entrepreneur");    echo "</br>";             
                                echo _("Amir   - Colnect master , uncle of six");             echo "</br>";             
                                echo _("Dana   - Tiger painter , likes to read lessons");     echo "</br>";  
                                echo _("Ayelet - Full time mother");                          echo "</br>";             
                                echo _("Almog  - Blogger , made his first QA steps at the age of seven"); echo "</br>";        
                                echo _("Inbar  - Very talented Wii player");                  echo "</br>";          
                                echo _("Raz    - QA and vegetarian");                         echo "</br>";
                                echo _("Yuval  - QA and future Messi");                       echo "</br>";
                            ?>   
                        </p>
                        <div id="logo4"></div>
                        <h2>  
                            <?php echo _("Tricks"); ?>
                        </h2>
                        <div class='cleaner_h20'></div>
                        <p>

                            <?php
                            echo _("New commands that have been learned will be saved even after the browser restarts"); echo "</br>";
                            echo _("The turtle remembers the student's progress over time, allowing the student to follow his own progress"); "</br>";
                            echo _("The drawing that appear below actually show live logo programs !"); echo "</br>";
                            ?>
                        </p>
                        <div id="logo5"></div>
                            <h2>
                                <?php  echo _("Credits");?>
                            </h2>
                            <div class='cleaner_h20'></div>
                            <p>
                                <?php
                                    echo _("A lot of written libraries facilitate the creation of the website"); echo "</br>";
                                    echo _("<a target='_blank' href='http://www.calormen.com/Logo/'> Joshua Bell - wrote the logo component </a>"); echo "</br>";
                                    echo _("<a target='_blank' href='https://github.com/replit/jq-console'> jsconsole- wrote the input element ( Console ) </a>"); echo "</br>";
                                    echo _("<a target='_blank' href='http://jquery.com'> jquery - component which enabled easy java scripting </a>"); echo "</br>";
                                    echo _("<a target='_blank' href='http://jqueryui.com/'> jqueryui - a collection of graphical components </a>"); echo "</br>";
                                    echo _("<a target='_blank' href='http://api.jquery.com/category/plugins/templates/'> jquerytemplates - enable transmission of data to html </a>"); echo "</br>";
                                ?>  
                            </p>
                            <div id="logo6"></div>

                            <h2> 
                                <?php echo _("Contact us"); ?>
                            </h2>
                            <div class='cleaner_h20'></div>
                            <p>
                                <a href="mailto:support@turtleacademy.com" target="_blank"> <?php echo _("Send an email"); ?> </a>
                            </p>
                   </div> <!-- end of span10 -->
              </div> <!-- end of row -->              
            </div> <!-- end of content -->
        </div> <!-- End of container --> 
        <script>
            function do_logo(id ,cmd) {
                $('#'+id).css('width', '100px').css('height', '100px').append('<canvas id="'+id+'c" width="100" height="100" style="position: absolute; z-index: 0;"></canvas>' +
                    '<canvas id="'+id+'t" width="100" height="100" style="position: absolute; z-index: 1;"></canvas>');
                var canvas_element2 = document.getElementById(id+"c");
                var turtle_element2 = document.getElementById(id+"t");
                var turtle2 = new CanvasTurtle(
                canvas_element2.getContext('2d'),
                turtle_element2.getContext('2d'),
                canvas_element2.width, canvas_element2.height);

                g_logo2 = new LogoInterpreter(turtle2, null);
                g_logo2.run(cmd);
            } 
            //do_logo ('logo1', 'fd 20');
            do_logo ('logo2', 'repeat 8 [fd 10 rt 360/8]');
            do_logo ('logo3', 'repeat 10 [repeat 8 [fd 10 rt 360/8] rt 360/10]');
            do_logo ('logo4', 'repeat 10 [fd repcount*8 rt 90] ht');
            do_logo ('logo5', 'window repeat 10 [fd 3 * repcount repeat 3 [fd 15 rt 360/3] rt 360/10] ht');
            do_logo ('logo6', 'window pu home repeat 20 [ setlabelheight 20-repcount fd repcount label "HTML5Fest bk repcount rt 18 ] ht');
        </script>
 <script>
        // Select language in main page
      $(document).ready(function() {
                    $('.dropdown-toggle').dropdown();
                    $.Storage.set("locale","<?php echo $_SESSION['locale']; ?>");
                    //Show selected lanugage from dropdown                   
                    try { 
                            var pages = $("#selectedLanguage").msDropdown({on:{change:function(data, ui) {
                                    var val = data.value;
                                    if(val!="")
                                           window.location = "<?php echo $root_dir; ?>lang/" + val; 
                            }}}).data("dd");
                                                        var pagename    = document.location.pathname.toString();
                            pagename        = pagename.split("/");
                            var pageIndex   = pagename[pagename.length-1];
                            if (pageIndex == "" || pageIndex == "index.php" )
                                 pageIndex   = "en";
                            pages.setIndexByValue(pageIndex);
                            //$("#ver").html(msBeautify.version.msDropdown);
                    } catch(e) {
                            //console.log(e);	
                    } 
	
                    //$("#ver").html(msBeautify.version.msDropdown);

                    //convert
                    $("select").msDropdown();
                    //createByJson();
                    $("#tech").data("dd");             
                    });
                        function showValue(h) {
                                    console.log(h.name, h.value);
                            }
                            $("#tech").change(function() {
                                    console.log("by jquery: ", this.value);
                            })
        </script>
    </body>
</html>

<?php

?>
