<?php

define('DB_HOST', 'us-cdbr-iron-east-03.cleardb.net');
define('DB_USER', 'ba1649382fca0b');
define('DB_PASSWORD', 'ca45ecec');
define('DB_NAME', 'heroku_0f45d99b98dee1a');

try{
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME .';charset=utf8', DB_USER, DB_PASSWORD);
} catch( Exception $e ) {
    trigger_error( "Database connection failed" );
    header( "Location: error.php" );
}