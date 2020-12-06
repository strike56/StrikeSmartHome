<?php

    include_once __DIR__.'/Engine/Autoload.php';

    use Engine\Page;

    print Page::run($_SERVER['REQUEST_URI']);
