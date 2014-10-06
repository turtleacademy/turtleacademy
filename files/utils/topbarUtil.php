<?php

class topbarUtil {

    public static function print_topbar($topbarPage) {
        global $root_dir;
        $topbarSpanSize = 16;
        //Topbar menu display items
        $topbarDisplay = array(
            "turtleacademy" => false,
            "exercise" => false,
            "helpus" => false,
            "publicPrograms" => true,
            "playground" => false,
            "forum" => false,
            "news" => false,
            "about" => false,
            "sample" => false
        );

        //$signUpDisplay = true;
        $languagesDisplay = true;


        $language = array(
            "en" => "en",
            "de" => "de",
            "es" => "es",
            "fi" => "fi",
            "nl" => "nl",
            "it" => "it",
            "pt" => "pt",
            "pl" => "pl",
            "ru" => "ru",
            "he" => "he",
            "zh" => "zh",
            "hr" => "hr"
        );
        //Will be sorted in the correct order 
        $displaylanguage = array(
            "en" => true,
            "es" => true,
            "de" => true,
            "nl" => true,
            "it" => true,
            "pt" => true,
            "pl" => false,
            "fi" => false,
            "ru" => true,
            "he" => true,
            "zh" => true,
            "hr" => false
        );
        $countryFlagName = array(
            "en" => "us",
            "de" => "de",
            "es" => "es",
            "fi" => "fi",
            "hr" => "hr",
            "pt" => "br",
            "it" => "it",
            "pl" => "pl",
            "ru" => "ru",
            "nl" => "nl",
            "he" => "il",
            "zh" => "cn"
        );
        $countryNativeName = array(
            "en" => "English",
            "ru" => "Русский",
            "es" => "Español",
            "fi" => "Finnish",
            "zh" => "中文",
            "it" => "italian",
            "he" => "עברית",
            "de" => "Deutsch",
            "nl" => "Dutch",
            "pt" => "Português",
            "pl" => "polish",
            "hr" => "Croatian"
        );
        switch ($topbarPage) {
            case "learn":
                $topbarDisplay['playground'] = true;
                $topbarDisplay['news'] = true;
                $topbarDisplay['about'] = true;
                $displaylanguage['de'] = true;
                $displaylanguage['pl'] = true;
                $displaylanguage['it'] = true;
                $displaylanguage['fi'] = true;
                $displaylanguage['nl'] = true;
                $displaylanguage['hr'] = true;
                break;
            case "faq":
                //$signUpDisplay = false;
                $languagesDisplay = false;
                $topbarSpanSize = 20;
                $topbarDisplay['playground'] = true;
                $topbarDisplay['news'] = true;
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                break;
            case "index":
                //$signUpDisplay = false;
                $topbarDisplay['playground'] = true;
                $topbarDisplay['news'] = true;
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                break;
            case "playground":
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                break;
            case "program":
                $topbarDisplay['playground'] = true;
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                $topbarSpanSize = 21;
                //$languagesDisplay = false;
                break;
            case "programUpdate":
                $topbarDisplay['playground'] = true;
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                $topbarDisplay['publicPrograms'] = false;
                $topbarSpanSize = 21;
                $languagesDisplay = true;
                break;
            case "institute":
                //$signUpDisplay = true;
                $languagesDisplay = false;
                break;
            case "users":
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                break;
            case "registration":
                //$signUpDisplay = false;
                $topbarDisplay['about'] = true;
                $topbarDisplay['exercise'] = true;
                $language['en'] = "en_US";
                $language['ru'] = "ru_RU";
                $language['he'] = "he_IL";
                $language['es'] = "es_AR";
                $language['it'] = "it_IT";
                $language['zh'] = "zh_CN";
                $language['de'] = "de_DE";
                $language['pt'] = "pt_BR";
                $language['pl'] = "pl_PL";
                $topbarSpanSize = 13;
                break;
            case "news":
                $topbarDisplay['turtleacademy'] = true;
                $topbarDisplay['exercise'] = true;
                break;
            case "documentation":
                $topbarDisplay['turtleacademy'] = true;
                $topbarDisplay['exercise'] = true;
                break;
        }
        topbarUtil::print_topbar_selected($root_dir, $topbarDisplay, $languagesDisplay/*, $signUpDisplay*/, $language, $topbarSpanSize, $displaylanguage, $countryNativeName, $countryFlagName);
    }

    private static function print_topbar_selected($rootDir, $topbarDisplay, $langDropDown/*, $signUpDisplay*/, $language, $topbarSpanSize, $displaylanguage, $countryNativeName, $countryFlagName, $showTurtleIcon = true) {
        global $cssleft, $cssright, $lang, $site_path , $documentation_page , $home_page , $lesson_page;
        ?>    
        <div class="topbar" id="topbarMainDiv" > 
            <div class="fill" id="topbarfill">
                <div class="container span<?php echo $topbarSpanSize; ?>" id="topbarContainer">
                        <?php
                        if ($showTurtleIcon) {
                            ?>
                        <a href="<?php echo $site_path . "/index/" . $lang; ?>"><img class="brand" id="turtleimg" lang="<?php echo $lang ?>" src="<?php echo $rootDir; ?>files/turtles.png" alt="Home Page"/></a> 
                            <?php
                        }//Close show icon
                        ?>
                    <ul class="nav" id="turtleHeaderUl" lang="<?php echo $lang ?>"> 
                        <?php
                        if ($topbarDisplay['turtleacademy'] == "true") {
                            echo "<li><a href='" . $site_path .$home_page . $lang . "'>";
                            echo _("TurtleAcademy");
                            echo "</a></li>";
                        }
                        if (isset($topbarDisplay['exercise']) && $topbarDisplay['exercise'] == "true") {
                            echo "<li><a href='" . $site_path . $lesson_page .$lang ."'>";
                            echo _("Lessons");
                            echo "</a></li>";
                        }
                        if (isset($topbarDisplay['publicPrograms'])) {
                            echo "<li><a href='" . $site_path . "/programs/$lang'>";
                            echo _("User programs");
                            echo "</a></li>";
                        }

                        if ($topbarDisplay['helpus'] == "true") {
                            echo "<li><a href='" . $site_path . "needed.php'>";
                            echo _("Help Us");
                            echo "</a></li>";
                        }
                        if ($topbarDisplay['playground'] == "true") {
                            echo "<li><a href='" . $site_path . "playground/" . $lang . "'>";
                            echo _("Playground");
                            echo "</a></li>";
                        }
                        if ($topbarDisplay['news'] == "true") {
                            echo "<li><a href='" . $site_path . "news/$lang'>";
                            echo _("News");
                            echo "</a></li>";
                        }
                        if ($topbarDisplay['forum'] == "true") {
                            echo "<li><a href='" . $site_path . "forum.php'>";
                            echo _("Forums");
                            echo "</a></li>";
                        }
                        if ($topbarDisplay['about'] == "true") {
                            echo "<li><a href='" . $site_path . $documentation_page . $lang ."'>";
                            echo _("About");
                            echo "</a></li>";
                        }
                        ?>
                    </ul> 
                            <?php
                            if ($langDropDown == "true") {
                                ?>
                        <form class="<?php
                                echo "pull-$cssleft form-inline";
                                ?>" id="turtleHeaderLanguage" lang="<?php echo $lang ?>">
                            <select name="selectedLanguage" id="selectedLanguage"> 
                                <?php
                                $blankImagePath = $rootDir . "Images/msdropdown/icons/blank.gif";
                                foreach ($language as $langKey => $langVal) {
                                    if ($displaylanguage[$langKey])
                                        echo "<option value='$langVal' data-image='$blankImagePath' data-imagecss='flag $countryFlagName[$langKey]' data-title='$countryNativeName[$langKey]' class='blank_pic'> $countryNativeName[$langKey] </option> ";
                                }
                                ?> 
                            </select>
                        </form>       
            <?php
        }//End if language display
        //If the user is exist
        if (isset($_SESSION['username'])) {
            ?>                       
                        <nav class="<?php echo "pull-$cssright"; ?>"  id="turtleHeaderLoggedUser">
                            <ul class="nav nav-pills <?php echo "pull-$cssright"; ?>" id="loggedUserUl"> 

                                <li class="cc-button-group btn-group"> 
                                    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" >
                                        <?php
                                        $displayUserName = $_SESSION['username'];
                                        if (isset($_SESSION['isOpenID'])) {
                                            $emailDetails = explode('@', $_SESSION['username']);
                                            $displayUserName = $emailDetails[0];
                                        }
                                        echo $displayUserName;
                                        if (isset($_SESSION['nmsg'])) {
                                            if ($_SESSION['nmsg']) {
                                                ?>
                                                <i class="icon-envelope innerIcon" lang="en"></i>
                    <?php
                }
            }
            ?>                          
                                    </a>
                                    <ul class="dropdown-menu" id="ddusermenu"role="menu" aria-labelledby="dLabel">
                                        <li><a tabindex="-1" href="<?php echo $rootDir . "users.php"; ?>"   class="innerLink" id="help-nav"><?php echo _("My account"); ?></a></li>
                                        <li><a tabindex="-1" href="<?php echo $rootDir . $documentation_page . $lang; ?>" class="innerLink" id="hel-nav"><?php echo _("Help"); ?></a></li>
                                        <li><a href="<?php echo $rootDir; ?>logout.php" class="innerLink"><?php echo _("Log out"); ?></a></li>
                                    </ul>
                                </li>
                            </ul> 
                        </nav>                                                                     

                        <?php
                    } //End if user exist 
                    else { //Only if need to display signup button
/*                        if ($signUpDisplay) {*/
                            ?>       
                            <ul class="nav <?php echo "pull-$cssright "; ?>" id="turtleHeaderLogIn" lang="<?php echo $lang ?>"> 
                                <li id="registrateBtn"><a id="menuRegBtn" href="<?php echo $rootDir; ?>registration.php" ><?php echo _("Login");echo "/";echo _("Sign Up")?></a></li>
                            </ul>                         
                <?php
            //} //End of if condition ? show signUP
        }
        ?> 
                </div> <!-- topbarContainer -->
            </div>    <!-- topbarfill -->        
        </div> <!-- End of Top menu --> 
        <?php
    }

// Close function printTopBar
}

// Close class topbarUtil           