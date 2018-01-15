<?php

namespace Tests;

use Tests\TestCaseBase;
use PHPHtmlParser\Dom;

/**
 * Description of TodoTest
 * This test class test the routes and the web page output
 * 
 * @author anan
 */
//./vendor/bin/phpunit --colors ./tests/TodoTest.php
class RoutesTest  extends TestCaseBase{
    
  /**
   * @test
   */
  public function index_status_code_should_be_200() {
    $response = $this->get('/');
    $this->assertSame($response->getStatusCode(), 200);
  }
    
  /**
   * @test
   */
  public function should_return_a_404_with_Page_not_found() {
    $response = $this->get('/blobblobblob');

    $this->assertSame($response->getStatusCode(), 404);
    $this->assertContains((string)$response->getBody(), "Page not found");
  }

  /**
   * @test
   */
  public function index_body_include_html() {
    $response = $this->get('/');
    
    $data = (string) $response->getBody();
    
    $dom = new Dom();
    $dom->load($data);
    $h1=$dom->find('h1')[0];
    $this->assertContains((string)$h1, "<h1>Sykes Cottages</h1>");
    
    $h3=$dom->find('h3')[0];
    $this->assertContains((string)$h3, "<h3>Search facility</h3>");
  }

  /**
   * @test
   */
  public function list_route_before_search_post() {
    if (isset($_SESSION['searchFields'])){
      unset($_SESSION['searchFields']);
    }
    
    $response = $this->get('/list');
    //if (!isset($_SESSION['searchFields'])) {
      //Redirect to index page '/'
      $this->assertSame($response->getStatusCode(), 302);
      
      $headers = $response->getHeaders();
      $this->assertSame($headers['Location'][0], '/');
      $this->assertSame($headers['Content-Type'][0], 'text/html; charset=UTF-8');
      
    //}else{
    //  $this->assertSame($response->getStatusCode(), 200);
    //}
    
  }
  
  /**
   * @test
   */
  public function search_route_with_get_method() {
    $response = $this->get('/search');
    $this->assertSame($response->getStatusCode(), 405);
  }
  
   /**
   * @test
   */
  public function search_route_with_post_empty_data() {
    $response = $this->post('/search',[]);
    $headers = $response->getHeaders();    
    
    $this->assertSame($response->getStatusCode(), 302);
    $this->assertSame($headers['Location'][0], '/');
    $this->assertSame($headers['Content-Type'][0], 'text/html; charset=UTF-8');
    
    //print_r($headers['Location']);
  }
  
  /**
   * @test
   */
  public function search_route_with_post_data() {  
    if (isset($_SESSION['searchFields'])){
      unset($_SESSION['searchFields']);
    }
    
    $response = $this->post('/search',[
      'startdate'     => '2018-03-01',
      'enddate'       => '2018-03-20',
//      'location'      => '',
//      'sleeps'        => '',
//      'beds'          => '',
//      'near_beach'    => '',
//      'accepts_pets'  => '',
    ]);
    $headers = $response->getHeaders();    
    
    $this->assertSame($response->getStatusCode(), 302);
    $this->assertSame($headers['Location'][0], '/list');
    $this->assertSame($headers['Content-Type'][0], 'text/html; charset=UTF-8');
    
    //print_r($headers['Location']);
  }
  
  /**
   * @test
   */
  public function list_route_after_search_post() {
    //$_SESSION['searchFields'] is set now
    
    $response = $this->get('/list');
    $this->assertSame($response->getStatusCode(), 200);
    $data = (string) $response->getBody();
    
    $dom = new Dom();
    $dom->load($data);
    $h1=$dom->find('h1')[0];
    $this->assertContains((string)$h1, "<h1>Sykes Cottages</h1>");
    
    $h3=$dom->find('h3')[0];
    $this->assertContains((string)$h3, "<h3>Available Properties</h3>");
  }
}
