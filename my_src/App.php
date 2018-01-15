<?php

namespace App;

/**
 * Description of App
 *
 * @author anan
 */
class App
{
  /**
   * Stores an instance of the Slim application.
   *
   * @var \Slim\App
   */
  protected $app;

  public function __construct($envFilePath = '')
  {

    $settings = require MY_APP_PATH. DS. 'settings.php';

    $app = new \Slim\App($settings);

    // Set up dependencies
    require MY_APP_PATH.DS.'dependencies.php';

    // Register routes
    require MY_APP_PATH.DS.'routes.php';
    
    
    $this->app = $app;
    //$this->setUpDatabaseManager();
    //$this->setUpDatabaseSchema();
  }
    
  /**
   * Get an instance of the application.
   *
   * @return \Slim\App
   */
  public function get()
  {
    return $this->app;
  }
}
