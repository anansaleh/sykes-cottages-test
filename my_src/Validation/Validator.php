<?php

namespace App\Validation;

/**
 * Description of Validation
 *
 * @author anan
 */
class Validator 
{
  public $errors;
  
  protected $dateFormat ;
  
  public function __construct($dateFormat = 'Y-m-d') {
    $this->dateFormat = $dateFormat;
  }

  public function validate(array $args) {
//    echo "<pre>";
//    echo print_r($args);
//    echo "</pre>";
    
    $startdate    = (array_key_exists('startdate'   , $args) ? $args['startdate']     : '');
    $enddate      = (array_key_exists('enddate'     , $args) ? $args['enddate']       : '');
    $location     = (array_key_exists('location'    , $args) ? $args['location']      : '');
    $sleeps       = (array_key_exists('sleeps'      , $args) ? $args['sleeps']        : '');
    $beds         = (array_key_exists('beds'        , $args) ? $args['beds']          : '');
    $near_beach   = (array_key_exists('near_beach'  , $args) ? $args['near_beach']    : '0');
    $accepts_pets = (array_key_exists('accepts_pets', $args) ? $args['accepts_pets']  : '0');
    
    // 1- Check-in
    if(!$this->isDate($startdate)){
      $this->errors['startdate']= 'invalid Date, must be like 2018-12-30';
    }else{
      $startdate = new \DateTime($startdate);
      $date2 =  new \DateTime('tomorrow');
      
      if($startdate < $date2){
        $this->errors['startdate']= 'Check-in, must be at least ' . $date2->format($this->dateFormat);
      }
    }

    // 2- Check-out
    if(!$this->isDate($enddate)){
      $this->errors['enddate']= 'invalid Date, must be like 2018-12-30';
    }else{
      $enddate = new \DateTime($enddate);
      
      if($startdate instanceof \DateTime && $enddate <= $startdate){
        $this->errors['enddate']= 'Check-out biggest than Check-in at les one day.';
      }
    }
    
    //3-Check location 
    // must be empty or integer
    if(!empty($location) && ( !is_numeric($location) || !is_int(intval($location)) ) ){
      $this->errors['location']= 'invalid location value.';
    }

    //4-Check sleeps
    // must be empty or integer
    if(!empty($sleeps) && ( !is_numeric($sleeps) || !is_int(intval($sleeps)) ) ){
      $this->errors['sleeps']= 'invalid sleeps value.';
    }
    
    //5-Check sleeps
    // must be empty or integer
    if(!empty($beds) && ( !is_numeric($beds) || !is_int(intval($beds)) )){
      $this->errors['beds']= 'invalid beds value.';
    }
    
    //6-Check near_beach
    // 0: any, 1:No, 2: yes
    if(!empty($near_beach)){
      if ( !is_numeric($near_beach) || !is_int(intval($near_beach)) ){
        $this->errors['near_beach']= 'invalid near beach value.';
      }elseif($near_beach < 0 || $near_beach > 2){
        $this->errors['near_beach']= 'invalid near beach value, must be 0, 1 or 2';
      }
      
    }   
    
    //7-Check accepts_pets
    // 0: any, 1:No, 2: yes
    if(!empty($accepts_pets) ){
      if ( !is_numeric($accepts_pets) || !is_int(intval($accepts_pets)) ){
        $this->errors['accepts_pets']= 'invalid accepts pets value.';
      }elseif($accepts_pets < 0 || $accepts_pets > 2){
        $this->errors['accepts_pets']= 'invalid accepts pets value, must be 0, 1 or 2';
      }
    }   
//    echo "<pre>";
//    echo print_r($this->errors);
//    echo "</pre>";
    return $this;
  }
  
  public function isDate($date)
  {
      $d = \DateTime::createFromFormat($this->dateFormat, $date);
      return $d && $d->format($this->dateFormat) == $date;
  }
  
  public function failed() {
    return !empty($this->errors);
  }


}

