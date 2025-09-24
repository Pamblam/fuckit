<?php

function fi_browser_installer($missing_perms, $db_file, $missing_tables, $post, $app_config_file, $server_config_file, $missing_user){
	$has_errors = false;

	// Ensure all filesystem permissions are OK!
	echo "<h4>Checking Permissions...</h4>";
	if(!empty($missing_perms)){
		echo "<p>Milton CMS requires permissions adjustments. From the command line, please run:</p>";
		echo "<textarea id='code-textarea' readonly rows='".(count($missing_perms)+1)."'>";
		foreach($missing_perms as $err) echo $err['solution'].";\n";
		echo "</textarea>";
		echo "<button onclick='navigator.clipboard.writeText(document.getElementById(`code-textarea`).value).then(e=>{this.innerHTML=`Text copied!`;setTimeout(()=>this.innerHTML=`Copy to Clipboard`,2000)})'>Copy to Clipboard</button>";
		echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
		$has_errors = true;
		return;
	}else{
		echo "<p>Permissions look good 👌</p>";
	}

	// Ensure DB file exists
	echo "<h4>Checking Database Tables...</h4>";
	if(!file_exists($db_file) || empty($pdo)){
		try{
			$pdo = new PDO('sqlite:'.$db_file);
			if(empty($pdo)) throw new Exception("Couldn't create database file.");
		}catch(Exception $e){
			echo "<p>Milton CMS could not create the database file. From the command line, please run:</p>";
			echo "<textarea id='code-textarea' readonly rows='2'>touch ".$db_file.";\n</textarea>";
			echo "<button onclick='navigator.clipboard.writeText(document.getElementById(`code-textarea`).value).then(e=>{this.innerHTML=`Text copied!`;setTimeout(()=>this.innerHTML=`Copy to Clipboard`,2000)})'>Copy to Clipboard</button>";
			echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
			$has_errors = true;
			return;
		}
	}
	
	// Iterate thru all the sql files and run them
	if(!empty($missing_tables)){
		foreach($missing_tables as $table){
			$sql = @file_get_contents(APP_ROOT."/database/sql/$table.sql");
			if(false === $sql){
				echo "<p>Can't scan the sql file ($table.sql). Ensure PHP has proper permissions to read it.</p>";
				echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
				$has_errors = true;
				return;
			}

			$sql_statements = explode(";\n", $sql);
			foreach($sql_statements as $sql_statement){
				try{
					$pdo->exec($sql_statement);
					echo "<p>Created table <code>$table</code></p>";
				}catch(PDOException $e){
					echo "<p>($table.sql) Error: ".$e->getMessage()."</p>";
					echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
					$has_errors = true;
					return;
				}
			}

		}
	}else{
		echo "<p>Tables look good 👌</p>";
	}

	// Add missing user
	echo "<h4>Checking User...</h4>";
	$form_errors = [];
	if(isset($post['create_user'])){

		if($post['confirm_password'] !== $post['password']){
			$form_errors[] = "Passwords don't match";
		}
		if(empty($post['username'])){
			$form_errors[] = "No username provided.";
		}
		if(empty($post['display_name'])){
			$form_errors[] = "No display name provided.";
		}
		try{
			if(empty($form_errors)){
				$stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `display_name`) VALUES (?, ?, ?);");
				$stmt->execute([
					$post['username'], 
					md5($post['password']), 
					$post['display_name']
				]);
				$missing_user = false;

			}
		}catch(PDOException $e){
			$form_errors[] = $e->getMessage();
		}
	}

	if($missing_user){
		$has_errors = true;
		echo "<p>There are no users for this install 🚫. Create one.</p>";
		echo "<form method='POST'>";
		if(!empty($form_errors)) echo '<ul><li>🚫 ' . implode('</li><li>🚫 ', $form_errors) . '</li></ul>';
		echo "<label>Username:</label><input name='username' placeholder='Username' /><br>";
		echo "<label>Display Name:</label><input name='display_name' placeholder='Display Name' /><br>";
		echo "<label>Password:</label><input name='password' type='password' placeholder='Password' /><br>";
		echo "<label>Confirm Password:</label><input name='confirm_password' type='password' placeholder='Confirm Password' /><br>";
		echo "<button type='submit' name='create_user' value=1>Create User</button>";
		echo "</form>";
		return;
	}else{
		echo "<p>Users look good 👌</p>";
	}

	// Checking config files
	echo "<h4>Checking Configuration...</h4>";
	if(!file_exists($app_config_file)){
		echo "<p>App config file doesn't exit 🚫. From the command line, please run:</p>";
		echo "<textarea id='code-textarea' readonly rows='2'>touch ".$app_config_file.";\n</textarea>";
		echo "<button onclick='navigator.clipboard.writeText(document.getElementById(`code-textarea`).value).then(e=>{this.innerHTML=`Text copied!`;setTimeout(()=>this.innerHTML=`Copy to Clipboard`,2000)})'>Copy to Clipboard</button>";
		echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
		$has_errors = true;
		return;
	}
	if(!file_exists($server_config_file)){
		echo "<p>Server config file doesn't exit 🚫. From the command line, please run:</p>";
		echo "<textarea id='code-textarea' readonly rows='2'>touch ".$server_config_file.";\n</textarea>";
		echo "<button onclick='navigator.clipboard.writeText(document.getElementById(`code-textarea`).value).then(e=>{this.innerHTML=`Text copied!`;setTimeout(()=>this.innerHTML=`Copy to Clipboard`,2000)})'>Copy to Clipboard</button>";
		echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
		$has_errors = true;
		return;
	}

	// App Config
	$form_errors = [];
	if(isset($post['create_app_config'])){
		if(empty($post['app_title'])){
			$form_errors[] = "No app title provided.";
		}
		if(empty($post['app_description'])){
			$form_errors[] = "No app description provided.";
		}
		if(!empty($post['app_og_url']) && !filter_var($post['app_og_url'], FILTER_VALIDATE_URL)){
			$form_errors[] = "Invalid image URL.";
		}
		try{
			if(empty($form_errors)){
				$app_config_obj = [
					'title' => $post['app_title'],
					'desc' => $post['app_description']
				];
				if(!empty($post['app_og_url'])) $app_config_obj['img'] = $post['app_og_url'];
				$res = @file_put_contents($app_config_file, json_encode($app_config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
				if(false === $res){
					$form_errors[] = "Can't create app config file. Ensure PHP has correct permissions and ownership.";
				}
			}
		}catch(PDOException $e){
			$form_errors[] = $e->getMessage();
		}
	}

	$cfg = false;
	try{
		$cfg = @file_get_contents($app_config_file);
		$cfg = @json_decode($cfg);
	}catch(Exception $e){ $cfg=false; }
	if(empty($cfg)){
		$has_errors = true;
		echo "<p>Blog is not configured 🚫.</p>";
		echo "<form method='POST'>";
		if(!empty($form_errors)) echo '<ul><li>🚫 ' . implode('</li><li>🚫 ', $form_errors) . '</li></ul>';
		echo "<label>Blog Title:</label><input name='app_title' placeholder='App Title' /><br>";
		echo "<label>Blog Description:</label><input name='app_description' placeholder='App Description' /><br>";
		echo "<label>Blog OG Image URL:</label><input name='app_og_url' placeholder='App OG Image URL' /><br>";
		echo "<button type='submit' name='create_app_config' value=1>Create App Config</button>";
		echo "</form>";
		return;
	}else{
		echo "<p>Blog Config look good 👌</p>";
	}

	// Server config
	$form_errors = [];
	if(isset($post['create_server_config'])){
		if(empty($post['base_url'])){
			$form_errors[] = "No base URL provided.";
		}
		if(empty($post['max_filesize'])){
			$form_errors[] = "No max filesize provided.";
		}
		if(!is_numeric($post['max_filesize'])){
			$form_errors[] = "Invalid max filesize provided.";
		}
		try{
			if(empty($form_errors)){
				$server_config_obj = [
					'base_url' => $post['base_url'],
					'max_upload_size' => $post['max_filesize']
				];
				$res = @file_put_contents($server_config_file, json_encode($server_config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
				if(false === $res){
					$form_errors[] = "Can't create server config file. Ensure PHP has correct permissions and ownership.";
				}
			}
		}catch(PDOException $e){
			$form_errors[] = $e->getMessage();
		}
	}

	// Server Config
	$cfg = false;
	try{
		$cfg = @file_get_contents($server_config_file);
		$cfg = @json_decode($cfg);
	}catch(Exception $e){ $cfg=false; }
	if(empty($cfg)){
		$has_errors = true;
		require APP_ROOT."/includes/functions/fi_file_upload_max_size.php";
		echo "<p>Server is not configured 🚫.</p>";
		echo "<form method='POST'>";
		if(!empty($form_errors)) echo '<ul><li>🚫 ' . implode('</li><li>🚫 ', $form_errors) . '</li></ul>';
		echo "<label>Base URL:</label><input name='base_url' placeholder='Base URL' value=\"".$_SERVER['REQUEST_URI']."\" /><br>";
		echo "<label>Max Upload File Size:</label><input type='number' name='max_filesize' placeholder='Max Upload File Size' value=\"".fi_file_upload_max_size()."\" /><br>";
		echo "<button type='submit' name='create_server_config' value=1>Create Server Config</button>";
		echo "</form>";
	}else{
		echo "<p>Server Config look good 👌</p>";
	}

	// Build the app
	if(!$has_errors){
		echo "<h4>Checking Configuration...</h4><ul>";
		require_once(APP_ROOT.'/includes/functions/fi_run_cmd.php');
		$cmds = [
			"Installing dependencies" => "npm i",
			"Mapping imports" => "php -q ./scripts/map_imports.php",
			"Building" => "webpack"
		];
		foreach($cmds as $desc=>$cmd){
			$res = fi_run_cmd($cmd, null, APP_ROOT);
			if($res->exit_status == 0){
				echo "<li>$desc 👌</li>";
			}else{
				$has_errors = true;
				echo "<li>$desc 🚫:".$res->stderr."</li>";
				break;
			}
		}
		echo "</ul>";

		if($has_errors){
			echo "<p>Unable to install, please run this manually in the command line:</p>";
			echo "<textarea id='code-textarea' readonly rows='4'>cd ".APP_ROOT.";\nnpm i;\nphp -q ./scripts/map_imports.php && webpack;\n</textarea>";
			echo "<button onclick='navigator.clipboard.writeText(document.getElementById(`code-textarea`).value).then(e=>{this.innerHTML=`Text copied!`;setTimeout(()=>this.innerHTML=`Copy to Clipboard`,2000)})'>Copy to Clipboard</button>";
			echo "<button onclick='window.location = window.location.href;'>Continue Installation</button>";
			return;
		}

		echo "<button onclick='window.location = window.location.href;'>Continue</button>";
	}
}