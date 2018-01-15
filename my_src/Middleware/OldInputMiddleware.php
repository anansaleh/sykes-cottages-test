<?php

namespace App\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
/**
 * Description of OldInputMiddleware
 *
 * @author anan
 */
class OldInputMiddleware extends Middleware
{
  public function __invoke(Request $request, Response $response, $next) {
    
    //Set the session of the old input into the view
    //
    // for the first time we enter the page the $_SESSION['old'] is empty
    // and the we fill it with the request parametrs
    // So when we re-enter the form the session[old] has data.
    
    if (isset($_SESSION['old'])) {
      $this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
    }else{
      $this->container->view->getEnvironment()->addGlobal('old', array());
    }
    
    $_SESSION['old'] = $request->getParams();
//    echo "Old session";
//    echo "<pre>";
//    echo print_r($_SESSION['old']);
//    echo "</pre>";
    $response = $next($request, $response);
    return $response;
  }
}