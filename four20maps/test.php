<?php
	//$this->logs->write_log("INFO", "Payment Type : Creditcard");
     session_start();  
                require_once 'Quickbookapi/QuickBooks.php';
                $post = $_POST;    
                $dsn = null;
                //Testing
             //   $application_login = 'bayfrontstaging.bayfrontorganics.com';
                // $connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
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
               // $MS->useDebugMode(false);
                
               
// If you want to see the full XML input/output, you can turn on debug mode
$MS->useDebugMode(true);

$routing = '122000661';
$account = '000507206962';

$info = QuickBooks_MerchantService_CheckingAccount::INFO_PERSONAL;
$type = QuickBooks_MerchantService_CheckingAccount::TYPE_CHECKING;

$first_name = 'rajesh';
$last_name = 'm';

$phone = '+1 (860) 634-1602';

$Check = new QuickBooks_MerchantService_CheckingAccount(
	$routing, 
	$account, 
	$info, 
	$type, 
	$first_name, 
	$last_name, 
	$phone);
echo "<pre>";
// We're going to transfer $295 out of their checking account
$amount = 2.0;

if ($Transaction = $MS->debitCheck($Check, $amount, QuickBooks_MerchantService::MODE_INTERNET))
{
	
	print_r($Transaction);
}
else
{
	print('An error occured during refund: ' . $MS->errorNumber() . ': ' . $MS->errorMessage() . "\n");
}
 ?>