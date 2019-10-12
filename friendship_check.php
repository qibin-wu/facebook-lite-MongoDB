<?php
session_start();
include_once("../app/vendor/autoload.php");
$to_email=$_SESSION["search_user"];
$from_email=$_SESSION["login_user"];
$l_user= $_SESSION["login_user"];
try
{   $i =0;
    $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


    $filter = ['$or' => [['from'=>$from_email,'to'=>$to_email],['from'=>$to_email,'to'=>$from_email]]];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery('MyDB.Friendship', $query);
    foreach ($rows as $row)
    {
       if( $row!== null)
            {
              $status=$row->status;
              $i = $i +1 ;
            }
    }
    if($i ==0)
    {
       $_SESSION["search_user"]=$to_email;
     header("location:main_page.php");
       exit();
    }
    else {
      session_start();
      $_SESSION["search_user"]=$to_email;
      $_SESSION["status"]=$status;
      header("location:main_page.php");
      exit();

    }

    }

catch (MongoDB\Driver\Exception\Exception $e) {

    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
}
