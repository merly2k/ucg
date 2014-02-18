<?php

// UI(user interfaces)
$template = "default"; //"index";
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
$context='';
$task_id = $this->param[0];


$context .= "<p><a>правила постпроцессинга</a></p><p>добавить правило запустить обработку базы</p>";
/**
$context.= "<select name='site' id='togo'>";
$context.= "<option value='' >select</option>";
foreach (@site_list() as $key => $value) {
    extract($value);
$context.= "<option value='" . WWW_BASE_PATH . "ptf/pub/url/$razdel' >$razdel</option>";    
}
*/

$ppt = new db();
$zapros = "select * from ppt where 1";
$ppt->get_rows($zapros);
$context.= "<table class='w-24 border'>
                <thead>
                    <tr>
                        <th class='span-2'>правило №</th>
                        <th class='span-5'>что искать</th>
                        <th class='span-5'>чем заменить</th>
                        <th colspan='2' class='span-2'>действия</th>
                    </tr>
                </thead>            
               ";
foreach($ppt->result as $r=>$vale){
    //print_r($vale);
    extract($vale);
    // id task_id  search_text repl_text 
    $context.= "
           <tr>
                <td>
                      $id 
                </td>
                <td ><textarea rows='1' readonly='readonly'>$search_text</textarea></td>
		<td ><textarea rows='1' readonly='readonly'>$repl_text</textarea></td>
		<td>
                <a href='del_ppt/id/$id'  onmouseover='updateTooltip(\".haz_help\",\"help/id/2\");'  class='ss_sprite ss_application_form_delete haz_help'></a>
                </td>
                <td>
                <a href='edit_ppt/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/3\");' class='ss_sprite ss_script_edit haz_help'></a>
                </td>
           </tr>
           ";
            
}
$context.= "</table><br />
                        <br /><br />";
//------------------------------------------------------------------------------
$postPrint.="
        function updateElement(tag,urls){
        //alert(tag);
                $.ajax(
                         {
                            type: \"get\",
                            url: urls,
                            success: function(html){                                     
                            $(tag).html(html);
                            }
                          }
                        );
              }
        function clearElement(tag){
        var clean='';
         $(tag).html(clean);
        }
        ";
$sidebar->right = "<div class='help'></div><a href='#' onClick='updateElement(\".help\",\"help/id\");'>test</a>";

include TEMPLATE_DIR . DS . $template . ".html";


?>
