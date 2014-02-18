<?php

/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */

/**
 * Description of command
 * use *.bat @c:\php\cli\php.exe script.php %1 %2 %3 %4
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
class command {
    

    public $classname = __CLASS__;
    public $patch;

    function __construct() {
        $this->patch = dirname(__FILE__);
        ini_set("max_execution_time", 0)     ;
    }
    function start($param,$arg) {
        
       $this->php_path="c:\\xampp\\php\\php.exe";
       $command_str=$this->php_path." ".APP_PATH."\\commands\\$param.php $arg";
       system($command_str);
       return TRUE;
       
    }
    

    public function set($name, $value=NULL) {
        $this->$name = $value;
        return TRUE;
    }

    public function get($name) {
        return $this->$name;
    }
    
    public function install(){
        
       $fp = popen("ftype phpfile=$php_path -f '%1' -- %~2");
       pclose($fp); 
    }

}

$worker=new command();
$rezalt=$worker->start('mcurl',$this->param[0]);
?>
