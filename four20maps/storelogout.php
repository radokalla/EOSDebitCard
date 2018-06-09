<?php
ini_set('error_reporting', 0);
ini_set('display_errors', 0); 
session_start();
unset($_SESSION['regSuccess']);
session_destroy();
 $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
            // Explicitly unset this cookie - shouldn't be redundant,
            // but it doesn't hurt to try
            setcookie('PHPSESSID', '', time()-1000);
?>
<script>
window.location='index.php';
</script>