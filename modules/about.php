        <?php
        // UI(user interfaces)
        $template="default"; //"index";
        $ajax.="
      
      $('#login_form').ajaxForm({ 
        // target identifies the element(s) to update with the server response 
        url:    'login',
        target: '#login', 
        type:   'POST',
        success: function() { 
            $('#login').fadeIn('slow'); 
        } 
        });       
        "; 
        
        $context="система создана для сбора информации в сети интернет<br />";
        $context.=laphabet_link("http://ya.ru/");
function laphabet_link($url){ 
    //range(0x410, 0x44F)
foreach (range(0x410, 0x42F) as $char) {

    $char = html_entity_decode("&#$char;", ENT_COMPAT, "UTF-8");
    $text.= '<a href="'.$url . $char . '">' . $char . '</a> | ';
}                       
return $text;
}
        include TEMPLATE_DIR.DS.$template.".html";
?>
 