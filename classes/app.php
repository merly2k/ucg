<?php

/**
 * Description of app
 *
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
class app extends application {

    public $classname = __CLASS__;
    public $ajax;
    public $content;
    public $tpl=array();
    public $postprint;
    public $db;
    public $auch;
    public $param;
    public $script;
    public $action;

    public function __construct() {
        parent::__construct();
        $this->db=new db();
      
    }

    public function set($name, $value=NULL) {
        $this->$name = $value;
        return TRUE;
    }

    public function get($name) {
        return $this->$name;
    }
    
           function router() {
            //echo "определяем параметры запроса и задаём параметры по умолчанию";
            $route_array = preg_replace("#route=#", "", $_SERVER["QUERY_STRING"]);
            $route = preg_split("#/#", trim($route_array, '/'));
            $length=count($route)-1;
            if (!$route[0]) {
                $route[0] = "index";
            }
            if (!$route[1]) {
                $route[1] = "index";
            }
            if (!$route[2]) {
                $this->param = array();
            } else {
                $this->param = array_splice($route, 2);
            }//определяем другие параметры скрипта                }
            
            $script = $route["0"]; //Определяем выполняемый скрипт
            $action = $route["1"]; //Определяем действие для скрипта(если нужно)
            $possibilities = array(
                ///APP_PATH . DS . $script . '.php', //искать в корне
                APP_PATH . DS . "modules" . DS . $script . '.php', //искать в корне модулей
                APP_PATH . DS . "modules" . DS . $script . DS . 'index.php', //искать в папках модулей
                $script . '.php'                                                //искать по текущему пути
            );
            foreach ($possibilities as $file) {
                if (file_exists($file)) {
                    //Инициализируем переменные шаблона
                    $sidebar = (object) '';
                    $sidebar->right = '';
                    $postPrint = '';
                    $ajax = '';
                    require_once($file);
                    //echo "file $file loaded";
                    return true;
                } else {
                    ob_clean();
                    return header("HTTP/1.0 404 Not Found");
                }
            }
        }
    
    public function __destruct() {
        
        $this->db->close();
       
    }

}

?>
