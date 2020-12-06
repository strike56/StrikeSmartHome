<?php
	
	for ($i = 1; $i < count($this->kernel->pages); $i++) {
		$this->kernel->pages[$i-1] = $this->kernel->pages[$i];
	}
	unset($this->kernel->pages[count($this->kernel->pages)-1]);

	$pages = $this->kernel->pages;
	$param = array();
	
	while( count($pages) ) {
	
		$file = dirname(__FILE__)."/layers/".implode("/", $pages).'.php';
		if (is_readable($file)) {
			$param = array_merge( array( implode("/", $pages) ), $param );	
			$stat = include $file;
			return $stat;
		}
		$param = array_merge( array( array_pop($pages) ), $param );	
	}
	
	return false;
