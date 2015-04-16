<?php
// controllers.php

$app->get('/', function () use ($app) {
    $people = $app['db']->fetchAll("SELECT a.id,a.name,a.lastname,a.post,b.book_name,b.book_holder FROM people AS a LEFT JOIN book AS b ON b.book_holder = a.id;");
    return $app['twig']->render('index.twig', array('people' => $people));
});

$app->get('/people', function () use ($app) {
    $sql = "SELECT * FROM people";
    $people = $app['db']->fetchAll($sql);
    return $app['twig']->render('people.twig', array('people' => $people));
});

$app->get('/people/add', function () use ($app) {
    return $app['twig']->render('people_add.twig');
});

$app->get('/people/add/', function () use ($app) {
    $app['db']->insert('people', array('name' => $_GET['name'], 'lastname' => $_GET['lastname'], 'post' => $_GET['post']));
    return $app['twig']->render('people_add.twig');
});

$app->get('/people/edit/', function () use ($app) {
    $app['db']->update('people', array('name' => $_GET['name'], 'lastname' => $_GET['lastname'], 'post' => $_GET['post'],), array('id' => $_GET['id']));
    return $app['twig']->render('people_add_ok.twig');
});

$app->get('/people/edit/{id}', function ($id) use ($app) {
    $sql = "SELECT * FROM people WHERE id = ".$id."";
    $people = $app['db']->fetchAll($sql);
    return $app['twig']->render('people_edit.twig', array('people' => $people));
});

$app->get('/people/delete/{id}', function ($id) use ($app) {
        $app['db']->delete('people', array('id' => $id));
        return $app['twig']->render('people_add_ok.twig');
});

$app->get('/books', function () use ($app) {
    $book = $app['db']->fetchAll("SELECT a.book_id,a.book_name,a.book_author,a.book_holder,b.id,b.name,b.lastname FROM book AS a LEFT JOIN people AS b ON a.book_holder = b.id;");
    return $app['twig']->render('books.twig', array('book' => $book));
});

$app->get('/books/add/', function () use ($app) {
    if(isset($_GET['book_name'])){
        $app['db']->insert('book', array('book_name' => $_GET['book_name'], 'book_author' => $_GET['book_author']));        
        return $app['twig']->render('book_add_ok.twig');
    }
    else{
        return $app['twig']->render('book_add.twig');
    }
});

$app->get('/books/delete/{id}', function ($id) use ($app) {
        $app['db']->delete('book', array('book_id' => $id));
        return $app['twig']->render('book_add_ok.twig');
});

$app->get('/books/edit/{id}', function ($id) use ($app) {
        $book = $app['db']->fetchAll('SELECT * FROM book WHERE book_id = ?', array($id));
        return $app['twig']->render('book_edit.twig', array('book' => $book));
});

$app->get('/books/edit/', function () use ($app) {
        $app['db']->update('book', array('book_name' => $_GET['book_name'], 'book_author' => $_GET['book_author']), array('book_id' => $_GET['book_id']));        
        return $app['twig']->render('book_add_ok.twig');
});

$app->get('/books/change/{id}', function ($id) use ($app) {
        $book = $app['db']->fetchAll('SELECT * FROM book WHERE book_id = ?', array($id));
        $people = $app['db']->fetchAll('SELECT * FROM people');
        return $app['twig']->render('book_change_holder.twig', array('book' => $book, 'people' => $people));
});
$app->get('/books/change/', function () use ($app) {
        $app['db']->update('book', array('book_holder' => $_GET['people_id']), array('book_id' => $_GET['book_id']));        
        return $app['twig']->render('book_add_ok.twig');
});