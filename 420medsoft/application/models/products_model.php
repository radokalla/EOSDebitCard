<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/main_model.php');
class Products_model extends main_model {
	
	private $tablename = '';
	private $options_tablename = '';	
	private $category_options_tablename = '';
	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'products';
		$this->categories_tablename = 'categories';
		$this->category_options_tablename = 'categoryoptions';
	}
	
	
}



?>