
<HTML>

<BODY>
  <h1>Register page</h1>

  <form action="register_process.php" method="post">
    <table cellspacing="10">
      <tr>
        <td>Email:</td>
        <td><input type="text" name="email"></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="pwd"></td>
      </tr>
      <tr>
        <td>Password Confirm:</td>
        <td><input type="password" name="pwd_c"></td>
      </tr>
      <tr>
        <td>Full Name:</td>
        <td><input type="text" name="full_name"></td>
      </tr>
      <tr>
        <td>Screen Name:</td>
        <td><input type="text" name="screen_name"></td>
      </tr>
      <tr>
        <td>Date of birth:</td>
        <td><input type="text" name="dob"></td>
        <td>(eg:31-06-2019)</td>
      </tr>
      <tr>
        <td> Gender:</td>
        <td> <select name="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="secret">Secret</option>
          </select></td>
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
        <td><input type="text" name="location"></td>
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
        <td><a href="http://localhost:8000">Cancel</a></td>
      </tr>
    </table>

  </form>



</BODY>
</HTML>
