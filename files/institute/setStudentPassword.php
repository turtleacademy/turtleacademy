<?php
require_once("../utils/userUtil.php");
    $return['status'] = "fail";
    if (isset($_POST['username']) && isset($_POST['password']) )
    {
        userUtil::update_user_password($_POST['username'],$_POST['password']);
        $return['status'] = "success";
    }
echo json_encode($return);
?>