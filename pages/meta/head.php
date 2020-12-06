<?php

    if (!empty($this->kernel->exchange->meta["head"])) {
        $out = implode("\r\n", $this->kernel->exchange->meta["head"]);
    } else $out = "";
