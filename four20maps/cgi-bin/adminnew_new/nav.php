<div class="pull-right adminlogin-top"><?php echo $lang['ADMIN_LOGGED_IN_AS']; ?>:
  <div class="btn-group">
    <button class="btn" onclick="document.location.href='stores.php'"><?php echo $_SESSION['User']['username']; ?></button>
    <button class="btn" onclick="document.location.href='settings.php'"><i class="icon-cog"></i></button>
    <button class="btn btn-info" onclick="document.location.href='change_password.php'"><?php echo $lang['ADMIN_CHANGE_PASSWORD']; ?></button>
    <button class="btn btn-danger" onclick="document.location.href='logout.php'"><?php echo $lang['ADMIN_LOGOUT']; ?></button>
  </div>
</div>
<?php /*?><div id="logo"></div><?php */?>
<div class="navbar admin-nav">
  <div class="navbar-inner"> <a class="brand" href="index.php"><img src="<?php echo ROOT_URL ?>images/logo.png"/><?php /*?><?php echo $lang['MENU_STORE_FINDER']; ?><?php */?></a>
    <ul class="nav navbar-nav">
      <li id="store-list" <?php if (strpos($_SERVER['PHP_SELF'],'stores.php') !== false) { ?>class="active"<?php } ?>><a href="./stores.php"><?php echo $lang['ADMIN_STORE_LIST']; ?></a></li>
      <!--<li id="add-store" <?php //if (strpos($_SERVER['PHP_SELF'],'stores_add.php') !== false) { ?>class="active"<?php// } ?>></li>-->
      <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'import.php') !== false) { ?>class="active"<?php } ?>><a href="./import.php">Import/Export Stores</a></li>
      <li id="add-store" <?php if (strpos($_SERVER['PHP_SELF'],'categories.php') !== false) { ?>class="active"<?php } ?>><a href="./categories.php"><?php echo $lang['SSF_CATEGORY_LIST']; ?></a></li>
      
      <li id="user-list" <?php if (strpos($_SERVER['PHP_SELF'],'users.php') !== false) { ?>class="active"<?php } ?>><a href="./users.php"><?php echo $lang['ADMIN_USER_LIST']; ?></a></li>
      <li id="add-user" <?php if (strpos($_SERVER['PHP_SELF'],'adminusers.php') !== false) { ?>class="active"<?php } ?>><a href="./adminusers.php">Admin Users</a></li>
      <li id="add-user" <?php if (strpos($_SERVER['PHP_SELF'],'addslist.php') !== false) { ?>class="active"<?php } ?>><a href="./addslist.php"><?php echo $lang['ADMIN_ADD_ADDS']; ?></a></li>
      <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'reviews.php') !== false) { ?>class="active"<?php } ?>><a href="./reviews.php">Reviews</a></li>
      <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'reports.php') !== false) { ?>class="active"<?php } ?>><a href="./reports.php">Reports</a></li>
	  <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'menu.php') !== false) { ?>class="active"<?php } ?>><a href="./menu.php">Menu</a></li>
      
      <li class="root">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Store <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'subscription.php') !== false) { ?>class="active"<?php } ?>><a href="./subscription.php">Subscriptions</a></li>
          <li id="import" <?php if (strpos($_SERVER['PHP_SELF'],'store_users.php') !== false) { ?>class="active"<?php } ?>><a href="./store_users.php">Store Users</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <div><br><br></div>
	<div class="alert alert-warning" id="msg" style="display:none; text-align:center"></div>
  </div>
