<?php

$template = "default"; //"index";
$ajax.="
        $('.Title').click(function () {
        $(this).next('.Spoiler').toggle();
        });
        
      $('#login_form').ajaxForm({ 
        // target identifies the element(s) to update with the server response 
        url:    'login',
        target: '#login', 
        type:   'POST',
        success: function() { 
            $('#login').fadeIn('slow'); 
        } 
        });
        
        $('.rte').rte({
    content_css_url: 'css/rte.css',
    media_url: '',
});

       ";
$postPrint.= "  
       
       ";
$context.="Редактирование аккаунтов для публикации<br/>";

$systems=new db();
$zapros="select * from user_accounts where `login`='".$_SESSION["username"]."'";

$systems->get_rows($zapros);
foreach ($systems->result as $key => $value) {
    extract($value);
$context.="<p><span class='w20'>$id</span>
    <div class='span-1'>$system </div>
    <div class='span-10'>$url</div>
    <span class='w40'>$user</span>
    <span class='w80'>$pasword</span>
    <span class='w40'>$url</span></p>";
}
$context.=$systems->lastState;
//// put your code here
        include TEMPLATE_DIR.DS.$template.".html";
?>
