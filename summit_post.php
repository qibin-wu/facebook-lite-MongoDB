<?php
session_start();
include_once("../app/vendor/autoload.php");
  $body = $_POST['post'];
   if (empty($body)) {
       echo "<script>alert('Post cannot be empty!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
       exit();
   }
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$doc = array(
  'body' => $_POST["post"],
  'from' => $_SESSION["login_user"],
  'post_time' => date('Y-m-d H:i:s'),
             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.Post', $bulk);
echo "<script>alert('Post Successfully!');location.href='main_page.php';</script>";
