<?php

/* 
 * define the default path for includes
 */

define('DS', DIRECTORY_SEPARATOR);

define("MY_ROOT" ,  realpath(dirname(__FILE__)));

define("MY_APP_PATH",  MY_ROOT.DS."my_src");
