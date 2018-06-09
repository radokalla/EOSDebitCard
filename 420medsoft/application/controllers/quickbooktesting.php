<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(APPPATH.'controllers/main.php');

class Quickbooktesting extends Main {
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	/*public function index()
	{
		require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
		require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
		require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
		require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
		
		//Specify QBO or QBD
		$serviceType = IntuitServicesType::QBO;
		
		// Get App Config
		$realmId = ConfigurationManager::AppSettings('RealmID');
		if (!$realmId)
			exit("Please add realm to App.Config before running this sample.\n");
		//exit("COnfigurations is Okay.\n");
			
		// Prep Service Context
		$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'),
													  ConfigurationManager::AppSettings('AccessTokenSecret'),
													  ConfigurationManager::AppSettings('ConsumerKey'),
													  ConfigurationManager::AppSettings('ConsumerSecret'));
		$serviceContext = new ServiceContext($realmId, $serviceType, $requestValidator);
		if (!$serviceContext)
			exit("Problem while initializing ServiceContext.\n");
		
		//exit("ServiceContext initialization is Okay.\n");
		// Prep Data Services
		$dataService = new DataService($serviceContext);
		if (!$dataService)
			exit("Problem while initializing DataService.\n");
		
		//exit("DataService initialization is Okay.\n");
		
		// Add a customer
		$customerObj = new IPPCustomer();
		$customerObj->Name = "Name" . rand();
		$customerObj->CompanyName = "CompanyName" . rand();
		$customerObj->GivenName = "GivenName" . rand();
		$customerObj->DisplayName = "DisplayName" . rand();
		$resultingCustomerObj = $dataService->Add($customerObj);
		
		// Echo some formatted output
		echo "Created Customer Id={$resultingCustomerObj->Id}. Reconstructed response body:\n\n";
		$xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingCustomerObj, $urlResource);
		echo $xmlBody . "\n";
	}*/
	
	function testing()
	{
		$this->load->library('quickbooks');
		
		$customer_details = $this->quickbooks->addCustomer(); //exit;
		$customer_details = json_decode($customer_details);
		echo "<pre>"; print_r($customer_details); exit;
		//$this->quickbooks->invoiceGeneration(); exit;
		$this->quickbooks->receivePayments(); exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */