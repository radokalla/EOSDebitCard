<?php
class Logs {
    /**
     * Constructor
     *
     * @access    public
     * @param    array the array of loggable items
     * @param    string    the log file path
     * @param     string     the error threshold
     * @param    string    the date formatting codes
     */
	var $log_path;
    function Logs()
    {
        $this->log_path = APPPATH.'logs/';
    }
   
   
    function write_log($level = 'error', $msg, $php_error = FALSE)
    {        
       
        $filepath = $this->log_path.'log-'.date('Y-m-d').EXT;
        $message  = '';
        
        if ( ! file_exists($filepath))
        {
            $message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
        }
            
        if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE) )
        {
            return FALSE;
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date('Y-m-d H:i:s'). ' --> '.$msg."\n";
        
        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
    
        @chmod($filepath, FILE_WRITE_MODE);         
        return TRUE;
    }

}

/* End of file Session.php */
/* Location: application/libraries */ 
?> 