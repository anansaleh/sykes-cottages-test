<?php

//$app->get('/', function ($request, $response){
//  //return 'Home';
//  return $this->view->render($response, 'index.twig');
//});
$app->get('/', "App\Controller\FacilityController:index");

//$app->get('/', "App\Controller\FacilityController:index")->setName('home');
//<form id="defaultForm" method="post" class="form-horizontal" action="{{ path_for('availability')}}">

$app->post('/search', "App\Controller\FacilityController:searchProperties")->setName('availability');

//$app->get('/list', "App\Controller\FacilityController:getList");
//$app->get('/list/{page:[0-9]+}', "App\Controller\FacilityController:getList");

$app->group('/list', function () {
  $this->map(['GET'], '', "App\Controller\FacilityController:getList");
  $this->map(['GET'], '/', "App\Controller\FacilityController:getList");
  $this->get('/{page:[0-9]+}', "App\Controller\FacilityController:getList");
});

$app->get('/jsontest', function ($request, $response, $args) {
    $response = $response->withStatus(201);
    $response = $response->withJson(['foo' => 'bar']);
    return $response;
});

//$app->get( '/list/{page}', function( $request, $response,  $args = [] ) use( $container )
//{
//    //$name = $request->getAttribute( "route" );
//    //var_dump($name);
//    $obj = new App\Controller\FacilityController($container);
//    return $obj->getList($request, $response, [$args]);
//    //$obj->getList($request, $response, $name );
//});



