<?php
// book.php

$app->get('/book', 'Book::getAllData');
$app->get('/book/add', 'Book::addBookView');
$app->post('/book/add', 'Book::addBook');
$app->get('/book/edit/{id}', 'Book::getEditbook');
$app->put('/book/edit', 'Book::updateEditbook');
$app->get('/book/delete', 'Book::deleteBookView');
$app->delete('/book/delete', 'Book::deleteBook');
$app->get('/book/change/{id}', 'Book::changeHolderView');
$app->put('/book/change', 'Book::changeHolder');

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

class Book{
        // Получить все данные
        public static function getAllData(Application $app){
            
            $book = $app['db']->fetchAll("SELECT a.b_id,a.b_name,a.b_author,a.b_holder,b.p_id,b.p_name,b.p_lastname FROM book AS a LEFT JOIN people AS b ON a.b_holder = b.p_id;");
            return $app['twig']->render('book.twig', array('book' => $book));          
        }
        
        // Добавить данные вью
        public static function addbookView(Application $app){
            return $app['twig']->render('book_add.twig');
        }
    
        // Добавить данные
        public static function addbook(Application $app, Request $request){
            if (!isset($request)){
                return $app['twig']->render('book_add.twig');            
            } 
            elseif ($request->get('b_name') == '' || $request->get('b_author') == ''){
                $error = "Not all the data entered";
                return $app['twig']->render('error.twig', array('error' => $error));
            }
            $app['db']->insert('book', array('b_name' => $request->get('b_name'), 'b_author' => $request->get('b_author')));;
            return $app['twig']->render('ok.twig');             
        
        }
    
        // Получить данные для обновления
        public static function getEditBook(Application $app, $id){
            
                if (!isset($id)) {
                    $app->abort(404, "book $id does not exist.");
                }
                $book = $app['db']->executeQuery('SELECT * FROM book WHERE b_id = ?', array($id));
                if (count($book) == 0) {
                    $app->abort(404, "book $id does not exist.");
                }
                return $app['twig']->render('book_edit.twig', array('book' => $book));
        }
     
        
        // Обновить данные
        public static function updateEditBook(Application $app, Request $request){
            
                if (!isset($request)) {
                    $app->abort(404, "Request $id does not exist.");
                }
                if($request->get('b_id') == '' ||  $request->get('b_name') == '' || $request->get('b_author') == ''){
                    $error = "Not all the data entered ";
                    return $app['twig']->render('error.twig', array('error' => $error));
                } else{
                    $b_id = $request->get('b_id');
                    $b_name = $request->get('b_name');
                    $b_author = $request->get('b_author');         
                }                                
                
                $app['db']->update('book', array('b_name' => $b_name, 'b_author' => $b_author), array('b_id' => $b_id));
                return $app['twig']->render('ok.twig');
        }
        // Удаление вью
        public static function deleteBookView(Application $app, Request $request){
            
                if(!isset($request) || $request->get('b_id') == ''){
                    $error = "Error ID";
                    return $app['twig']->render('error.twig', array('error' => $error));
                }
                $b_id = $request->get('b_id');
                $b_name = $request->get('b_name');
                $b_author = $request->get('b_author');
                return $app['twig']->render('book_delete.twig', array('b_id' => $b_id, 'b_name' => $b_name, 'b_author' => $b_author));
        }
        // Удаление данных
        public static function deleteBook(Application $app, Request $request){
            
                if(!isset($request) || $request->get('b_id') == ''){
                    $error = "Error ID";
                    return $app['twig']->render('error.twig', array('error' => $error));
                }
                $app['db']->delete('book', array('b_id' => $request->get('b_id')));
                return $app['twig']->render('ok.twig');
        }        
        public static function changeHolderView(Application $app, $id){
            $book = $app['db']->fetchAll('SELECT * FROM book WHERE b_id = ?', array($id));
            $people = $app['db']->fetchAll('SELECT * FROM people');
            return $app['twig']->render('book_change_holder.twig', array('book' => $book, 'people' => $people));
        }
        
        public static function changeHolder(Application $app, Request $request){
            if (!isset($request)) {
                    $app->abort(404, "Request $id does not exist.");
            }
            $app['db']->update('book', array('b_holder' => $request->get('p_id')), array('b_id' => $request->get('b_id')));        
            return $app['twig']->render('ok.twig');
        }
        
    }
