<?php
// app.php

require __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

require __DIR__.'/registers.php';
require __DIR__.'/main.php';
require __DIR__.'/people.php';
require __DIR__.'/book.php';

return $app;