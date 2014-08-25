<?php
    require_once("../environment.php");
    require_once("../localization.php");
    require_once("../localization_js.php");
    require_once("../files/utils/programUtil.php");
    if (isset($_GET['id']) && isset($_GET['username']))
    {
        echo $_GET['id'];
        echo $_GET['username'];
        $item = new MongoId($_GET['id']);
        $username = $_GET['username'];
        echo programUtil::set_program_username($item,$username);
    }
    elseif (isset($_GET['id']) && isset($_GET['title']))
    {
        echo $_GET['id'];
        echo $_GET['title'];
        $item = new MongoId($_GET['id']);
        $title = $_GET['title'];
        echo programUtil::set_program_title($item,$title);
    }
    else
    {
        echo "Please insert the get var , 'id' and 'username' ?id=&username= Or for title ?id=&title";
    }
?>