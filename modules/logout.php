<?php

/*
You can expire the session which clears the session data, deletes the 
session cache file from your web server's hard drive, and expires the sid
cookie on the user's computer.
*/
$s=new sessions();
$s->expire();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>logout</title>
  <style>
  body {
    font-family:"Trebuchet MS","Arial";
    font-size:11pt;
  }
  h1 {
    margin:10px 0px 5px 0px;
  }
  </style>
  <script type="text/JavaScript">
<!--
setTimeout("location.href = 'index';",1500);
-->
</script>
  </head>
  <body>
  
  <h2>Выход</h2>
  Вы закончили работу под своей учетной записью и будете перенаправлены на главную страницу сайта
  
  </body>
</html>
