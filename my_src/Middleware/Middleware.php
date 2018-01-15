<?php
namespace App\Middleware;

/**
 * Description of Middleware
 *
 * @author anan
 */
class Middleware 
{
  protected $container;
  
  public function __construct($container) {
    $this->container = $container;
  }

}
