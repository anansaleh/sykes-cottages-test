# sykes-cottages-test
This is test project for Sykes Cottages properties, I implement it by using PHP Slim3 Framework, PHPUnit and bootstrap
# specification
Please read files ./docs/instructions.txt and ./docs/Sykes Cottages Interview Test - search.docx as I have got them from Sykes Cottages bu mail so you can understand wich tables I have used in this project

# Required
1. php >= 5.5 and if you used apache you must have RewriteEngine On
2. MySql Server 5.7

# Installation
It recommend you the Composer manager. 
Open terminal and run the following command
> composer update 

This will install Slim 3 Framework, PHPUnit, and others libraries.

# prepare database
1. Create empty database in your MySql server
2. open file **/docs/sykes_interview.sql** and execute the sql command to create tables and store data in your new database
3. open ./my_src/settings.php and set the database config.
>   // Database connection settings
    "db" => [
      "host" => "localhost",
      "database_name" => "your DB name",
      "username" => "root",
      "password" => "root"
    ],
4. If you want you can change the folder **./my_src** with any name but you must: 
i. open ./config.php and change the **define("MY_APP_PATH",  MY_ROOT.DS."my_src");** with your new name
ii. open the **./composer.json** and change the
>     "autoload": {
        "psr-4": {
            "App\\": "my_src/" ---> change with your new re-name folder
        }
    },
5. Set up your apache to locate the default folder to **./public**
6. Else if you want to run the application without apache you can go to **./public**, open new terminal and run the following command
> php -S localhost:8000
6. To run the test in root folder **./** open terminal and run the following command:
>  vendor/bin/phpunit
That will execute 27 tests and 242 assertions by phpunit

# Notes
I have test the project in php7.1 and php5.6 and it work probably