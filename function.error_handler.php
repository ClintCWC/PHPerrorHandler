<?php

$old_error_handler = set_error_handler("error_handler");
register_shutdown_function('fatalErrorShutdownHandler');

function error_handler($errno, $errstr, $errfile, $errline, $errcontext=''){
	$display_error = array();
	global $global_error_count;
	$global_error_count ++;

	global $config;
	global $PHP_errors;
	$template_error 		= false;
	 $time_of_error 		=  date($config['date_format']);
	 $error_levels 			= array(
        1			=>	'E_ERROR',
        2			=>	'E_WARNING',
       	4			=>	'E_PARSE',
        8			=>	'E_NOTICE',
        16			=>	'E_CORE_ERROR',
        32			=>	'E_CORE_WARNING',
       	64			=> 	'E_COMPILE_ERROR',
        128		=>	'E_COMPILE_WARNING',
        256		=>	'E_USER_ERROR',
        512		=>	'E_USER_WARNING',
        1024		=>	'E_USER_NOTICE',
        2048		=>	'E_STRICT',
        4096		=>	'E_RECOVERABLE_ERROR',
        8192		=>	'E_DEPRECATED',
        16384	=>	'E_USER_DEPRECATED',
        32767	=>	'E_ALL'
    );	
	
	if (!isset($PHP_errors)){$PHP_errors="";}
	$error = "0x".time()." $time_of_error $error_levels[$errno]: $errstr | File: $errfile | Line: $errline. ";
	$PHP_errors .= $error; 
	
	if (stripos($error, '.tpl')!==false and stripos($error, 'E_NOTICE')!==false ){
		$template_error = true;
	}
	
	
	
	//debug(debug_backtrace());
	
	if ($config['show_errors']){
		if (!$template_error ){
			$display_error[] = "$time_of_error $error_levels[$errno] <b>$errstr</b> in file <b>$errfile</b> at line <b>$errline</b>.<br/>\n";
			$display_error[] = '<i>';
			foreach(debug_backtrace() as $step){
				if (isset($step['file']) and $step['file'] != $errfile){
					$display_error[] = 'Called by '.$step['file'].' at line '.$step['line'].'<br/>';
				}
			}
			$display_error[] = '</i><br/>';
		}else{
			//echo simple error for templates
			$errfile =end( explode('\\', $errfile) );
			$display_error[] = "$error_levels[$errno] <b>$errstr</b> in file <b>$errfile</b> at line <b>$errline</b>.<br/>\n";
		}
		echo implode('',$display_error);
	}
	
	
	
	if (!$template_error ){
		require_once('function.log_error.php');
		log_error("0x".time().' '.str_replace(array("\n", "\r"),'',strip_tags(implode('|',$display_error))));
	}
	
	if ($config['stopOnError']){exit;}
	//return false; //show normal php errors
	return true; //not show normal php errors
}

function fatalErrorShutdownHandler(){
  $last_error = error_get_last();
 if ($last_error['type'] === 1 or $last_error['type'] === 4 ) {
    // fatal error
	//debug($last_error);
   	//error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
  }
}