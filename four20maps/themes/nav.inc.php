<div style="float:right; padding:10px;">Language: 
<select onChange="changeLang(this.value)">
<option value="en_US" <?php if(!isset($_SESSION['language']) || $_SESSION['language']=="en_US") { ?>selected<?php } ?>>English</option>
<option value="sv_SE" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="sv_SE") { ?>selected<?php } ?>>Swedish</option>
<option value="es_ES" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="es_ES") { ?>selected<?php } ?>>Spanish</option>
<option value="fr_FR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="fr_FR") { ?>selected<?php } ?>>French</option>
<option value="de_DE" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="de_DE") { ?>selected<?php } ?>>German</option>
<option value="cn_CN" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="cn_CN") { ?>selected<?php } ?>>Chinese</option>
<option value="kr_KR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="kr_KR") { ?>selected<?php } ?>>Korean</option>
<option value="jp_JP" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="jp_JP") { ?>selected<?php } ?>>Japanese</option>
<option value="ar_AR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="ar_AR") { ?>selected<?php } ?>>Arabic</option>
</select>
</div>


<div id="logo"></div>

<div class="navbar">
<div class="navbar-inner">
<a class="brand" href="./index.php"><?php echo $lang['MENU_STORE_FINDER']; ?></a>
            
<ul class="nav">

	<li class="divider-vertical"></li>
	<li id="store-list" <?php if (strpos($_SERVER['PHP_SELF'],'index.php') !== false) { ?>class="active"<?php } ?>><a href="<?php echo ROOT_URL; ?>index.php"><?php echo htmlspecialchars($lang['MENU_STORE_FINDER'], ENT_NOQUOTES, 'UTF-8'); ?></a></li>
	<li class="divider-vertical"></li>
	<li id="add-store" <?php if (strpos($_SERVER['PHP_SELF'],'newstore.php') !== false) { ?>class="active"<?php } ?>><a href="<?php echo ROOT_URL; ?>newstore.php"><?php echo $lang['REQUEST_ADD_STORE']; ?></a></li>
	<li class="divider-vertical"></li>
	<li id="add-store" <?php if (strpos($_SERVER['PHP_SELF'],'guide.php') !== false) { ?>class="active"<?php } ?>><a href="<?php echo ROOT_URL; ?>docs"><?php echo $lang['USER_GUIDE']; ?></a></li>
	<li class="divider-vertical"></li>
	<li id="add-store" <?php if (strpos($_SERVER['PHP_SELF'],'features.php') !== false) { ?>class="active"<?php } ?>><a href="<?php echo ROOT_URL; ?>features.php"><?php echo $lang['FEATURES']; ?></a></li>
	<li class="divider-vertical"></li>
	<li id="logout"><a href="admin/index.php"><?php echo $lang['ADMINISTRATOR']; ?></a></li>
	<li class="divider-vertical"></li>
	<li id="logout"><a href="http://codecanyon.net/user/highwarden"><?php echo $lang['SUPPORT']; ?></a></li>
	<li class="divider-vertical"></li>
	<li id="logout"><a class="btn-danger" style="color:#fff; overflow:hidden;" href="http://codecanyon.net/item/super-store-finder/3630922"><?php echo $lang['BUY_NOW']; ?></a></li>

</ul>
  </div>
</div>	

<script>
function changeLang(v){
document.location.href="?langset="+v;
}
</script>