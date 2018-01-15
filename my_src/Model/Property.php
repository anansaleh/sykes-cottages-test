<?php

namespace App\Model;


/**
 * Description of Loacation
 *
 * @author anan
 */
class Property
{
  
  /**
   *
   * @var PDO database connection
   */
  private $db;
  
  // constructor with $db as database connection
  public function __construct(\PDO $db){
    $this->db = $db;
  }
  
  public function getAllLoacation() {
    $query = "SELECT * FROM locations ORDER BY location_name";
    
    // prepare query statement
    $stmt = $this->db->prepare($query);
    
    // execute query
    $stmt->execute();
    
    //return $stmt->fetchAll(\PDO::FETCH_CLASS);
    return $stmt->fetchAll();
  }
  
  public function getProperties($param=null) {
    $query = $this->getQueryString($param);
    if($param['limit'] >= 1){
      $query .= " LIMIT ".$param['limit']." OFFSET " .$param['offSet'] . " ";
    }
    //print($query);
    //LIMIT 2 OFFSET 2(start from 0,1,....); or LIMIT 2 , 2;
    //
    //
    
    // prepare query statement
    $stmt = $this->db->prepare($query);
    
//    // bind values
//    $stmt->bindParam(":startdate",    $param['startdate'],\PDO::PARAM_STR, 10);
//    $stmt->bindParam(":enddate",      $param['enddate'],\PDO::PARAM_STR, 10);
//    $stmt->bindParam(":location",     $param['location'], \PDO::PARAM_INT);
//    $stmt->bindParam(":sleeps",       $param['sleeps'], \PDO::PARAM_INT);
//    $stmt->bindParam(":beds",         $param['beds'], \PDO::PARAM_INT);
//    $stmt->bindParam(":accepts_pets", $param['accepts_pets'], \PDO::PARAM_INT);
//    $stmt->bindParam(":near_beach",   $param['near_beach'], \PDO::PARAM_INT);
//    echo "pppppp";
//    echo "<pre>";
//    echo print_r($param);
//    echo "</pre>";
//    die();
    
    // execute query
    $stmt->execute();
    
    //return $stmt->fetchAll(\PDO::FETCH_CLASS);
    return $stmt->fetchAll();
  }
  
  public function getListCount($param) {
    $query = "SELECT COUNT(*) As countRaw FROM ( ". $this->getQueryString($param) . " ) AS tmp ";
    
    $stmt = $this->db->prepare($query);
    
    // execute query
    $stmt->execute();
    $count=$stmt->fetchAll(\PDO::FETCH_CLASS);
    //return $stmt->fetchAll(\PDO::FETCH_CLASS);
    return $count[0]->countRaw;
  }

  private function getQueryString($param) {
    $query = "SELECT properties.* , locations.location_name FROM properties "
      . " INNER JOIN locations ON properties._fk_location = locations.__pk ";
    if($param){
      $q_pro_in_booking = "SELECT `_fk_property` FROM bookings
                           WHERE (bookings.start_date BETWEEN '".$param['startdate']."' AND '".$param['enddate']."') 
                                  OR (bookings.end_date BETWEEN '".$param['startdate']."' AND  '".$param['enddate']."') ";

      $filter=[
          'location'      => ' (_fk_location = '.$param['location'].') OR ('.$param['location'].' = -1)',
          'sleeps'        => ' (sleeps >= '.$param['sleeps'].') OR ('.$param['sleeps'].'< 1) ',
          'beds'          => ' (beds >= '.$param['beds'].') OR ('.$param['beds'].' < 1) ',
          'accepts_pets'  => ' (accepts_pets = '.$param['accepts_pets'].') OR ('.$param['accepts_pets'].' = -1) ',
          'near_beach'    => ' (near_beach = '.$param['near_beach'].') OR ('.$param['near_beach'].' = -1) '        
      ];

      $query .= " WHERE properties.__pk NOT IN ( " . $q_pro_in_booking . " ) "
                . " AND ( " .$filter['location']. ") "
                . " AND ( " .$filter['sleeps']. ") "
                . " AND ( " .$filter['beds']. ") "
                . " AND ( " .$filter['accepts_pets']. ") "
                . " AND ( " .$filter['near_beach']. ") ";
    }
    return $query;
  }


}
