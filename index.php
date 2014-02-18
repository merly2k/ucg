<?php

error_reporting(5);
ini_set("max_execution_time", 0);

require_once 'config.php';

function __autoload($class_name) {
    $possibilities = array(
        APP_PATH . DS . $class_name . '.php',
        APP_PATH . DS . "classes" . DS . $class_name . '.php',
        APP_PATH . DS . "classes" . DS . "modules" . DS . $class_name . '.php',
        $class_name . '.php'
    );
    foreach ($possibilities as $file) {
        if (file_exists($file)) {
            require_once($file);
            return true;
        }
    }
    return false;
}

$app = new app();
$app->router();
unset($app);



?>