<?php
class includeCssAndJsFiles {
    //Static function to load pages according to page type
    public static function include_all_page_files($pageName) 
    {
        $additional_files = "";
        global $_SESSION , $root_dir , $locale_domain , $site_path , $localize , $minified;
        $has_navigator          = false;
        $has_console            = false;
        $has_lessons            = false;
        $has_alerts             = false;
        $has_command_line       = false;
        $add_turtle_commands    = false;
        $run_logo_command       = true;
        $run_get_text           = true;
        switch ($pageName) {
            case "index":
                $additional_files    = "<link rel='stylesheet' href='".$root_dir."files/css/index" . $minified .".css' type='text/css' media='all'/>";
            break;
            case "donate":
                $additional_files    = "<link rel='stylesheet' href='".$root_dir."files/css/donation.css' type='text/css' media='all'/>";
            break;
            case "brainpop":
                $has_navigator        = true;
                $has_console          = true;
                $has_lessons          = true;
                $has_command_line     = true;
                $additional_files     = $additional_files . "<link href='".$root_dir."files/bootstrap/css/jquery-ui.css' rel='stylesheet' >" ;
                $localize = array(
                    'lesson' => _('lesson'),
                    'hint' => _('hint'),
                    'Solution'  => _('Solution'),
                    'Welcome to the Turtle world' => _('Welcome to the Turtle world'),
                    'Hi' => _('Hi'),
                    'Error Loading History' => _('Error Loading History'),

                    //Logo.js
                    'Command and number should be seperate by space e.g forward 50 and not forward50' => _('Command and number should be seperate by space e.g forward 50 and not forward50'),
                    'Don\'t know how to {name}' => _('Don\'t know how to {name}'),
                    'Don\'t know how to {name}' => _('Don\'t know how to {name}'),
                    'end' => _('end'),
                    "Please don't try to override primitive functions" => _("Please don't try to override primitive functions"),
                    //Carousel
                    '>>' => _('>>'),'<<' => _('<<'),
        
                    'Error' => _('Error')
                );
                $add_turtle_commands  = true;            
                break;
            case "learn":
                $additional_files = $additional_files . "<script type='application/javascript' src='".$root_dir."files/jqconsole.js' ></script>\n";
                $localize = array(
                    'Add lesson step' => ('Add lesson step'),
                    'lesson' => _('lesson'),
                    'hint' => _('hint'),
                    'Solution'  => _('Solution'),
                    'Welcome to the Turtle world' => _('Welcome to the Turtle world'),
                    'Hi' => _('Hi'),
                    'Error Loading History' => _('Error Loading History'),

                    //Logo.js
                    'Command and number should be seperate by space e.g forward 50 and not forward50' => _('Command and number should be seperate by space e.g forward 50 and not forward50'),
                    'Don\'t know how to {name}' => _('Don\'t know how to {name}'),
                    'Don\'t know how to {name}' => _('Don\'t know how to {name}'),
                    'end' => _('end'),
                    "Please don't try to override primitive functions" => _("Please don't try to override primitive functions"),
                    //Carousel
                    '>>' => _('>>'),'<<' => _('<<'),
        
                    'Error' => _('Error')
                );
                $additional_files = $additional_files . "<script type='application/javascript' src='".$root_dir."files/interface.js"/*?locale=".$locale_domain."*/."'></script>\n";
                $additional_files = $additional_files . "<link rel='stylesheet' href='//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css'/>\n" ; 
                $additional_files = $additional_files . "<link rel='stylesheet' href='//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css'/>\n" ; 
                $additional_files = $additional_files . "<link rel='stylesheet' href='".$root_dir."files/css/doc.css' type='text/css' media='all'/>\n" ; 
                $additional_files = $additional_files . "<link rel='stylesheet' href='".$root_dir."files/css/interface.css' type='text/css' media='all'/>\n" ;
                $additional_files = $additional_files . "<link href='".$root_dir."files/bootstrap/css/jquery-ui.css' rel='stylesheet' >" ;

                $has_navigator       = true;
                $has_console         = true;
                $has_lessons         = true;
                $has_command_line     = true;
                $add_turtle_commands  = true;
                break; 
           case "news":
                $additional_files = $additional_files . "<link rel='stylesheet' href='".$root_dir."files/css/news.css' type='text/css' media='all'/>\n" ;
               break;
           case "registration":
               $additional_files = $additional_files . "<link rel='stylesheet' type='text/css' href='" . $root_dir . "files/css/registration.css' /> ";
               $additional_files = $additional_files . "<link rel='stylesheet' href='".$root_dir."files/css/zocial.css' type='text/css' media='all'/>\n" ;
               $additional_files = $additional_files . "<script type='application/javascript' src='".$root_dir."ajax/libs/jquery/validator/dist/jquery.validate.js'></script>\n";
               $run_logo_command = false;
               break;
           case "doc":
               $additional_files = $additional_files . "<link rel='stylesheet' href='".$root_dir."files/css/doc.css' type='text/css' media='all'/>\n" ;
               $additional_files = $additional_files .  "<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js'></script>"; //Because of the messeging dialog
               break;           
           case "users":
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/users.css'/> ";
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/badges.css'/> "; 
               $additional_files = $additional_files . "<link href='".$root_dir."files/bootstrap/css/jquery-ui.css' rel='stylesheet' >" ;
               $has_lessons = true; // Because of messeging dialog
               break;
            case "user-program-page":
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/users.css'/> ";
               $run_logo_command = false;
               $run_get_text     = false;
               break;
            case "faq":
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/index.min.css'/> "; 
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/faq.css'/> "; 
               break;
           case "faqadmin":
               $additional_files = $additional_files . "<link rel='stylesheet' type='text/css' href='" . $root_dir . "files/css/registration.css' /> ";
               $additional_files = $additional_files . "<link rel='stylesheet' type='text/css' href='" . $root_dir . "files/css/zocial.css' /> ";
               $additional_files = $additional_files . "<link rel='stylesheet' type='text/css' href='" . $root_dir . "files/css/faq.css' /> ";
               $additional_files = $additional_files . "<script type='application/javascript' src='".$root_dir."ajax/libs/jquery/validator/dist/jquery.validate.js'></script>\n";

               break;
           case "playground":
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/doc.css'/> "; 
               $additional_files = $additional_files ."<link rel='stylesheet' type='text/css' href='".$root_dir."files/css/playground.css'/> "; 
               $additional_files = $additional_files . "<link href='".$root_dir."files/bootstrap/css/jquery-ui.css' rel='stylesheet' >" ;
              $has_console         = true;
               $has_command_line     = true;
                               global $localize;
                $localize = array(
                    'lesson' => _('lesson'),
                    'hint' => _('hint'),
                    'Solution'  => _('Solution'),
                    'Welcome to the Turtle world' => _('Welcome to the Turtle world'),
                    'Hi' => _('Hi'),
                    'Error Loading History' => _('Error Loading History'),
                    'Error' => _('Error')
                );
                $add_turtle_commands  = true;
               break;
           case "user-program":
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/lib/codemirror.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/addon/runmode/runmode.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/addon/edit/closebrackets.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/addon/edit/matchbrackets.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/addon/display/placeholder.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/codemirror/addon/selection/active-line.js' ></script>\n";
               $additional_files = $additional_files ."<script type='application/javascript' src='" . $root_dir . "files/codemirror/mode/logo/logo.js' ></script>\n";
               $additional_files = $additional_files . "<link rel='stylesheet' href='//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css'/>\n" ; 

               $additional_files = $additional_files . "<link   href='".$root_dir."files/codemirror/mode/logo/logo.css' rel='stylesheet' >";             
               $additional_files = $additional_files . "<link   href='".$root_dir."files/codemirror/lib/codemirror_turtle.css' rel='stylesheet' >";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/jqconsole.js' ></script>\n";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "ajax/libs/jquery/editable/jquery.editable.js'></script>";
               $additional_files = $additional_files . "<link   href='".$root_dir."files/codemirror/mode/logo/logo.css' rel='stylesheet'></link>";
               $additional_files = $additional_files . "<script type='application/javascript' src='" . $root_dir . "files/interface_user_program.js?locale=" . $locale_domain."'></script>";
               $has_command_line      = true;
               $has_alerts           = true;
               $add_turtle_commands  = true;
               break;
        }
    includeCssAndJsFiles::includingFiles($additional_files , $has_navigator ,  $has_console ,$has_lessons , $has_alerts ,
            $has_command_line , $add_turtle_commands , $pageName , $run_logo_command , $run_get_text);
    }
    private static function includingFiles($additional_files , $hasNavigator ,  $hasConsole ,$hasLessons , $hasAlerts ,
            $has_command_line , $add_turtle_commands , $pageName , $run_logo_command , $run_get_text)
    {
        global $root_dir,$env , $locale_domain,$site_path , $localize , $minified;
        //Userd in Turtle.js will be added for all the objects
        $localize['black'] = _('black');$localize['blue'] = _('blue');$localize['lime'] = _('lime');$localize['cyan'] = _('cyan');
        $localize['red'] = _('red');$localize['magenta'] = _('magenta');$localize['yellow'] = _('yellow');$localize['white'] = _('white');
        $localize['brown'] = _('brown');$localize['tan'] = _('tan');$localize['green'] = _('green');$localize['aquamarine'] = _('aquamarine');
        $localize['salmon'] = _('salmon');$localize['purple'] = _('purple');$localize['orange'] = _('orange');$localize['gray'] = _('gray');
                
        if ($add_turtle_commands)
        {
                //command
                $localize['fd'] = _('fd');$localize['forward'] = _('forward');
                $localize['back'] = _('back');$localize['bk'] = _('bk');
                $localize['left'] = _('left');$localize['lt'] = _('lt');
                $localize['rt'] = _('rt');$localize['right'] = _('right');
                $localize['label'] = _('label');$localize['pos'] = _('pos');$localize['print'] = _('print');
                $localize['cs'] = _('cs');$localize['clearscreen'] = _('clearscreen');
                $localize['pu'] = _('pu');$localize['penup'] = _('penup');
                $localize['pd'] = _('pd');$localize['pendown'] = _('pendown');
                $localize['ht'] = _('ht');$localize['hideturtle'] = _('hideturtle');
                $localize['st'] = _('st');$localize['showturtle'] = _('showturtle');
                $localize['setwidth'] = _('setwidth');$localize['home'] = _('home');
                $localize['setx'] = _('setx');$localize['sety'] = _('sety');$localize['setxy'] = _('setxy');
                $localize['setheading'] = _('setheading');$localize['seth'] = _('seth');$localize['arc'] = _('arc');
                $localize['pos'] = _('pos');$localize['heading'] = _('heading');
                $localize['towards'] = _('towards');
                $localize['repeat'] = _('repeat');$localize['repcount'] = _('repcount');
                $localize['for'] = _('for');
                $localize['to'] = _('to');$localize['end'] = _('end');$localize['END'] = _('END');
                $localize['make'] = _('make');$localize['list'] = _('list');
                $localize['first'] = _('first');$localize['butfirst'] = _('butfirst');
                $localize['last'] = _('last');$localize['butlast'] = _('butlast');
                $localize['item'] = _('item');$localize['pick'] = _('pick');
                $localize['setcolor'] = _('setcolor');$localize['fill'] = _('fill');
                $localize['filled'] = _('filled');
                $localize['sum'] = _('sum');$localize['minus'] = _('minus');$localize['random'] = _('random');
                $localize['setlabelheight'] = _('setlabelheight');$localize['xcor'] = _('xcor');$localize['ycor'] = _('ycor');
                
                
        }
        /* Load JQuery files */
        echo "<script type='application/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>";
        //echo "<script type='application/javascript' src='".$root_dir."files/dd/js/jquery/jquery-1.8.2.min.js' ></script>";
        ?> 
       <script>
        if (!window.jQuery) 
            document.write('<script src="<?php echo $root_dir."ajax/libs/jquery/1.8.2/jquery-1.8.2.min.js";?>"><\/script>');
        </script>
        <?php
        
        if ($hasLessons)
        {
            echo "<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js'></script>";
                ?> 
               <script>
                    if (!window.jqconsole) 
                        document.write('<script src="<?php echo $root_dir."ajax/libs/jqueryui/1.10.0/js/jquery-ui-1.10.0.custom.js";?>"><\/script>');
                </script>
                <?php
         }
        if ($hasAlerts)
        {
            echo "<script type='application/javascript' src='".$root_dir."alerts/jquery.alerts.js' ></script>";
            echo "<link   href='".$root_dir."alerts/jquery.alerts.css' rel='stylesheet' >";
        }
        if ($hasNavigator)
        {
            echo "<script type='application/javascript' src='".$root_dir."files/jquery.tmpl.js' ></script>";
        }
        if ($pageName == "learn" || $pageName == "user-program" || $pageName == "brainpop" || $pageName == "playground" 
            || $pageName == "users")
        {
            echo "<script type='application/javascript' src='".$root_dir."files/jquery.Storage.js' ></script>";
            echo "<script type='application/javascript' src='".$root_dir."loadUsrDataToStorage.php?locale=".$locale_domain."' ></script>\n" ;
        }
        
        //echo "<link   href='".$rootDir."ajax/libs/jqueryui/1.10.0//css/ui-lightness/jquery-ui-1.10.0.custom.css' rel='stylesheet' >";
    /* End load Jquery files */
        //echo "<script type='application/javascript' src='".$root_dir."loadUsrDataToStorage.php?locale=".$locale_domain."' ></script>\n" ;
    /* Start DropDown files */
        
        echo "<script type='application/javascript' src='".$root_dir."files/dd/js/msdropdown/jquery.dd.min.js' ></script>";    
         
    /* End drop down files */


    /* Load boostraps files */
        
        //echo "<script type='application/javascript' src='".$rootDir."files/bootstrap/js/bootstrap.js' ></script>" ; 
        echo "<script type='application/javascript' src='".$root_dir."files/bootstrap/js/bootstrap.min.js' ></script>" ; 
        // else
        //    echo "<script src='http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.1/bootstrap.min.js'>"; 
        if ($hasNavigator)
        {    
            //echo "<script type='application/javascript' src='".$root_dir."files/bootstrap/js/bootstrap-carousel.js' ></script>" ; 
        }
        echo "<link href='".$root_dir."files/bootstrap/css/boostrap_all.min.css' rel='stylesheet' >" ;

    
    /* loading some other files */
        if (!isset($locale_domain))
            $locale_domain = "en_US"; 
        // Loading getText related files according to locale
        $file_path = "locale/" . $locale_domain . "/LC_MESSAGES/messages.po";
        //$po_file = "<link   rel='value' type='application/x-po' href='$site_path/locale/" . $locale_domain . "/LC_MESSAGES/messages.po'" . " />";
        $po_file = $_SERVER['DOCUMENT_ROOT'] . "/locale/" . $locale_domain . "/LC_MESSAGES/messages.po'";
        if (file_exists($file_path))
            try{
                //echo $po_file;
                //echo $_SERVER['DOCUMENT_ROOT'];
                //include_once $po_file;
            }catch(Exception $e)
            {
               //$po_file =  "<link   rel='value' type='application/x-po' href='$site_pate_with_www/locale/" . $locale_domain . "/LC_MESSAGES/messages.po'" . " />"; 
                $po_file = "$site_pate_with_www/locale/" . $locale_domain . "/LC_MESSAGES/messages.po'" . " />";
                //echo $po_file;
                //include_once $po_file;
            }
        
        //End Loading translation file   
        $is_user_login =   isset($_SESSION['username']);
        if ($is_user_login) {
             echo "<script type='application/javascript' src='".$root_dir."clearStorageData.php' ></script>\n" ;   
        } 
        if ($hasLessons)
        {
            echo "<script type='application/javascript' src='".$root_dir."readMongo.php?locale=".$locale_domain."' ></script>\n" ;  
        }
        if ($run_logo_command)
        {
            echo "<script type='application/javascript' src='".$root_dir."files/logo".$minified .".js'></script>\n" ; 
            echo "<script type='application/javascript' src='".$root_dir."files/turtle".$minified .".js'></script>\n" ; 
        }
        if ($has_command_line)
        {
            echo "<script type='application/javascript' src='".$root_dir."files/floodfill.js' ></script>\n" ; 
        }
        
        echo "<link rel='stylesheet' href='".$root_dir."files/css/topbarAndFooter.min.css' type='text/css' media='all'/>";
        //echo "<link rel='stylesheet' href='".$root_dir."files/css/topbar.css' type='text/css' media='all'/>"; 
        //echo "<link rel='stylesheet' href='".$root_dir."files/css/footer.css' type='text/css' media='all'/>";
  ?> 
    <!-- Google Analytics Tracking --> 
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
    <!-- End of Google Analytics Tracking -->  
    <?php
    global $localize;
    if (isset ($localize) && $run_get_text) {
        echo '<script type="application/javascript">';
        echo 'gt='.json_encode($localize).';';
        echo '</script>';
    }
    echo $additional_files;
    }   
}
?>