<?php
//coneccion a la ddbb
define('DDBBHOST',"mysql");
define('DDBBPASSWORD',"rootpassword");
define('DDBBUSER',	"root");
//define('DDBBDATABASE',"gp2020_dev");
define('DDBBDATABASE',"clone_dev");
define('DDBBROWS',"0");

define('URLABSOLUTA',"https://gp2020-dev.aper.cloud/");

/*define("MAIL_MAILER", 'smtp');
define("MAIL_HOST", 'smtp.gmail.com');
define("MAIL_PORT", 587);    
define("MAIL_SMTP_SECURE", 'tls');    
define("MAIL_SMTP_AUTH", true);    
define("MAIL_USERNAME", 'mercadoidealq@gmail.com');    
define("MAIL_PASSWORD", 'moldes2175');*/

define("MAIL_MAILER", 'smtp');
define("MAIL_HOST", 'email-smtp.us-east-1.amazonaws.com');
define("MAIL_PORT", 587);
define("MAIL_SMTP_SECURE", 'tls');
define("MAIL_SMTP_AUTH", true);
define("MAIL_USERNAME", 'AKIAS3OHGPRQ7TVPFXDJ');
define("MAIL_PASSWORD", 'BNsv3yeEYicMWB+szlZZBLmkd4RkZ0Wqiq8j139AXEM7');

// define("MAIL_MAILER", 'smtp');
// define("MAIL_HOST", 'smtp.gmail.com');
// define("MAIL_PORT", 587);
// define("MAIL_SMTP_SECURE", 'tls');
// define("MAIL_SMTP_AUTH", true);
// define("MAIL_USERNAME", 'testmailingdev00@gmail.com');
// define("MAIL_PASSWORD", 't35tm41l1ng!!');


define("DESTINO_AVISOS", "vpastoriza@main.net.ar, sroca@main.net.ar");

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

ini_set('display_errors', 0);
?>