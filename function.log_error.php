<?php

function log_error($new_error){
	$cumulative_error_log = $new_error;
	global $config;
	if(file_exists($config['documentroot'].$config['error_log'])){
		$log_file_contents = file_get_contents($config['documentroot'].$config['error_log']);
		$log_file_array = explode("\n",$log_file_contents);
		$log_file_array = array_slice($log_file_array, 0-$config['error_log_count']);
		$cumulative_error_log = implode("\n",$log_file_array).$new_error;
	}
	
	//debug($cumulative_error_log);
	
	file_put_contents($config['documentroot'].$config['error_log'], $cumulative_error_log."\n", LOCK_EX);
}

?>