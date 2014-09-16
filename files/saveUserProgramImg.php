<?php
   
    if (!isset($_POST['programCode']))
    {
      $programCode = "";  
    } else {
         $programCode                =   $_POST['programCode'];
    }
    $programtitle               =   $_POST['programtitle'];
    $programUpdate              =   $_POST['update'];
    $program_id                 =   $_POST['programid'];
    $img                        =   $_POST['imgBase64'];
    $img_60_40                  =   "";
    $img_200_130                =   "";
    if (isset($_POST['img_60_40']))
        $img_60_40                  =   $_POST['img_60_40'];
    if (isset($_POST['img_200_130']))
        $img_200_130                =   $_POST['img_200_130'];
    
    $precedence                 =   "99";
   
    $return['programId']        =   $program_id;
    
    $return['programCode']      =   $programCode;
    $return['programUpdate']    =   $programUpdate;
    
    
    $m                          =   new MongoClient();
    $db                         =   $m->turtleTestDb;
    $user_programs               =   "programs";
    $user_Programs_Collection     =   $db->$user_programs;

   
        //Fetching the current object
        $the_object_id                   =   new MongoId($program_id);
        $criteria                   =   $user_Programs_Collection->findOne(array("_id" => $the_object_id));
        //Changing all the values but createdDate
        $dateCreated        =   $criteria["dateCreated"];
        $num_comments       =   $criteria["numOfComments"];
        $comments           =   $criteria["comments"];
        $username           =   $criteria["username"];
        $precedence         =   $criteria["precedence"];
        $dipp               =   $criteria["displayInProgramPage"];
        $father_program     =   $criteria["fatherProgram"];   
        $son_progrms        =   $criteria["sonPrograms"];
        $last_updated       =   $criteria["lastUpdated"];
        
        $ranks              =   $criteria["ranks"];
        $num_of_ranks       =   $criteria["numOfRanks"];
        $rank_total_score   =   $criteria["totalRankScore"];
        $numOfSpinOffs      =   $criteria["numOfSpinOffs"];
        if (!isset($_POST['programCode']))
        {
             $programCode = $criteria["code"]; 
        }
        
        $return['60-40'] = $img_60_40; 
        $structure = array("username" => $username, "dateCreated" => $dateCreated , "displayInProgramPage" => $dipp ,
            "lastUpdated" => $last_updated , "programName" => $programtitle ,"code" => $programCode ,
            "numOfComments" => $num_comments , "comments" => $comments ,"precedence" => $precedence , "img_60_40" => $img_60_40 ,
            "img_200_130" => $img_200_130 , "img" => "", 
            "sonPrograms" => $son_progrms , "numOfSpinOffs" => $numOfSpinOffs , "fatherProgram" =>  $father_program,
            "ranks" => $ranks , "numOfRanks" => $num_of_ranks , "totalRankScore" => $rank_total_score);
        $result = $user_Programs_Collection->update($criteria, array('$set' => $structure));
        $return['result'] = $result;

    echo json_encode($return);
?>
