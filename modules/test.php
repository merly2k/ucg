<?php
/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */
//echo "Это тестовый файл";
error_reporting(10);
$image=file_get_contents("http://merlinsoft.od.ua/img/host-panel.jpg");
     $ajax=""; 
     $postPrint="";
     $sidebar->right="";
     $cont=new image_encoder();
     $cont->filetupe="jpg";
     $context="<img src='".$cont->encode_file($image, $cont->filetype)."' alt='' title='' />";
include TEMPLATE_DIR.DS."default.html";
?>
