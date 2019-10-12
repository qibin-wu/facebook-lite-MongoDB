<?php
session_start();
$ID_response=explode("=", $_SERVER["QUERY_STRING"]);
$TO_ID=$ID_response[0];

$response_to_user=explode("*",$ID_response[1]);
$response=$response_to_user[0];
$to_user=$response_to_user[1];

$from_user=$_SESSION["login_user"];

$response=str_replace("%20"," ",$response);
session_start();
include_once("../app/vendor/autoload.php");
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$doc = array(
  'root_id' => $TO_ID,
  'parent_id' => $TO_ID,
  'body' => $response,
  'from' => $from_user,
  'to' => $to_user,
  'type' => 'to_post',
  'response_time' => date('Y-m-d H:i:s'),

             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.RESPONSE', $bulk);
echo "<script>alert('Response Successfully!');location.href='main_page.php';</script>";
