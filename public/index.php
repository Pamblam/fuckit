<?php
require realpath(dirname(dirname(__FILE__)))."/includes/env.php";

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
$index = strpos($url, $config->base_url);
$relative_url = substr($url, $index + strlen($config->base_url));
$url_parts = explode('/', $relative_url);
if(count($url_parts) === 2 && $url_parts[0] === 'post'){
	$slugOrId = $url_parts[1];
	if(is_numeric($slugOrId)){
		$post = Post::fromID($pdo, $slugOrId);
	}else{
		$post = Post::fromColumn($pdo, 'slug', $slugOrId);
	}
	if(!empty($post)){
		$Parsedown = new Parsedown();
		$html = $Parsedown->text($post->get('body'));
		$post_title = $post->get('title');
		$post_summary = $post->get('summary');
		$post_image = $post->get('graph_img');
	}
}
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Fuckit</title>
		<?php if(!empty($config) && !empty($pdo)): ?>
			<link href="<?php echo $config->base_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<?php endif; ?>
		<?php if(!empty($post_title)): ?>
			<meta property="og:title" content="<?php echo addslashes($post_title); ?>" />
		<?php endif; ?>
		<?php if(!empty($post_summary)): ?>
			<meta name="description" content="<?php echo addslashes($post_summary); ?>" />
			<meta property="og:description" content="<?php echo addslashes($post_summary); ?>" />
		<?php endif; ?>
		<?php if(!empty($post_image)): ?>
			<meta property="og:image" content="<?php echo $_SERVER['HTTP_HOST'].$config->base_url.$post_image; ?>" />
		<?php endif; ?>
	</head>
	<body>
		<?php if(empty($config) || empty($pdo)): ?>
			<div style="width:90vw; margin:1em auto; text-align:center; font-family:'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'">
				<h1>Fuckit</h1>
				<p>You need to run the installer script. From the app root, run:<br><code>npm run setup</code></p>
			</div>
		<?php else: ?>
			<div id='app_container'><?php if(!empty($html)) echo $html; ?></div>
			<script src="<?php echo $config->base_url; ?>assets/js/bootstrap.bundle.min.js"></script>
			<script src="<?php echo $config->base_url; ?>assets/js/main.js"></script>
		<?php endif; ?>
	</body>
</html>