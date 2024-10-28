<?php

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
require APP_ROOT."/includes/functions/fi_check_file_app_permissions.php";
require APP_ROOT."/includes/functions/fi_md_to_html.php";
require APP_ROOT."/includes/functions/fi_get_base_url.php";
require APP_ROOT."/includes/functions/fi_is_404.php";
require APP_ROOT."/includes/functions/fi_print_og_tags.php";

$missing_perms = fi_check_file_app_permissions();

if(empty($missing_perms) && !empty($config) && !empty($pdo)){
	if(fi_is_404()) http_response_code(404);
}
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title><?php echo empty($config) || empty($config->title) ? "Fuckit" : $config->title; ?></title>

		<?php if(empty($missing_perms) && !empty($config) && !empty($pdo)): ?>
			<link href="<?php echo $config->base_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<?php endif; ?>

		<?php fi_print_og_tags(); ?>

	</head>
	<body>

		<?php if(!empty($missing_perms) || empty($config) || empty($pdo)): ?>

			<div style="width:90vw; margin:1em auto; font-family:'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'">
				<h1>Fuckit</h1>
				<p>Fuckit cannot access either the database or the config file. From the command line, please run:</p>
				<code>cd <?php echo APP_ROOT; ?> && \<br>
					<?php foreach($missing_perms as $err) echo $err['solution']." && \\<br>"; ?>
					npm run setup</code>
			</div>

		<?php else: ?>

			<div id='app_container'><?php if(!empty($html)) echo $html; ?></div>
			<script src="<?php echo $config->base_url; ?>assets/js/bootstrap.bundle.min.js"></script>
			<script src="<?php echo $config->base_url; ?>assets/js/main.js"></script>

		<?php endif; ?>

	</body>
</html>