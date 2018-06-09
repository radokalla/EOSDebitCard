<?php
 
// Require the library code
require_once FCPATH . 'Quickbookapi/QuickBooks.php';
class Quickbooks
{
    var $CI;
    var $DB;
    var $token;
    var $oauth_consumer_key;
    var $oauth_consumer_secret;
    var $quickbooks_oauth_url;
    var $quickbooks_success_url;
    var $quickbooks_menu_url;
    var $dsn;
    var $encryption_key;
    var $the_username;
    var $the_tenant;
	var $IntuitAnywhere;
    
    function Quickbooks()
    {
        $this->CI =& get_instance();
        $this->DB = $this->CI->load->database();
		
		$this->CI->load->library('logs');
				
		$this->CI->logs->write_log("INFO", "Quickbook Initialized");
		
		// Your OAuth token (Intuit will give you this when you register an Intuit Anywhere app)
		$this->token = '0e02b658bf53fb4608b9fc0bb653045b6bcb';
		
		// Your OAuth consumer key and secret (Intuit will give you both of these when you register an Intuit app)
		$this->oauth_consumer_key = 'qyprdFR9jN8u1xDUQcVxFgs41AUKfu';
		$this->oauth_consumer_secret = 'dD7whsON4Satwct12CHsDJssgINhjdMprHqS68iy';
		// This is the URL of your OAuth auth handler page
		$this->quickbooks_oauth_url = base_url('Quickbookapi/docs/partner_platform/example_app_ipp_v3/oauth.php');
		
		// This is the URL to forward the user to after they have connected to IPP/IDS via OAuth
		$this->quickbooks_success_url = base_url('Quickbookapi/docs/partner_platform/example_app_ipp_v3/success.php');
		
		// This is the menu URL script 
		$this->quickbooks_menu_url = base_url('Quickbookapi/docs/partner_platform/example_app_ipp_v3/menu.php');
		
		// This is a database connection string that will be used to store the OAuth credentials 
		//$this->dsn = 'mysql://root:@localhost/bayfront';	
		$this->dsn = 'mysql://bayfront_rado:Rado5280@localhost/bayfront_bayfrontdb';		
		// You should set this to an encryption key specific to your app
		$this->encryption_key = 'bcde1234';
		
		// Do not change this unless you really know what you're doing!!!  99% of apps will not require a change to this.
		$this->the_username = 'DO_NOT_CHANGE_ME';
		
		// The tenant that user is accessing within your own app
		$this->the_tenant = 12345;
		
		// Initialize the database tables for storing OAuth information
		if (!QuickBooks_Utilities::initialized($this->dsn))
		{
			// Initialize creates the neccessary database schema for queueing up requests and logging
			QuickBooks_Utilities::initialize($this->dsn);
		}
		
		// 
		$this->IntuitAnywhere = new QuickBooks_IPP_IntuitAnywhere($this->dsn, $this->encryption_key, $this->oauth_consumer_key, $this->oauth_consumer_secret, $this->quickbooks_oauth_url, $this->quickbooks_success_url);
		
		$this->CI->logs->write_log("INFO", "Check : ".$this->IntuitAnywhere->check($this->the_username, $this->the_tenant)." Test : ".$this->IntuitAnywhere->test($this->the_username, $this->the_tenant));
			
		// Are they connected to QuickBooks right now? 
		if ($this->IntuitAnywhere->check($this->the_username, $this->the_tenant) and 
			$this->IntuitAnywhere->test($this->the_username, $this->the_tenant))
		{
			
			$this->CI->logs->write_log("INFO", "Quickbook connected successfully");
		
			// Yes, they are 
			$quickbooks_is_connected = true;
		
			// Set up the IPP instance
			$IPP = new QuickBooks_IPP($this->dsn);
		
			// Get our OAuth credentials from the database
			$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
			// Tell the framework to load some data from the OAuth store
			$IPP->authMode(
				QuickBooks_IPP::AUTHMODE_OAUTH, 
				$this->the_username, 
				$creds);
		
			// Print the credentials we're using
			//print_r($creds);
		
			// This is our current realm
			$realm = $creds['qb_realm'];
		
			// Load the OAuth information from the database
			$Context = $IPP->context();
		
			// Get some company info
			$CompanyInfoService = new QuickBooks_IPP_Service_CompanyInfo();
			$quickbooks_CompanyInfo = $CompanyInfoService->get($Context, $realm);
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Quickbook not connected.");
			// No, they are not
			$quickbooks_is_connected = false;
		}
    }
    
	
	public function addCustomer($NewCustomer)
	{
		return 25;
		
		$this->CI->logs->write_log("INFO", "Entered in Adding customer.");
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($this->dsn);
		
		// Get our OAuth credentials from the database
		$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(
			QuickBooks_IPP::AUTHMODE_OAUTH, 
			$this->the_username, 
			$creds);
		
		// This is our current realm
		$realm = $creds['qb_realm'];
		
		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			// Set the IPP version to v3 
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);
			
			$CustomerService = new QuickBooks_IPP_Service_Customer();
										
			$Customer = new QuickBooks_IPP_Object_Customer();
			$Customer->setTitle('Ms');
			$Customer->setGivenName($NewCustomer['GivenName']);
			$Customer->setMiddleName($NewCustomer['MiddleName']);
			$Customer->setFamilyName($NewCustomer['FamilyName']);
			//$Customer->setDisplayName($NewCustomer['DisplayName']);
			$Customer->setDisplayName($NewCustomer['GivenName'].' '.$NewCustomer['FamilyName']);
		
			// Terms (e.g. Net 30, etc.)
			$Customer->setSalesTermRef(4);
			
			// Phone #
			$PrimaryPhone = new QuickBooks_IPP_Object_PrimaryPhone();
			$PrimaryPhone->setFreeFormNumber($NewCustomer['PrimaryPhone']);
			$Customer->setPrimaryPhone($PrimaryPhone);
		
			// Mobile #
			$Mobile = new QuickBooks_IPP_Object_Mobile();
			$Mobile->setFreeFormNumber($NewCustomer['Mobile']);
			$Customer->setMobile($Mobile);
			
			// Fax #
			/*$Fax = new QuickBooks_IPP_Object_Fax();
			$Fax->setFreeFormNumber('860-532-0089');
			$Customer->setFax($Fax);*/
					
			// Bill address
			$BillAddr = new QuickBooks_IPP_Object_BillAddr();
			$BillAddr->setLine1($NewCustomer['Line1']);
			$BillAddr->setLine2($NewCustomer['Line2']);
			$BillAddr->setCity($NewCustomer['City']);
			$BillAddr->setCountrySubDivisionCode($NewCustomer['CountrySubDivisionCode']);
			$BillAddr->setPostalCode($NewCustomer['PostalCode']);
			$Customer->setBillAddr($BillAddr);
		
			// Email
			$PrimaryEmailAddr = new QuickBooks_IPP_Object_PrimaryEmailAddr();
			$PrimaryEmailAddr->setAddress($NewCustomer['PrimaryEmailAddr']);
			$Customer->setPrimaryEmailAddr($PrimaryEmailAddr);
		
			if ($resp = $CustomerService->add($Context, $realm, $Customer))
			{
				//echo "<pre>"; print_r((int)$resp); exit;
				
				$vowels = array("{", "}", "-");
				$resp = str_replace($vowels, "", $resp);
				$this->CI->logs->write_log("INFO", 'Adding customer : Our new customer ID is: [' . $resp . '] (name "' . $Customer->getDisplayName(). '")');
				
				return $resp;	
				//print('Our new customer ID is: [' . $resp . '] (name "' . $Customer->getDisplayName() . '")');
			}
			else
			{
				$this->CI->logs->write_log("ERROR", "Adding customer : ".$CustomerService->lastError($Context));
				return false;	
				//print($CustomerService->lastError($Context));
			}
								
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Adding customer : Unable to load a context");
			die('Unable to load a context...?');
		}
	}
	
	public function updateCustomer($NewCustomer, $CusRefID)
	{
		return true;
		
		$this->CI->logs->write_log("INFO", "Entered in update customer");
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($this->dsn);
		
		// Get our OAuth credentials from the database
		$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(
			QuickBooks_IPP::AUTHMODE_OAUTH, 
			$this->the_username, 
			$creds);
		
		// This is our current realm
		$realm = $creds['qb_realm'];
		
		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			// Set the IPP version to v3 
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);	
		
			$CustomerService = new QuickBooks_IPP_Service_Customer();
			//echo $CusRefID; exit;
			// Get the existing customer first (you need the latest SyncToken value)
			$customers = $CustomerService->query($Context, $realm, "SELECT * FROM Customer WHERE Id = '".$CusRefID."' ");
			$Customer = $customers[0];
				
			$Customer->setTitle('Ms');
			$Customer->setGivenName($NewCustomer['GivenName']);
			$Customer->setMiddleName($NewCustomer['MiddleName']);
			$Customer->setFamilyName($NewCustomer['FamilyName']);
			//$Customer->setDisplayName($NewCustomer['DisplayName']);			
			
			$Customer->setDisplayName($NewCustomer['GivenName'].' '.$NewCustomer['FamilyName']);
			
			//Terms (e.g. Net 30, etc.)
			$Customer->setSalesTermRef(4);
			
			// Phone #
			$PrimaryPhone = $Customer->getPrimaryPhone();			
			$PrimaryPhone->setFreeFormNumber($NewCustomer['PrimaryPhone']);
			$Customer->setPrimaryPhone($PrimaryPhone);
		
			// Mobile #
			$Mobile = $Customer->getMobile();
			$Mobile->setFreeFormNumber($NewCustomer['Mobile']);
			$Customer->setMobile($Mobile);
					
			// Bill address
			$BillAddr = $Customer->getBillAddr();
			$BillAddr->setLine1($NewCustomer['Line1']);
			$BillAddr->setLine2($NewCustomer['Line2']);
			$BillAddr->setCity($NewCustomer['City']);
			$BillAddr->setCountrySubDivisionCode($NewCustomer['CountrySubDivisionCode']);
			$BillAddr->setPostalCode($NewCustomer['PostalCode']);
			$Customer->setBillAddr($BillAddr);
		
			//Email			
			$PrimaryEmailAddr = $Customer->getPrimaryEmailAddr();
			$PrimaryEmailAddr->setAddress($NewCustomer['PrimaryEmailAddr']);
			
			
	
				
			if ($CustomerService->update($Context, $realm, $Customer->getId(), $Customer))
			{
				$this->CI->logs->write_log("INFO", 'Update customer : Our Old customer ID is: [' . $Customer->getId() . '] (name "' . $NewCustomer['DisplayName']. '")');
				return true;
			}
			else
			{
				$this->CI->logs->write_log("ERROR", "Update customer : ".$CustomerService->lastError($Context));
				return false;
			}
								
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Update customer : Unable to load a context");
			die('Unable to load a context...?');
		}
	}
	
	public function invoiceGeneration($orderProductDetails, $CustomerRefID, $total_price, $deliveryType, $tax_percentage, $tax_amount, $delivery_charge, $is_credit_ard_payment = false)
	{
		return 25;
		
		$AccountRefID = ($is_credit_ard_payment) ? 99 : 54;
		
		$this->CI->logs->write_log("INFO", "Entered in Invoice Generation");
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($this->dsn);
		
		// Get our OAuth credentials from the database
		$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(
			QuickBooks_IPP::AUTHMODE_OAUTH, 
			$this->the_username, 
			$creds);
		
		// Print the credentials we're using
		//print_r($creds);
		
		// This is our current realm
		$realm = $creds['qb_realm'];
		
		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			// Set the IPP version to v3 
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);
			
			$InvoiceService = new QuickBooks_IPP_Service_Invoice();
			
			$Invoice = new QuickBooks_IPP_Object_Invoice();
			
			$DocNumber = 'BFR' . mt_rand(0, 10000000);
			$TxnDate = date('Y-m-d');
			
			$Invoice->setDocNumber($DocNumber);
			$Invoice->setTxnDate($TxnDate);
			
			/////////////////////////////////////////// For tax code
			
			$TaxCodeService = new QuickBooks_IPP_Service_TaxCode();

			$taxcodes = $TaxCodeService->query($Context, $realm, "SELECT * FROM TaxCode  WHERE name = 'Sales Tax'");//WHERE name = 'Sales Tax'
			
			$this_tax_code = "";
			$this_tax_name = "";
			//echo "<pre>"; print_r($taxcodes); exit;
			if($taxcodes)
			{
			foreach ($taxcodes as $TaxCode)
			{
				$this_tax_code = $TaxCode->getId();
				$this_tax_name = $TaxCode->getName();
			}
		}
			$vowels = array("{", "}", "-");
			$this_tax_code = str_replace($vowels, "", $this_tax_code);
			
			///////////////////////////////////////////
			
			foreach($orderProductDetails as $orderProductDetail)
			{
				$Line = new QuickBooks_IPP_Object_Line();
				$Line->setDetailType('SalesItemLineDetail');
				$Line->setTaxable('true');
				$Line->setAmount($orderProductDetail['productPrice'] * $orderProductDetail['quantity']);
				$Line->setDescription('Test description goes here.');
			
				$SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
				$SalesItemLineDetail->setItemRef($orderProductDetail['QBcode']);
				$SalesItemLineDetail->setUnitPrice($orderProductDetail['productPrice']);
				$SalesItemLineDetail->setQty($orderProductDetail['quantity']);
				$SalesItemLineDetail->setTaxCodeRef('TAX');
			
				$Line->addSalesItemLineDetail($SalesItemLineDetail);
			
				$Invoice->addLine($Line);
			}
			
			  /* <TxnTaxDetail>
      <TxnTaxCodeRef>2</TxnTaxCodeRef>
      <TotalTax>13.00</TotalTax>
      <TaxLine>
        <Amount>13.00</Amount>
        <DetailType>TaxLineDetail</DetailType>
        <TaxLineDetail>
          <TaxRateRef>1</TaxRateRef>
          <PercentBased>true</PercentBased>
          <TaxPercent>6.5</TaxPercent>
          <NetAmountTaxable>200.00</NetAmountTaxable>
        </TaxLineDetail>
      </TaxLine>
    </TxnTaxDetail>*/
	
			
			//TxnTaxDetail		
			
			
			//taxes
			$TxnTaxDetail = new QuickBooks_IPP_Object_TxnTaxDetail();
			$TxnTaxDetail->setTxnTaxCodeRef($this_tax_code);
			$TxnTaxDetail->setTotalTax($tax_amount);
			
			$TaxLine = new QuickBooks_IPP_Object_TaxLine();
			 
			$TaxLine->setAmount($tax_amount);
			$TaxLine->setDetailType('TaxLineDetail');
		
			$TaxLineDetail = new QuickBooks_IPP_Object_TaxLineDetail();
			$TaxLineDetail->setTaxRateRef($this_tax_code);
			$TaxLineDetail->setPerCentBased('true');
			$TaxLineDetail->setTaxPercent($tax_percentage);
			$TaxLineDetail->setNetAmountTaxable($total_price-$tax_amount-$delivery_charge);
		
			$TaxLine->addTaxLineDetail($TaxLineDetail);
			$TxnTaxDetail->addTaxLine($TaxLine);
			$Invoice->addTxnTaxDetail($TxnTaxDetail);
				
				
			if($deliveryType == 'delivery')
			{
				$Line = new QuickBooks_IPP_Object_Line();
				$Line->setDetailType('SalesItemLineDetail');
				$Line->setTaxable('false');
				$Line->setAmount($delivery_charge * 1);
				$Line->setDescription('Test description goes here.');
			
				$SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
				$SalesItemLineDetail->setItemRef(20);
				$SalesItemLineDetail->setUnitPrice($delivery_charge);
				$SalesItemLineDetail->setQty(1);
			
				$Line->addSalesItemLineDetail($SalesItemLineDetail);			
				$Invoice->addLine($Line);
			}
		
			$Invoice->setCustomerRef($CustomerRefID);
			
			//echo "<pre>"; print_r($Invoice); exit;
			
			if ($resp = $InvoiceService->add($Context, $realm, $Invoice))
			{
				$this->CI->logs->write_log("INFO", 'Our new Invoice ID is: [' . $resp . ']');
				
				if($this->receivePayments($DocNumber, $TxnDate,  $CustomerRefID, $total_price, $resp, $AccountRefID))
				{
					$vowels = array("{", "}", "-");
					$resp = str_replace($vowels, "", $resp);			
					return $resp;
				}
				else
					return false;
				//return $resp;
				//print('Our new Invoice ID is: [' . $resp . ']');
			}
			else
			{
				$this->CI->logs->write_log("ERROR", "Invoice Generation : ".$InvoiceService->lastError());
				//print($InvoiceService->lastError()); exit;
				return false;
			}
		
			/*print('<br><br><br><br>');
			print("\n\n\n\n\n\n\n\n");
			print('Request [' . $IPP->lastRequest() . ']');
			print("\n\n\n\n");
			print('Response [' . $IPP->lastResponse() . ']');
			print("\n\n\n\n\n\n\n\n\n");*/
			
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Invoice Generation : Unable to load a context");
			die('Unable to load a context...?');
		}
	}
	
    public function receivePayments($DocNumber, $TxnDate,  $CustomerRefID, $total_price, $resp, $AccountRefID = 54)
	{
		return true;
		
		$this->CI->logs->write_log("INFO", "Entered in Receive Payments");
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($this->dsn);
		
		// Get our OAuth credentials from the database
		$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(
			QuickBooks_IPP::AUTHMODE_OAUTH, 
			$this->the_username, 
			$creds);
		
		// Print the credentials we're using
		//print_r($creds);
		
		// This is our current realm
		$realm = $creds['qb_realm'];
		
		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			// Set the IPP version to v3 
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);
			
			$PaymentService = new QuickBooks_IPP_Service_Payment();
			
			// Create payment object
			$Payment = new QuickBooks_IPP_Object_Payment();
			
			$Payment->setPaymentRefNum($DocNumber);
			$Payment->setTxnDate($TxnDate);
			$Payment->setTotalAmt($total_price);
			
			//$payment->setDepositToAccountId ='';
			//$payment->setDepositToAccountName='';
			// Create line for payment (this details what it's applied to)
			$Line = new QuickBooks_IPP_Object_Line();
			$Line->setAmount($total_price);
			
			// The line has a LinkedTxn node which links to the actual invoice
			$LinkedTxn = new QuickBooks_IPP_Object_LinkedTxn();
			$LinkedTxn->setTxnId($resp);
			$LinkedTxn->setTxnType('Invoice');
		
			$Line->setLinkedTxn($LinkedTxn);
		
			$Payment->addLine($Line);
		
			$Payment->setCustomerRef($CustomerRefID);
				
			$Payment->setDepositToAccountRef($AccountRefID);
		 
		
			// Send payment to QBO 
			if ($resp = $PaymentService->add($Context, $realm, $Payment))
			{
				$this->CI->logs->write_log("INFO", 'Our new Payment ID is: [' . $resp . ']');
				return $resp;
			}
			else
			{
				$this->CI->logs->write_log("ERROR", "Receive Payments : ".$PaymentService->lastError());
				return false;
			}
				
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Receive Payments : Unable to load a context");
			die('Unable to load a context...?');
		}
	}
	
	public function getItemCode($Name)
	{
		return 25;
		
		$this->CI->logs->write_log("INFO", "Entered in Get Item Code");
		
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($this->dsn);
		
		// Get our OAuth credentials from the database
		$creds = $this->IntuitAnywhere->load($this->the_username, $this->the_tenant);
		
		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(
			QuickBooks_IPP::AUTHMODE_OAUTH, 
			$this->the_username, 
			$creds);
		
		// Print the credentials we're using
		//print_r($creds);
		
		// This is our current realm
		$realm = $creds['qb_realm'];
		
		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);
	
			$ItemService = new QuickBooks_IPP_Service_Term();
			$items = $ItemService->query($Context, $realm, "SELECT * FROM Item WHERE Name='".$Name."' ");
			$rerutnItems = array();
			foreach ($items as $Item)
			{
				$vowels = array("{", "}", "-");
				$resp = str_replace($vowels, "", $Item->getId());
				$rerutnItems[$resp] = $Item->getName();
				$this->CI->logs->write_log("INFO", 'Get Item Code : Our Item ID is: [' . $resp . '] (name "' . $Item->getName(). '")');
			}
			return $rerutnItems;				
		}
		else
		{
			$this->CI->logs->write_log("ERROR", "Get Item Code : Unable to load a context");
			die('Unable to load a context...?');
		}
	}
	
	
	
}
/* End of file Session.php */
/* Location: application/libraries */ 
?> 