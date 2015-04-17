<?php
// app.php

require __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;


require __DIR__.'/registers.php';
//require __DIR__.'/controllers.php';
require __DIR__.'/people.php';
return $app;