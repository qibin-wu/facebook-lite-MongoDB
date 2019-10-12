<?php
session_start();
include_once("../app/vendor/autoload.php");
$location = $_POST['location'];
 if (empty($location)) {
     echo "<script>alert('location cannot be empty!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
     exit();
 }
 $screen = $_POST['screen_name'];
  if (empty($screen)) {
      echo "<script>alert('screen_name cannot be empty!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
      exit();
  }
$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['email' => $_SESSION["login_user"]],
    ['$set' => ['screen_name'=>$screen, 'status'=>$_POST["status"], 'location'=>$location, 'vl'=>$_POST["vl"]]],
    ['multi' => false, 'upsert' => false]
);
$mongo->executeBulkWrite('MyDB.Members', $bulk);
echo "<script>alert('Updated Successfully!');location.href='main_page.php';</script>";
