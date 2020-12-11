<?php

    include_once __DIR__.'/vendor/autoload.php';

    use Engine\Page;

    print Page::run($_SERVER['REQUEST_URI']);
