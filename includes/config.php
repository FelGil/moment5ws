<?php
//If devmode or not
$devMode = true;


if($devMode) {
    //activate error reports
    error_reporting(-1);
    ini_set("display_errors", 1);
}
//Loads classes.
spl_autoload_register(function ($class_name){
    include __DIR__.'classes/' . $class_name . '.class.php';
    
});

//load local or global database if in devmode or not.
if($devMode) {
    define("DBHOST","localhost");
    define("DBUSER","root");
    define("DBPASS","");
    define("DBDATABASE","w3m5");
} else {
    define("DBHOST","studentmysql.miun.se");
    define("DBUSER","fegi2000");
    define("DBPASS","xq4a6BxWUT");
    define("DBDATABASE","fegi2000");
}