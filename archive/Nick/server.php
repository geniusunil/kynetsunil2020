<?php
	
/*
	cat /proc/loadavg > work_files/server/load.txt
	free -m > work_files/server/ram.txt
	uptime > work_files/server/uptime.txt
*/
	
	require 'functions.php';
	$logFile = 'LOGS/server.log';
	setLog();
	
	@mkdir('work_files/server');
	
	$r = array();
	$r['load'] = removeSpaces(file_get_contents('work_files/server/load.txt'));
	$r['ram'] = removeSpaces(file_get_contents('work_files/server/ram.txt'));
	$r['uptime'] = removeSpaces(file_get_contents('work_files/server/uptime.txt'));
	
	$sc = file_get_contents($logFile);
	if (substr_count($sc, '[cpu] =>') >= 1440) {
		$remLines = 7;
		$lines = file($logFile, FILE_IGNORE_NEW_LINES);
		$newFile = '';
		for ($i = $remLines; $i < count($lines); $i++) {
			$newFile .= $lines[$i].PHP_EOL;
		}
		$newFile = substr($newFile, 0, -1);
		file_put_contents($logFile, $newFile);
	}
	
	logg(print_r($r, true));
	
?>