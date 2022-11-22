<?php

require_once('xml.class.php');

use Lucky\jsonChecker as jsonChecker;
$jsonController = new jsonChecker;



$input = array("AppName"=>"Test","Code"=>"1234","Adres"=>"111","Number"=>"1234a");


$pageJson = '{
    "AppName": "varchar(15)",
    "Code": ["varchar(60)","1","4"],
    "Adres": ["varchar(30)","1","2"],
    "Number": "int"
}
';


$input =  $jsonController->checkJsonData($pageJson,json_encode($input));

if(isset($input["Status"]) && $input["Status"] == "false"){
    echo json_encode($input);
    exit;
}

$input = $jsonController->checkArrayIsset($pageJson,$input);


echo json_encode($input);


?>