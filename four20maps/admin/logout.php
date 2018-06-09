<?php
session_start();
 
unset($_SESSION['is_admin']);
session_destroy(); 
 
header("location:http://www.four20maps.com/admin/index.php");
?>
 