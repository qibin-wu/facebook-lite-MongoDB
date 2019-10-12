<?php
session_start();
?>
<HTML>

<BODY>
  <h1>Profile Maintenance</h1>
<?php

  include_once("../app/vendor/autoload.php");
  $l_user= $_SESSION["login_user"];
  try
  {
      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


      $filter = ['email' => $l_user];
      $options = [];

      $query = new MongoDB\Driver\Query($filter, $options);
      $rows = $mng->executeQuery('MyDB.Members', $query);
      foreach ($rows as $row)
      {
         if( $row!== null)
              {
                $screen_name=$row->screen_name;
                $location=$row->location;
              }
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

?>
  <form action="profile_process.php" method="post">
    <table cellspacing="10">
      <tr>
        <td>Screen Name:</td>
        <?php

        echo "<td><input type=\"text\" name=\"screen_name\" value=\"".$screen_name."\"></td>";
        ?>
      </tr>
      <tr>
        <td>Status:</td>
        <td><select name="status">
            <option value="single">Single</option>
            <option value="married">Married</option>
            <option value="divorced">Divorced</option>
          </select></td>
      </tr>
      <tr>
        <td>Location:</td>
          <?php
          echo "<td><input type=\"text\" name=\"location\" value=\"".$location."\"></td>";
          ?>
      </tr>
      <tr>
        <td>Visibility Level:</td>
        <td><select name="vl">
         <option value="private">Private</option>
         <option value="friends_only">Friends-only</option>
         <option value="everyone">Everyone</option>
       </select></td>
     </tr>
      <tr>
        <td><input type="submit" value="submit"></td>
        <td><a href="main_page.php">Cancel</a></td>
      </tr>
    </table>

  </form>



</BODY>
</HTML>
