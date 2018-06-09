<?php

/*

** Developed by Joe Iz

** Details: http://highwardenhuntsman.blogspot.com

*/

function auth() {
	
	if(!isset($_SESSION["StoreID"])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>'ADMIN AUTHENTICATION REQUIRED');
		echo "<script>document.location.href='index.php'</script>";
		exit;
	}
/*	if(isset($_SESSION['Time'])) {
		$period = 60*60;
		if( time() - $_SESSION['Time'] >= $period ) {
			unset($_SESSION["StoreID"]);
			unset($_SESSION["regSuccess"]);
			$_SESSION['notification'] = array('type'=>'bad','msg'=>'ADMIN SESSION EXPIRED');
			header('Location: index.php');
			exit;
		}
	}*/
return TRUE;
}



function convertXMLtoArray( $xml , $recursive = false ) {

    if( ! $recursive ){

		$array = simplexml_load_string ( $xml ) ;

    } else {

        $array = $xml ;

    }



    $newArray = array () ;

    $array = ( array ) $array ;

    foreach ( $array as $key => $value ) {

        $value = ( array ) $value ;

        if ( isset ( $value [ 0 ] ) ) {

            $newArray [ $key ] = trim ( $value [ 0 ] ) ;

        } else {

            $newArray [ $key ] = convertXMLtoArray ( $value , true ) ;

        }

    }

    return $newArray ;

}





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







function json_stores_list($sql) {

global $lang;



	$db = db_connect();



	$stores = $db->get_rows($sql);



	$json = array();



	if(!empty($stores)) {



		$json['success'] = 1;



		$json['stores'] = array();



		foreach($stores as $k=>$v) {



			// store img

			

			$upload_dir = ROOT.'admin/imgs/stores/'.$v['id'].'/';



			$files = get_files($upload_dir);



			if(is_array($files)) {

				$files = array_values($files);

			}



			$img = '';

			if($files !== FALSE && isset($files[0])) {

				$img = ROOT_URL.'admin/imgs/stores/'.$v['id'].'/'.$files[0];

			}

			

			$cat_img = '';

			$cat_name = '';

			

			if($v['cat_id']>0){

				// cat img

				

				$cat_upload_dir = ROOT.'admin/imgs/categories/'.$v['cat_id'].'/';



				$cat_files = get_files($cat_upload_dir);



				if(is_array($cat_files)) {

					$cat_files = array_values($cat_files);

				}



				if($cat_files !== FALSE && isset($cat_files[0])) {

					$cat_img = ROOT_URL.'admin/imgs/categories/'.$v['cat_id'].'/'.$cat_files[0];

				}



				$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id='".$v['cat_id']."'");



				if(!empty($cats)):

				foreach($cats as $a=>$b):

				$cat_name = $b['cat_name'];

				endforeach;

				endif;

			

			}



			

			$json['stores'][] = array(
			
				'storeid'=>$v['id'], 

				'name'=>$v['name'],

				'address'=>$v['address'],

				'telephone'=>$v['telephone'],

				'email'=>$v['email'],

				'website'=>$v['website'],

				'description'=>$v['description'],

				'lat'=>$v['latitude'],

				'lng'=>$v['longitude'],
				'DatabaseName'=>$v['DatabaseName'],

				'titlewebsite'=>$lang['ADMIN_WEBSITE'],

				'titleemail'=>$lang['ADMINISTRATOR_EMAIL'],

				'titletel'=>$lang['ADMIN_TELEPHONE'],

				'titlecontactstore'=>$lang['CONTACT_THIS_STORE'],

				'titlekm'=>$lang['KM'],

				'titlemiles'=>$lang['MILES'],

				'cat_name'=>$cat_name,

				'cat_img'=>$cat_img,
				'cat_icon'=>"admin/".$v['cat_icon'],

				'img'=>$img

			);



		}

	} else {



		$json = array('success'=>0,'msg'=>$lang['STORE_NOT_FOUND']);

	}



return json_encode($json);

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



	echo "<meta http-equiv='refresh' content='0;url=".$url."'>";

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

class FlashMessage {

    public static function render() {
        if (!isset($_SESSION['messages'])) {
            return null;
        }
        $messages = $_SESSION['messages'];
        unset($_SESSION['messages']);
        return implode('<br/>', $messages);
    }

    public static function add($message) {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }
        $_SESSION['messages'][] = $message;
    }

}