<?php
// main.php

$app->get('/', 'MainView::index');

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class MainView{
    public static function index(Application $app){
        $people = $app['db']->fetchAll("SELECT a.p_id,a.p_name,a.p_lastname,a.p_post,b.b_name,b.b_author,b.b_holder FROM people AS a LEFT JOIN book AS b ON b.b_holder = a.p_id;");
        return $app['twig']->render('index.twig', array('people' => $people));
    }
}
