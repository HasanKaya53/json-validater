<?php

namespace Lucky;


class jsonChecker
{

    function checkType($dataType,$comparisonJsonArray,$dataName,$key){
        if($dataType == "int"){
            //sayısal olmak zorundadır.
            if(!is_numeric($comparisonJsonArray[$dataName])) return array("Status"=>"false","ErrMsg"=>$dataName." parameter must be integer. Key:".$key);
        }
        else if(strstr($dataType,"varchar")){
            //bu bir varchar veya nvarchar değerdir.

           
                $fakeDataType = "";
                $fakeDataType = str_replace("varchar","",$dataType);
                $fakeDataType = str_replace("n","",$fakeDataType);
                $fakeDataType = str_replace("(","",$fakeDataType);
                $fakeDataType = str_replace(")","",$fakeDataType);
    
                if($fakeDataType != "max"){
                    if(!is_numeric($fakeDataType)) {
                        return array("Status"=>"false","ErrMsg"=>$dataType." parameter varchar(??) length not found. Key:".$key);
                    }
                        
                    if(isset($comparisonJsonArray[$dataName]) && strlen($comparisonJsonArray[$dataName]) > $fakeDataType)  
                        return array("Status"=>"false","ErrMsg"=>$dataName." parameter must be shorter than ".$fakeDataType." length:".$key);
                }
            
          
        } 
    }
    function checkJsonData($realJson,$comparisonJson,$jsonSizeOfChecker = 0){

        //realJson = "karşılaştırma da temel alınacak json verisi",
        //comparisonJson = "Karşılaştırma yapılacak json."
        //jsonSizeOfChecker = "1 ise: Jsonların veri uzunlukları eşit olmak zorundadır".
    
        $RealJsonArray = json_decode($realJson,true);
        $comparisonJsonArray = json_decode($comparisonJson,true);
    
        if(!is_array($RealJsonArray))	return (array("Status"=>"false","ErrMsg"=>"1. Submitted Json Array Could Not Be Translated"));
        if(!is_array($comparisonJsonArray))	return (array("Status"=>"false","ErrMsg"=>"2. Submitted Json Array Could Not Be Translated"));

      

        if(sizeof($RealJsonArray) < sizeof($comparisonJsonArray))	return (array("Status"=>"false","ErrMsg"=>"The sent Json is longer than the JSON in the system. Sent Json longer: ".sizeof($comparisonJsonArray)." System json longer:".sizeof($RealJsonArray)));
        if($jsonSizeOfChecker == 1) 	if(sizeof($RealJsonArray) != sizeof($comparisonJsonArray))	return (array("Status"=>"false","ErrMsg"=>"Submitted Json must equal system JSON."));


        $toManyDataSendColumnName = ["Customer"];
    
        $jsonData = "";
        foreach($RealJsonArray as $key => $value){
    
            $dataName = trim($key);
            //value kısmı arrayse ve değilse ne yapılcağı
 
            
            if(is_array($value)){
                //value kısmı arrayse ve aşağıdaki parametrelerden her biri varsa
                if(sizeof($value) != 3){
                    return (array("Status"=>"false","ErrMsg"=>"DataType or dataRequire or minLengthChecker value not found (SORT IN THIS TIME). These are mandatory in case the Value part is an array. "));
                }

                

                if(!is_array($value[0])){
                    $dataType = trim($value[0]);
                }else{
                    $dataType = "array";
                }
            
                
                $require = trim($value[1]);
                $minLength = trim($value[2]);
                
            }else{
                $dataType = trim($value);
                $require = 0;
                $minLength = 0; 
            
            }
    
            //varolma zorunluluğu
            if($require == "1" || $require == 1){
                //bu alan zorunludur.
                if(!array_key_exists($dataName,$comparisonJsonArray))	return (array("Status"=>"false","ErrMsg"=> "Missing Parameter in Sent JSON! Key:".$key));
            }
    
            //minimum uzunluk kontrolu
            if($minLength != 0){
                if(array_key_exists($dataName,$comparisonJsonArray)){
                    if(strlen($comparisonJsonArray[$dataName]) < $minLength) return (array("Status"=>"false","ErrMsg"=>$dataName." parameter is under ".$minLength." characters long. Key:".$key));
                }
            }
    
    
            //veri tipi kontrolu
            if($dataType == "array"){
                
          

                if(is_array($value[0])){

                    foreach($value[0] as $vKey => $vValue){
                        //echo json_encode($value);
                        //echo json_encode([$vValue,$comparisonJsonArray[$key][0],trim($vKey),$key]);
                        $typeChecker = $this->checkType($vValue,$comparisonJsonArray[$key][0],trim($vKey),$key);

                        if(is_array($typeChecker)){
                            break;
                        }
                    
                    }
                    //echo json_encode($value[0]);
                }
                
            }else{
                $typeChecker = $this->checkType($dataType,$comparisonJsonArray,$dataName,$key);
            }

            if(is_array($typeChecker)){
                return $typeChecker;
            }


             
        }
    
    
        foreach($comparisonJsonArray as $key => $value){
            if(!array_key_exists($key,$RealJsonArray)){
                unset($comparisonJsonArray[$key]);
            }
        }
    
        return ($comparisonJsonArray);
    
    
    }
    function checkArrayIsset($realJson,$compArray){

        $realJson = json_decode($realJson,true);

        foreach($realJson as $key => $value){
            if(!array_key_exists($key,$compArray) || empty($compArray[$key]))  {
                $data = "...";
            }
            else{
                $data = $compArray[$key];
            }


            $compArray[$key] = $data;
        }

        return $compArray;

    }

}


?>