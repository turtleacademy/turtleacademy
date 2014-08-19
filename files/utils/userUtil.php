<?php
     class userUtil {
         
     public static function varify_user($username , $password) 
     {
           $password = md5($password); 
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $users = $db->users;
           
           $userQuery       = array('username' => $username , 'password' => $password , 'confirm'=> true);
           $resultcount     = $users->count($userQuery);
           //Case no user found
           if ($resultcount == 0)
           {
               return false;
           }
           else
           {
               self::user_login_log($username , $db);
               
               return true;
           }
     }
     /*
      * Stripping the email address from the user name
      */
     
     public static function strip_user_email($username)
     {
        $hasMail = false;
        if (strpos($username, '@') !== false) {
            $name_before_adding_mail_address  = explode('@', $username);
            $username     = $name_before_adding_mail_address[0];
            $hasMail = true;
        }
        $user['name'] = $username ; $user['email'] = $hasMail ;
        return $user;
     }
     public static function get_user_institute_email($username)
     {
         $m = new MongoClient();
         $db = $m->turtleTestDb;	
         $users = $db->users;
         $userQuery       = array('username' => $username );
         $cursor = $users->findone($userQuery);
         return $cursor['institute_email'];
         
     }
     public static function find_mail_user($username,$reg) //username_email
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $users = $db->users;
           //echo "user name is " . $username . " reg is " . $reg;
           $usernameArr = explode($reg, $username);
           $displayUserName = $usernameArr[0];
           //echo $displayUserName;

// Case Sensitive

            $where = array('username' => array('$regex' => new MongoRegex("/^$displayUserName/")));
            $cursor = $users->findone($where);
            return $cursor['username'];
     }
     /* 
      * Putting user login info
      */
     private static function user_login_log($username , $db)
     {
         $loginTime = date("Y-m-d H:i:s");
         $loginUser   =   $db->users_login ;
         //Check if user already exist
         $userQuery       = array('username' => $username );
         $resultcount     = $loginUser->count($userQuery);
         //if exist do
         if ($resultcount > 0){
             $criteria = $loginUser->findOne(array('username' => $username ));
             $loggedinfo    =   $criteria['logged'];
             $loggedinfo  .= " ," . $loginTime;
             $result = $loginUser->update($criteria, array('$set' => array("username" => $username, "logged" => $loggedinfo)));
         }
         
         //else do
         else{
                $structure = array("username" => $username, "logged" => $loginTime);
                $result = $loginUser->insert($structure, array('safe' => true));
         }
     } 
     
     /*
      * Getting the username
      * if the username equal to Admin then show all lessons
      */
     public static function show_user_lessons($username) 
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $users = $db->lessons_created_by_guest;
           if ($username == "lucio")
           {
               $results     = $users->find();
           }
           else {
           $userQuery       =  array('username' => $username);
           $results     = $users->find($userQuery);
           }
           //Case no user found
               return $results;
     }
     /*
      * Will find all the programs created by the user
      */
     
    public static function find_user_programs($username) 
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $programs = $db->programs;


           $userProgramQuery       =  array('username' => $username);
           $results     = $programs->find($userProgramQuery);
           return $results;
           //Case no user found
               
     }
     /*
      * Find the user public programs can also get a criteria to get specific group programs
      */
     public static function get_institiute_user_programs($email)
     {
        $m                  = new MongoClient();
        $db                 = $m->turtleTestDb;	
        $users              = $db->users;
        $programs           = $db->programs;
        $userQuery          = array('institute_email' => $email);
        $instituteUsers     = $users->find($userQuery);
        
        $getInstituteNames  = array();
        foreach ($instituteUsers as $insUser)
        {
            $tempUsername = $insUser['username'];
            $getInstituteNames[] = array('username' => $tempUsername);
        }
        $institutePrograms  = null;
        $ddd = array('$or' => $getInstituteNames);
        $results     = $programs->find($ddd);
        //$count      =  $programs->count($ddd); 
        //$count2 = $programs->count(array('$or' => array(array('username' => 'testo33') , array('username' => 'test1000') , array('username' => 'test1001') , array('username' => 'test1002') , array('username' => 'Testo1') , array('username' => 'student5') )));
        return $results;
     }
     public static function find_user_public_programs($username) 
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $programs = $db->programs;


           $userProgramQuery       =  array('$or' => array(array('username' => $username , 'displayInProgramPage' => true), array('username' => $username , 'displayInProgramPage' => 'true')));
           $results     = $programs->find($userProgramQuery);
           return $results;
           //Case no user found
               
     }
     
     public static function find_public_programs() 
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $programs = $db->programs;


           $userProgramQuery       =  array('$or' => array(array('displayInProgramPage' => true), array('displayInProgramPage' => 'true')));
           //$programs->ensureIndex(array('totalRankScore' => 1));
           $results     = $programs->find($userProgramQuery);
           return $results;
           //Case no user found          
     }
     /*
      * Obsolate 
      * Can use the collectionUtil function CollectionItemAddAttribute
      */
     public static function add_property_to_user_collection ($property , $val)
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $users = $db->users;
           $cursor = $users->find();
            foreach ($cursor as $user) {
                $userobj = $user;
                $newdata = array('$set' => array($property => $val));
                $users->update($user, $newdata);
            } 
     }
     /*
      * Will copy privous collection object of userOpenId TO the Users collection
      */ 
     public static function copy_db_openid_user_to_users($username)
     {
         $m = new MongoClient();
         $db = $m->turtleTestDb;	
         $usersOpenId = $db->user_open_id;
         $users = $db->users;
         //Check if user already exist in users db
         $userQuery       = array('username' => $username , 'email'=> $username);
         $resultcount     = $users->count($userQuery);
         //if User already exist in db do nothing else copy info between collection
         if ($resultcount > 0){
            // do Nothing
         }
         else
         {
            $openid_user = $usersOpenId->findOne(array('contact/email' => $username));       
            //New user object definition
            $email                  =   $openid_user['contact/email'];      
            $user['username']       =   $email;
            $user['password']       =   md5($email);
            $user['badges']         =   "";
            $user['confirm']        =   true ;
            $user['email']          =   $email;
            $user['fullname']       =   $openid_user['namePerson/first'] . " " . $openid_user['namePerson/last'] ;
            $user['pref/language']  =   $openid_user['pref/language'];
            
            $result = $users->insert($user, array('safe' => true)); 
         }
     }
     public static function get_user_new_messages_indication($username)
     {
            $m = new MongoClient(); 
            $db = $m->turtleTestDb;
            $strcol = $db->messages;
            $newMessagesQuery     = array ('sendto' => $username , 'read' => false);
            $new_messages_query_all     = array ('sendto' => 'all'     , 'read' => false);
            $numOfNewMsg    = $strcol->count($newMessagesQuery) + $strcol->count($new_messages_query_all) ;
            
            if ($numOfNewMsg > 0)
                return true;
            else 
                return false;
     }
     /*
      * Will copy all open_id objects to users
      */ 
    public static function copy_db_all_openid_users_to_users()
     {
         $m = new MongoClient();
         $db = $m->turtleTestDb;	
         $users_open_id_col = $db->user_open_id;
         $users = $db->users;
         
         $open_id_users     = $users_open_id_col->find();
         $date = date('Y-m-d H:i:s');
         foreach ($open_id_users as $OpenIdUser) {
                $email           =   $OpenIdUser['contact/email']; 
                $userQuery       = array('username' => $email , 'email'=> $email);
                $resultcount     = $users->count($userQuery);
                //if User already exist in db do nothing else copy info between collection
                if ($resultcount > 0){
                    // do Nothing
                }
                else
                {
                    $user = null;
                    //New user object definition   
                    $user['username']       =   $email;
                    $user['password']       =   md5($email);
                    $user['badges']         =   "";
                    $user['confirm']        =   true ;
                    $user['email']          =   $email;
                    $user['fullname']       =   $OpenIdUser['namePerson/first'] . " " . $OpenIdUser['namePerson/last'] ;
                    $user['pref/language']  =   $OpenIdUser['pref/language'];
                    $user['date']           =   $date;

                    $users->insert($user, array('safe' => true)); 
                }
         } 
         //Check if user already exist in users db
         
     }   
     
     /*
      * This function will get all the users of a specific institue
      * @param email - the institute admin email
      * @return - all the users matching the criteria
      */
     public static function get_institiute_users_by_institue_admin_email ($email)
     {
        $m = new MongoClient();
        $db = $m->turtleTestDb;	
        $users = $db->users;
        $userQuery       = array('institute_email' => $email);
        $results     = $users->find($userQuery);
        return $results;
     }
     
    public static function get_num_of_varified_users() 
     {
           $m = new MongoClient();
           $db = $m->turtleTestDb;	
           $users = $db->users;
           
           $user_query       = array('confirm' => true);
           $resultcount     = $users->count($user_query);
           //Case no user found
          
               return $resultcount;
           
     }
     
     
    }
?>
