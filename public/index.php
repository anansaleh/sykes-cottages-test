<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__.'/../config.php';

require __DIR__.'/../vendor/autoload.php';

session_start();  


// Run app
$app = (new App\App())->get();

$app->run();

//session_destroy();