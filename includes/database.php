<?php

function getDatabaseConnection($dbname){
    $host = 'localhost';
    $username = 'web_user';
    $password = 's3cr3t';
    
    // Creates new connection
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Setting ErrorHandling in Exception
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $dbConn;
}
?>