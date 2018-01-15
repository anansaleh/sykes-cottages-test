<?php

namespace Tests;

use Tests\TestCaseBase;
use App\Validation\Validator;

/**
 * Description of ValidatorTest
 * This Test class test the App\Validation\Validator as single class
 *
 * @author anan
 */
class ValidatorTest  extends TestCaseBase{
  protected $validator;
  
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
    $this->validator= new Validator();
  }
  /** @test  */
  public function validate_all_fields() {
    $this->fields= $this->getSearchField();
    $validation = $this->validator->validate($this->fields); 
    $this->assertTrue(empty($validation->errors));    
    $this->assertFalse($validation->failed()); 
  }
  
  /** @test  */
  public function validate_dates_fields() {
    
    //////////////////////////////
    //startdate test
    
    //change startdate date format
    $this->fields['startdate'] = '01-03-2018';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['startdate'], "invalid Date, must be like 2018-12-30");
    
    
    //startdate not date
    $this->fields['startdate'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['startdate'], "invalid Date, must be like 2018-12-30");
    
    
    //startdate les than tomorrow
    $this->fields['startdate'] = date("Y-m-d");
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['startdate'], "Check-in, must be at least 2018-01-16");
    
    //////////////////////////////
    //enddate test
    
    $this->fields= $this->getSearchField();
    
    //change enddate date format
    $this->fields['enddate'] = '20-03-2018';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['enddate'], "invalid Date, must be like 2018-12-30");
    
    //enddate not date
    $this->fields['enddate'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['enddate'], "invalid Date, must be like 2018-12-30");
    
    //enddate les than tomorrow or startdate
    $this->fields['startdate'] = date("Y-m-d", strtotime("tomorrow"));
    $this->fields['enddate'] = date("Y-m-d");
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['enddate'], "Check-out biggest than Check-in at les one day.");    
    
  }
  
  /** @test  */
  public function validate_location_field_is_not_number() {
    $this->fields= $this->getSearchField();
    $this->fields['location'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['location'], "invalid location value.");
  }
  
  /** @test  */
  public function validate_sleeps_field_is_not_number() {
    $this->fields= $this->getSearchField();
    $this->fields['sleeps'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['sleeps'], "invalid sleeps value.");
  }
  
  /** @test  */
  public function validate_beds_field_is_not_number() {
    $this->fields= $this->getSearchField();
    $this->fields['beds'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['beds'], "invalid beds value.");
  }
  
  
  /** @test  */
  public function validate_near_beach_field() {
    
    $this->fields= $this->getSearchField();
    
    //more than 2
    $this->fields['near_beach'] = 3;
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['near_beach'], "invalid near beach value, must be 0, 1 or 2");
    
    //les than 0
    $this->fields['near_beach'] = -1;
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['near_beach'], "invalid near beach value, must be 0, 1 or 2");
    
    //more not a number
    $this->fields['near_beach'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['near_beach'], "invalid near beach value.");
  }
  
  /** @test  */
  public function validate_accepts_pets_field() {
    
    $this->fields= $this->getSearchField();
    
    //more than 2
    $this->fields['accepts_pets'] = 3;
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['accepts_pets'], "invalid accepts pets value, must be 0, 1 or 2");
    
    //les than 0
    $this->fields['accepts_pets'] = -1;
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['accepts_pets'], "invalid accepts pets value, must be 0, 1 or 2");
    
    //more not a number
    $this->fields['accepts_pets'] = '5dsdkdkssjd';
    $validation = $this->validator->validate($this->fields); 
    $this->assertFalse(empty($validation->errors));    
    $this->assertTrue($validation->failed()); 
    //print_r($validation->errors);    
    $this->assertContains($validation->errors['accepts_pets'], "invalid accepts pets value.");
  }
}
