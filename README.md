<h2> What Can It Do ? </h2>
<p>  

* Comparing the length of data in json.
* Comparing types of data in json
* Comparing the number of elements in two different jsons.
* checking for elements inside two different jsons.

</p>


<h2> Features </h2>
<p> 

* Work continues for the embedded json generation type in the system.
* 

</p>


<h2> Installation </h2>
<p> You can install the latest version of JSON validator with the following command: </p>
<code> composer require hasankaya/json-validater </code>

<h2> Documentation </h2>

<p> First you have to specify a json format type to use in the comparison. </p>

<b> For Example: </b>

<code> 
$pageJson = '{
    "AppName": "varchar(15)",
    "Code": ["varchar(60)","1","4"],
    "Adres": ["varchar(30)","0","2"],
    "Number": "int"
}
';
</code>

<p> The definition of varchar(15) used in the AppName parameter indicates that it will be of type varchar only and up to 15 characters long. </p>
<p> The array created at the Code parameter indicates that there will be a maximum of 60 characters of type varchar(60). The second value in the array means that this field will have to be in the next json.  .Also, the fact that the third value in the array is 1 indicates that the value in the next json will be at least 1 character..</p>



<p> For Example: </p>
<p> 

<code>


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

</code>


</p>







