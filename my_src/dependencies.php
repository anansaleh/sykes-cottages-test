<?php

/* 
 * Application configuration
 */
$container = $app->getContainer();

//

// Looad database Connection 
$container['db'] = function ($container) {
  $settings = $container->get('settings')['db'];
  $db = new App\Database\DbConnect($settings);
  return $db->getConnection();

//    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['database_name'] .";port=3306",
//        $settings['username'], $settings['password']);
    
//    $pdo= new PDO("mysql:host=". $settings['host'] .";dbname=sykes_interview","root", "root");
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    
};

//Load View engine Twig for render and set extensions and folder
$container['view'] = function ($container) {
    
  //Get views render folder from settings. see settings.php
  $settings = $container->get('settings')['renderer'];

  //create Slim\Views\Twig instanse 
  $view = new \Slim\Views\Twig($settings['template_path'], [
    'cache' => FALSE, // for production change this to the directory of the cache folder
    'debug' => true,
  ]);

  //Add option extensions
  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));
  $view->addExtension(new Twig_Extension_Debug());
  return $view;
};


// Load Validation for validate post form
$container['validator'] = function ($container) {
  return new App\Validation\Validator($container->get('settings')['dateFormat']);
};

$container['notFoundHandler'] = function ($container) {
  return function ($request, $response) use ($container) {
    $res = $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    return $res;
  };
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));

//$app->add(function ($request, $response, callable $next) {
//    $route = $request->getAttribute('route');
//    print($request->getUri());
//    var_dump($route);
//    // return NotFound for non existent route
//    if (empty($route)) {
//        throw new \\NotFoundException($request, $response);
//    }
//
//    $name = $route->getName();
//    $groups = $route->getGroups();
//    $methods = $route->getMethods();
//    $arguments = $route->getArguments();
//
//    // do something with that information
//
//    return $next($request, $response);
//});