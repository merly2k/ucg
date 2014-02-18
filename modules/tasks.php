        <?php
        // UI(user interfaces)
        $template="default"; //"index";
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
        $context="Результаты грабинга";
        $list=site_list();
        $context.= "<hr /> ";
        foreach ($list as $key => $value) {
            extract($value);//[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
            
            $res_g=grabbage_result($base_url);
            $context.= "

            <div width='100%' class='alt1' style='border-collapse: collapse; border: solid thin black;'>
 <div id='sp_$key' class='alt2 Title' ><span class='column span-10'>$base_url </span><span class='prepend-1'>".$res_g['count']." статей</span><span class='push-0'>развернуть&nbsp;</span>
</div>
 <div class='Spoiler' style='display: none;'>
            ".$res_g['articles']."</div>
            </div><br /> 
                
               ";
            }    
            
            
            
            
            
            $context.= "<br /><a href='add_task'>добавить задачу</a>";
        
        include TEMPLATE_DIR.DS.$template.".html";
        
        function site_list() {
        $db=new db();
        $zapros="select `base_url` from sites where `login`='".$_SESSION['username']."' 
union
select `base_url` from urllist_sites where `login`='".$_SESSION['username']."' 
union
select `base_url` from rss_sites where `login`='".$_SESSION['username']."' 
;";
        $db->get_rows($zapros);
        return $db->result;
        }
        
        function grabbage_result($site){
            $SDB=new db();
            $zapros="Select `id`, `full_URI`,`zagolovok` ,`g_content` from grabed where `site`='$site' and `published`='';";
            //print_r($zapros);
            $SDB->get_rows($zapros);
            $es=0;
            foreach ( $SDB->result as $key => $grabed) {
                $es++;
                extract($grabed);
                
                $out.="<div style='border-collapse: collapse; border: solid thin black; width: 100%;'>
                    <label>Заголовок:</label>'$zagolovok'<br />
                    ".$g_content."<br />
                        
               <a href='publicator/id/$id'>Опубликовать</a></div>";
                
            }
            $ut=array("articles"=>$out,"count"=>$es);
            return $ut;
        }

        ?>
 
