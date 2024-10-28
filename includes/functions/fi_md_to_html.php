<?php

function fi_md_to_html($markdown){
	$markdown = preg_replace('/!\[[^\]]*\]\((assets\/[^\)]+)\)/', '![]('.$GLOBALS['config']->base_url.'$1)', $markdown);
	$Parsedown = new Parsedown();
	return $Parsedown->text($markdown);
}