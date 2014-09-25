<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php 
    if (!isset ($root_dir))
        $root_dir = "/";
    $boostrapPath =   $root_dir."files/bootstrap/";
        ?>
        <script type="application/javascript" src="<?php echo $boostrapPath ."js/bootstrap.js"; ?>"></script>
        <script type="application/javascript" src="<?php echo $boostrapPath ."js/bootstrap.min.js"; ?>"></script>
        <link href="<?php echo $boostrapPath ."css/bootstrap.all.css"; ?>" rel="stylesheet"> 


