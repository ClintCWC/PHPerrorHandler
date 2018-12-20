<?php
require_once('function.error_handler.php');
$config['documentroot']						= $_SERVER['DOCUMENT_ROOT'];
$config['show_errors'] 						= true;
$config['date_format']						= 'd\/m\/y';
$config['error_log']			 					= 	'logs/error_log.txt';
$config['error_log_count']					= 	30;
$config['stopOnError']							= true;



?>