<?php

/**
 * Run a command with optional stdin
 * @param string $cmd - the command to run
 * @param string|string[] $stdin - the stdin to feed to the command
 * @param string $cwd - the working directory in which to run the command
 * @return object containing exit_status, stdout, stderr and elapsed
 */
function fi_run_cmd($cmd, $stdin=null, $cwd=null){
	if(empty($cwd)) $cwd = getcwd();
	if(empty($stdin)) $stdin = array();
	if(!is_array($stdin)) $stdin = array($stdin);
	
	$started = microtime(true);
	
	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		2 => array("pipe", "w")   // stderr is a pipe that the child will write to
	);
	$process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
	
	$stderr = '';
	$stdout = '';
	$status = 0;
	
	if (is_resource($process)) {
		foreach($stdin as $input){
			fwrite($pipes[0], $input);
		}
		fclose($pipes[0]);

		$stdout = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[2]);

		$status = proc_close($process);
	}else{
		$stderr = 'Unable to run command.';
		$status = 1;
	}

	$elapsed = microtime(true) - $started;
	
	return (object) array(
		'exit_status' => $status,
		'stderr' => $stderr,
		'stdout' => $stdout,
		'elapsed' => $elapsed
	);
}