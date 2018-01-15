<?php

namespace Tests;

use Tests\TestCaseBase;
use App\Model\Property;

/**
 * Description of TestPropertyModel
 * This Test class test the App\Model\Property as single class
 * @author anan
 */
class TestPropertyModel  extends TestCaseBase{
  
  protected $property;
  protected $dataToDB;
  
  protected function getDataToDB() {
    return [
            'startdate'    => '2018-05-23',
            'enddate'      => '2018-06-05'  ,
            'location'     => -1, 
            'sleeps'       => -1, 
            'beds'         => -1, 
            'near_beach'   => -1,
            'accepts_pets' => -1,
            'limit'        => -1,
            'offSet'       => -1
          ];
  }
  
  public function setUp()
  {
    parent::setUp();
    $this->property= new Property($this->db);
  }
  
   /** @test  */
  public function locations_should_return_array_list_of_location_item() {

    $locations = $this->property->getAllLoacation();
    
    $this->assertTrue(is_array($locations));
    
    //$this->assertEquals(5, count($locations));
    $this->assertCount(5, $locations);
    $this->assertArrayHasKey('__pk', $locations[0]);
    $this->assertArrayHasKey('location_name', $locations[0]);
  }
  
  /** @test  */
  public function getAllLoacation_test_locations_items_records() {
    $locations = $this->property->getAllLoacation();
    foreach($locations as $location){
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
  
  /** @test  */
  public function getProperties_by_default_data(){
    $this->dataToDB = $this->getDataToDB();    
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    
    $this->assertCount(5, $properties);
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[2]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[3]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[4]['property_name'], 'Beach Cottage'); 
    
    
    $count= $this->property->getListCount($this->dataToDB);
    $this->assertEquals(5, $count);

  }
  
  /** @test  */
  public function getProperties_by_date_in_booking_dates(){
    $this->dataToDB = $this->getDataToDB(); 
    
    //we have booking date in our DB from '2017-08-26 to 2017-09-02     
    
    //1- startdate startdate booking date
    $this->dataToDB['startdate'] = '2018-08-30';
    $this->dataToDB['enddate'] = '2018-09-06';
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(4, $properties);
    
    $count= $this->property->getListCount($this->dataToDB);
    $this->assertEquals(4, $count);
    $this->assertEquals( $properties[0]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[1]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[2]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[3]['property_name'], 'Beach Cottage'); 
    
    //2- enddate inside booking date
    $this->dataToDB['startdate'] = '2018-08-20';
    $this->dataToDB['enddate'] = '2018-08-30';
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(4, $properties);
    
    $count= $this->property->getListCount($this->dataToDB);
    $this->assertEquals(4, $count);
    $this->assertEquals( $properties[0]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[1]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[2]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[3]['property_name'], 'Beach Cottage'); 
    
    //3- both startdate and enddate inside booking date
    $this->dataToDB['startdate'] = '2018-08-26';
    $this->dataToDB['enddate'] = '2018-09-02';
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(4, $properties);
    
    $count= $this->property->getListCount($this->dataToDB);
    $this->assertEquals(4, $count);
    $this->assertEquals( $properties[0]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[1]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[2]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[3]['property_name'], 'Beach Cottage'); 
  }
  
  /** @test  */
  public function getProperties_by_change_location(){
    $this->dataToDB = $this->getDataToDB();
    
    $this->dataToDB['location']=1; // Cornwall
    //print_r($this->dataToDB);    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(1, $properties);
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    
    
    $this->dataToDB['location']=5; // Scotland
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));     
    $this->assertCount(2, $properties);
    $this->assertEquals( $properties[0]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[1]['property_name'], 'Coach House'); 

    $this->dataToDB['location']=2; // Lake District    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(0, $properties);  
  }
  
  
  /** @test  */
  public function getProperties_by_change_sleep(){
    $this->dataToDB = $this->getDataToDB();
    $this->dataToDB['sleeps']=6; 
    //print_r($this->dataToDB);
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(2, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Cosey');
    $this->assertEquals( $properties[1]['property_name'], 'Beach Cottage'); 
    

    $this->dataToDB['sleeps']=8; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(1, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Beach Cottage'); 
  }
  
  /** @test  */
  public function getProperties_by_change_beds(){
    $this->dataToDB = $this->getDataToDB();
    $this->dataToDB['sleeps']=3;
    //print_r($this->dataToDB);
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(4, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[2]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[3]['property_name'], 'Beach Cottage'); 
    
    ////////////////
    $this->dataToDB['sleeps']=4; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(4, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[2]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[3]['property_name'], 'Beach Cottage'); 
  }
  
  /** @test  */
  public function getProperties_by_change_near_beach(){
    $this->dataToDB = $this->getDataToDB();
    
    // 1- Yes, it must be near_beach
    $this->dataToDB['near_beach']=1;
    //print_r($this->dataToDB);
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(3, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[2]['property_name'], 'Beach Cottage'); 
    
    // 2- No, it must be not near_beach
    $this->dataToDB['near_beach']=0; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(2, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[1]['property_name'], 'Coach House');  
    
    // 3- Any (yes/no)
    $this->dataToDB['near_beach']=-1; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(5, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[2]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[3]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[4]['property_name'], 'Beach Cottage'); 
  }
  /** @test  */
  public function getProperties_by_change_accepts_pets(){
    $this->dataToDB = $this->getDataToDB();
    
    // 1- Yes, it must be accepts_pets
    $this->dataToDB['accepts_pets']=1; 
    //print_r($this->dataToDB);
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(3, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Coach House');     
    $this->assertEquals( $properties[2]['property_name'], 'Beach Cottage'); 
    
    // 2- No, it must be not accepts_pets
    $this->dataToDB['accepts_pets']=0; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(2, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[1]['property_name'], 'The Retreat'); 
    
    // 3- Any (yes/no)
    $this->dataToDB['accepts_pets']=-1; 
    
    $properties= $this->property->getProperties($this->dataToDB);
    $this->assertTrue(is_array($properties));
    $this->assertCount(5, $properties);
    
    $this->assertEquals( $properties[0]['property_name'], 'Sea View');
    $this->assertEquals( $properties[1]['property_name'], 'Cosey'); 
    $this->assertEquals( $properties[2]['property_name'], 'The Retreat'); 
    $this->assertEquals( $properties[3]['property_name'], 'Coach House'); 
    $this->assertEquals( $properties[4]['property_name'], 'Beach Cottage'); 
  }
}
