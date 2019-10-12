<?php
session_start();
include_once("../app/vendor/autoload.php");
function checkLike()
{
  $url_pra=explode("=", $_SERVER["QUERY_STRING"]);
  $pr_id=$url_pra[0];
  $from_user=$_SESSION["login_user"];

    $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


    $filter = ['p_r_id'=>$pr_id , 'from'=>$from_user];
    $options = [];
    $i=0;
    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery('MyDB.MEMBER_LIKES', $query);
    foreach ($rows as $row)
    {
       if( $row!== null)
            {
              $i = $i +1 ;

            }
    }
    
    
if($i==0)
{
  return false;
}
else {
  return true;
}
}
function like()

{
  $url_pra=explode("=", $_SERVER["QUERY_STRING"]);
  $pr_id=$url_pra[0];
  $from_user=$_SESSION["login_user"];

  $mongo = new MongoDB\Driver\Manager("mongodb://mongo:27017");
  $bulk = new MongoDB\Driver\BulkWrite;

  $doc = array(
  'p_r_id' => $pr_id,
  'from' => $from_user,
             );

$bulk->insert($doc);
$mongo->executeBulkWrite('MyDB.MEMBER_LIKES', $bulk);


}

if(checkLike())
{
  
    echo "<script>alert('You already Liked it!');location.href='main_page.php';</script>";
}
else {
    like();
    echo "<script>alert('Liked Successfully!');location.href='main_page.php';</script>";

}
