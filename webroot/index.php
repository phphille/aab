<?php

require __DIR__.'/config_with_app.php';

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

$di->setShared('getTextFilter', function() {
    $getTextFilter = new \Anax\Content\CTextFilter();
    return $getTextFilter;
});

$di->setShared('paginering', function() {
    $paginering = new \Phpmvc\paginering\CPaginering();
    return $paginering;
});

$di->setShared('pagineringNameUrl', function() {
    $pagineringNameUrl = new \Phpmvc\paginering\CPagineringNameUrl();
    return $pagineringNameUrl;
});

$di->setShared('ccheckUser', function() use ($di){
    $ccheckUser = new \Anax\Login\CCheckUser($di);
    return $ccheckUser;
});

$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');






$di->set('IndexController', function() use ($di) {
    $controller = new \Anax\Index\IndexController();
    $controller->setDI($di);
    return $controller;
});

$app->router->add('', function() use ($app) {

    $app->theme->setTitle("AAB");

    $app->dispatcher->forward([
        'controller' => 'index',
        'action'     => 'index',
    ]);

});




$di->set('QuestionsController', function() use ($di) {
    $controller = new \Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});

$app->router->add('questions', function() use ($app) {

    $app->theme->setTitle("Frågor");

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'index',
    ]);

});

$app->router->add('ask-question', function() use ($app) {

    $app->theme->setTitle("Frågor");

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'ask-question',
    ]);

});




$di->set('TagsController', function() use ($di) {
    $controller = new \Anax\Tags\TagsController();
    $controller->setDI($di);
    return $controller;
});

$app->router->add('tags', function() use ($app) {

    $app->theme->setTitle("Taggar");

    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'index',
    ]);

});



$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});
$app->router->add('users', function() use ($app) {

    $app->theme->setTitle("Användare");

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'index',
    ]);

});



$di->set('LoginController', function() use ($di) {
    $controller = new \Anax\Login\LoginController();
    $controller->setDI($di);
    return $controller;
});
$app->router->add('login', function() use ($app) {

    $app->theme->setTitle("Logga in");

    $app->dispatcher->forward([
        'controller' => 'login',
        'action'     => 'index',
    ]);

});


$app->router->add('source', function() use ($app) {

    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ], 'jumbotron');
});



$app->router->add('about', function() use ($app) {

    $app->theme->setTitle("Om");

    $about = $app->fileContent->get('about.md');
    $about = $app->getTextFilter->doFilter($about, 'shortcode, markdown');


    $app->views->add('default/page', [
        'title' => null,
        'content' => $about
    ], 'jumbotron');

});



$app->router->handle();
$app->theme->render();
