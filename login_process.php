<?php
    include_once("../app/vendor/autoload.php");
    try
    {
        $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


        $filter = ['email' => $_POST["email"],'pwd' => $_POST["pwd"]];
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
            echo "<script>alert('User name or password Incorrect');location.href='index.html';</script>";
        }
        else {
          session_start();
          $_SESSION["login_user"]=$_POST["email"];
          header("location:main_page.php");
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

?>
