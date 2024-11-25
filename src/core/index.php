<?php
require APP_ROOT."/includes/functions/fi_print_og_tags.php";
require APP_ROOT."/includes/functions/fi_md_to_html.php";
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo empty($config->title) ? "Fuckit" : $config->title; ?></title>
		<link href="<?php echo $config->base_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<?php fi_print_og_tags(); ?>
	</head>
	<body>
		<div id='app_container'><?php if(!empty($html)) echo $html; ?></div>
		<script src="<?php echo $config->base_url; ?>assets/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo $config->base_url; ?>assets/js/main.js"></script>
	</body>
</html>