<?php

namespace App\Controller;

use App\Model\Property;
use Slim\Views\Twig as View;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as valid;


/**
 * Description of FacilityController
 *
 * @author anan
 */
class FacilityController extends BaseController
{
  private $property;
    
    
  public function __construct($container) {
    parent::__construct($container);
    $this->property= new Property($container->db);
  }

  
  public function index(Request $request, Response $response) {
    $data = $this->property->getAllLoacation();
    $outData = [
      'title'     => 'Search facility',
      'locations' => $data
    ];
    if($this->container->get('settings')['html_output']){
      return $this->view->render($response, 'index.twig', $outData);
    }else{
      $response = $response->withStatus(200);
      $response = $response->withJson($outData);
      return $response;
    }
    
  }
  
  public function searchProperties(Request $request, Response $response) {
    $searchFields =[
      'startdate'     => $request->getParam('startdate'),
      'enddate'       => $request->getParam('enddate'),
      'location'      => $request->getParam('location'),
      'sleeps'        => $request->getParam('sleeps'),
      'beds'          => $request->getParam('beds'),
      'near_beach'    => $request->getParam('near_beach'),
      'accepts_pets'  => $request->getParam('accepts_pets'),
    ];
    
    //Validate searchfield
    $validation = $this->validator->validate($searchFields); 
    
    if($validation->failed()){
      $_SESSION['errors'] = $validation->errors;
      return $response->withRedirect('/');
    }
    
    $_SESSION['searchFields'] = $searchFields;
    
    //Redirect to list page
    return $response->withRedirect('/list');

  }
  
  public function getList( $request, $response, $args = null) {
    
    //Check Session Search
    if (!isset($_SESSION['searchFields'])) {
      return $response->withRedirect('/');
    }    
    $searchFields = $_SESSION['searchFields'];
    
    //Set pagination values
    $page = $request->getAttribute('page');
    if(!$page){
      $page = 1;
    }
    
    $page      = (intval($page) > 0) ? $page : 1;
    $limit     = $this->container->get('settings')['records_per_page']; // Number of posts on one page.
    $offSet      = ($page - 1) * $limit;
    
    
    //prepare data for Search in DB    
    // -1 : any
    $dataToDB =[
          'startdate'    => $searchFields['startdate'],
          'enddate'      => $searchFields['enddate']  ,
          'location'     => (array_key_exists('location'    , $searchFields) ? $searchFields['location']      : -1), 
          'sleeps'       => (array_key_exists('sleeps'      , $searchFields) ? $searchFields['sleeps']        : -1), 
          'beds'         => (array_key_exists('beds'        , $searchFields) ? $searchFields['beds']          : -1), 
          'near_beach'   => (array_key_exists('near_beach'  , $searchFields) ? intval($searchFields['near_beach']) -1    : -1),
          'accepts_pets' => (array_key_exists('accepts_pets', $searchFields) ? intval($searchFields['accepts_pets']) -1  : -1),
          'limit'        => $limit,
          'offSet'       => $offSet
    ];
    $dataToDB["location"] = (empty($dataToDB["location"])? -1 : $dataToDB["location"]);
    $dataToDB["sleeps"] = (empty($dataToDB["sleeps"])? -1 : $dataToDB["sleeps"]);
    $dataToDB["beds"] = (empty($dataToDB["beds"])? -1 : $dataToDB["beds"]);
    

//    echo "<pre>";
//    echo print_r($dataToDB);
//    echo "</pre>";
    
    $data = $this->property->getProperties($dataToDB);
//    echo "<pre>";
//    echo print_r($data);
//    echo "</pre>";

    $count     = $this->property->getListCount($dataToDB); // Count of all available properties
    //print $count;

    $outData =[
        'title'       => 'Available Properties',
        'properties'  => $data,
        'pagination'  => [
            'needed'        => $count > $limit,
            'count'         => $count,
            'page'          => $page,
            'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
            'limit'         => $limit
        ],
        
    ];
    //$this->container->get('settings')['html_output']= FALSE;
    if($this->container->get('settings')['html_output']){
      return $this->view->render($response, 'list.twig', $outData);
    }else{
      $response = $response->withStatus(200);
      $response = $response->withJson($outData);
      return $response;
    }
    

  }


}
