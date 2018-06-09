<?php 
ob_start();
/*
** Developed by Joe Iz
** Details: http://highwardenhuntsman.blogspot.com
*/

session_start();

function auth() {
	if($_SESSION['is_admin']!='1') {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_AUTHENTICATION_REQUIRED']);		
		echo "<script>document.location.href='index.php'</script>";
	}
	else
	{
		return TRUE;
	}
return TRUE;
}



function logout() {
	session_destroy();
	redirect('index.php');
}


/**
 * Standard Connects to database
 */
function db_connect() {

	$db = new DB(array(
		'hostname'=>HOSTNAME,
		'username'=>DB_USERNAME,
		'password'=>DB_PASSWORD,
		'db_name'=>DB_NAME
	));


	if($db===FALSE) {
		print_debug($db->errors);
		exit;
	}

return $db;
}


function check_user($username, $password) {

	$db = db_connect();

	$user = $db->get_row("SELECT users.* FROM users WHERE users.username='".$db->escape($username)."' and is_admin=1 and status = '1' ");
	

	if(empty($user)) {
		return FALSE;
		exit;
	}


	if(md5($password) != $user['password']) {
		return FALSE;
		exit;
	}
	
		$_SESSION['User'] = $user;
		$_SESSION['is_admin'] = 1;
	
	$_SESSION['Time'] = time();
	

	$user_info['modified'] = date('Y-m-d H:i:s');
		
	$db->update('users',$user_info,$_SESSION['User']['id']);

return TRUE;
}


function print_debug($arr) {
	echo '<pre>';
	if(is_string($arr)) {
		echo $arr;
	} else {
		print_r($arr);
	}
	echo '</pre>';
}

function notification() {
	$str = '';
	if(isset($_SESSION['notification'])) {
		if(!isset($_SESSION['notification']['type']) || !isset($_SESSION['notification']['msg'])) {
			return '';
		}
		$class = '';
		switch($_SESSION['notification']['type'])
		{
			case 'good':
				$class = ' class="alert fade in"';
				break;
			case 'bad':
				$class = ' class="alert alert-block alert-error fade in"';
				break;
		}
		$str = "<p{$class}>".$_SESSION['notification']['msg']."</p>";
		unset($_SESSION['notification']);
	}
return $str;
}


function redirect($url) {
	echo "<script>document.location.href='".$url."'</script>";
	exit;
}


function get_files($dir) {
	if(!is_dir($dir)) {
	return FALSE;
	}

	$files = @scandir($dir);
	foreach($files as $k=>$v) {
		if(strpos($v,'.')==0) {
			unset($files[$k]);
		}
	}

return $files;
}


function create_dir($dir) {
	$res = TRUE;
	if(!is_dir($dir)) {
		$res = mkdir($dir);
		@chmod($dir,0777);
	}
return $res;
}


if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {

        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}