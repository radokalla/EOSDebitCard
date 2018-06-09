<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'controllers/main.php');

class Checkout extends Main {



	public function __construct()

	{

		parent::__construct();

		//$this->output->clear_page_cache();

$this->qb_offline=$this->data['admin_settings']['qb_offline'];
$this->data['qb_offline']=$this->data['admin_settings']['qb_offline'];

	}

	

	public function index()

	{

		$this->load->model('patient_model');

		$this->data['patientDetails'] = $this->patient_model->getPatientDetails();

		

		$this->load->view('frontend/includes/header', $this->data);

		$this->load->view('frontend/checkout/index', $this->data);

		$this->load->view('frontend/includes/footer', $this->data);

	}

	

	public function checkCreditCardDetails()

	{

		if($this->isAjaxRequest())

		{

			$this->load->helper('form');

			if($this->input->post())

			{
				$this->load->library('logs');
				
				$this->logs->write_log("INFO", "Checking Creditcard details");
				
				require_once FCPATH . 'Quickbookapi/QuickBooks.php';

				$post = $this->input->post();	

				$dsn = null;
				
				//Testing
				//$application_login = 'bayfrontstaging.bayfrontorganics.com';
				//$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
				
				//Live
				//$application_login = 'bayfrontorganics.bayfrontorganics.com';
				//$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';
				
				//$application_login = 'cali-oil-qbms-production.www.cali-oil.com';
				//$connection_ticket = 'SDK-TGT-64-IS7KVbNbZuCIpL2W1XaVpw';
				
				$application_login = $this->application_login;
				$connection_ticket = $this->connection_ticket;
				

				$path_to_private_key_and_certificate = null;

				

				$MS = new QuickBooks_MerchantService(

					$dsn, 

					$path_to_private_key_and_certificate, 

					$application_login,

					$connection_ticket);
				//Testing
				$MS->useTestEnvironment(true);
				
				//Live
				//$MS->useTestEnvironment(false);

				$MS->useDebugMode(false);

				$this->logs->write_log("INFO", "Merchant Service connection successful.");

				$name = $post['nameOnCard'];

				$number = $post['cardNumber'];

				$expyear = $post['expiry_year'];

				$expmonth = $post['expiry_month'];

				$address = '';

				$postalcode = '';

				$cvv = $post['CVVCode'];

				
				// Create the CreditCard object

				$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);

				

				// We're going to authorize $295.00

				$total_price = 0;

				foreach($this->data['session']['cart'] as $cart_key => $product)

				{

					$cart_item_ids = explode(":",$cart_key);	

					$orderProductDetails[] = array("categotyID" => $cart_item_ids[0], "subCategotyID" => $cart_item_ids[1], "productID" => $cart_item_ids[2], "subCategotyName" => $product['categotyName'], "productName" => $product['productName'], "QBcode" => $product['QBcode'], "quantity" => $product['quantity'], "productPrice" => $product['productPrice']);

					$total_price += ($product['quantity']*$product['productPrice']);

				}

				

				$tax_amount=$total_price*($this->data['tax_percentage']/100);

				$total_price += $tax_amount;

							

				/*if ($Transaction = $MS->authorize($Card, $total_price))

				{*/
					

				$this->logs->write_log("INFO", "Credit card authorization successful.");
				
					$creditdata = array('creditData' => array('nameOnCard' => $post['nameOnCard'], 'cardNumber' => $post['cardNumber'], 'expiry_year' => $post['expiry_year'], 'expiry_month' => $post['expiry_month'], 'CVVCode' => $post['CVVCode'], 'authorized' => true, 'saveDetails' => isset($post['save_details']) ? true : false));

					

					$this->session->set_userdata($creditdata);

					$return_arr = array('success' => true, 'message' => 'Card authorized!');

				/*}

				else

				{
					$this->logs->write_log("ERROR", "Credit card authorization : ".$MS->errorMessage());

					$return_arr = array('success' => false, 'message' => $MS->errorMessage());

				}*/

			}

			else

				$return_arr = array('success' => false, 'message' => 'Not Authorized');

		}

		else

			$return_arr = array('success' => false, 'message' => 'Check the url');

		

		echo json_encode($return_arr);

	}

	

	public function addorder($deliveryType = 'delivery', $paymentType = 'cash')

	{

		$this->load->model('orders_model');

		$this->load->model('patient_model');
		
		
		$this->load->library('quickbooks');

		$this->load->library('logs');

		if(!isset($this->data['session']['PATIENT_ID']) || empty($this->data['session']['PATIENT_ID']))

		{

			echo $error = "Please login.";

		}

		else if(!isset($this->data['session']['cart']) || (count($this->data['session']['cart']) <= 0))

		{

			echo $error = "No items in cart.";

		}

		else

		{

			$total_price = 0;

			foreach($this->data['session']['cart'] as $cart_key => $product)

			{

				$cart_item_ids = explode(":",$cart_key);	

				$orderProductDetails[] = array("categotyID" => $cart_item_ids[0], "subCategotyID" => $cart_item_ids[1], "productID" => $cart_item_ids[2], "subCategotyName" => $product['categotyName'], "productName" => $product['productName'], "QBcode" => $product['QBcode'], "quantity" => $product['quantity'], "productPrice" => $product['productPrice']);

				$total_price += ($product['quantity']*$product['productPrice']);

			}

			$deliveryType = ($deliveryType != 'pickup' ) ?  'delivery' : 'pickup';

			$paymentType = ($paymentType != 'cash' ) ?  'careditcard' : 'cash';

			

			$tax_amount=$total_price*($this->data['tax_percentage']/100);

			$total_price += $tax_amount;

			$delivery_charge = 0;

			if($deliveryType != 'pickup' )

				 $delivery_charge = $this->data['delivery_charge'];

			$total_price += $delivery_charge;

			

			////////////////// Checking creditcard detials //////////////////////////////////////////

			

			$this->data['patientDetails'] = $this->patient_model->getPatientDetails();

		

			if($paymentType == 'cash')

			{

				$this->logs->write_log("INFO", "Payment Type : Cash");
		$order_details = true;
                if($this->qb_offline)
			    {
				$order_details = $this->quickbooks->invoiceGeneration($orderProductDetails, $this->data['patientDetails']['QBCodeID'], $total_price, $deliveryType, $this->data['tax_percentage'], $tax_amount, $delivery_charge, false);
				}

				

				if($order_details)

				{

					$this->orders_model->addOrder($orderProductDetails, $this->data['session']['PATIENT_ID'], $total_price, $deliveryType, $TransactionDetails['CreditCardTransID'], $this->data['tax_percentage'], $tax_amount, 'patient', $this->data['session']['PATIENT_ID'], $paymentType, $delivery_charge, $order_details);					

					$this->emptycart();

					redirect(base_url('index.php/main/thankyou')); exit;

				}

				else

				{
					$this->logs->write_log("Error", "Cash : Problem in adding order.");

					redirect(base_url('index.php/main/thankyou/Problem in adding order. Please try again')); exit;

				}

			}

			else

			{

				$this->logs->write_log("INFO", "Payment Type : Creditcard");
				
				require_once FCPATH . 'Quickbookapi/QuickBooks.php';

				$post = $this->input->post();	

				$dsn = null;

				//Testing
				$application_login = 'bayfrontstaging.bayfrontorganics.com';
				$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
				
				//Live
				//$application_login = 'bayfrontorganics.bayfrontorganics.com';
				//$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';

				$path_to_private_key_and_certificate = null;

				

				$MS = new QuickBooks_MerchantService(

					$dsn, 

					$path_to_private_key_and_certificate, 

					$application_login,

					$connection_ticket);
					
				//Testing
				$MS->useTestEnvironment(true);				
				
				//Live
				//$MS->useTestEnvironment(false);

				$MS->useDebugMode(false);

				
				$this->logs->write_log("INFO", "Merchant service initialized.");
				

				$name = $this->data['session']['creditData']['nameOnCard'];

				$number = $this->data['session']['creditData']['cardNumber'];

				$expyear = $this->data['session']['creditData']['expiry_year'];

				$expmonth = $this->data['session']['creditData']['expiry_month'];

				$address = '';

				$postalcode = '';

				$cvv = $this->data['session']['creditData']['CVVCode'];

				$creditCardDetails = array('name' => $name, 'number' => $number, 'expyear' => $expyear, 'expmonth' => $expmonth, 'address' => $address, 'postalcode' => $postalcode, 'cvv' => $cvv);
				// Create the CreditCard object

				$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);

				

				// To authorize 				

				/*if ($Transaction = $MS->authorize($Card, $total_price))

				{*/
					$this->logs->write_log("INFO", "Credit card authorization completed.");
					// To charge 

					if ($Transaction = $MS->charge($Card, $total_price))

					{
						$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);
						
						$TransactionDetails = $Transaction->toArray();					

						if($TransactionDetails['PaymentStatus'] == 'Completed')

						{$order_details = true;
                             if($this->qb_offline)
							{
							$order_details = $this->quickbooks->invoiceGeneration($orderProductDetails, $this->data['patientDetails']['QBCodeID'], $total_price, $deliveryType, $this->data['tax_percentage'], $tax_amount, $delivery_charge, true);
							}

				

							if($order_details)

							{

								$this->orders_model->addOrder($orderProductDetails, $this->data['session']['PATIENT_ID'], $total_price, $deliveryType, $TransactionDetails['CreditCardTransID'], $this->data['tax_percentage'], $tax_amount, 'patient', $this->data['session']['PATIENT_ID'], $paymentType, $delivery_charge, $order_details);	

							

								if($this->data['session']['creditData']['saveDetails'])

									$this->patient_model->addPatientCreditCardDetails($this->data['session']['PATIENT_ID'], $this->data['session']['creditData']);

									

								$this->emptycart();

								redirect(base_url('index.php/main/thankyou')); exit;

							}

							else

							{
								$this->logs->write_log("Error", "Creditcard : Problem in adding order.");
								
								redirect(base_url('index.php/main/thankyou/ordererror')); exit;

							}

						}

						

					}

					else

					{
						$this->logs->write_log("Error", "Charge  : ".$MS->errorMessage());

						redirect(base_url('index.php/main/thankyou/'.$MS->errorMessage())); exit;

					}

					

				/*}

				else

				{
					$this->logs->write_log("Error", "Authorization error : ".$MS->errorMessage());

					redirect(base_url('index.php/main/thankyou/'.$MS->errorMessage())); exit;

				}*/

			////////////////// Checking creditcard detials //////////////////////////////////////////

			}

		}	

	}

	

	/*public function  testcheckout()

	{

		ini_set("display_errors", 1);

		

		require_once FCPATH . 'Quickbookapi/QuickBooks.php';

		$post = $this->input->post();	

		$dsn = null;

		$application_login = 'bayfrontstaging.bayfrontorganics.com';

		$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';

		$path_to_private_key_and_certificate = null;

		

		$MS = new QuickBooks_MerchantService(

			$dsn, 

			$path_to_private_key_and_certificate, 

			$application_login,

			$connection_ticket);

			

		$MS->useTestEnvironment(true);

		$MS->useDebugMode(false);

		

		//echo "<pre>"; print_r($MS); exit;

		

		$name = 'Keith Palmer';

		$number = '5105105105105100';

		$expyear = date('Y');

		$expmonth = date('m');

		$address = '56 Cowles Road';

		$postalcode = '06279';

		$cvv = null;

		

		// Create the CreditCard object

		$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);

		echo "<pre>"; print_r($Card); //exit;

		// We're going to authorize $295.00

		$amount = 295.0;

		$Transaction = $MS->authorize($Card, $amount);

		echo "*******************<pre>";	print_r($Transaction);	

			

		if ($Transaction = $MS->authorize($Card, $amount))

		{

			print('Card authorized!' . "\n");

			print_r($Transaction);	

		}

	else

	{

	 print('An error occured during authorization: ' . $MS->errorNumber() . ': ' . $MS->errorMessage() . "\n");

	}

		exit;

	}*/

}

/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */