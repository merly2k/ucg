<?php
error_reporting(0);
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
$ppt = new db();
$zapros = "select SQL_CALC_FOUND_ROWS * from ppt where 1";
$ppt->get_rows($zapros);
//print_r($ppt->result);
$all=count($ppt->result); 
$task_id = $this->param[0];
switch ($this->param[0]) {
    case 'start':
        $cur=1;
        $context.="<div id='progress'>начинаем обработку базы</div>";
        
        $sidebar->right = "<div class='help'></div><a href='#' onClick='updateElement(\".help\",\"help/id\");'>test</a>";
        include TEMPLATE_DIR . DS . $template . ".html";
        $gr=new db();
        foreach ($ppt->result as $value) {
            extract($value);
            $pravilo="UPDATE grabed SET `g_content`=REPLACE(`g_content`, '".stripslashes($search_text)."', '".stripslashes($repl_text)."');";
            $gr->query($pravilo);
            if($gr->lastState<=''){
                
                if($cur<$all){ $a = "<progress max='$all' value='$cur'><strong>Progress: $cur % done.</strong></progress><br /> применено правило: $cur ! ".$gr->lastState."<br />";}
                else{$a="Обработка завершена";}
                echo '<script>document.getElementById("progress").innerHTML ="' . $a . '";</script>';
            }
            $cur++;
        }
        $gr->close();
        break;
    case 'setup':
        $page= $this->param[1];
        $perpage=10;
        $start=$page*$perpage;
        $end=$perpage;
        $limit="$start,$end" ;
        $zapros = "select SQL_CALC_FOUND_ROWS * from ppt order by `id` DESC limit $limit";
        $pptg=new db();
        $pptg->get_rows($zapros);
        $all=$pptg->found;
        $pages=(int)($all/$perpage);
        if($pages>0){
      $paginator.="<ul id='pagination-digg'>";
        if($page!=0){
      $paginator.="<li><a href='".WWW_BASE_PATH."postprocess/test/setup/".($page-1)."'>«</a></li>";
      } else {$paginator.="<li class='previous-off'>«</li>";}
        
      for ($isa=0;$isa<=$pages;$isa++){
            if($page==$isa){
        $paginator.=" <li class='active'>".($page+1)."</li> ";        
       
            }else{
        $paginator.="<li><a href='".WWW_BASE_PATH."postprocess/test/setup/$isa'>".($isa+1)."</a></li> ";
      
        }
        }
        if($page<$pages){
      $paginator.="<li class='next'><a href='".WWW_BASE_PATH."postprocess/test/setup/".($page+1)."'>»</a></li></ul> ";
        }
        else{$paginator.="<li class='next-off'>»</li></ul>";}
        
        } else {$paginator='';}
             
$context.="<p><a href='".WWW_BASE_PATH."postprocess/test/start'>запустить обработку базы</a> | <a href='".WWW_BASE_PATH."postprocess/test/setup'>настройки обработок базы</a></p><h3>настройки постпроцессинга</h3>";

$context.="<p>добавить правило</p><form method='POST' action='".WWW_BASE_PATH."save_ajax'>
<label><input type='hidden' name='task_id' val='0' /></label>
<label>что ищем: <textarea name='search_text' rows='2' cols='30'></textarea></label>
<label>чем заменяем: <textarea name='repl_text' rows='2' cols='30'></textarea></label>
<input type='submit' value='добавить' name='ok' />
</form>";
$grid = new editable_table();
$grid->greed=$pptg->result;
$grid->act='update';
$grid->table="customer";
$grid->fieldset=array(
    "id" => "readonly",
    "task_id" =>"hidden",
    "search_text" =>"textarea",
    "repl_text" =>"textarea"
    );
$grid->t_header = array("id" => "#","task_id"=>"правило для раздела","search_text"=>"что искать","repl_text"=>"чем заменять");
$grid->request_uri=WWW_BASE_PATH.'save_ajax';
$grid->render_greed();
$ajax.=$grid->ajax;
$context.= "<br />стр.$paginator<br />";  
$context.= $grid->content;
$context.= "<br />стр.$paginator<br />";          
$sidebar->right = "<div class='help'></div><a href='#' onClick='updateElement(\".help\",\"help/id\");'>test</a>";

include TEMPLATE_DIR . DS . $template . ".html";
        break;
        default:
$context .='';

$context .= "<p><a href='".WWW_BASE_PATH."postprocess/test/start'>запустить обработку базы</a> | <a href='".WWW_BASE_PATH."postprocess/test/setup'>настройки обработок базы</a></p>";
/**
$context.= "<select name='site' id='togo'>";
$context.= "<option value='' >select</option>";
foreach (@site_list() as $key => $value) {
    extract($value);
$context.= "<option value='" . WWW_BASE_PATH . "ptf/pub/url/$razdel' >$razdel</option>";    
}
*/



    $context.= "
           задано правил: $all
           ";
            
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


        break;
}

?>
 