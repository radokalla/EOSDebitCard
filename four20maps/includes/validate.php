<?php

// validate request store add


function validate_request_add_store()
{

    global $lang;

    global $fields;

    global $images;

    global $db;

    global $errors;

    $fields = array(

        'name' => array(

            'rule' => '/.+/',

            'message' => $lang['ADMIN_STORE_NAME_VALIDATE'],

            'value' => '',

            'required' => true

        ) ,

        'address' => array(

            'rule' => '/.+/',

            'message' => $lang['ADMIN_STORE_ADDRESS_VALIDATE'],

            'value' => '',

            'required' => true

        ) ,

        'telephone' => array(

            'rule' => '/[0-9 +]/',

            'message' => $lang['ADMIN_STORE_TELEPHONE_VALIDATE'],

            'value' => '',

            'required' => false

        ) ,

        'email' => array(

            'rule' => "/^([a-z0-9\+_\-']+)(\.[a-z0-9\+_\-']+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",

            'message' => $lang['ADMIN_STORE_EMAIL_VALIDATE'],

            'value' => '',

            'required' => false

        ) ,

        'website' => array(

            'rule' => '/.+/',

            'message' => $lang['ADMIN_STORE_WEBSITE_VALIDATE'],

            'value' => '',

            'required' => false

        ) ,

        'description' => array(

            'rule' => '/.+/',

            'message' => $lang['ADMIN_STORE_DESCRIPTION_VALIDATE'],

            'value' => '',

            'required' => false

        ) ,

        'latitude' => array(

            'rule' => '/[0-9.\-]/',

            'message' => $lang['ADMIN_STORE_LATITUDE_VALIDATE'],

            'value' => '',

            'required' => true

        ) ,

        'longitude' => array(

            'rule' => '/[0-9.\-]/',

            'message' => $lang['ADMIN_STORE_LONGITUDE_VALIDATE'],

            'value' => '',

            'required' => true

        )

    );

    $session_id = session_id();

    $tmp_upload_folder = ROOT . 'temp_upload/' . $session_id . '/';

    $resize_image_width = 100;

    if (isset($_POST['delete_image']))
    {

        $delete = array_keys($_POST['delete_image']);

        $image = $delete[0];

        if (file_exists($tmp_upload_folder . $image))
        {

            if (!@unlink($tmp_upload_folder . $image))
            {

                $errors = $lang['ADMIN_STORE_IMAGE_DELETE_FAILED'] . $v;

            }

        }

    }

    if ($_POST)
    {

        $errors = array();

        foreach ($fields as $k => $v)
        {
            if (isset($_POST[$k]))
            {
                $required = (isset($v['required'])) ? (!empty($_POST[$k])) ? true : $v['required'] : true;
                if (isset($v['rule']) && $required && !preg_match($v['rule'], $_POST[$k]))
                {
                    if (isset($v['rule']) && !preg_match($v['rule'], $_POST[$k]))
                    {
                        if (isset($v['message']) && !empty($v['message']))
                        {
                            $errors[] = $v['message'];
                        }
                    }
                }
                $fields[$k]['value'] = $_POST[$k];
            }

        }

        if ($_FILES && $_FILES['file']['error'] != 4)
        {

            $allowed_mimetypes = array(
                'image/gif',
                'image/jpeg',
                'image/pjpeg',
                'image/x-png',
                'image/png'
            );

            if (!in_array($_FILES['file']['type'], $allowed_mimetypes))
            {

                $errors[] = $lang['ADMIN_STORE_ALLOWED_IMAGE'];

            }
            else
            {
				
                create_dir($tmp_upload_folder);

                $img = new Image(array(
                    'filename' => $_FILES['file']['tmp_name']
                ));

                if ($img !== false)
                {

                    if ($img->resize_to_width($resize_image_width))
                    {
                        $safe_name = strtolower(str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\-_. ]/', '', $_FILES['file']['name'])));

                        if ($img->save($tmp_upload_folder . $safe_name))
                        {
                            $_POST['image'] = 'http://www.four20maps.com/' . $tmp_upload_folder . $safe_name;
                        }
                        else $errors[] = $lang['ADMIN_STORE_THUMB_FAILED'];
                    }
                    else
                    {
                        $errors[] = $lang['ADMIN_STORE_THUMB_FAILED'];
                    }

                }
                else
                {
                    $errors[] = $lang['ADMIN_STORE_IMAGE_FAILED'];
                }

            }

        }

        if (empty($errors))
        {
            mysql_query("SET NAMES utf8");
            if (!get_magic_quotes_gpc())
            {
                $_POST['name'] = addslashes($_POST['name']);
                $_POST['address'] = addslashes($_POST['address']);
                $_POST['description'] = addslashes($_POST['description']);
            }
            $_POST['description'] = mysql_real_escape_string($_POST['description']);
            $_POST['zipcode'] = mysql_real_escape_string($_POST['zipcode']);

            $_POST['timings'] = json_encode($_POST['timings']);
            $_POST['first_time_patients'] = mysql_real_escape_string($_POST['first_time_patients']);
            $_POST['announcement'] = mysql_real_escape_string($_POST['announcement']);
            $_POST['about_us'] = mysql_real_escape_string($_POST['about_us']);
//$_POST['cat_id'] =$_POST['StoreUserSubscriptionId'];
            #var_dump($_POST);
            if (isset($_POST['id']) && $_POST['id'] == '')
            {
                if (!$db->insert('stores', $_POST))
                {
                    $errors[] = $lang['ADMIN_STORE_SAVE_FAILED'];
                }
                else
                {
                    $insert_id = $db->get_insert_id();
                    $userId = $_SESSION['StoreID'];
                    $sql = mysql_query("select * from StoreUserSubscription where Status = '1' and StoreUserSubscriptionId =" . $_POST['StoreUserSubscriptionId']);
                    $det = mysql_fetch_array($sql);
                    $_SESSION['StoreSuc'] = 'Update Successful';
                    if (empty($det))
                    {
                        $_SESSION['notification'] = array(
                            'type' => 'bad',
                            'msg' => 'Cannot add store to the selected Subscription'
                        );
                    }
                    mysql_query("update StoreUserSubscription SET Status = '1' where UserId = '$userId' and StoreUserSubscriptionId =" . $_POST['StoreUserSubscriptionId']);
                    $_SESSION['status'] = '1';
                    if (is_dir($tmp_upload_folder))
                    {
                        $files = get_files($tmp_upload_folder);
                        if (!empty($files))
                        {
                            if (create_dir(ROOT . 'temp_upload/' . $session_id . '/' . $v))
                            {

                                foreach ($files as $k => $v)
                                {
                                    if (@copy(ROOT . 'temp_upload/' . $session_id . '/' . $v, ROOT . 'temp_upload/' . $session_id . '/' . $v))
                                    {
                                        @unlink(ROOT . 'temp_upload/' . $session_id . '/' . $v);
                                    }
                                }
                            }
                            @unlink(ROOT . 'temp_upload/' . $session_id . '/');
                        }
                    }

                    mail(ADMINISTRATOR_EMAIL, $lang['ADD_STORE_REQUEST_EMAIL_TITLE'], $lang['ADD_STORE_REQUEST_EMAIL_BODY'], "From: no-reply@gmail.com");
                    $_SESSION['status'] = '1';

                    //redirect(ROOT_URL.'index.php');
                    
                }
            }
            else
            {
                $db->update('stores', $_POST, $_POST['id']);
                $insert_id = $_POST['id'];
                $_SESSION['status'] = '1';
                if (is_dir($tmp_upload_folder))
                {
                    $files = get_files($tmp_upload_folder);
                    if (!empty($files))
                    {
                        if (create_dir(ROOT . 'temp_upload/' . $session_id . '/' . $v))
                        {

                            foreach ($files as $k => $v)
                            {
                                if (@copy(ROOT . 'temp_upload/' . $session_id . '/' . $v, ROOT . 'temp_upload/' . $session_id . '/' . $v))
                                {
                                    @unlink(ROOT . 'temp_upload/' . $session_id . '/' . $v);
                                }
                            }
                        }
                        @unlink(ROOT . 'temp_upload/' . $session_id . '/');
                    }
                }
            }

            //updatejson();
            
        }
        else
        {
            echo '<pre>';
            print_r($errors);
        }

    }

    $images = array();

    if (is_dir($tmp_upload_folder))
    {

        $images = get_files($tmp_upload_folder);

        foreach ($images as $k => $v)
        {

            $images[$k] = 'http://www.four20maps.com/temp_upload/' . $session_id . '/' . $v;

        }

    }
    //echo "rajesh";exit;
    updatejson();

}

function updatejson()
{
    ini_set("display_errors", 1);
    $db = db_connect();
    /*$stores = "SELECT s.*,c.cat_icon,c.cat_name, st.SubscriptionTypeId,
    st.Icon1, st.Icon2, st.Icon3 ,IFNULL(IFNULL(st.OrderId,c.`OrderId`),0) AS OrderId
    FROM stores s 
    LEFT JOIN categories c ON c.id = s.cat_id 
    AND IFNULL(s.`createdby`,0) =0
    LEFT JOIN StoreUserSubscription suc
    ON suc.StoreUserSubscriptionId = s.StoreUserSubscriptionId 
    AND IFNULL(s.`createdby`,0) > 0
    LEFT JOIN SubscriptionTypes st
    ON st.SubscriptionTypeId = suc.SubscriptionId 
    WHERE s.status=1 AND approved=1
    ORDER BY OrderId DESC,s.store_views DESC";*/
    $stores = "SELECT s.*,st.icon1 as cat_icon,sc.SubscriptionCategoryAliasName as cat_name,st.SubscriptionTypeId,
				st.Icon1, st.Icon2, st.Icon3 ,IFNULL(IFNULL(st.OrderId,c.`OrderId`),0) AS OrderId
				FROM stores s 
				LEFT JOIN categories c ON c.id = s.cat_id 
				AND IFNULL(s.`createdby`,0) =0
				LEFT JOIN StoreUserSubscription suc
				ON suc.StoreUserSubscriptionId = s.StoreUserSubscriptionId 
				AND IFNULL(s.`createdby`,0) > 0
				LEFT JOIN SubscriptionTypes st
				ON st.SubscriptionTypeId = s.cat_id 
				LEFT JOIN SubscriptionCategory sc
				ON sc.SubscriptionCategoryId = st.SubscriptionCategoryId
				WHERE s.status=1 AND approved=1
				ORDER BY OrderId DESC,s.store_views DESC";
    $stores = mysql_query($stores);
    $farm_pacakages = "SELECT SubscriptionTypeId FROM `SubscriptionTypes` WHERE `SubscriptionCategoryId`= 4";
    $farm_pacakage_result = mysql_query($farm_pacakages);
    $farm_subscription_type_array = array();
    while ($farm_store = mysql_fetch_assoc($farm_pacakage_result))
    {
        $farm_subscription_type_array[] = $farm_store['SubscriptionTypeId'];
    }
    while ($row_store = mysql_fetch_assoc($stores))
    {
        #var_dump($row_store);die;
        $cat_img = "";
        if ($row_store['cat_id'] > 0)
        {
            // cat img
            $cat_upload_dir = 'http://www.four20maps.com/admin/imgs/categories/' . $row_store['cat_id'] . '/';
            $cat_files = get_files($cat_upload_dir);
            if (is_array($cat_files)) $cat_files = array_values($cat_files);

            if ($cat_files !== false && isset($cat_files[0])) $cat_img = 'http://www.four20maps.com/admin/imgs/categories/' . $row_store['cat_id'] . '/' . $cat_files[0];

        }

        $upload_dir = 'http://www.four20maps.com/admin/imgs/stores/' . $row_store["id"] . '/';
        $files = get_files($upload_dir);
        if (is_array($files)) $files = array_values($files);
        if ($files !== false && isset($files[0]))
        {
            $img = 'http://www.four20maps.com/admin/imgs/stores/' . $row_store['id'] . '/' . $files[0];
        }
        if ($row_store["Icon1"] != '') $img = "http://www.four20maps.com/admin/" . $row_store["Icon1"];
        else $img = "http://www.four20maps.com/admin/" . $row_store["cat_icon"];

        if ((!(substr($row_store["image"], 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://')))
        {
            $simg = 'http://www.four20maps.com/' . $row_store["image"];
        }
        else
        {
            $simg = $row_store["image"];
        }
        if ($row_store["cat_name"] == 'Delivery') $cat_typeImg = $row_store['Icon2'];
        else $cat_typeImg = $row_store['Icon3'];
        if ($cat_typeImg == '') $cat_typeImg = $img;
        $store[] = array(
            "id" => $row_store["id"],
            "name" => $row_store["name"],
            "address" => $row_store["address"],
            "telephone" => $row_store["telephone"],
            "fax" => $row_store["fax"],
            "mobile" => $row_store["mobile"],
            "email" => $row_store["email"],
            "website" => $row_store["website"],
            "description" => $row_store["description"],
			 "timings"=>json_decode($row_store["timings"]),
			"first_time_patients"=>$row_store["first_time_patients"],
			"announcement"=>$row_store["announcement"],
			"about_us"=>$row_store["about_us"],
			"created" => date('F dS, Y', strtotime($row_store["created"])),
            "img" => $img,
            "latitude" => $row_store["latitude"],
            "longitude" => $row_store["longitude"],
            "cat_id" => $row_store["cat_id"],
            "cat_img" => $cat_img,
            "cat_icon" => $img,
            "image" => $simg,
            "cat_name" => $row_store["cat_name"],
            "ctype_icon" => $cat_typeImg,
            "OrderId" => $row_store["OrderId"]
        );
        if (!in_array($row_store["SubscriptionTypeId"], $farm_subscription_type_array))
        {
            $non_register_user_store[] = array(
                "id" => $row_store["id"],
                "name" => $row_store["name"],
                "address" => $row_store["address"],
                "telephone" => $row_store["telephone"],
                "fax" => $row_store["fax"],
                "mobile" => $row_store["mobile"],
                "email" => $row_store["email"],
                "website" => $row_store["website"],
                "description" => $row_store["description"],
			    "timings"=>json_decode($row_store["timings"]),
			"first_time_patients"=>$row_store["first_time_patients"],
			"announcement"=>$row_store["announcement"],
			"about_us"=>$row_store["about_us"],
			"created" => date('F dS, Y', strtotime($row_store["created"])),
                "img" => $img,
                "latitude" => $row_store["latitude"],
                "longitude" => $row_store["longitude"],
                "cat_id" => $row_store["cat_id"],
                "cat_img" => $cat_img,
                "cat_icon" => $img,
                "image" => $simg,
                "cat_name" => $row_store["cat_name"],
                "ctype_icon" => $cat_typeImg,
                "OrderId" => $row_store["OrderId"]
            );
        }

    }
    $json_register_data = json_encode($store);
    $json_non_register_user_store = json_encode($non_register_user_store);
    $non_register_user_storecontent = "var default_stores=" . $json_non_register_user_store . ";";
    $register_user_storecontent = "var default_stores=" . $json_register_data . ";";

    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/js/json_non_register_user_store.js', "w") or die("Unable to open file!");
    fwrite($myfile, $non_register_user_storecontent);
    fclose($myfile);

    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/js/json_register_data.js', "w") or die("Unable to open file!");
    fwrite($myfile, $register_user_storecontent);
    fclose($myfile);
}

?>
