<?php
require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Fuckit</title>
		<?php if(!empty($config) && !empty($pdo)): ?>
			<link href="<?php echo $config->base_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<?php endif; ?>
	</head>
	<body>
		<?php if(empty($config) || empty($pdo)): ?>
			<div style="width:90vw; margin:1em auto; text-align:center; font-family:'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'">
				<h1>Fuckit</h1>
				<p>You need to run the installer script. From the app root, run:<br><code>npm run setup</code></p>
			</div>
		<?php else: ?>
			<div id='app_container'></div>
			<script src="<?php echo $config->base_url; ?>assets/js/bootstrap.bundle.min.js"></script>
			<script src="<?php echo $config->base_url; ?>assets/js/main.js"></script>
		<?php endif; ?>
	</body>
</html>