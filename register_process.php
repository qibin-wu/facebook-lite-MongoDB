<?php
include_once("../app/vendor/autoload.php");
$password = $_POST['pwd'];
   $confirmPassword = $_POST['pwd_c'];
   if ($password != $confirmPassword) {
       echo "<script>alert('Password should same!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
       exit();
   }

$data=$_POST['dob'];
$is_date=strtotime($data)?strtotime($data):false;

if($is_date===false){
  echo "<script>alert('Incorrect Date of birth!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    exit();
}

$mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$doc = array(
  'email' => $_POST["email"],
  'pwd' => $_POST["pwd"],
  'full_name' => $_POST["full_name"],
   'screen_name' => $_POST["screen_name"],
   'dob' => $_POST["dob"],
   'gender' => $_POST["gender"],
   'status' => $_POST["status"],
   'location' => $_POST["location"],
   'vl' => $_POST["vl"],
             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.Members', $bulk);
echo "<script>alert('Register Successfully!');location.href='index.html';</script>";
