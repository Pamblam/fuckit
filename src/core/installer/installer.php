<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Install Milton CMS</title>
		<style>
			.container{
				width: 90vw; 
				margin: 1em auto; 
				font-family: 'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'
			}
			#code-textarea{
				border-radius: 3px;
				resize: none;
				display: block;
				width: 100%;
				border: none;
				background: #1c1c1c;
				color: #f0f0f0;
				font-family: 'Courier New', monospace;
				overflow: auto;
				margin: 2px 5px;
			}
			button{
				margin: 2px 5px;
				padding: 2px 5px;
			}
			input{
				border-radius: 3px;
				border: 1px solid black;
				margin: 2px 5px;
				padding: 2px 5px;
			}
			label{
				margin: 2px 5px;
				display: block;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h1>Install Milton CMS</h1>
			<?php 
				fi_browser_installer(
					$missing_perms, 
					$db_file, 
					$missing_tables, 
					$_POST, 
					$app_config_file, 
					$server_config_file,
					$missing_user,
					$missing_deps,
					$missing_node_modules
				); 
			?>
		</div>
	</body>
</html>