<?php
// registers.php


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../web/views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_pgsql', 
        'dbname' => 'library',
        'host' => '127.0.0.1',
        'user' => 'testuser',
        'password' => 'testuser',
        'charset' => 'utf8'
    )
));
