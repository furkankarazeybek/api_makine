<?php

require_once('db.php');
require_once('../model/task.php');
require_once('../model/response.php');

// attempt to set up connections to read and write db connections
try {
  $writeDB = DB::connectWriteDB();
  $readDB = DB::connectReadDB();
}
catch(PDOException $ex) {
  // log connection error for troubleshooting and return a json error response
  error_log("Connection Error: ".$ex, 0);
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Database connection error");
  $response->send();
  exit;
}

// within this if/elseif statement, it is important to get the correct order (if query string GET param is used in multiple routes)
// check if taskid is in the url e.g. /tasks/1

// check if taskid is in the url e.g. /tasks/1
if (array_key_exists("taskid",$_GET)) {
  // get task id from query string
  $taskid = $_GET['taskid'];

  //check to see if task id in query string is not empty and is number, if not return json error
  if($taskid == '' || !is_numeric($taskid)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Task ID cannot be blank or must be numeric");
    $response->send();
    exit;
  }
  
  // if request is a GET, e.g. get task
  if($_SERVER['REQUEST_METHOD'] === 'GET') {
    // attempt to query the database
    try {
      // create db query
      $query = $readDB->prepare('SELECT id,UrunKodu, HedefSayi,HedefSure,UretimMiktari,Ortalama,Sure,HedefIslem,IslemSuresi,MakinaNo,Personel,SonMakine from tbltasks where id = :taskid');
      $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
  		$query->execute();

      // get row count
      $rowCount = $query->rowCount();

      // create task array to store returned task
      $taskArray = array();

      if($rowCount === 0) {
        // set up response for unsuccessful return
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Task not found");
        $response->send();
        exit;
      }

      // for each row returned
     

      // bundle tasks and rows returned into an array to return in the json data
      $returnData = array();
      $returnData['rows_returned'] = $rowCount;
      $returnData['tasks'] = $taskArray;

      // set up response for successful return
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit;
    }
    // if error with sql query return a json error
    catch(TaskException $ex) {
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage($ex->getMessage());
      $response->send();
      exit;
    }
    catch(PDOException $ex) {
      error_log("Database Query Error: ".$ex, 0);
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Failed to get task");
      $response->send();
      exit;
    }
  }
  // else if request if a DELETE e.g. delete task
  
  // handle updating task
  
  // if any other request method apart from GET, PATCH, DELETE is used then return 405 method not allowed
  else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
  } 
}
// get tasks that have submitted a SonMakine filter

// handle getting all tasks page of 20 at a time

// handle getting all tasks or creating a new one
elseif(empty($_GET)) {
  // if request is a GET e.g. get tasks
  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    // attempt to query the database
    try {
      // create db query
      $query = $readDB->prepare('SELECT id,UrunKodu,HedefSayi,HedefSure,UretimMiktari,Ortalama,Sure,HedefIslem,IslemSuresi,MakinaNo,Personel,SonMakine from tbltasks');
      $query->execute();

      // get row count
      $rowCount = $query->rowCount();

      // create task array to store returned tasks
      $taskArray = array();

      // for each row returned
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        // create new task object for each row
        $task = new Task($row['id'], $row['UrunKodu'], $row['HedefSayi'], $row['HedefSure'], $row['UretimMiktari'],$row['Ortalama'],$row['Sure'],$row['HedefIslem'],$row['IslemSuresi'],$row['MakinaNo'],$row['Personel'], $row['SonMakine']);

        // create task and store in array for return in json data
        $taskArray[] = $task->returnTaskAsArray();
      }

      // bundle tasks and rows returned into an array to return in the json data
      $returnData = array();
      $returnData['rows_returned'] = $rowCount;
      $returnData['tasks'] = $taskArray;

      // set up response for successful return
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit;
    }
    // if error with sql query return a json error
    catch(TaskException $ex) {
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage($ex->getMessage());
      $response->send();
      exit;
    }
    catch(PDOException $ex) {
      error_log("Database Query Error: ".$ex, 0);
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Failed to get tasks");
      $response->send();
      exit;
    }
   
  }



  
  // else if request is a POST e.g. create task
  elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // create task
   
      
      // get POST request body as the POSTed data will be JSON format
      $rawPostData = trim(file_get_contents("php://input"));
      
      if(!$jsonData = json_decode($rawPostData)) {
        // set up response for unsuccessful request
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Request body is not valid JSON");
        $response->send();
        exit;
      }
      
      if(!isset($jsonData->UrunKodu) || !isset($jsonData->SonMakine)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (!isset($jsonData->UrunKodu) ? $response->addMessage("UrunKodu field is mandatory and must be provided") : false);
        (!isset($jsonData->SonMakine) ? $response->addMessage("SonMakine field is mandatory and must be provided") : false);
        $response->send();
        exit;
      }
      
      // create new task with data, if non mandatory fields not provided then set to null
      $newTask = new Task(null, $jsonData->UrunKodu, (isset($jsonData->HedefSayi) ? $jsonData->HedefSayi : null), $jsonData->HedefSure,$jsonData->UretimMiktari,$jsonData->Ortalama,$jsonData->Sure,$jsonData->HedefIslem,$jsonData->IslemSuresi,$jsonData->MakinaNo,$jsonData->Personel, $jsonData->SonMakine);

      $UrunKodu = $newTask->getUrunKodu();
      $HedefSayi = $newTask->getHedefSayi();
      $HedefSure = $newTask->getHedefSure();
      $UretimMiktari = $newTask->getUretimMiktari();
      $Ortalama = $newTask->getOrtalama();
      $Sure = $newTask->getSure();
      $HedefIslem = $newTask->getHedefIslem();
      $IslemSuresi = $newTask->getIslemSuresi();
      $MakinaNo = $newTask->getMakinaNo();
      $Personel = $newTask->getPersonel();
      $SonMakine = $newTask->getSonMakine();

      // create db query
      $query = $writeDB->prepare('insert into tbltasks (UrunKodu, HedefSayi,HedefSure,UretimMiktari,Ortalama,Sure,HedefIslem,IslemSuresi,MakinaNo,Personel,SonMakine) values (:UrunKodu, :HedefSayi, :HedefSure, :UretimMiktari, :Ortalama,:Sure, :HedefIslem, :IslemSuresi, :MakinaNo, :Personel, :SonMakine) ON DUPLICATE KEY UPDATE UretimMiktari= values(UretimMiktari),Ortalama=values(ortalama),Sure=values(Sure),IslemSuresi=values(IslemSuresi)');
      $query->bindParam(':UrunKodu', $UrunKodu, PDO::PARAM_STR);
      $query->bindParam(':HedefSayi', $HedefSayi, PDO::PARAM_STR);
      $query->bindParam(':HedefSure', $HedefSure, PDO::PARAM_STR);
      $query->bindParam(':UretimMiktari', $UretimMiktari, PDO::PARAM_STR);
      $query->bindParam(':Ortalama', $Ortalama, PDO::PARAM_STR);
      $query->bindParam(':Sure', $Sure, PDO::PARAM_STR);
      $query->bindParam(':HedefIslem', $HedefIslem, PDO::PARAM_STR);
      $query->bindParam(':IslemSuresi', $IslemSuresi, PDO::PARAM_STR);
      $query->bindParam(':MakinaNo', $MakinaNo, PDO::PARAM_STR);
      $query->bindParam(':Personel', $Personel, PDO::PARAM_STR);
      $query->bindParam(':SonMakine', $SonMakine, PDO::PARAM_INT);
      $query->execute();
      
      // get row count
      $rowCount = $query->rowCount();

      // check if row was actually inserted, PDO exception should have caught it if not.
      if($rowCount === 0) {
        // set up response for unsuccessful return
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to create task");
        $response->send();
        exit;
      }
      
      // get last task id so we can return the Task in the json
      $lastTaskID = $writeDB->lastInsertId();
      // create db query to get newly created task - get from master db not read slave as replication may be too slow for successful read
      $query = $writeDB->prepare('SELECT id,UrunKodu,HedefSayi,HedefSure,UretimMiktari,Ortalama,Sure,HedefIslem,IslemSuresi,MakinaNo,Personel,SonMakine from tbltasks where id = :taskid');
      $query->bindParam(':taskid', $lastTaskID, PDO::PARAM_INT);
      $query->execute();

      // get row count
      $rowCount = $query->rowCount();
      
      // make sure that the new task was returned
      if($rowCount === 0) {
        // set up response for unsuccessful return
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to retrieve task after creation");
        $response->send();
        exit;
      }
      
      // create empty array to store tasks
      $taskArray = array();

      // for each row returned - should be just one
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        // create new task object
        $task = new Task($row['id'], $row['UrunKodu'], $row['HedefSayi'], $row['HedefSure'], $row['UretimMiktari'],$row['Ortalama'],$row['Sure'],$row['HedefIslem'],$row['IslemSuresi'],$row['MakinaNo'],$row['Personel'], $row['SonMakine']);

        // create task and store in array for return in json data
        $taskArray[] = $task->returnTaskAsArray();
      }
      // bundle tasks and rows returned into an array to return in the json data
      $returnData = array();
      $returnData['rows_returned'] = $rowCount;
      $returnData['tasks'] = $taskArray;

      //set up response for successful return
      $response = new Response();
      $response->setHttpStatusCode(201);
      $response->setSuccess(true);
      $response->addMessage("Task created");
      $response->setData($returnData);
      $response->send();
      exit;      
    }
    // if task fails to create due to data types, missing fields or invalid data then send error json
    
    // if error with sql query return a json error
    
    
  
  // if any other request method apart from GET or POST is used then return 405 method not allowed
  else {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
  } 
}
// return 404 error if endpoint not available
else {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Endpoint not found");
  $response->send();
  exit;
}
