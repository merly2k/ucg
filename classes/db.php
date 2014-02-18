<?php

class db {
   public $query;
   public $result =array();
   public $succes;
   private $a;
   static $inst;
   public $lastState;
   public $found;

   function __construct() {
         $host = DB_HOST;
         $login = DB_USER;
         $password = DB_PASSWORD;
         $dbname = DB_NAME;

   mysql_connect ($host, $login, $password) or die(mysql_error());
   mysql_select_db ($dbname);
    $inst=1;
    $error = mysql_error();
    if ($error) throw new Exception($error);

    mysql_select_db($dbname);
    $error = mysql_error();
    $inst++;
    if ($error) throw new Exception($error);
    
    mysql_query('set names "utf8"');
    $inst++;
    $error = mysql_error();
    if ($error) throw new Exception($error);
    }
    
    

    function q($zapros){
        $this->clRes();
        $this->result=mysql_fetch_array (mysql_query ($zapros));
        $this->lastState=mysql_error();
    }
    
    function q_null($zapros){
        
        $r = mysql_query ($zapros);
        if (!mysql_error()){
            $this->result= $r;
            $this->lastState=mysql_error();
        }else{
            $this->result=-1;
            $this->lastState=mysql_error();
        }
    }

    function query ($zapros){
        $this->clRes();
        $this->result= mysql_query ($zapros);
        $this->lastState=mysql_error();
    }
    
    function get_rows($zapros){
        $this->clRes();
        $rest=mysql_query($zapros);
        $this->found= $this->count_row();
        while ($row = mysql_fetch_assoc($rest)) {
            $this->a[]=$row;
        }
        $this->result= $this->a;
        $this->lastState=mysql_error();
    }
    
    function get_cols($zapros){
        $this->clRes();
        $rest=mysql_query($zapros);
        $this->found=  $this->count_row();
        $this->lastState=mysql_error();
        while ($row = mysql_fetch_assoc($rest)) {
            foreach ($row as $key => $value) {
               $this->result[$key][]=$value;
            }
                
        }
        
    }
    function fetch_array ($zapros){
        //echo $zapros;
        $this->clRes();
        $this->result= mysql_fetch_assoc(mysql_query($zapros));
        $this->found=  $this->count_row();
        $this->lastState=mysql_error();
    }
    function clRes() {
        $this->result=(array) null;
        $this->lastState=mysql_error();
    }
    
    function count_row() {
        $ar=mysql_fetch_array(mysql_query('SELECT FOUND_ROWS()as found'));
        return (int)$ar['found'];
    }
    
    function close(){
        mysql_close();
    }
}
?>


