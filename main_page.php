<?php
session_start();
?>
<html>
<head>
  <script type="text/javascript">
  function disp_prompt(post_id,to_user)
    {
    var response=prompt("Please enter your Response","")
    if (response!=null && response!="")
      {
      document.location="resopnse_post.php?"+post_id+"="+response+"*"+to_user;
      }
    }
    function disp_prompt2(response_id,root_post,to_user)
      {
      var response=prompt("Please enter your Response","")
      if (response!=null && response!="")
        {
        document.location="response_response.php?"+response_id+"="+response+"*"+root_post+"*"+to_user;
        }
      }
  </script>
    <title>Main Page</title>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
        }
        .main{
            width: 90%;
            height: 100%;
            position: absolute;

        }
        .top{
            width: 100%;
            height: 0px;
            float: left;
        }
        .bottom{
            width: 100%;
            height: auto ;
            float: left;
        }
        .left{

            width: 30%;
            height: 1000px;
            float: left;
        }
        .middle{
            width: 40%;
            height: 500px;
            float: left;
        }
        .right{
            width: 30%;
            height: 1000px;
            float: left;
        }
        .white{
          background-color: #FFFFFF;
        }
        .button{
            height: 60px;
            width: 90px;
        }
        .button2{
            height: 25px;
            width: 100px;
        }
        .button3{
            height: 25px;
            width: 100px;
            margin-left:60px;
        }
        .button4{
            height: 25px;
            width: 100px;
            margin-right:30px;
        }


    </style>
</head>
<body style="background-color:#E9EBEE;">

    <div class="main">
        <div class="left" align="right">
          <table cellspacing="15">
            <tr>
              <td><h2>Managing account</h2></td>
            </tr>
            <tr>
              <td><li><a href="profile.php">Profile Maintenance</a></li></td>
            </tr>
              <tr>
                <td><li><a href="delete.php">Delete Account</a></li></td>
              </tr>
              <tr>
                <td><li><a href="index.html">Log Out</a></li></td>
              </tr>
          </table>

        </div>
        <div class="middle" align="center" >

                    <table>
                      <tr>
                        <td>
                          <form action="summit_post.php" method="post">
                            <table cellspacing="20" >
                              <tr>
                                <td> <textarea name="post" rows="6"  style="width:400px; height:100px"></textarea></td>
                              </tr>
                                <tr>
                                  <td> <input type="submit" value="Post" class="button"></td>
                                </tr>
                                <tr>
                                  <td>
                                    <?php
                                    session_start();
                                    include_once("../app/vendor/autoload.php");
                                    function checkFriend($p)
                                    {
                                      $isFriend=false;
                                      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                      $filter = ['$or' => [['from' =>$_SESSION["login_user"] ,'to'=>$p->from],['from' =>$p->from,'to'=>$_SESSION["login_user"]]]];
                                      $options = [];

                                      $query = new MongoDB\Driver\Query($filter, $options);
                                      $rows = $mng->executeQuery('MyDB.Friendship', $query);
                                      foreach ($rows as $row)
                                      {
                                         $isFriend=true;
                                      }

                                      $isFriendOnly=false;
                                      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                      $command = new MongoDB\Driver\Command([
                                          'aggregate' => 'Post',
                                          'pipeline' => [
                                              ['$lookup' => ["from" => "Members","localField" => "from","foreignField" => "email","as" => "members_post"]],

                                          ],
                                          'cursor' => new \stdClass,
                                      ]);
                                      $cursor = $mng->executeCommand('MyDB', $command);

                                      foreach ($cursor as $document)
                                      {
                                        $etID=$p->from;

                                        if($etID==$document->from)
                                        {

                                          $doc=  $document->members_post;
                                          foreach ($doc as $d)
                                          {
                                            $vl=$d->vl;
                                            if($vl=='friends_only')
                                            {
                                              $isFriendOnly=true;
                                              break;
                                            }
                                          }
                                        }

                                      }

                                      if($isFriend and $isFriendOnly)
                                      {
                                        return true;
                                      }
                                      else {
                                        return false;
                                      }

                                    }
                                    function checkVisible($postss){
                                      $personal=false;
                                      $everyone=false;
                                      $friend=false;

                                      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                      $command = new MongoDB\Driver\Command([
                                          'aggregate' => 'Post',
                                          'pipeline' => [
                                              ['$lookup' => ["from" => "Members","localField" => "from","foreignField" => "email","as" => "members_post"]],

                                          ],
                                          'cursor' => new \stdClass,
                                      ]);
                                      $cursor = $mng->executeCommand('MyDB', $command);

                                      foreach ($cursor as $document)
                                      {
                                        $etID=$postss->from;

                                        if($etID==$document->from)
                                        {

                                          $doc=  $document->members_post;
                                          foreach ($doc as $d)
                                          {
                                            $vl=$d->vl;
                                            if($vl=='everyone')
                                            {
                                              $everyone=true;
                                              break;
                                            }
                                          }
                                        }

                                      }

                                      $eti=$postss->from;
                                      if($eti==$_SESSION["login_user"])
                                      {
                                        $personal=true;
                                      }

                                      if(checkFriend($postss))
                                      {
                                        $friend=true;
                                      }


                                      if($personal or $everyone or $friend)
                                      {
                                        return true;
                                      }
                                      else {
                                        return false;
                                      }





                                    }
                                    function getscreen_name($email){
                                      try
                                           {
                                              $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");


                                              $filter = ['email' => $email ];
                                              $options = [];

                                              $query = new MongoDB\Driver\Query($filter, $options);
                                              $rows = $mng->executeQuery('MyDB.Members', $query);
                                              foreach ($rows as $row)
                                              {
                                                 $screen_name=$row->screen_name;
                                              }

                                          } catch (MongoDB\Driver\Exception\Exception $e) {

                                              $filename = basename(__FILE__);

                                              echo "The $filename script has experienced an error.\n";
                                              echo "It failed with the following exception:\n";

                                              echo "Exception:", $e->getMessage(), "\n";
                                              echo "In file:", $e->getFile(), "\n";
                                              echo "On line:", $e->getLine(), "\n";
                                          }
                                          return $screen_name;

                                    }

                                    function getPost()
                                    {                                  
                                      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                      $filter =  [];
                                      $options = [];

                                      $query = new MongoDB\Driver\Query($filter, $options);
                                      $rows = $mng->executeQuery('MyDB.Post', $query);

                                      foreach ($rows as $row)
                                      {
                                          $post[]=$row;
                                      }


                                          return $post;
                                    }

                                    function showToPost($pid)
                                    {
                                      $to_post=array();
                                        $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                              $filter = ['root_id' =>(string)$pid, 'type'=>'to_post'];
                                              $options = [];

                                              $query = new MongoDB\Driver\Query($filter, $options);
                                              $rows = $mng->executeQuery('MyDB.RESPONSE', $query);
                                              foreach ($rows as $row)
                                              {
                                                $to_post[]=$row;
                                              }





                                          return $to_post;
                                    }
                                    function showToResponse($postID)
                                    {
                                      $to_response=array();
                                      $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                                            $filter = ['root_id' =>(string)$postID, 'type'=>'to_response'];
                                            $options = [];

                                            $query = new MongoDB\Driver\Query($filter, $options);
                                            $rows = $mng->executeQuery('MyDB.RESPONSE', $query);
                                            foreach ($rows as $row)
                                            {
                                              $to_response[]=$row;
                                            }

                                          return $to_response;
                                    }


                                  $post=getPost();
                                  rsort($post);
                                  $num=count($post);
                                  echo "<table class=\"white\"   style=\"width:400px\"  cellspacing=\"5\">\n";

                                      for($j=0;$j<$num;$j++)
                                      {
                                        if(!checkVisible($post[$j]))
                                        {
                                          continue;
                                        }


                                       // show screen name
                                        echo "<tr>";
                                        echo "<td>";
                                        if($j!=0){
                                        echo"<HR width=\"101%\" color=#9F35FF SIZE=10>";
                                      }
                                        echo"<b><font color=\"blue\" size=\"4\">".getscreen_name($post[$j]->from)."</font></b>";
                                        echo "</td>";
                                        echo "</tr>";




                                          // show body and follow buttons and responses

                                          echo "<tr>";
                                          echo "<td>";
                                          echo"".$post[$j]->body."";
                                          echo "</td>";
                                          echo "</tr>";

                                          echo "<tr>";
                                          echo "<td>";
                                          echo"<font size=\"1\">".$post[$j]->post_time."</font>";
                                          echo "</td>";
                                          echo "</tr>";

                                          echo "<tr style=\"background-color:#E9EBEE;\">";
                                          echo"<td><a href=\"like_process.php?".$post[$j]->_id."=1\"><button type=\"button\" class=\"button3\">Like</button></a><button type=\"button\" class=\"button3\" onclick=\"disp_prompt('".$post[$j]->_id."','".$post[$j]->from."')\">Response</button></td>";
                                          echo "</tr>";

                                              //show responses to post
                                              $pp=$post[$j]->_id;
                                              $response=showToPost($pp);
                                              // rsort($response);
                                              $response_num=count($response);

                                              echo "<tr>";

                                              for($k=0;$k<$response_num;$k++)
                                              {



                                                echo "<td style=\"word-wrap:break-word;word-break:break-all;padding-left:20px\">";
                                                echo"<b><font color=\"blue\">".getscreen_name($response[$k]->from)."</font></b>";
                                                echo"&nbsp;&nbsp;".$response[$k]->body."";
                                                echo "</td>";



                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td style=\"word-wrap:break-word;word-break:break-all;padding-left:20px\">";
                                                echo"<font size=\"1\">".$response[$k]->response_time."</font>";
                                                echo "</td>";
                                                echo "<tr>";
                                                 echo"<td align=\"right\"><a href=\"like_process.php?".$response[$k]->_id."=1\"><button type=\"button\" class=\"button4\">Like</button></a><button type=\"button\" onclick=\"disp_prompt2('".$response[$k]->_id."','".$post[$j]->_id."','".$response[$k]->from."')\"
                                                 value=\"Response\" class=\"button2\" >Response</button></td>";
                                                 echo "</tr>";

                                              }
                                              echo "</tr>";
                                               echo "<tr>";
                                              //to response

                                              $to_response=showToResponse($post[$j]->_id);
                                              rsort($to_response);
                                              $to_response_num=count($to_response);
                                              for($l=0;$l<$to_response_num;$l++)
                                              {

                                                echo "<td style=\"word-wrap:break-word;word-break:break-all;padding-left:20px\">";
                                                echo"<b><font color=\"blue\">".getscreen_name($to_response[$l]->from)."</font></b>";
                                                echo"&nbsp;&nbsp;<font color=\"blue\">".getscreen_name($to_response[$l]->to)."</font>";
                                                echo"&nbsp;&nbsp;".$to_response[$l]->body."";
                                                echo "</td>";



                                                echo "</tr>";
                                                echo "<tr>";
                                                echo "<td style=\"word-wrap:break-word;word-break:break-all;padding-left:20px\">";
                                                echo"<font size=\"1\">".$to_response[$l]->response_time."</font>";
                                                echo "</td>";
                                                echo "<tr >";
                                                 echo"<td align=\"right\"><a href=\"like_process.php?".$to_response[$l]->_id."=1\"><button type=\"button\" class=\"button4\">Like</button></a><button type=\"button\" onclick=\"disp_prompt2('".$to_response[$l]->_id."','".$post[$j]->_id."','".$to_response[$l]->from."')\"
                                                 class=\"button2\" >Response</button></td>";
                                                 echo "</tr>";


                                              }
                                                echo "</tr>";


                                           }





                                    echo "</table>";
                                    ?>

                                  </td>
                                </tr>
                            </table>
                            </form>
                        </td>
                      </tr>


                      </table>

        </div>
        <div class="right" align="left">

          <table cellspacing="10" class="white" style="width:500px;margin-top:20px">
            <tr>
              <td><h2>Friendship Requests</h2></td>
            </tr>

                        <?php
                        include_once("../app/vendor/autoload.php");
                        $aa=array();
                        $i=0;
                        $l_user= $_SESSION["login_user"];
                        try
                        {
                            $mng = new MongoDB\Driver\Manager("mongodb://mongo:27017");
                            $filter = ['to' => $l_user,'status'=>'Pending'];
                            $options = [];

                            $query = new MongoDB\Driver\Query($filter, $options);
                            $rows = $mng->executeQuery('MyDB.Friendship', $query);
                            foreach ($rows as $row)
                            {
                               if( $row!== null)
                                    {
                                      $b=$row->from;
                                      $aa[]=$b;
                                      $i = $i +1 ;

                                    }
                            }
                            if($i !=0)
                            {
                              for($k=0; $k<$i; $k++)
                                {
                                  echo "
                                    <tr><td>".$aa[$k]."</td><td><a href=\"deal_with_friend.php?".$aa[$k]."=Accepted\"><button type=\"button\" class=\"button2\">Accept</button></a></td>
                                    <td><a href=\"deal_with_friend.php?".$aa[$k]."=Declined\"><button type=\"button\" class=\"button2\">Decline</button></a></td> </tr>
                                     ";
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





          </table>


          <form action="search_process.php" method="post" >
            <table cellspacing="10" class="white" style="width:500px; margin-top:30px">
              <tr>
                <td><h2>Search a friend</h2></td>
              </tr>
              <tr>
                  <td>E-mail:</td>
                  <td><input type="text" name="email">
                  </td><td><input type="submit" value="Search" class="button2"></td>
              </tr>
              <?php
                include_once("../app/vendor/autoload.php");

              if(!empty($_SESSION["search_user"]))
              {

                if(empty($_SESSION["status"]))
                {
                  echo "<tr>
                  <td>".$_SESSION["search_user"]."</td>
                  <td></td>
                  <td><a href=\"add_friend.php\"><button type=\"button\" class=\"button2\">Add Friend</button></a></td>
                </tr>";
                $_SESSION["to_user"]=$_SESSION["search_user"];
                $_SESSION["search_user"]=NULL;
                $_SESSION["status"]=NULL;
              }else{
                echo "<tr>
                <td>".$_SESSION["search_user"]."</td>
                <td></td>
                <td>".$_SESSION["status"]."</td>
              </tr>";
              $_SESSION["search_user"]=NULL;
              $_SESSION["status"]=NULL;
              }

              }



              ?>
            </table>
          </form>
        </div>
    </div>
</body>
</html>
