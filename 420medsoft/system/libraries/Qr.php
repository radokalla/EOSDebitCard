<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Grid Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Grid
 * @author		Inbucosolutions Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/Grid.html
 */
class CI_Qr {

    var $CI;
    var $CurTime;
	var $data="Test Srinivas";
	var $level="L" ; // L || M || Q || H ;
	var $size="8"; // 1- 10;
	var $short=false;
	var $QR;
	var $lpath='public/qrcodes';
	var $sep="/";
	var $tpath="";
	var $path="";
    function CI_Qr()
    {
        $this->CI =& get_instance();
        $this->CurTime = time();
		$path=getcwd();
		$this->path=$path;
		$sep=((strpos($path,'\\')>0)?'\\':'/');
		$clpath=$path.$sep.'system'.$sep.'qr';
		chdir($clpath);
		include "qrlib.php";    
		chdir($path);
		$this->tpath=$this->getPath($this->lpath);
		$this->QR=new QRcode();
    }


	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}
	
	public function Generate($data="",$size="8",$short=false,$level="H")
	{
		$this->size=$size;	
		$this->level=$level;
		if($data)
			$this->data=$data;
		if($this->short || $short)
			$this->data=$this->GetShortUrl($this->data);
        $filename = md5($this->data.'|'.$this->level.'|'.$this->size).'.png';
		$this->QR->png($this->data, $this->tpath.$filename, $this->level, $this->size, 2);    
		return $this->lpath.'/'.$filename;
	}
	
	public function GetShortUrl($request)
	{
		$curl = curl_init('https://www.the-qrcode-generator.com/api/shorten');
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);	
		if($response){
			$response=json_decode($response,true);
			return $response['short_url'];
		} else {
			$this->GetShortUrl($request);
		}
	}
	
	public function GetPath($path="")
	{
		
		$rpath=getcwd();
		$sep=((strpos('1'.$rpath,'/')>0)?'/':'\\');
		if($path){
			$ssep=((strpos('1'.$path,'/')>0)?'/':((strpos('1'.$path,'\\')>0)?'\\':''));
			if($ssep)
				$path=explode($ssep,$path);
			else 
				$path[0]=$path;
			$rpath.=$sep;
			if(is_array($path)){
				foreach($path as $p){
					$rpath.=$p;
					if(!file_exists($rpath))
						mkdir($rpath);
					$rpath.=$sep;
				}
				$rpath=rtrim($rpath,$sep);
			} else {
				$rpath.=$path;
				if(!file_exists($rpath))
					mkdir($rpath);
			}
		}
		$rpath.=$sep;
		return $rpath;
	}

}
// END Grid Class

/* End of file Grid.php */
/* Location: ./system/libraries/Grid.php */