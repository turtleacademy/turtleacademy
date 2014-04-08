<?php

        $m                          =   new Mongo();
        $thisdb                     =   $m->turtleTestDb;
        $colname                    =   "programs";
        $collection            =   $thisdb->$colname;
        $criteria = $collection->find();
        foreach ($criteria as $cursor)
        {
            $cursor2 = $cursor;
            if ($cursor2['totalRankScore'] == "0")
            {
                echo "change";
                $cursor2['totalRankScore'] = intval(0);
                 
            }
            $collection->update($cursor,$cursor2);
        }
        /*
        $cursor = $criteria; 
        $cursor["$attName"] = $attVal ;
          $result = $collection->update($criteria,$cursor);
         *
         */