<?php

namespace Tests;

use Slim\Http\Environment;
use Slim\Http\Request;
use App\App;

require_once realpath(dirname(__FILE__)).'/../config.php';

/**
 * Description of TestCaseBase
 *
 * @author anan
 */
abstract class TestCaseBase  extends \PHPUnit_Framework_TestCase{
  
  protected $app;
  
  protected $container;
  
  public function setUp()
  {
    $this->app = (new App())->get(); 
    $this->container = $this->app->getContainer();
  }
  
  public function __get($property) {
    if($this->container->{$property}) {
      return $this->container->{$property};
    }
  }
  
  protected function get($url)
  {
      $env = Environment::mock([
          'REQUEST_METHOD' => 'GET',
          'REQUEST_URI'    => $url,
          ]);
      $req = Request::createFromEnvironment($env);
      $this->app->getContainer()['request'] = $req;
      return $this->app->run(true);
  }
  

  protected function post($url, $body)
  {
      $env = Environment::mock([
          'REQUEST_METHOD' => 'POST',
          'REQUEST_URI'    => $url,
          'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
      ]);
      $req = Request::createFromEnvironment($env)->withParsedBody($body);
      $this->app->getContainer()['request'] = $req;
      return $this->app->run(true);
  }
}
