<?php

require_once '../utils/userUtil.php';
$username = "lucio";
$password = "q1w2e3";

    $userExist  =   userUtil::varifyUser($username, $password);
            if ($userExist)
                echo "User exist";
            else
                echo "User does not exist";
?>
