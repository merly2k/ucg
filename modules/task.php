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
        
      $('starttask').ajaxForm({ 
        // target identifies the element(s) to update with the server response
        url:    'command',
        target: '.help', 
        type:   'POST',
        success: function() { 
            $('.help').fadeIn('slow'); 
        } 
        });  
       
        "; 
        
        $context="ЗАДАЧИ";
        $db=new db();
        $zapros="select `id`,`razdel`,`base_url` ,`grab_mask`, `start_url` ,`end_url` ,`pravilo` ,`start` from sites where login='".$_SESSION['username']."';";
        $db->get_rows($zapros);
        
        $context.= "<table class='w-24 border'>
                <thead>
                    <tr>
                        <th colspan='2' class='span-3'>раздел</th>
			<th class='span-5'>сайт</th>
			<th class='span-4'>маска поиска</th>
			<th class='span-3'>начать с</th>
                        <th class='span-3'>закончить на</th>
                        <th class='span-2'>префикс</th>
                        <th colspan='2' class='span-2'>действия</th>
                    </tr>
                </thead>            
               ";
        foreach ($db->result as $key => $value) {
            extract($value);//[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
            if ($start!='started'){$start_text='Старт';}else{$start_text='Стоп';}
            $context.= "
                <tr>
                        <td >
            <form class='starttask' method='post' action='command'>
            <input type='hidden' name='id' value='$id'>
            <a href='mcurl/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/4\");' class='ss_sprite ss_control_play_blue haz_help'></a>
            </form></td>
            <td >$razdel | ст.:".get_records($base_url)."</td>
            <td >$base_url</td>
	    <td >$grab_mask</td>
	    <td >$start_url </td>
            <td >$end_url</td>
            <td onmouseover='updateTooltip(\".haz_help\",\"help/id/1\");' class='haz_help'>$pravilo</td>
            <td>
            <a href='del_task/id/$id'  onmouseover='updateTooltip(\".haz_help\",\"help/id/2\");'  class='ss_sprite ss_application_form_delete haz_help'></a>
            </td>
            <td>
            <a href='edit_task/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/3\");' class='ss_sprite ss_script_edit haz_help'></a>
            </td>
            </tr>
                    ";
            }       
            $context.= "<tr>
            <td colspan='6'><a class='ss_sprite ss_control_play_blue' href='".WWW_BASE_PATH."allmcurl'>&nbsp; запустить все периодические задачи</a></td>
            <td colspan='3'><a class='ss_sprite ss_table_add' href='add_task'>&nbsp; Добавить задачу</td>
            </tr>    
            </table><br /><br />";
            $db->close();
            
            $zapros1="SELECT  `id`,`razdel`,  `base_url`,  `login`,  `grab_mask`,  `pravilo` FROM `urllist_sites`";
            $dbl=new db;
            $dbl->get_rows($zapros1);
            
            $context.= "<table class='w-24 border'>
                <thead>
                    <tr>
                        <th colspan='2' class='span-3'>раздел</th>
			<th class='span-5'>сайт</th>
			<th class='span-4'>маска поиска списка</th>
                        <th class='span-2'>маска поиска контента</th>
                        <th colspan='2' class='span-2'>действия</th>
                    </tr>
                </thead>            
               ";
            foreach ($dbl->result as $key => $value) {
            extract($value);//[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
            if ($start!='started'){$start_text='Старт';}else{$start_text='Стоп';}
            $context.= "
           <tr>
                <td>
                       <form class='starttask' method='post' action='command'>
                            <input type='hidden' name='id' value='$id'>
                            <a href='smcurl/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/4\");' class='ss_sprite ss_control_play_blue haz_help'></a>
                       </form>
                </td>
                <td >$razdel | ст.:".get_records($base_url)."</td>
                <td >$base_url</td>
		<td >$grab_mask</td>
		<td onmouseover='updateTooltip(\".haz_help\",\"help/id/1\");' class='haz_help'>$pravilo</td>
                <td>
                <a href='del_task_list/id/$id'  onmouseover='updateTooltip(\".haz_help\",\"help/id/2\");'  class='ss_sprite ss_application_form_delete haz_help'></a>
                </td>
                <td>
                <a href='edit_task_list/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/3\");' class='ss_sprite ss_script_edit haz_help'></a>
                </td>
           </tr>
           ";
            }       
            $db->close();
           $context.= "
               <tr>
               <td colspan='4'><a class='ss_sprite ss_control_play_blue haz_help' href='".WWW_BASE_PATH."allstart'>&nbsp; запустить все списочные задачи</a></td>
               <td colspan='3'><a class='ss_sprite ss_table_add' href='task_list'>&nbsp; добавить списочную задачу</a></td>    
               </tr>
               </table><br /><br />";
           
  
            $zapros2="SELECT  `id`,`razdel`, `base_url`,  `login`, `pravilo` FROM `rss_sites`";
            $dbr=new db;
            $dbr->get_rows($zapros2);
            
            $context.= "<table class='w-24 border'>
                <thead>
                    <tr>
                        <th colspan='2' class='span-3'>раздел</th>
			<th class='span-4'>rss</th>
                        <th class='span-2'>маска поиска контента</th>
                        <th colspan='2' class='span-2'>действия</th>
                    </tr>
                </thead>            
               ";
            foreach ($dbr->result as $key => $value) {
            extract($value);//[base_url][pravilo][start]
            if ($start!='started'){$start_text='Старт';}else{$start_text='Стоп';}
            $context.= "
            <tr>
                <td>
                 <form class='starttask' method='post' action='command'>
                    <input type='hidden' name='id' value='$id'>
                    <a href='rsscurl/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/4\");' class='ss_sprite ss_control_play_blue haz_help'></a>
                </form>
                </td>
                <td >$razdel | ст.:".get_records($base_url)."</td>
		<td >$base_url</td>
		<td onmouseover='updateTooltip(\".haz_help\",\"help/id/1\");' class='haz_help'>$pravilo</td>
                <td>
                    <a href='del_rss_list/id/$id'  onmouseover='updateTooltip(\".haz_help\",\"help/id/2\");'  class='ss_sprite ss_application_form_delete haz_help'></a>
                </td>
                <td>
                    <a href='edit_rss_list/id/$id' onmouseover='updateTooltip(\".haz_help\",\"help/id/3\");' class='ss_sprite ss_script_edit haz_help'></a>
                </td>
            </tr>
            ";
            }       
            $db->close();
           $context.= "<tr>
               <td colspan='4'><a class='ss_sprite ss_control_play_blue haz_help' href='".WWW_BASE_PATH."allrsscurl'>&nbsp; запустить все rss задачи</a></td>
               <td colspan='3'><a href='".WWW_BASE_PATH."rss_list' class='ss_sprite ss_table_add'>&nbsp; добавить RSS задачу</a></td>    
               </tr>
               </table><br /><br /><br /><br />";
           
           
           
           
            $postPrint.="
        function updateTooltip(tag,urls){
            $(tag).tooltip({
                    txt: $.ajax({
                        url: urls,
                        async: false 
                                }).responseText,
                    effect: 'show',
                    duration: 90
               });        
        }
        function clearElement(tag){
        var clean='';
         $(tag).html(clean);
        }
        ";
            //<a href='#' onClick='updateElement(\".help\",\"test1\");'>test</a>
     $sidebar->right="<h4>подсказка</h4><div class='help'></div>";
        include TEMPLATE_DIR.DS.$template.".html";
function get_records($task){
    $tdb=new db();
    $zapros="select count(*) as `res` from grabed where site='$task' and published='';";
    //echo $zapros;
    $tdb->get_rows($zapros);
    return $tdb->result[0]['res'];
}
?>
 