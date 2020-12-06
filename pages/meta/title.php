<?php

    if (!empty($this->kernel->exchange->meta["title"])) {
        $title = $this->kernel->exchange->meta["title"];
    } else {
        $title = $this->kernel->api->msg->get("site.title");
    }

    $out = $title;