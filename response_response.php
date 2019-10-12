<?php
session_start();
$url=explode("=", $_SERVER["QUERY_STRING"]);
$response_id=$url[0];

$para=explode("*",$url[1]);
$response=$para[0];
$root_post=$para[1];
$to_user=$para[2];

$from_user=$_SESSION["login_user"];

$response=str_replace("%20"," ",$response);

include_once("../app/vendor/autoload.php");
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$doc = array(
  'root_id' => $root_post,
  'parent_id' => $response_id,
  'body' => $response,
  'from' => $from_user,
  'to' => $to_user,
  'type' => 'to_response',
  'response_time' => date('Y-m-d H:i:s'),

             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.RESPONSE', $bulk);
echo "<script>alert('Response Successfully!');location.href='main_page.php';</script>";


