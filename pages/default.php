<?php


    if ($this->kernel->page == "Calendar") {
        header("Location: /lv/calendar");
        die();
    }

    $code = str_replace('-', '.', $this->kernel->page);

	$document = $this->kernel->api->document->get( $code, false, true );

	if (!$document) return false;

//    $r = $this->kernel->db->query("SELECT * FROM _menu WHERE code = '$code'");
//    if ($r && $r->numRows()) {
//        $menu = $r->getRow();
//        if (!$menu['default']) return false;
//    }

//	if (isset($document['redirect']) && $document['redirect']) {
//		$url = preg_match("#^https?://#", $document['redirect']) ? $document['redirect'] : (str_replace(array('%url', '%lang'), array($this->kernel->cfg['url'], $this->kernel->langName), $document['redirect']));
//		header("Location: $url");
//		die();
//	}

	if (!isset($document['text']) || !$document['text']) return false;

    $tmplFile = PUBLIC_DIR."/tmpl/".$this->kernel->page.".tmpl";
    if (!is_readable($tmplFile)) {
        $tmplFile = PUBLIC_DIR."/tmpl/default.tmpl";
    }
	$tmpl = $this->kernel->tmpl->createFromFile($tmplFile);
    $tmpl->set($document);

//	if (isset($document['title']) && $document['title']) {
//		$tmpl->block('TITLE')->setVar(array(
//			'title'	=> $document['title']
//		))->parse();
//	}
//	unset($document['title']);

//	if (isset($document['image']) && $document['image']) {
//		$imageHTML = $tmpl->block('image')->setVar('src', $this->kernel->image->get($document['image']))->parse();
//		$document['text'] = str_replace('[image]', $imageHTML, $document['text'], $count);
//		if ($count) $tmpl->block('IMAGE')->reset();
//	}

//	$textTmpl = $this->kernel->tmpl->create( $document['text'] );
//	$document['text'] = $textTmpl->get();

//	$tmpl->setVar(array(
//		'title'	=> $this->kernel->api->msg->get("menu.$code")
//	))->setVar( $document );

	$out = $tmpl->get();

	return true;
