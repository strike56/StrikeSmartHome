<?php

	$type = $this->kernel->pages[1];
	if (!$type) return false;
	$subtype = isset($this->kernel->pages[2]) ? $this->kernel->pages[2] : false;
	
	header("X-Robots-Tag: noindex, nofollow");
	
############################################################
	
	$type = preg_replace("#[^a-zA-Z0-9_-]#", "", $type);
	$f = __DIR__."/ajax/".$type.".php";
	if (is_readable($f)) {
		$stat = include $f;
		return $stat;
	}
	
############################################################
	
	return false;