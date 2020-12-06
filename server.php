<?php

    include __DIR__ . '/Engine/Autoload.php';

    use \Engine\Server;

    Server::init();
    Server::run();