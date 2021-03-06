
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">  
    <?php
    if (session_id() == '')
        session_start();
    //If the user is not logged in yet redirect
    require_once("../../environment.php");

    $display_page = true;
    require_once("../../localization.php");
    require_once("../footer.php");
    require_once("../cssUtils.php");
    require_once("../utils/languageUtil.php");
    require_once('../utils/topbarUtil.php');
    require_once('../utils/badgesUtil.php');
    require_once('../utils/userUtil.php');
    require_once('../utils/pagination.php');


    ?>

<script> 
    var program_images = "" ;
    function do_logo(id ,cmd) {
    $('#'+id).css('width', '60px').css('height', '40px').append('<canvas id="'+id+'c" width="60" height="40" style="position: absolute; z-index: 0;"></canvas>' +
        '<canvas id="'+id+'t" width="60" height="40" style="position: absolute; z-index: 1;"></canvas>');
    var canvas_element2 = document.getElementById(id+"c");
    var turtle_element2 = document.getElementById(id+"t");
    var turtle2 = new CanvasTurtle(
    canvas_element2.getContext('2d'),
    turtle_element2.getContext('2d'),
    canvas_element2.width, canvas_element2.height);
    var rate = 0.1;
    g_logo2 = new LogoInterpreter(turtle2, null );
    g_logo2.setRatio(rate);
    cmd = cmd + " ht";
    g_logo2.run(cmd);
} 
</script>

<html dir="<?php echo $dir ?>" lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8">
        <title> <?php $display_username ?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <?php
        require_once("../utils/includeCssAndJsFiles.php");
        includeCssAndJsFiles::include_all_page_files("users");
        echo "<link rel='stylesheet' href='".$root_dir."files/css/pagination.css' type='text/css' media='all'/>"; 
        echo "<script type='application/javascript' src='".$root_dir."files/floodfill.js' ></script>\n" ; 
        ?>
    </head>
    
    <body>
        <?php
        //Get the page current url
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
            <div class='cleaner_h40'></div>
            <div class='row'>
            <div class='span16'id="usrLessonDiv" lang="<?php echo $lang ?>"> 
                <h2><?php
                          echo _("programs created by users");
                    ?>
                </h2>
                <span class='arrow'></span> 
                    <form class='form-stacked' id='diaplay-programs' method="post" action="">
                        <table class='zebra-striped ads' id="my_lessons" lang="<?php echo $lang ?>">
                            <thead>
                                <tr>
                                    <th class='span1'><?php echo _("num"); ?></th>
                                    <th class='span2'><?php echo _("pic"); ?></th>
                                    <th class='span4'>
                                        <?php echo _("creator"); 
                                            echo  "<span class='arrow' style='width:20px;float:left'><a href=\"$site_path/programs/$lang/asc/username/1\"><i class='icon-fixed-width icon-chevron-up'></i></a></span>"; 
                                            echo  "<span style='width:20px;float:left'><a href=\"$site_path/programs/$lang/desc/username/1\"><i class='icon-fixed-width icon-chevron-down'></i></a></span>"; 
                                         ?>    
                                    </th>
                                    <th class='span5'>
                                        <?php echo _("program name"); 
                                            echo  "<span class='arrow' style='width:20px;float:left'><a href=\"$site_path/programs/$lang/asc/programName/1\"><i class='icon-fixed-width icon-chevron-up'></i></a></span>"; 
                                            echo  "<span style='width:20px;float:left'><a href=\"$site_path/programs/$lang/desc/programName/1\"><i class='icon-fixed-width icon-chevron-down'></i></a></span>"; 
                                         ?>    
                                    </th>
                                    <th class='span4'><?php echo _("Date Created"); ?></th>
                                    <th class='span5'>
                                        <?php echo _("Last updated"); 
                                            echo  "<span class='arrow' style='width:20px;float:left'><a href=\"$site_path/programs/$lang/asc/lastUpdated/1\"><i class='icon-fixed-width icon-chevron-up'></i></a></span>"; 
                                            echo  "<span style='width:20px;float:left'><a href=\"$site_path/programs/$lang/desc/lastUpdated/1\"><i class='icon-fixed-width icon-chevron-down'></i></a></span>"; 
                                         ?>    
                                    </th>
                                    <th class='span4'><?php echo _("Actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset ($_SESSION['institute_email']))
                                $allPrograms = userUtil::get_institiute_user_programs($_SESSION['institute_email']);
                            else
                                echo " U r not logged in as a maneger";
                            // Here we will determine how to sort the user programs
                            $sortColumn = 'precedence';
                            $sortDirection = 1;
                            if (isset($_GET['column']))
                            {
                                $sortColumn = $_GET['column'];
                            }
                            if (isset ($_GET['order']))
                            {
                                if ($_GET['order'] == "asc")
                                    $sortDirection = 1;
                                else {
                                    $sortDirection = -1;
                                }
                            }                                
                            $allPrograms->sort(array($sortColumn => $sortDirection));
                            // End of sorting the user programs
                            
                            $limit = 15;
                            $adjacents = 3;
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
                            $num_of_programs = $allPrograms->count();
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
                            
                            $pagination = pagination($limit,$adjacents,$page,$start,$num_of_programs , $targetpage);
                            /*
                            //Start pagination
                            $limit = 15;
                            $adjacents = 3;
                            if (isset($_GET['page']))
                            {
                                $page = $_GET['page'];
                                $start = ($page - 1) * $limit +1; 
                            }
                            else
                            {
                                $start = 1;
                                $page = 1; //if no page var is given, default to 1.
                            }

                            //if no page var is given, set start to 0
                            $num_of_programs = $allPrograms->count();
                            $total_pages = ceil($num_of_programs/$limit);
                            $lastpage   = $total_pages;
                            $prev = $page - 1;							//previous page is page - 1
                            $next = $page + 1;							//next page is page + 1
                                        //lastpage is = total pages / items per page, rounded up.
                            $lpm1 = $lastpage - 1;
                            //echo $num_of_programs;
                            $pagination = "";
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
                            if($lastpage > 1)
                            {	
                                    $pagination .= "<div class=\"pagination\">";
                                    //previous button
                                    if ($page > 1) 
                                            $pagination.= "<a href=\"$targetpage/$prev\">� previous</a>";
                                    else
                                            $pagination.= "<span class=\"disabled\">� previous</span>";	

                                    //pages	
                                    if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
                                    {	
                                            for ($counter = 1; $counter <= $lastpage; $counter++)
                                            {
                                                    if ($counter == $page)
                                                            $pagination.= "<span class=\"current\">$counter</span>";
                                                    else
                                                            $pagination.= "<a href=\"$targetpage/$counter\">$counter</a>";					
                                            }
                                    }
                                    elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
                                    {
                                            //close to beginning; only hide later pages
                                            if($page < 1 + ($adjacents * 2))		
                                            {
                                                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                                    {
                                                            if ($counter == $page)
                                                                    $pagination.= "<span class=\"current\">$counter</span>";
                                                            else
                                                                    $pagination.= "<a href=\"$targetpage/$counter\">$counter</a>";					
                                                    }
                                                    $pagination.= "...";
                                                    $pagination.= "<a href=\"$targetpage/$lpm1\">$lpm1</a>";
                                                    $pagination.= "<a href=\"$targetpage/$lastpage\">$lastpage</a>";		
                                            }
                                            //in middle; hide some front and some back
                                            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                            {
                                                    $pagination.= "<a href=\"$targetpage/1\">1</a>";
                                                    $pagination.= "<a href=\"$targetpage/2\">2</a>";
                                                    $pagination.= "...";
                                                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                                                    {
                                                            if ($counter == $page)
                                                                    $pagination.= "<span class=\"current\">$counter</span>";
                                                            else
                                                                    $pagination.= "<a href=\"$targetpage/$counter\">$counter</a>";					
                                                    }
                                                    $pagination.= "...";
                                                    $pagination.= "<a href=\"$targetpage/$lpm1\">$lpm1</a>";
                                                    $pagination.= "<a href=\"$targetpage/$lastpage\">$lastpage</a>";		
                                            }
                                            //close to end; only hide early pages
                                            else
                                            { 
                                                    $pagination.= "<a href=\"$targetpage\1\">1</a>";
                                                    $pagination.= "<a href=\"$targetpage\2\">2</a>";
                                                    $pagination.= "...";
                                                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                                                    {
                                                            if ($counter == $page)
                                                                    $pagination.= "<span class=\"current\">$counter</span>";
                                                            else
                                                                    $pagination.= "<a href=\"$targetpage/$counter\">$counter</a>";					
                                                    }
                                            }
                                    }

                                    //next button
                                    if ($page < $counter - 1) 
                                            $pagination.= "<a href=\"$targetpage/$next\">next �</a>";
                                    else
                                            $pagination.= "<span class=\"disabled\">next �</span>";
                                    $pagination.= "</div>\n";		
                            }
                            // END pagination
                             
                             */

                            $i = 0;
                            $display_program_in_page = false;

                            $programImage = "";
                            foreach ($allPrograms as $program) {

                                $i++;
                                if ($i == $start)
                                    $display_program_in_page = true;
                                if ($i == $start + $limit )
                                    $display_program_in_page = false;
                                if ($display_program_in_page)
                                {
                            ?>
                                <tr>
                                    <td class="span1"><?php echo $i;?></td>
                                    <?php
                                    if (strlen($program['img']) > 20)
                                    {
                                    ?>
                                    <td id="logo<?php echo $i;?>" style="background : url(<?php echo $program['img'] ?>);"> 
                                    <?php
                                    } 
                                    else{
                                ?>
                                        <td id="logo<?php echo $i;?>" > 
                                <?php
                                    }
                                    ?>

                                    </td>
                                    <td>
                                        <a class='' href="<?php
                                            echo $root_dir . $user_profile;
                                            $programCreator    = $program['username'];
                                            $hasMail = false;
                                            if (strpos($programCreator, '@') !== false) {
                                                $hasMail            =   true;
                                                $name_before_adding_mail_address  = explode('@', $programCreator);
                                                $programCreator     = $name_before_adding_mail_address[0];
                                            }
                                            echo $programCreator;
                                            if ($hasMail)
                                                echo "_email";
                                            ?>"> 
                                            <?php
                                                echo $programCreator;
                                                $code = $program['code'];

                                                $newstr = str_replace("\n", " ", $code);

                                            ?>  
                                        </a>
                                    </td>
                                    <td><?php echo $program['programName'] ?></td>
                                    <td><?php echo $program['dateCreated'] ?></td>
                                    <td><?php echo $program['lastUpdated'] ?></td>
                                    <td>
                                        <a class='btn small info' href="<?php
                                            echo $root_dir . "users/programs/";
                                            echo $program['_id'];
                                            ?>">
                                            <?php
                                                echo _("View");
                                                if (strlen($program['img']) < 20 )
                                                    $programImage = $programImage . "do_logo('logo" .$i ."','" . $newstr . "');** " ;

                                            ?>
                                        </a>
                                    </td>
                                </tr>

                            <?php

                                }// End if it's a program we need to display in this page
                            } // End of foreach loop
                            ?> 
                            </tbody>  
                        </table>
                    </form>  
                        <?php echo $pagination; ?>
                    </div><!-- end of center content -->
                </div>
                 <div id="logo1"></div>
            <?php
            if (isset($footer))
                echo $footer;
            ?>
            </div>
        <script>
                    $(document).ready(function() {
                     selectLanguage("<?php echo $_SESSION['locale']; ?>" ,  "<?php echo $root_dir; ?>programs/", "users.php" ,"en" );         
                    
                    function saveProgramImage(programtitle  , programid , username , canvasid)
                    {
                        var canvas_element = document.getElementById(canvasid);
                        var dataURL = canvas_element.toDataURL(); 
                        var ispublic = true;
                        var saveProgramUrl  = sitePath + "files/saveUserProgram.php";
                       

                        if (programtitle!=null){
                                $.ajax({
                                type : 'POST',
                                url : saveProgramUrl,
                                dataType : 'json',
                                data: {
                                    programtitle    :   programtitle ,
                                    update          :   true ,
                                    programid       :   programid,
                                    ispublic        :   ispublic,
                                    imgBase64       :   dataURL,
                                    username        : username
                                },

                                success : function(data){
                                                   
                                },       
                                error : function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert('fail');  
                                }
                            });
                        }
                    }
                      <?php
                        $images = explode("**", $programImage);
                        foreach ($images as $image)
                        {
                            ?>
                                    
                            try
                            {
                                <?php 
                                    //For now ignoring the problem when User programs contains '' " signs
                                    if ( substr_count($image, "'")  == 4 )
                                    {
                                    echo $image; echo "\n"; 
                                    
                                    }
                                  ?>
                            }
                            catch(err)
                            {
                            //Handle errors here
                            }
                                
                        <?php
                        }
                        $i = 0;
                        foreach ($allPrograms as $program) {                              
                                $i++;
                                if ($i == $start)
                                    $display_program_in_page = true;
                                if ($i == $start + $limit )
                                    $display_program_in_page = false;
                                if ($display_program_in_page)
                                {
                                    //(programname  , programCode , programid , username ,canvasid)
                                    $progTitle = $program['programName'];
                                    $progCodeBeforeEdit  = $program['code'];
                                    $progCode = str_replace("\n", " ", $progCodeBeforeEdit);
                                    $progId    = $program['_id'];
                                    $username  = $program['username'];
                                    $progImg   = $program['img'];
                                    $canvasid  = "logo" .$i ."c";
                                   
                                    if (strlen($progImg) < 20 )
                                    { 
                                       echo "try{ ";
                                        echo "saveProgramImage(\"$progTitle\"  , '$progId' , '$username' , '$canvasid'); ";
                                        echo " } catch(err) {} ; \n";
                                    }
                                     
                                }// End if it's a program we need to $progCode in this page
                            } // End of foreach loop
                     ?>
                             
                 });
        </script>


    <?php
} // End of rull Checking for session.username exists
?> 
    </body>
</html>
