<?php
     
    function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        $httponly = true; // This stops javascript being able to access the session id. 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        //session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(); // regenerated the session, delete the old one.  
    }
    if (session_id() == '')
    {
        sec_session_start();
    }
    $LocaleVal = array(
        "en" => "en_US",
        "es" => "es_AR",
        "de" => "de_DE",
        "nl" => "nl_NL",
        "pt" => "pt_BR",
        "pl" => "pl_PL",
        "fi" => "fi_FI",
        "ru" => "ru_RU",
        "he" => "he_IL",
        "zh" => "zh_CN"   
    );
    
    //$locale = "en_us";
    if (isset ($_SESSION["locale"]))
        $locale =   $_SESSION["locale"];
    if (isset ($_GET['l']))
        $locale =   $_GET['l'];
    if (isset ($_GET['locale']))
        $locale =   $_GET['locale'];
    if ( !isset ($locale))
    {
        if (isSet($_GET["ltranslate"])) 
        {
            $localeEnv = $_GET["ltranslate"].".utf8";
            if (isset($LocaleVal[$localeEnv])) 
                $localeDomain   =   $LocaleVal[$localeEnv];
        }
        else
        {
            $localeEnv  = "en_US.utf8";
            $locale     = "en_US";
            $localeDomain = $locale;
        }
    }
    else {
        if (isset($LocaleVal[$locale])) 
            $localeDomain   =   $LocaleVal[$locale];
        else
            $localeDomain   =   $locale;
       $localeEnv = $localeDomain.".utf8" ; 
    } 

    $_SESSION['locale'] = $locale;
    $lang               =  substr($locale, 0, 2);
    $dir = ($locale == 'he_IL') ? 'rtl' : 'ltr';
    $cssright = ($dir == 'ltr') ? 'right' : 'left';
    $cssleft = ($dir == 'ltr') ? 'left' : 'right';
    //echo " locale env is " . $localeEnv ;
    putenv("LC_ALL=$localeEnv");
    setlocale(LC_ALL, $localeEnv);
    //setlocale(LC_ALL, "sp_SP.utf8");
    bindtextdomain("messages", "./locale");
    textdomain("messages");
    
?>
    <!-- In order to infrom js files on which locale are we right now -->
    <script type="text/javascript">
            var locale   = "<?php echo $locale; ?>";
            var rootDir  = "<?php echo $rootDir; ?>";
            var sitePath =   "<?php echo $sitePath; ?>";

    </script>
