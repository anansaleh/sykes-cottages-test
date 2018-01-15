<?php

namespace App\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Description of ValidationErrorsMiddleware
 *
 * @author anan
 */
class ValidationErrorsMiddleware extends Middleware
{
  public function __invoke(Request $request, Response $response, $next) {
    
    //Get the session errors and pass them to the view
    @$this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
    
    //remove the errors from the session
    unset($_SESSION['errors']);
    
    
    
    $response = $next($request, $response);
    return $response;
  }
}
