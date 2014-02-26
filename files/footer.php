    
<?php
    //TODO - remove unneeded variable comments
        //$currentFile    = $_SERVER["PHP_SELF"];
        //$parts          = Explode('/', $currentFile);
        //$currentPage    = $parts[count($parts) - 1];
        //$newLesson      = _('Create new Lesson');
        if (!isset ($root_dir)) $root_dir = "/";
        //if (!isset ($locale)) $locale = "en_US";
        $footer =
        "<footer id='footer'>
            <div id='footer_elem' lang='" . $lang ."'>
                <ul>
                    <li>
                        <a id='doc' title='"._('Project documentation')."' href='".$root_dir."project/doc/".$lang."'>
                        <b>". _('Documentation') ."</b>
                        </a>
                    </li>  
                    <li>
                         <a href='".$root_dir."faq.php'><b>"._('FAQ')."</b></a>  
                    </li>
                    <li>                       
                        <a href='mailto:support@turtleacademy.com'><b>"._('Contact Us')."</b></a>
                    </li>
                    <li>
                        <a href='".$root_dir."donate.php'><b>"._('Donate')."</b></a>
                    </li>
                </ul>
             </div> 
             <div id='turtleAt'>
                <p> &copy; 2014 " . _('TurtleAcademy') . " </p>
            </div>
        </footer>";       
              
?>