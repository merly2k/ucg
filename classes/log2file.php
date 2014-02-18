<?php

/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */

/**
 * Description of log2file
 *
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
class log2file {

    private $classname = __CLASS__;
    private $index = 0;
    private $tracearray = array();

    function __construct() {
        ;
    }

    function log($msg='default message', $file='log') {
        try {
            $from = get_class($this);
            $raw = debug_backtrace();
            $this->index = 0;
            foreach ($raw as $entry) {
                $output = $entry['class'] . $entry['type'] . $entry['function'];
                $this->tracearray[$this->index++] = $output;
            }
        } catch (Exception $e) {
            
        }

    file_put_contents("logs/" . $file, 'ERROR ' . $this->tracearray[2] . "  message: " . $msg . "  (" . date('c') . " )\r\n", FILE_APPEND);
    }
    function dump(){
        return var_dump($this);
    }
}

?>
