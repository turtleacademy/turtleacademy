<?php
    //require_once("../environment.php");
     $img64 = $_POST['imgBase64'];
   //  file_put_contents($sitePath .'/img/tmp/newImage.JPG',$decoded);
    $return['imag'] = $img64 ;
    
    $m                          =   new MongoClient();
    $db                         =   $m->turtleTestDb;
    $img                        =   "img";
    $imgCol     =   $db->$img;
        $structure = array("img" => $img64 );
        $result = $imgCol->insert($structure);
    echo json_encode($return);
?>
