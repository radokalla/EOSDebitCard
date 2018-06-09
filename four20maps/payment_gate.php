<?php
	//$this->logs->write_log("INFO", "Payment Type : Creditcard");
     session_start();  
                require_once 'Quickbookapi/QuickBooks.php';
                $post = $_POST;    
                $dsn = null;
                //Testing
                //$application_login = 'bayfrontstaging.bayfrontorganics.com';
              //  $connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
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
                $total_price = $_SESSION['session']['total_price'];
                $name = $_SESSION['session']['nameOnCard'];
                $number = $_SESSION['session']['cardNumber'];
                $expyear = $_SESSION['session']['expiry_year'];
                $expmonth = $_SESSION['session']['expiry_month'];
                $address = '';
                $postalcode = '';
                $cvv = $_SESSION['session']['CVVCode'];
                $creditCardDetails = array('name' => $name, 'number' => $number, 'expyear' => $expyear, 'expmonth' => $expmonth, 'address' => $address, 'postalcode' => $postalcode, 'cvv' => $cvv);
                // Create the CreditCard object
                $Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
                echo "Please Don't refresh the page..";
                // To authorize                 
            /*    if ($Transaction = $MS->authorize($Card, $total_price))
                {
					echo "Credit card authorization completed.";*/
                    //$this->logs->write_log("INFO", "Credit card authorization completed.");
                    // To charge 
                    if ($Transaction = $MS->charge($Card, $total_price))
                    {
						echo "Successfull charge for Creditcard : ".$total_price;
                        //$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);
                        
                        $TransactionDetails = $Transaction->toArray();
						if($TransactionDetails['PaymentStatus'] == 'Completed')
                        {
							include_once './includes/config.inc.php';
							$username = $_SESSION['regSuccess'];
							$user_result = mysql_query("select UserId from StoreUsers where Username='$username'")or die(mysql_error());
							$user_row = mysql_fetch_array($user_result);
							$UserId = $user_row['UserId'];
							$Username = $_SESSION['StoreID'];
							$SubscriptionType = $_SESSION['session']['subs_type'];
							$AmountPaid = $total_price;
							$ClientTransID = $TransactionDetails['ClientTransID'];
							$CreditCardTransID = $TransactionDetails['CreditCardTransID'];
							$TxnAuthorizationTime = $TransactionDetails['CreditCardTxnInfo_CreditCardTxnResultInfo_TxnAuthorizationTime'];
							$User = $_SESSION['StoreID'];
							$return =  mysql_query("insert into Transactions (UserId, Username, SubscriptionTypeId, AmountPaid, ClientTransID, CreditCardTransID, TxnAuthorizationTime) VALUES('$User','$username','$SubscriptionType','$AmountPaid','$ClientTransID','$CreditCardTransID','$TxnAuthorizationTime')")or die(mysql_error());
							//FlashMessage::add('Transaction Successful..');
							$_SESSION["NOPAYMENT"] = "0";
							$date = date('Y-m-d H:i:s');
							mysql_query("UPDATE StoreUsers SET Payment='1' where UserId='$UserId'")or die(mysql_error());
							$det = mysql_query("select Duration from SubscriptionTypes where SubscriptionTypeId='$SubscriptionType'")or die(mysql_error());
							$duration = mysql_fetch_array($det);
							$end_date = date('Y-m-d', strtotime("+".$duration['Duration']));
							$User = $_SESSION['StoreID'];
							mysql_query("insert into StoreUserSubscription (SubscriptionId,UserId,CreatedDate,EndDate)VALUES('$SubscriptionType','$User','$date','$end_date')")or die(mysql_error());
							$_SESSION["SubscriptionType"] = $SubscriptionType;
							$_SESSION["CreditCardTransID"] = $CreditCardTransID;
							$_SESSION["TxnAuthorizationTime"] = $TxnAuthorizationTime;
							$_SESSION["price"] = $total_price;
							$_SESSION["Cardno"] = $TransactionDetails['CreditCardTxnInfo_CreditCardTxnInputInfo_CreditCardNumber'];
							$_SESSION["Status"] = $TransactionDetails['PaymentStatus'];
							?>
							<script>
								window.location.href = "dashboard.php";
							</script>
							<?php 
                        }
                        
                    }
                    else
                    {
					//	echo $MS->errorMessage();
					//	header('Location:addstore.php');
                       // $this->logs->write_log("Error", "Charge : ".$MS->errorMessage());
                        //redirect(base_url('index.php/main/thankyou/'.$MS->errorMessage())); exit;
						echo $MS->errorMessage();
					$_SESSION['CardError'] = 'Invalid Card Details';
					?>
					<script>
						window.location.href = "payment.php?type=<?php echo $_SESSION['session']['subs_type'];  ?>";
					</script>
						
                  <?php  }
                    
              /*  }
                else
				{
					echo $MS->errorMessage();
					$_SESSION['CardError'] = 'Invalid Card Details';
					?>
					<script>
						window.location.href = "payment.php?type=<?php echo $_SESSION['session']['subs_type'];  ?>";
					</script>
					<?php 
                    
                }*/
 ?>