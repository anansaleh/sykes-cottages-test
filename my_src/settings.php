<?php

return [
  'settings' => [
    'displayErrorDetails' => TRUE, // set to false in production
    'debug'               => TRUE,
    
    'html_output'         => TRUE,

    // Date Format
    'dateFormat'          => 'Y-m-d',
    
    //Pagination
    'records_per_page'     => 2,
    
    // Database connection settings
    "db" => [
      "host" => "localhost",
      "database_name" => "sykes_interview",
      "username" => "root",
      "password" => "root"
    ],
    
    // Renderer settings
    'renderer' => [
      'template_path' => MY_APP_PATH.DS.'resources'.DS.'views',
    ]
  ],
];

