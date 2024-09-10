<?php

require realpath(dirname(dirname(__FILE__)))."/includes/env.php";
require APP_ROOT."/includes/functions/checkAppFilePerms.php";
require APP_ROOT."/includes/functions/mdToHTML.php";
require APP_ROOT."/includes/functions/getBaseURL.php";

$missing_perms = checkAppFilePerms();
$meta_tags = [];

if(empty($missing_perms) && !empty($config) && !empty($pdo)){

	$meta_tags['og:type'] = 'website';
	if(!empty($GLOBALS['config']->img)) $meta_tags['og:image'] = $GLOBALS['config']->img;
	if(!empty($GLOBALS['config']->title)) $meta_tags['og:site_name'] = $GLOBALS['config']->title;
	if(!empty($GLOBALS['config']->desc)) $meta_tags['og:description'] = $GLOBALS['config']->desc;

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
			$html = mdToHTML($post->get('body'));

			$meta_tags['og:type'] = 'article';
			$meta_tags['og:image'] = $post->get('graph_img');
			$meta_tags['og:title'] = $post->get('title');
			$meta_tags['og:description'] = $post->get('summary');

			if(!empty($meta_tags['og:image']) && strpos($meta_tags['og:image'], 'assets/') === 0){
				$meta_tags['og:image'] = getBaseURL() . $meta_tags['og:image'];
			}
		}
	}

	if(!empty($meta_tags['og:image'])){

		if(strpos($meta_tags['og:image'], getBaseURL()) !== 0){
			$meta_tags['og:image'] = getBaseURL() . ltrim($meta_tags['og:image'], '/');
		}

		list($og_img_width, $og_img_height) = getimagesize($meta_tags['og:image']);
		if(!empty($og_img_width) && !empty($og_img_height)){
			$meta_tags['og:image:width'] = $og_img_width;
			$meta_tags['og:image:height'] = $og_img_height;
		}
	}
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

		<?php foreach($meta_tags as $property=>$content): ?>
			<meta property="<?php echo $property; ?>" content="<?php echo addcslashes($content, '"'); ?>" />
			<?php if('og:description' === $property): ?>
				<meta name="description" content="<?php echo addcslashes($content, '"'); ?>" />
			<?php endif; ?>
		<?php endforeach; ?>

	</head>
	<body>

		<?php if(!empty($missing_perms) || empty($config) || empty($pdo)): ?>

			<div style="width:90vw; margin:1em auto; font-family:'Helvetica Neue','Noto Sans','Liberation Sans',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol','Noto Color Emoji'">
				<h1>Fuckit</h1>
				<p>Fuckit cannot access the either the database or the config file. From the command line, please run:</p>
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