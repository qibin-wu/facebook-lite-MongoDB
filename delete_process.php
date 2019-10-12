<?php
session_start();
// delete like
  include_once("../app/vendor/autoload.php");
  $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->delete(['from' => $_SESSION["login_user"]], ['limit' => 0]);
  $mongo->executeBulkWrite('MyDB.MEMBER_LIKES', $bulk);

// delete response
  $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->delete(['$or' => [['from' => $_SESSION["login_user"]],['to' => $_SESSION["login_user"]]]], ['limit' => 0]);
  $mongo->executeBulkWrite('MyDB.RESPONSE', $bulk);

  // delete post
    include_once("../app/vendor/autoload.php");
    $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['from' => $_SESSION["login_user"]], ['limit' => 0]);
    $mongo->executeBulkWrite('MyDB.Post', $bulk);

    // delete friendship
      $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
      $bulk = new MongoDB\Driver\BulkWrite;
      $bulk->delete(['$or' => [['from' => $_SESSION["login_user"]],['to' => $_SESSION["login_user"]]]], ['limit' => 0]);
      $mongo->executeBulkWrite('MyDB.Friendship', $bulk);

      // delete user
        include_once("../app/vendor/autoload.php");
        $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['from' => $_SESSION["login_user"]], ['limit' => 0]);
        $mongo->executeBulkWrite('MyDB.Members', $bulk);


  echo "<script>alert('Successful Delete!');location.href='main_page.php';</script>";
