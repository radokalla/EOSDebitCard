<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'controllers/main.php');

class Myaccount extends Main {



	public function __construct()

	{

		parent::__construct();

		//$this->output->clear_page_cache();

		$this->checkPatientAuthentication();

	}

	

	public function index()

	{

		$this->load->model('patient_model');

		$this->data['patientDetails'] = $this->patient_model->getPatientDetails();

		

		if($this->input->post())

		{

			$post = $this->input->post();

			try

			{

				$Customer = array(	'GivenName' => $post['firstName'],

									'MiddleName' => '',

									'FamilyName' => $post['lastName'],

									'DisplayName' => $post['userName'],

									'PrimaryPhone' => $post['phone'],

									'Mobile' => $post['phone'],

									'Line1' => $post['address1'],

									'Line2' => $post['address2'],

									'City' => $post['city'].' '.$post['state'],

									'CountrySubDivisionCode' => $post['country'],

									'PostalCode' => $post['zip'],

									'PrimaryEmailAddr' => $post['email'],

								 );

				

				$this->load->library('quickbooks');

				//echo $this->data['patientDetails']['QBCodeID']."<pre>"; print_r($Customer); exit;

				$customer_details = $this->quickbooks->updateCustomer($Customer, $this->data['patientDetails']['QBCodeID']);

				

				if($customer_details)

				{

					$patientDetails = $this->patient_model->updatePatientDetails($post);

					if($patientDetails)

						$this->data['profileSuccess'] = "Updated Successfully";

					else

						$this->data['profileError'] = "Problem with updating profile. Please try again.";

				}

				else

				{

					$this->data['profileError'] = "Problem with updating profile. Please try again.";

				}

				

			}

			catch (Exception $e)

			{

				echo "Caught exception: Exception in updating Patient\n";

			}

		}

		$this->data['patientDetails'] = $this->patient_model->getPatientDetails();

		$this->data['patientCreditCardDetails'] = $this->patient_model->getPatientCreditCardDetails();

		$this->load->view('frontend/includes/header', $this->data);

		$this->load->view('frontend/myaccount/index', $this->data);

		$this->load->view('frontend/includes/footer', $this->data);

	}

	

	public function uploadImage()

	{

		//

		$path = "uploaded/frontendMyaccount/";

		$allowTypes = array('xls','xlsx','pdf','csv', "bmp");

		$valid_formats = array("jpg", "png", "gif","jpeg","ico");

		/*

		list($id,$fieldname)=explode("_",$_REQUEST['page']);

		$str="";$imgpath = '';$error = "";$msg = "";

		

		$fileid		   = $_REQUEST['name'];

		$filename      = str_replace('-','_',$_REQUEST['name']);

		$arr		   = explode('-',$fileid);	

		$imgsetpath	   = $arr[0].'-'.$arr[1].'-imgPath';

			

		$successmssgid = $fileid.'-success-mssg';

		$errormssgid   = $fileid."-error-mssg";

		*/

		$error="";

		$filename      = str_replace('-','_',$_REQUEST['name']);



		if(!empty($_FILES[$filename]['error']))

		{

			switch($_FILES[$filename]['error'])

			{

		

				case '1':

					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';

					break;

				case '2':

					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';

					break;

				case '3':

					$error = 'The uploaded file was only partially uploaded';

					break;

				case '4':

					$error = 'No file was uploaded.';

					break;

		

				case '6':

					$error = 'Missing a temporary folder';

					break;

				case '7':

					$error =  'Failed to write file to disk';

					break;

				case '8':

					$error = 'File upload stopped by extension';

					break;

				case '999':

				default:

					$error = 'No error code avaiable';

			}

		}elseif(empty($_FILES[$filename]['tmp_name']) || $_FILES[$filename]['tmp_name'] == 'none')

		{

			$error ='No file was uploaded.'.implode(',',$_FILES[$filename]);

		}else 

		{

			$message = '';

			$extension 	 =	 end(explode(".", $_FILES[$filename]["name"]));

			

				if(getimagesize($_FILES[$filename]['tmp_name'])){

			

					$imgname 	 =	 'bayfront-image-'.time();	

						

					$img_path	 =	 $path.$imgname.'.'.$extension;

					$org_img_path =  $path.'original/'.$imgname.'.'.$extension;

					$temp_path	 = 	 $_FILES[$filename]['tmp_name'];

					

					$msg = 'Image Path:'.$img_path;



					require_once("SimpleImage.class.php");

					//code for resizing image	

					$image = new SimpleImage();

					//echo $org_img_path; exit;

					$image->load($temp_path);

					$image->save($org_img_path);

					

					/*$image->crop(210,210);

					$image->save($img_path);*/

					

					$image->save($img_path);

						$x ='0';  $height_sts = false;

						$y = '0'; $width_sts  = false;

						

						$imgwidth = 227; $imgheight = 243;

						

						$upImgWidth=$image->getWidth(); $upImgHeight=$image->getHeight();

						//Thumbnail Image

						if(($upImgWidth  > $imgwidth) || ($upImgHeight > $imgheight)){

							if($upImgWidth  > $imgwidth)	{

								$image->resizeToWidth($imgwidth);

								$image->save($img_path);	

								$image->load($img_path);

							} 

							

							$upImgHeight = $image->getHeight();

							if($upImgHeight > $imgheight)	{

								$image->resizeToHeight($imgheight);	

								$image->save($img_path);

							}

							$image->load($img_path);

							$newheight = $image->getHeight(); $newwidth = $image->getWidth();

							$hdiff = $imgheight - $newheight; $wdiff = $imgwidth - $newwidth;

							/*

							echo '<script language="JavaScript" type="text/javascript">'."\n";	

							echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.' New Height:'.$newheight.'**** New Width:'.$newwidth.'");';

							echo "\n".'</script>';

							*/

						}else{

							$image->save($img_path);

							$hdiff = $imgheight-$upImgHeight; $wdiff = $imgwidth-$upImgWidth;

						}

						/*

						echo '<script language="JavaScript" type="text/javascript">'."\n";	

						echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.'");';

						echo "\n".'</script>';

						*/

						$y= $hdiff>0 ? round($hdiff/2) : 0; $x= $wdiff>0 ? round($wdiff/2) : 0;

						$style = 'margin-left : '.$x.'px; margin-top :  '.$y.'px';

					

					//Thumbnail Image

					//$image->resize(150,150);

//			

//					$image->save($thumb_path);

				}else{

					$error = 'Please upload a valid image';

				}

			

		}	

		$result=array('error'=>$error,'img_path'=>$img_path);

		echo json_encode($result);

		/*

		echo '<script language="JavaScript" type="text/javascript">'."\n";

		echo 'var parDoc = window.parent.document;';

		if(!empty($error)){

			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '".$error."';";

		}else{

			

			echo "parDoc.getElementById('".$imgsetpath."').value = '".$img_path."';";

			

			echo  "parDoc.getElementById('".$imgsetpath."_img').src = '".base_url($img_path)."';";

			

			echo  "parDoc.getElementById('".$imgsetpath."_img').setAttribute('style', '".$style."');";

			

			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '';";

			

			if($_REQUEST['filetype']=='file'){

				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = 'File uploaded successfully';";

			}else{

				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = '<img src=\"".base_url($img_path)."\" width=\"50\" height=\"50\">';";

			}

		}

		echo "\n".'</script>';

		*/

		exit(); // do not go futher

	}



}



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */