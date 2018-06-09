<?php 
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();

?>
<div class="sidebar-menu">
  <ul>
    <li <?php if($controller == "main" && $method == "dashboard"){?> class="active"<?php } ?>><a href="<?php echo base_url("index.php/main/dashboard"); ?>">Dashboard</a></li>
    <li <?php if($controller == "main" && $method == "editprofile"){?> class="active"<?php } ?>><a href="<?php echo base_url("index.php/main/editprofile"); ?>">Edit Profile</a></li>
    <li <?php if($controller == "main" && $method == "sitedetails"){?> class="active"<?php } ?>><a href="<?php echo base_url("index.php/main/sitedetails"); ?>">Patient Website Set Up</a></li>
    <li <?php if($controller == "main" && $method == "settings"){?> class="active"<?php } ?>><a href="<?php echo base_url("index.php/main/settings"); ?>">Settings</a></li>
    <li <?php if($controller == "main" && $method == "changepassword"){?> class="active"<?php } ?>><a href="<?php echo base_url("index.php/main/changepassword"); ?>">Change Password</a></li>
    <li><a href="<?php echo base_url('index.php/main/logout');?>">Logout</a></li>
  </ul>
</div>
