<?php
	//$this->logs->write_log("INFO", "Payment Type : Creditcard");
     session_start();  
                require_once 'Quickbookapi/QuickBooks.php';
                $post = $_POST;    
                $dsn = null;
                //Testing
               // $application_login = 'bayfrontstaging.bayfrontorganics.com';
                //$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
				$application_login = 'vendorqbms.www.cali-oil.com';
				$connection_ticket = 'SDK-TGT-1-kJybhGiiZGc5Z3Y0qOrx0Q';	
                
                $path_to_private_key_and_certificate = null;
                
                $MS = new QuickBooks_MerchantService(
                    $dsn, 
                    $path_to_private_key_and_certificate, 
                    $application_login,
                    $connection_ticket);
                    
                //Testing
                //$MS->useTestEnvironment(true);                
                
                //Live
                $MS->useTestEnvironment(false);
               $MS->useDebugMode(false);
                
                //$this->logs->write_log("INFO", "Merchant service initialized.");
				$_SESSION['time'] = time();
                $total_price = $_POST['total_price']*$_POST['qty'];
                $name = $_POST['nameOnCard'];
                $number = $_POST['cardNumber'];
                $expyear = $_POST['expiry_year'];
                $expmonth = $_POST['expiry_month'];
                $address = '';
                $postalcode = '';
                $cvv = $_SESSION['session']['CVVCode'];
                $creditCardDetails = array('name' => $name, 'number' => $number, 'expyear' => $expyear, 'expmonth' => $expmonth, 'address' => $address, 'postalcode' => $postalcode, 'cvv' => $cvv);
                // Create the CreditCard object
                $Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
                     // To authorize                 
            /*    if ($Transaction = $MS->authorize($Card, $total_price))
                {
					echo "Credit card authorization completed.";*/
                    //$this->logs->write_log("INFO", "Credit card authorization completed.");
                    // To charge 
                    if ($Transaction = $MS->charge($Card, $total_price))
                    {
						 
                        //$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);
                        
                        $TransactionDetails = $Transaction->toArray();
						if($TransactionDetails['PaymentStatus'] == 'Completed')
                        {
							include_once './includes/config.inc.php';
							$userId = $_SESSION['StoreID'];
							$ClientTransID = $TransactionDetails['ClientTransID'];
							$CreditCardTransID = $TransactionDetails['CreditCardTransID'];
							$TxnAuthorizationTime = $TransactionDetails['CreditCardTxnInfo_CreditCardTxnResultInfo_TxnAuthorizationTime']; 
							$owner_id=$_POST['owner_id'];
							$category_id=$_POST['category_id'];
							$product_id=$_POST['product_id'];
							$store_id=$_POST['store_id'];
							$qty=$_POST['qty'];;
							mysql_query("INSERT INTO `order_header`(`user_id`, `total_amount`, `CreditCardTransID`) VALUES ('".$userId."','".$total_price."','".$CreditCardTransID."')");
							$order_id=mysql_insert_id();				
							
							 mysql_query("INSERT INTO `order_detail`( `order_id`, `user_id`, `owner_id`, `category_id`, `product_id`, `store_id`, `price`,qty) VALUES ('".$order_id."','".$userId."','".$owner_id."','".$category_id."','".$product_id."','".$store_id."','".$total_price."','".$qty."')");
							$return_arr = array('success' => true, 'message' => 'Card authorized!');
							echo json_encode($return_arr);
							 
                        }
                        
                    }
                    else
                    {
						$return_arr = array('success' => false, 'message' => $MS->errorMessage());
							echo json_encode($return_arr);
					 
				    }
                    
              
 ?>