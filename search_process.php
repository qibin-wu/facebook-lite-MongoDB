<?php
session_start();
include_once("../app/vendor/autoload.php");
$email = $_POST['email'];

   if (empty($email)) {
       echo "<script>alert('email cannot empty!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
       exit();
   }
   if ($email== $_SESSION["login_user"]){
       echo "<script>alert('You cannot search yourself!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
       exit();
   }

    try
    {
        $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


        $filter = ['email' => $email];
        $options = [];

        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $mng->executeQuery('MyDB.Members', $query);
        $i = 0;
        foreach ($rows as $row)
        {
           if( $row!== null)
                {
                  $i = $i +1 ;
                }
        }
        if($i ==0)
        {
          echo "<script>alert('Email Incorrect');location.href='main_page.php';</script>";
        }
        else {
          $_SESSION["search_user"]=$email;
          header("location:friendship_check.php");
          exit();
        }

    } catch (MongoDB\Driver\Exception\Exception $e) {

        $filename = basename(__FILE__);

        echo "The $filename script has experienced an error.\n";
        echo "It failed with the following exception:\n";

        echo "Exception:", $e->getMessage(), "\n";
        echo "In file:", $e->getFile(), "\n";
        echo "On line:", $e->getLine(), "\n";
    }
