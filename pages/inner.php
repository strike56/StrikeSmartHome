<?php
/*
    $u = $_SERVER['REQUEST_URI'];
    if ($u == "" || $u == "/" || $u == PUBLIC_URL || $u == PUBLIC_URL."/") {
        $u = PUBLIC_URL."/lv";
        header("Location: ".$u);
        die();
    }
*/

	$tmpl = $this->kernel->tmpl->createFromFile(PUBLIC_DIR."/tmpl/inner.tmpl");
	$out = $tmpl->get();
