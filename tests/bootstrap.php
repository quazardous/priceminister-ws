<?php

if (!is_file(__DIR__ . '/config.php')) {
    throw new \RuntimeException('Please copy and adapt the file tests/config-dist.php to tests/config.php !');
}

include_once __DIR__ . '/config.php';

include_once __DIR__ . '/../vendor/autoload.php';