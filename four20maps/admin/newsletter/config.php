<?php
ini_set('mysql.connect_timeout', 300);
ini_set('default_socket_timeout', 300);
set_time_limit(0);

define("_DB_NAME","four20ma_newsletter");
define("_DB_USER","four20ma_dbuser");
define("_DB_PASS","Rado6611");
define("_DB_SERVER","localhost");
/*define("_DB_NAME","newscad");
define("_DB_USER","root");
define("_DB_PASS","");
define("_DB_SERVER","localhost");*/
define("_TEMPLATE_DIR","templates/");
define("_NEWSLETTERS_DIR","newsletters/");
define("_IMAGES_DIR","images/");
define("_MAIL_SMTP",false); 
define("_MAIL_SMTP_HOST","smtp.ex2.secureserver.net"); 
define("_MAIL_SMTP_AUTH",false); 
define("_MAIL_SMTP_USER","cdllab@biomeddental.com"); 
define("_MAIL_SMTP_PASS","Rado5280$"); 


define("_DEBUG_MODE",false);
define("_DEMO_MODE",false);
ini_set("display_errors",_DEBUG_MODE);

// date format for printing dates to the screen (uses php date syntax)
define("_DATE_FORMAT","d/m/Y"); 
// date format for inputting dates into the system
// 1 = DD/MM/YYYY
// 2 = YYYY/MM/DD
// 3 = MM/DD/YYYY
define("_DATE_INPUT",1); 
switch(_DATE_INPUT){
	case 1: define('_DATE_INPUT_HELP','DD/MM/YYYY'); break;
	case 2: define('_DATE_INPUT_HELP','YYYY/MM/DD'); break;
	case 3: define('_DATE_INPUT_HELP','MM/DD/YYYY'); break;
}
