<?php
$sName = "localhost";
$uName = "root";
$pass = "";
$dbName = "boatbike";

try{
    $conn = new PDO("mysql:host=$sName;dbname=$dbName", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed: ". $e->getMessage();
}

?>