<?php
/**
 *  Testing vbPost class
 */

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
        
$('.rte').rte({
    content_css_url: 'css/rte.css',
    media_url: '',
});";   

//print_r($this);
//echo $this->param[0]; //получаем номер записи
$id=$this->param[0];
    $template="default"; //"index";
$pdb= new db();
$zapros="Select `id`, `full_URI`, `g_content` from grabed where `id`='$id';";
//echo $zapros;
$pdb->get_rows($zapros);
extract($pdb->result[0]);

$usites=new db;
$zsitelist="select `login`,`system`,`url`,`user`,`pasword`,`list_url` from user_accounts where login='".$_SESSION['username']."';";
$usites->get_rows($zsitelist);
$maxSitesToProcess = 1;
$optlist="<select name='publish_to'>";
foreach ($usites->result as $n => $site) {
    extract($site);//[login][system][url][user][pasword][list_url]
    $optlist.="<option name='to' value='$system:$url:$user:$pasword:$list_url'>$url:$user</option>";
}
$optlist.="</select>";

$context="
<form name='publikator' method='POST'>
<textarea name='article'class='rte'>$g_content</textarea>
  $optlist  
<input type='submit' value='submit'/>
</form>
";

// paths
$path = __DIR__. "/";
$cookiesPath = APP_PATH;

// Post on site


include TEMPLATE_DIR.DS.$template.".html";
?>
