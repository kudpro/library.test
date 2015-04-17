<?php
// people.php

$app->get('/people', 'People::getAllData');
$app->get('/people/add', 'People::addPeopleView');
$app->post('/people/add', 'People::addPeople');
$app->get('/people/edit/{id}', 'People::getEditPeople');
$app->put('/people/edit', 'People::updateEditPeople');
$app->get('/people/delete', 'People::deletePeopleView');
$app->delete('/people/delete', 'People::deletePeople');

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

class People{
        // Получить все данные
        public static function getAllData(Application $app){
            
            $people = $app['db']->fetchAll("SELECT * FROM people");
            return $app['twig']->render('people.twig', array('people' => $people));          
        }
        
        // Добавить данные вью
        public static function addPeopleView(Application $app){
            return $app['twig']->render('people_add.twig');
        }
    
        // Добавить данные
        public static function addPeople(Application $app, Request $request){
            if (!isset($request)){
                return $app['twig']->render('people_add.twig');            
            } 
            elseif ($request->get('p_name') == '' || $request->get('p_lastname') == '' || $request->get('p_post') == ''){
                $error = "Not all the data entered";
                return $app['twig']->render('error.twig', array('error' => $error));
            }
            $app['db']->insert('people', array('p_name' => $request->get('p_name'), 'p_lastname' => $request->get('p_lastname'), 'p_post' => $request->get('p_post'),));;
            return $app['twig']->render('ok.twig');             
        
        }
    
        // Получить данные для обновления
        public static function getEditPeople(Application $app, $id){
            
                if (!isset($id)) {
                    $app->abort(404, "People $id does not exist.");
                }
                $people = $app['db']->executeQuery('SELECT * FROM people WHERE p_id = ?', array($id));
                if (count($people) == 0) {
                    $app->abort(404, "People $id does not exist.");
                }
                return $app['twig']->render('people_edit.twig', array('people' => $people));
        }
     
        
        // Обновить данные
        public static function updateEditPeople(Application $app, Request $request){
            
                if (!isset($request)) {
                    $app->abort(404, "Request $id does not exist.");
                }
                if($request->get('p_id') == '' ||  $request->get('p_name') == '' || $request->get('p_lastname') == '' || $request->get('p_post') == ''){
                    $error = "Not all the data entered ";
                    return $app['twig']->render('error.twig', array('error' => $error));
                } else{
                    $p_id = $request->get('p_id');
                    $p_name = $request->get('p_name');
                    $p_lastname = $request->get('p_lastname');
                    $p_post = $request->get('p_post');                 
                }                                
                
                $app['db']->update('people', array('p_name' => $p_name, 'p_lastname' => $p_lastname, 'p_post' => $p_post), array('p_id' => $p_id));
                return $app['twig']->render('ok.twig');
        }
        // Удаление вью
        public static function deletePeopleView(Application $app, Request $request){
            
                if(!isset($request) || $request->get('p_id') == ''){
                    $error = "Error ID";
                    return $app['twig']->render('error.twig', array('error' => $error));
                }
                $p_id = $request->get('p_id');
                $p_name = $request->get('p_name');
                $p_lastname = $request->get('p_lastname');
                return $app['twig']->render('people_delete.twig', array('p_id' => $p_id, 'p_name' => $p_name, 'p_lastname' => $p_lastname));
        }
        // Удаление данных
        public static function deletePeople(Application $app, Request $request){
            
                if(!isset($request) || $request->get('p_id') == ''){
                    $error = "Error ID";
                    return $app['twig']->render('error.twig', array('error' => $error));
                }
                $app['db']->delete('people', array('p_id' => $request->get('p_id')));
                return $app['twig']->render('ok.twig');
        }        
        
        
    }
