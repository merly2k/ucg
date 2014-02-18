<?php

/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */
define("PRODUCT", "prod"); //@val = dev or prod
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__)); // локальный путь к скриптам проекта
define('WWW_BASE_PATH','http://'.$_SERVER['SERVER_NAME'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME'])); //определяем адрес
define('WWW_CSS_PATH', WWW_BASE_PATH . 'css/'); //пути к файлам стилей
define('WWW_JS_PATH', WWW_BASE_PATH . 'js/'); //пути к файлам яваскриптов
define('WWW_IMAGE_PATH', WWW_BASE_PATH . 'images/'); //пути к файлам изображений
define("TEMPLATE_DIR", APP_PATH.DS."templates");
define("APP_LOG", APP_PATH . DS . "logs"); //пути к файлам логов

if (PRODUCT == 'dev') {
    define("DB_HOST", "localhost");   //Database host.
    define("DB_USER", "root");        //Database username.
    define("DB_PASSWORD", "azazello"); //Database password.
    define("DB_NAME", "graber");       //Database.
} else {
    define("DB_HOST", "localhost");    //Database host.
    define("DB_USER", "root");         //Database username.
    define("DB_PASSWORD", "azazello"); //Database password.
    define("DB_NAME", "graber");        //Database.
}
/**
 * Description of config
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
?>
