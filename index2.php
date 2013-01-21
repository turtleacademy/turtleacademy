
<!DOCTYPE html>
<?php
    if(session_id() == '') 
        session_start();
    //if (!isset($_SESSION['username']))
    //        $_SESSION['username'] = "lucio";
    if ( !isset ($locale))
    {
        $locale = "en_US";
    }                 
    require_once("localization.php");
    require_once("files/footer.php");
    require_once("files/cssUtils.php");
    require_once("files/utils/languageUtil.php");
    $relPath    =   "files/bootstrap/twitter-bootstrap-sample-page-layouts-master/";
    $ddPath     =   "files/test/dd/";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
        <?php
            echo _("Turtle Academy - learn logo programming in your browser");
//        אקדמיית הצב - למד תכנות לוגו היישר מתוך הדפדפן                
            $currentFile = $_SERVER["PHP_SELF"];
            $parts = Explode('/', $currentFile);
            $currentPage = $parts[count($parts) - 1];
        ?>         
        </title>    
     <!-- Adding the dropdown dd directory related -->
        <script src="<?php echo $ddPath . 'js/jquery/jquery-1.8.2.min.js'?>"></script> 
      <!-- <msdropdown> -->
        <link rel="stylesheet" type="text/css" href="<?php echo $ddPath . 'css/msdropdown/dd.css'?>" />
        <script src="<?php echo $ddPath . 'js/msdropdown/jquery.dd.min.js'?>"></script>
      <!-- </msdropdown> -->
        <link rel="stylesheet" type="text/css" href="<?php echo $ddPath . 'css/msdropdown/skin2.css' ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $ddPath .  'css/msdropdown/flags.css' ?>" /> 
     <!-- Finish the dropdown dd directory related -->
        <!--<script type='application/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js'></script> -->
        <script type='application/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js'></script>
       
        <!--<script  type="text/javascript" src="ajax/libs/jquery/1.6.4/jquery.js"></script> <!--- equal to googleapis -->
        <script  type="text/javascript" src="ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script> <!--- equal to googleapis -->
          
        <!--<script  type="text/javascript" src="ajax/libs/jquery/jquery.min.js"></script> <!--- equal to googleapis v
        
        <script type="application/javascript" src="files/compat.js"></script> <!-- ECMAScript 5 Functions -->
        <script type="application/javascript" src="files/logo.js"></script> <!-- Logo interpreter -->
        <script type="application/javascript" src="files/turtle.js"></script> <!-- Canvas turtle -->
        <script type="application/javascript" src="files/jquery.tmpl.js"></script> <!-- jquerytmpl -->
        <!--<script src="<?php echo $relPath . 'scripts/jquery.min.js' ?>"></script>-->
        <?php
            $file_path = "locale/".$locale."/LC_MESSAGES/messages.po";
            $po_file =  "<link   rel='gettext' type='application/x-po' href='locale/".$locale."/LC_MESSAGES/messages.po'"." />";       
            if ( file_exists($file_path))
                echo $po_file;            
        ?>       
        <script type="text/javascript">
                var locale = "<?php echo $locale; ?>";
        </script>
        <!--<link   rel="gettext" type="application/x-po" href="locale/he_IL/LC_MESSAGES/messages.po" /> <!-- Static Loading hebrew definition -->
        <script type="application/javascript" src="readMongo.php?locale=<?php echo $locale?>"></script> <!-- Lessons scripts -->
        <script type="application/javascript" src="files/Gettext.js"></script> <!-- Using JS GetText -->
        <script type="application/javascript" src="files/interface.js?locale=<?php echo $locale?>"></script> <!-- Interface scripts -->
        <script type="application/javascript" src="files/jqconsole.js"></script> <!-- Console -->
        <script type="application/javascript" src="files/jquery.Storage.js"></script> <!-- Storage -->
        <link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css' type='text/css' media='all'/>
        <link rel='stylesheet' href='./files/css/interface.css' type='text/css' media='all'/> 
        <link rel='stylesheet' href='./files/css/footer.css' type='text/css' media='all'/> 
        <link href="<?php echo $relPath . 'styles/bootstrap.min.css' ?>" rel="stylesheet"> 
        <?php
             cssUtils::loadcss($locale, "./files/css/interface");       
        ?>    
        <script type="application/javascript"> <!-- Google Analytics Tracking -->

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

      
<!--
        <header id="title">
            <h1><img src="files/turtles.png" alt="צב במשקפיים">
            <?php
                 echo _('Turtle Academy');
            //        אקדמיית הצב                    
             ?> 
                    <a href=he.php><img src='Images/flags/Israel.png'  title='עברית' class='flagIcon' /></a>
                    <a href=index.php> <img src='Images/flags/UnitedStates.png'  title='English' class='flagIcon' /></a> 
                    <a href=zh.php> <img src='Images/flags/China.png'  title='中文' class='flagIcon' /></a>  
                    <a href=es.php> <img src='Images/flags/Argentina.png'  title='Español' class='flagIcon' /></a>  
            </h1>
        </header>
-->
        <div id="main">
            <!-- Should be different for log in user and for a guest -->
            <div class="topbar">
                <div class="fill">
                    <div class="container span16">
                        <img class="brand"  src="files/turtles.png" alt="צב במשקפיים">
                        <a class="brand" style="color:gray;" href="index.html">TurtleAcademy</a>
                        <ul class="nav">
                            <li><a href="index.php" style="color:gray;" >Home</a></li>
                            <li class="active"><a href="index.html">Sample</a></li>
                        </ul>

                        <form class="pull-left" action=""> 
                            <select name="countries" id="selectedLanguage" style="width:200px;">
                                <option value='es.php' data-image="Images/msdropdown/icons/blank.gif" data-imagecss="flag ar" data-title="Argentina">Español</option>
                                <option value='es.php' data-image="images/msdropdown/icons/blank.gif" data-imagecss="flag es" data-title="Spain">Español</option>
                                <option value='il.php' data-image="Images/msdropdown/icons/blank.gif" data-imagecss="flag il" data-title="Israel">עברית</option>
                                <option value='index2.php' data-image="images/msdropdown/icons/blank.gif" data-imagecss="flag us" data-title="United States">English</option>
                                <option value='zh.php' data-image="images/msdropdown/icons/blank.gif" data-imagecss="flag cn" data-title="China">中文</option>
                            </select>
                        </form>       
                        <?php
                            if (isset($_SESSION['username']))
                            {
                        ?>                       
                                <p class="pull-right">Hello <a href="#">              
                                        <?php
                                            echo $_SESSION['username'];
                                        ?>
                                    </a>
                                    <a href="logout.php">Log out</a>
                                </p>

                        <?php
                            }
                            else
                            {
                        ?>                                   
                                <form action="log.php" class="pull-right" method='post'> 
                                    <input class="input-small" style="color:gray;" name="username" type="text" placeholder="Username">
                                    <input class="input-small" style="color:gray;" name="password" type="password" placeholder="Password"> 
                                    <button class="btn" type="submit">Sign in</button>
                                </form>
                         <?php
                            }
                         ?>
                    </div>
                </div>
            </div>
            <div id="header" class="menu" >
                <div id="progress">
                </div>
            </div>
            <div id="logoer"> 
                <div id="display"> 
                    <!-- <canvas id="sandbox" width="660" height="350" class="ui-corner-all ui-widget-content" style="position: absolute; z-index: 0;">-->
                    <canvas id="sandbox" width="660" height="350" class="ui-corner-all ui-widget-content">   
                            <span style="color: red; background-color: yellow; font-weight: bold;">
                            <?php
                                echo _("Your browser does not support canvas - an updated browser is recommended");
                                //    הדפדפן שלך אינו תומך בקנבס - מומלץ להשתמש בדפדפן עדכני יות                                
                            ?>                                      
                            </span>
                    </canvas>
                    <!--<canvas id="turtle" width="660" height="350" style="position: absolute; z-index: 1;"> -->
                    <canvas id="turtle" width="660" height="350">   
                        <!-- drawing box -->
                    </canvas>
                </div>

                <div id="console" class="ui-corner-all ui-widget-content"><!-- command box --></div>
            </div>

            <div id="accordion">
            </div>
            <div id="lessonnav">
                <?php
                     //should be change to all rtl lnaguages
                    $lu = new languageUtil("turtleTestDb" , "rtlLanguages");
                    $isRtlLocale = $lu->findIfLocaleExist($locale);
                   // if($locale == 'he_IL')
                    if ($isRtlLocale)
                    {
                ?>  
                    
                    <button id="nextlesson"> 
                    <?php
                        //should be change to all rtl lnaguages
                       // echo ($locale == 'he_IL') ?  "&larr;" :  "&rarr;";     
                        echo ($isRtlLocale) ?  "&larr;" :  "&rarr;"; 
                        echo _("Next");                   
                    ?> 
                    </button>
                    <button id="prevlesson">
                    <?php
                    //echo ($locale == 'he_IL') ?  "&rarr;" :  "&larr;";  
                    echo ($isRtlLocale) ?  "&rarr;" :  "&larr;";  
                    echo _("Prev");                    
                    ?>            
                    </button>
                <?php
                    }else{
                ?>     
                    <button id="prevlesson">
                    <?php
                       // echo ($locale == 'he_IL') ?  "&rarr;" :  "&larr;";  
                        echo ($isRtlLocale) ?  "&rarr;" :  "&larr;";  
                        echo _("Prev");                    
                    ?>            
                    </button>
                    <button id="nextlesson"> 
                    <?php
                        //should be change to all rtl lnaguages
                        echo ($isRtlLocale) ?  "&larr;" :  "&rarr;";   
                        //echo ($locale == 'he_IL') ?  "&larr;" :  "&rarr;"; 
                        echo _("Next");                   
                    ?> 
                    </button>

                <?php
                    } //ending else
                ?>
                  
            </div>
        </div>
        
        <!--
        <footer id="footer">
            &copy; TurtleAcademy, <a id="doc" title="תעוד הפרוייקט" href="doc.html">
            <?php
                 echo _("project doc");
            //        תעוד הפרוייקט                     
             ?> 
                                 
            </a>
            <div id="langicons">
                <a href="<?php echo $currentPage ?>?locale=he_IL"><img src="Images/flags/il.png"  title="עברית" /></a>
                <a href="<?php echo $currentPage ?>"> <img src="Images/flags/us.png"  title="English" /></a>              
            </div>    
        </footer>
        -->
        <?php echo $footer ?>
        
        <script>
        // Select language in main page
      $(document).ready(function() {
               
                    $('#selectedLanguage').change(function() { 
                        window.location = this.value;
                       // alert('Handler for .change() called.' +  this.value);
                    });
                   
                    //no use
	try {
		var pages = $("#pages").msDropdown({on:{change:function(data, ui) {
                        var val = data.value;
                        if(val!="")
                                window.location = val;
                }}}).data("dd");
		var pagename = document.location.pathname.toString();
		pagename = pagename.split("/");
		pages.setIndexByValue(pagename[pagename.length-1]);
		$("#ver").html(msBeautify.version.msDropdown);
	} catch(e) {
		//console.log(e);	
	}
	
	$("#ver").html(msBeautify.version.msDropdown);
		
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
    </body></html>