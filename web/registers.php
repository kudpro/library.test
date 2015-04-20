<?php
// registers.php


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../web/views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql', 
        'dbname' => 'kudpro_library',
        'host' => '127.0.0.1',
        'user' => 'library',
        'password' => 'library',
        'charset' => 'utf8'
    )
));