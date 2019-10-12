<?php
session_start();
$emailstatus=explode("=", $_SERVER["QUERY_STRING"]);
$email=$emailstatus[0];
$status=$emailstatus[1];

include_once("../app/vendor/autoload.php");
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;
if($status=="Accepted")
  {
    $bulk->update(
    ['from' => $email],
    ['$set' => ['status'=>$status, 'start_time'=>date('Y-m-d H:i:s')]],
    ['multi' => false, 'upsert' => false]
);}
else {
  $bulk->update(
    ['from' => $email],
    ['$set' => ['status'=>$status]],
    ['multi' => false, 'upsert' => false]
  );
}
$mongo->executeBulkWrite('MyDB.Friendship', $bulk);
echo "<script>alert('Successful ".$status."!');location.href='main_page.php';</script>";
