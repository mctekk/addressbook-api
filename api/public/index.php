<?php

use Canvas\Bootstrap\Api;

require_once __DIR__ . '/../../library/Core/autoload.php';

$bootstrap = new Api();

$bootstrap->setup();
$bootstrap->run();
