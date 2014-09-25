<?php
     class programUtil {
        public static function find_public_programs() 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programs = $db->programs;


            $userProgramQuery       =  array('displayInProgramPage' => true);
            $results     = $programs->find($userProgramQuery);
            return $results;
            //Case no user found          
        }
       
        public static function find_program_comments($program_id) 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs;
            $program = $programcol->findOne(array("_id" => $program_id));
            return $program['comments'];
            //Case no user found          
        }
         
        public static function find_program_num_comments($program_id) 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs;
            $program = $programcol->findOne(array("_id" => $program_id));
            return $program['numOfComments'];
            //Case no user found          
        }
        
        //Getting the user rank for the program
        public static function program_ave_rank($program_id) 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs;
            $program = $programcol->findOne(array("_id" => $mongoid));
            $rank = $program['rank'];
            if (!is_array($rank))
            {
                return null;
            }
            else{
                    
            }
            return $program['comments'];
            //Case no user found          
        }
        /*
         * Input : $program_id - a mongo object ID representing the new program
         * Return: the program object
         */
        public static function get_program_by_id($program_id)
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs;
            $program = $programcol->findOne(array("_id" => $program_id));
            
            return $program;
            //Case no user found  
        }
        
        /*
         * Input  : $program_id - a mongo object ID representing the new program
         * Input  : $username - a string representing the username
         * Return : The user rank 
         */
             
        public static function program_rank_by_user($program_id , $username) 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs;
            $program = $programcol->findOne(array("_id" => $program_id));
            $ranks = $program['ranks'];
            $user_prev_rank = 0;
            if (is_array($ranks))
            {
                $user_prev_rank = 7;
                foreach ($ranks as $rank)
                {
                    if ($rank['username'] == $username)
                    {
                        $user_prev_rank =  $rank['value'];
                        break;
                    }
                }
            }
            return $user_prev_rank;
            //Case no user found          
        }
        
        /*
         * In case some programs don't have a username assignned 
         */
         public static function program_anonymous_user() 
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs; 
            $program = $programcol->find(array("username" => ""));
            
            return count($program);
            //Case no user found          
        }
        
        public static function set_program_username($mongoid , $username)
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs; 
            $criteria = $programcol->findOne(array("_id" => $mongoid));
            $cursor = $criteria; 
            $cursor["username"] =   $username;
            $result = $programcol->update($criteria,$cursor);
        }
        
        public static function set_program_title($mongoid , $title)
        {
            $m = new MongoClient();
            $db = $m->turtleTestDb;	
            $programcol = $db->programs; 
            $criteria = $programcol->findOne(array("_id" => $mongoid));
            $cursor = $criteria; 
            $cursor["programName"] =   $title;
            $result = $programcol->update($criteria,$cursor);
        }
     }  
?>
