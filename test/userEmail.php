<?php
    $phpDirPath = "../files/registration/inc/php/";
    include_once $phpDirPath . 'config.php';
    include_once $phpDirPath . 'functions.php';
    require_once("../environment.php");
    require_once("../localization.php");
    require_once("../localization_js.php");
    require_once("../files/utils/programUtil.php");
    require_once("../files/utils/userUtil.php");
   
    $users      =   userUtil::get_varified_users_collection();
    $random     =   rand(1, 600);   
    $i          =   0;
    $userName   =   "";
    $email      =   "";
    
    foreach ($users as $user)
    {
        if ($i == $random)
        {
            $userName   =   $user['username'];
            $email  =   $user['email'];
        }
        $i++;
        //function send_email_ready($info , $sitePath , $templateType = "newyear" , $locale = "en_us"){	
        echo $i;
    }
    echo $userName . " , " . $email ;
    $userName   =   "Lucio";
    $email  =   "2591165769@qq.com" ;
     $info = array(
        'username'      => $userName,
        'email'         => $email,
        'msgWelcome'    => 'New year studying'
        );
    send_email_ready($info , $site_path , $templateType = "newyear")
?>