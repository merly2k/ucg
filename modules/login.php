<?php
/*
* login.php
* Instantiate a new session object. If session exists, it will be restored,
* otherwise, a new session will be created--placing a sid cookie on the user's
* computer.
*/
$s=new sessions();
//print_r($s->data);
$status_msg = "";

if (isset($_POST['method'])) {
  /*
  Form was submitted, let's validate and test authentication.
  */
  if (Validate()) {
    if (Auth()) {
      /*
      Use the session to "remember" that the user is logged in already.
      */
      $s->data['logged_in'] = true;

      /*
      Store the username in the session if you want.
      */
      $s->data['username'] = $_POST['username'];
      
      /*
      We need to "remember" what page the user orignally wanted before
      we redirected to login. Pull this value from the session, then remove
      it from the session.
      */
      $dest = $s->data['page_destination'];
      unset($s->data['page_destination']);
      $s->save();
      
      /*
      Finally, redirect to where the user wanted to go.
      */
      echo "<script type='text/javascript'>
        location.replace('$dest');
        </script>";
      //echo profile();
      
    }else{LoginForm();}
  }
} else {
    
if(isset($s->data['logged_in']) ){
 if ($s->data['logged_in']):echo profile();
 
 endif;  
 }else{
 LoginForm();}
}



/*
Validate() will validate the form data. You can modify this per your
requirements.
*/
function Validate() {
  global $username, $pword, $status_msg;
  $ret = true;
  $username = strip_tags($_POST['username']);
  if (strlen($username) == 0) {
    $ret = false;
    $status_msg .= "Не введен логин!<br />";
  }
  $pword = strip_tags($_POST['pword']);
  if (strlen($pword) == 0) {
    $ret = false;
    $status_msg .= "Не введен пароль!<br />";
  }
  return $ret;
}

/*
Auth() function to validate username and password. You must return either
true or false. Insert your own auth code inside this function. You'll probably
want to test the username and password against an accounts table in your
database or something like that.
*/
function Auth() {
  global $username, $pword, $status_msg;
  $user_db=new db();
  $username=stripcslashes($username);
  $pass=md5($pword);
  $zapros="select * from users where login='$username';";
  //echo $zapros;
  $user_db->get_rows($zapros);
  if ($pass != $user_db->result[0]['password']) {
    $status_msg .= "Ошибка авторизации!<br />";
    $user_db->close();
    return false;
  }
  $_SESSION['username']=$username;
  $_SESSION['pword']=$pword;
 $user_db->close();
  return true;
}

/*
LoginForm() outputs the user form to enter username and pword. You can modify
this any way you want for your own look and feel, but keep the hidden "method"
field.
*/
function LoginForm() {
  global $username, $pword, $status_msg;
  echo"

      <h2 class='shadowtext'>Вход в систему</h2>
      <form id='login_form' method='POST' action='login'>
          Логин   :<br /><input type='text' name='username' value='$username' /><br />
          Пароль  :<br /><input type='password' name='pword' value='$pword' /><br />
          $status_msg <br />
          <input type='submit' value='Login' />
          <input type='hidden' name='method' value='login' />
        <p><a href='register'>регистрация</a><br />
           <a href='reminder'>забыли пароль</a> 
        </p>
       </form>
";
}

function profile(){
    $pword=(!$_SESSION['pword'] ? strip_tags($_POST['pword']) : strip_tags($_SESSION['pword']));
    $username = (!$_SESSION['username'] ? strip_tags($_POST['username']) : strip_tags($_SESSION['username']));
    $user_db=new db();
    $username=stripcslashes($username);
    $pass=md5($pword);
    $zapros="select * from users where login='$username';";
    $user_db->get_rows($zapros);
    extract($user_db->result[0]);
    $out="<b>
    Добро пожаловать,<br/>$fname<br/>
    $name $s_name</b><br/>
    вы вошли как:<b>$login<b><br/><a href='edit_profile'>изменить профайл</a><br />
    дата регистрации:$registred<br />
    <a href='logout'>выйти</a>
    ";
    
    return $out;
    
}
?>
