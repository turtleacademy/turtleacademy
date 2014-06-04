<?php
    error_reporting(E_ERROR | E_PARSE);
    $username                   =   $_POST['username'];
    if (!isset($_POST['programCode']))
    {
      $programCode = "";  
    } else {
         $programCode                =   $_POST['programCode'];
    }
    $programtitle               =   $_POST['programtitle'];
    $programUpdate              =   $_POST['update'];
    $program_id                 =   $_POST['programid'];
    $ispublic                   =   $_POST['ispublic'];
    $img                        =   $_POST['imgBase64'];
    $img_60_40                  =   "";
    $img_200_130                =   "";
    if (isset($_POST['img_60_40']))
        $img_60_40                  =   $_POST['img_60_40'];
    if (isset($_POST['img_200_130']))
        $img_200_130                =   $_POST['img_200_130'];
    
    $precedence                 =   "99";
    
    $return['programId']        =   $program_id;
    $return['username']         =   $username; 
    $return['programCode']      =   $programCode;
    $return['programUpdate']    =   $programUpdate;
    $lastUpdated                =   date('Y-m-d H:i:s');
    
    $m                          =   new MongoClient();
    $db                         =   $m->turtleTestDb;
    $user_programs               =   "programs";
    $user_Programs_Collection     =   $db->$user_programs;

    if ($programUpdate == "false" || $programUpdate == false)
    {
        $return['isFirstUserProgram'] = true;
        $structure = array("username" => $username, "dateCreated" => $lastUpdated ,"displayInProgramPage" => $ispublic ,
            "lastUpdated" => $lastUpdated , "programName" => $programtitle ,
             "code" => $programCode , "numOfComments" => "0" , "comments" => "" ,"precedence" => "99" ,
            "img" => $img  , "img_60_40" => $img_60_40 , "img_200_130" => $img_200_130 ,
            "sonPrograms" => "" , "fatherProgram" =>  "",
            "ranks" => "" , "numOfRanks" => intval(0) , "numOfSpinOffs" => intval(0) , "totalRankScore" => intval(0));
        $result = $user_Programs_Collection->insert($structure);
        $newDocID = $structure['_id'];
        $return['programId'] = $newDocID; 
    }
    else
    {
        //Fetching the current object
        $the_object_id                   =   new MongoId($program_id);
        $criteria                   =   $user_Programs_Collection->findOne(array("_id" => $the_object_id));
        //Changing all the values but createdDate
        $dateCreated        =   $criteria["dateCreated"];
        $num_comments       =   $criteria["numOfComments"];
        $comments           =   $criteria["comments"];
        $precedence         =   $criteria["precedence"];
        $dipp               =   $criteria["displayInProgramPage"];
        $father_program     =   $criteria["fatherProgram"];   
        $son_progrms        =   $criteria["sonPrograms"];
        $ranks              =   $criteria["ranks"];
        $num_of_ranks       =   $criteria["numOfRanks"];
        $rank_total_score   =   $criteria["totalRankScore"];
        $numOfSpinOffs      =   $criteria["numOfSpinOffs"];
        if (!isset($_POST['programCode']))
        {
             $programCode = $criteria["code"]; 
        }
        
        $structure = array("username" => $username, "dateCreated" => $dateCreated , "displayInProgramPage" => $ispublic , 
            "lastUpdated" => $lastUpdated , "programName" => $programtitle ,"code" => $programCode ,
            "numOfComments" => $num_comments , "comments" => $comments ,"precedence" => $precedence ,
            "img" => $img  , "img_60_40" => $img_60_40 , "img_200_130" => $img_200_130 ,
            "sonPrograms" => $son_progrms , "numOfSpinOffs" => $numOfSpinOffs , "fatherProgram" =>  $father_program,
            "ranks" => $ranks , "numOfRanks" => $num_of_ranks , "totalRankScore" => $rank_total_score);
        $result = $user_Programs_Collection->update($criteria, array('$set' => $structure));     
    }

    echo json_encode($return);
?>
