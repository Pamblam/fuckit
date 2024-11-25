<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Install Fuckit</title>
	</head>
	<body>
		<div style="width:90vw; margin:1em auto; font-family:'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'">
			<h1>Install Fuckit</h1>
			<?php 
			$has_errors = false;

			// Ensure all filesystem permissions are OK!
			echo "<h4>Checking Permissions...</h4>";
			if(!empty($missing_perms)){
				echo "<p>Fuckit requires permissions adjutments. From the command line, please run:</p>";
				foreach($missing_perms as $err) echo '<code>'.$err['solution'].";</code><br>"; 
				echo "<button onclick='window.location = window.location.href;'>Continue</button>";
				$has_errors = true;
				exit;
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
					echo "<p>Fuckit could not create the database file. From the command line, please run:</p>";
					echo '<code>touch '.$db_file."</code><br>";
					echo "<button onclick='window.location = window.location.href;'>Continue</button>";
					$has_errors = true;
					exit;
				}
			} 
			// Iterate thru all the sql files and run them
			if(!empty($missing_tables)){
				foreach($missing_tables as $table){
					$sql = @file_get_contents(APP_ROOT."/database/sql/$table.sql");
					if(false === $sql){
						echo "<p>Can't scan the sql file ($table.sql). Ensure PHP has proper permissions to read it.</p>";
						echo "<button onclick='window.location = window.location.href;'>Continue</button>";
						$has_errors = true;
						exit;
					}

					$sql_statements = explode(";\n", $sql);
					foreach($sql_statements as $sql_statement){
						try{
							$pdo->exec($sql_statement);
							echo "<p>Created table <code>$table</code></p>";
						}catch(PDOException $e){
							echo "<p>($table.sql) Error: ".$e->getMessage()."</p>";
							echo "<button onclick='window.location = window.location.href;'>Continue</button>";
							$has_errors = true;
							exit;
						}
					}

				}
			}else{
				echo "<p>Tables look good 👌</p>";
			}

			// Add missing user
			echo "<h4>Checking User...</h4>";
			$form_errors = [];
			if(isset($_POST['create_user'])){
				if($_POST['confirm_password'] !== $_POST['password']){
					$form_errors[] = "Passwords don't match";
				}
				if(empty($_POST['username'])){
					$form_errors[] = "No username provided.";
				}
				if(empty($_POST['display_name'])){
					$form_errors[] = "No display name provided.";
				}
				try{
					if(empty($form_errors)){
						$stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `display_name`) VALUES (?, ?, ?);");
						$stmt->execute([
							$_POST['username'], 
							md5($_POST['password']), 
							$_POST['display_name']
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
				echo "<label style='display:block'>Username:<br><input name='username' placeholder='Username' /></label>";
				echo "<label style='display:block'>Display Name:<br><input name='display_name' placeholder='Display Name' /></label>";
				echo "<label style='display:block'>Password:<br><input name='password' type='password' placeholder='Password' /></label>";
				echo "<label style='display:block'>Confirm Password:<br><input name='confirm_password' type='password' placeholder='Confirm Password' /></label>";
				echo "<button type='submit' name='create_user' value=1>Create User</button>";
				echo "</form>";
			}else{
				echo "<p>Users look good 👌</p>";
			}

			// Checking config files
			echo "<h4>Checking Configuration...</h4>";
			if(!file_exists($app_config_file)){
				echo "<p>App config file doesn't exit 🚫. From the command line, please run:</p>";
				echo '<code>touch '.$app_config_file.";</code><br>";
				echo "<button onclick='window.location = window.location.href;'>Continue</button>";
				$has_errors = true;
				exit;
			}
			if(!file_exists($server_config_file)){
				echo "<p>Server config file doesn't exit 🚫. From the command line, please run:</p>";
				echo '<code>touch '.$server_config_file.";</code><br>";
				echo "<button onclick='window.location = window.location.href;'>Continue</button>";
				$has_errors = true;
				exit;
			}

			// App Config
			$form_errors = [];
			if(isset($_POST['create_app_config'])){
				if(empty($_POST['app_title'])){
					$form_errors[] = "No app title provided.";
				}
				if(empty($_POST['app_description'])){
					$form_errors[] = "No app description provided.";
				}
				if(!empty($_POST['app_og_url']) && !filter_var($app_img, FILTER_VALIDATE_URL)){
					$form_errors[] = "Invalid image URL.";
				}
				try{
					if(empty($form_errors)){
						$app_config_obj = [
							'title' => $_POST['app_title'],
							'desc' => $_POST['app_description']
						];
						if(!empty($_POST['app_og_url'])) $app_config_obj['img'] = $_POST['app_og_url'];
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
				echo "<p>App is not configured 🚫.</p>";
				echo "<form method='POST'>";
				if(!empty($form_errors)) echo '<ul><li>🚫 ' . implode('</li><li>🚫 ', $form_errors) . '</li></ul>';
				echo "<label style='display:block'>App Title:<br><input name='app_title' placeholder='App Title' /></label>";
				echo "<label style='display:block'>App Description:<br><input name='app_description' placeholder='App Description' /></label>";
				echo "<label style='display:block'>App OG Image URL:<br><input name='app_og_url' placeholder='App OG Image URL' /></label>";
				echo "<button type='submit' name='create_app_config' value=1>Create App Config</button>";
				echo "</form>";
			}else{
				echo "<p>App Config look good 👌</p>";
			}

			// Server config
			$form_errors = [];
			if(isset($_POST['create_server_config'])){
				if(empty($_POST['base_url'])){
					$form_errors[] = "No base URL provided.";
				}
				if(empty($_POST['max_filesize'])){
					$form_errors[] = "No max filesize provided.";
				}
				if(!is_numeric($_POST['max_filesize'])){
					$form_errors[] = "Invalid max filesize provided.";
				}
				try{
					if(empty($form_errors)){
						$server_config_obj = [
							'base_url' => $_POST['base_url'],
							'max_upload_size' => $_POST['max_filesize']
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
				echo "<label style='display:block'>Base URL:<br><input name='base_url' placeholder='Base URL' value=\"".$_SERVER['REQUEST_URI']."\" /></label>";
				echo "<label style='display:block'>Max Upload File Size:<br><input type='number' name='max_filesize' placeholder='Max Upload File Size' value=\"".fi_file_upload_max_size()."\" /></label>";
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
						echo "<li>$desc 🚫:".$res->stderr."</li>";
						break;
					}
				}
				echo "</ul>";

				echo "<button onclick='window.location = window.location.href;'>Continue</button>";
			}

			?>

		</div>
	</body>
</html>