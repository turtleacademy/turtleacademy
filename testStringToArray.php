<?php
  $str =  '[ [ "1", "11", "111", "111", "<p>\n\t1111</p>\n" ], [ "11", "111", "111", "111", "<p>\n\t111</p>\n" ], [ "2", "2211", "2222111", "22222111", "<p>\n\t1111222222</p>\n" ] ]' ;
  echo $str ;
  echo '************';
  $exploded = json_decode($str);
  var_dump($exploded);
  echo $str[0];
  
?>
