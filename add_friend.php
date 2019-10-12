<?php
session_start();
include_once("../app/vendor/autoload.php");
$to_email=$_SESSION["to_user"];
$from_email=$_SESSION["login_user"];
$_SESSION["to_user"]=null;
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$doc = array(
  'from' => $from_email,
  'to' => $to_email,
  'status' => 'Pending',
   'start_time' => 'null',
             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.Friendship', $bulk);
echo "<script>alert('Friendship request has been sent!');location.href='main_page.php';</script>";
