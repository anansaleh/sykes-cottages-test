<?php
namespace Tests;

use Tests\TestCaseBase;
use App\Controller\FacilityController;

/**
 * Description of FacilityControllerTest
 * The scope of this test class is the logic functionality of the current controller
 * properties list and pagination
 * So I set app->setting->html_output =false in setUp to have json
 *
 * @author anan
 */
class FacilityControllerTest extends TestCaseBase{
  
  protected $facility;
  
  protected $fields;
  
  protected function getSearchField() {
    return [
      'startdate'     => '2018-03-01',
      'enddate'       => '2018-03-20',
      'location'      => '',
      'sleeps'        => '',
      'beds'          => '',
      'near_beach'    => '',
      'accepts_pets'  => '',
    ];
  }
  
  public function setUp()
  {
    parent::setUp();
    
    //set html_output =false
    $this->container->get('settings')['html_output']= FALSE;
    //$this->facility= new FacilityController($this->container);
  }
  
  /** @test  */
  public function index_this_must_show_json_with_locations_and_page_title() {
    $response = $this->get('/');
    $data = json_decode($response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    
    //page title
    $this->assertSame($data['title'], 'Search facility');
    
    //Locations
    //print_r($data['locations']);    
    $this->assertCount(5, $data['locations']);
    foreach($data['locations'] as $location){
      $this->assertArrayHasKey('__pk', $location);
      $this->assertArrayHasKey('location_name', $location);
      switch (intval($location['__pk'])){
        case 1:
          $this->assertEquals( $location['location_name'], 'Cornwall');
          break;
        case 2:
          $this->assertEquals( $location['location_name'], 'Lake District');
          break;
        case 3:
          $this->assertEquals( $location['location_name'], 'Yorkshire');
          break;
        case 4:
          $this->assertEquals( $location['location_name'], 'Wales');
          break;
        case 5:
          $this->assertEquals( $location['location_name'], 'Scotland');
          break;
      }
    }
  }
  
  /**
   * @test
   */
  public function list_properties() {    
    $_SESSION['searchFields']= $this->getSearchField();
    $response = $this->get('/list');
    $data = json_decode($response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    
    //page title
    $this->assertSame($data['title'], 'Available Properties');
    
    //Properties we have 5 records count=5
    //but because the pagination our we set in settings->records_per_page =2
    //So it output 2 records  
    $this->assertCount(2, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(1, $data['pagination']['page']);
    $this->assertEquals(3, $data['pagination']['lastpage']);
    $this->assertTrue($data['pagination']['needed']);
    
    //Change settings->records_per_page
    $this->container->get('settings')['records_per_page'] =3;
    
    //now we must have 3 records for the first page
    $response = $this->get('/list');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(3, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(1, $data['pagination']['page']);
    $this->assertEquals(2, $data['pagination']['lastpage']); 
    $this->assertTrue($data['pagination']['needed']);
    
  }
  /**
   * @test
   */
  public function list_properties_pagination() { 
    //Set settings->records_per_page = 2
    $this->container->get('settings')['records_per_page'] =2;
    
    //default page= 1
    $response = $this->get('/list');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(2, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(1, $data['pagination']['page']);
    $this->assertEquals(3, $data['pagination']['lastpage']);
    $this->assertTrue($data['pagination']['needed']);
    
    $this->assertEquals( $data['properties'][0]['property_name'], 'Sea View');
    $this->assertEquals( $data['properties'][0]['__pk'], 1);
    $this->assertEquals( $data['properties'][1]['property_name'], 'Cosey'); 
    $this->assertEquals( $data['properties'][1]['__pk'], 2);
    
    //page= 2
    $response = $this->get('/list/2');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(2, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(2, $data['pagination']['page']);
    $this->assertEquals(3, $data['pagination']['lastpage']);
    $this->assertTrue($data['pagination']['needed']);
    
    $this->assertEquals( $data['properties'][0]['property_name'], 'The Retreat');
    $this->assertEquals( $data['properties'][0]['__pk'], 3);
    $this->assertEquals( $data['properties'][1]['property_name'], 'Coach House'); 
    $this->assertEquals( $data['properties'][1]['__pk'], 4);
    
    //page= 3
    $response = $this->get('/list/3');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(1, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(3, $data['pagination']['page']);
    $this->assertEquals(3, $data['pagination']['lastpage']);
    $this->assertTrue($data['pagination']['needed']);
    
    $this->assertEquals( $data['properties'][0]['property_name'], 'Beach Cottage');
    $this->assertEquals( $data['properties'][0]['__pk'], 5);
    
    // Set records_per_page = 10
    // pagination['needed'] = false
    // we have 5 properties lastpage= 1, page=1
    $this->container->get('settings')['records_per_page'] =10;
    
    $response = $this->get('/list');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(5, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(1, $data['pagination']['page']);
    $this->assertEquals(1, $data['pagination']['lastpage']);
    $this->assertFalse($data['pagination']['needed']);
    $this->assertEquals( $data['properties'][0]['property_name'], 'Sea View');
    $this->assertEquals( $data['properties'][0]['__pk'], 1);
    $this->assertEquals( $data['properties'][4]['property_name'], 'Beach Cottage');
    $this->assertEquals( $data['properties'][4]['__pk'], 5);
    
    $response = $this->get('/list/2');
    $data = json_decode($response->getBody(), true);
    $this->assertCount(0, $data['properties']);
    $this->assertEquals(5, $data['pagination']['count']);
    $this->assertEquals(2, $data['pagination']['page']);
    $this->assertEquals(1, $data['pagination']['lastpage']);
    $this->assertFalse($data['pagination']['needed']);
  }
}
