        <?php
        error_reporting(1);
        //print_r($_POST);
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
		

		// Функция открывает выделятор - попап c iframe для выбора блока
		showSelectBlock = function(){
			$('#blockSelector').show();
			var baseURL = $('#base_url').val();

			// Проверяем если пользователь забыл вписать протокол в УРЛ - дописываем его автоматически
			if( baseURL.substr(0,7)!=='http://' && baseURL.substr(0,8)!=='https://' ) baseURL='http://'+baseURL;

			// Получаем нужную страницу
			window.frames.grab_site.location.href='".WWW_BASE_PATH."get_page/?site='+baseURL;
		};

		
		// Функция закрывает выделятор
		hideSelectBlock = function()
		{
			$('#blockSelector').hide();
			window.frames.grab_site.location.href='about:blank';
		};

		 // В эту функцию передается путь к выбранному блоку из iframe
		 sendPath = function(path)
		 {
			$('#grab_mask').val(path);
			hideSelectBlock();
		 };

		 // В эту функцию отправляется выделенный в iframe-е путь
		 path = function( pp )
		 {
			$('#path').text(pp);
		 };


        "; 

        $postPrint='';
        $id=(int)$this->param[0];
        $dbz=new db;
        $zapros="select * FROM `rss_sites` WHERE  `id`=$id LIMIT 1;";
        $dbz->get_rows($zapros);
        $tram=$dbz->result[0];

        $context="Изменение RSS задачи ";
        if(empty($_POST['ok'])){
        $context.=form_edtask($tram);
        }elseif($_POST['step']=="2")
        {

         $context.=step2($_POST);}
        elseif($_POST['step']=="3")
            {update_url_list_task($id);}
        
        include TEMPLATE_DIR.DS.$template.".html";
        
             function form_edtask($param) {
          //[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
          extract($param);
            $ret="<form method='post' class='box'>
                <label>Раздел новостей <input type='text' name='razdel' id='razdel' value='$razdel' />
                <label>адрес RSS фида <input type='url' name='base_url' id='base_url' value='$base_url'/></label>
                <input type='hidden' name='id' value='$id' />
                <input type='hidden' name='step' value='2' />
                <input type='submit' name='ok' value='OK'>
            </form>
            ";
            return $ret;
        }
        
        function step2($param) {

          //[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
          //print_r($param);
          extract($param);
          $surl=get_rss($base_url);
          //echo $surl[link];
          //
            $ret="<form method='post' class='box'>
                $base_url<br>".$surl[0][link]."
                <input type='hidden' name='razdel' id='razdel' value='$razdel'>
                <input type='hidden' name='base_urls' id='base_urls' value='$base_url' />
                <input type='hidden' name='base_url' id='base_url' value='".$surl[0]['link']."'>
                <label>что собирает<input type='search' placeholder='div.content>span.news' name='pravilo' id='grab_mask' /></label>
                <input type='button' onclick='showSelectBlock();' name='select' value='выбрать'><br />
                <input type='hidden' name='step' value='3' />
                <input type='submit' name='ok' value='save'>
            </form>
            <div id='blockSelector' style='width:80%; height:75%; border: 1px solid black; background-color: #8AA1B6; position: absolute; top:10%; left:10%; padding: 10px 10px; padding-top: 30px;' hidden><div id='path' style='position:absolute; top:7px; left:10px;'></div><a href='' onclick='hideSelectBlock();' style='margin-right: 10px; right: 0px; position: absolute; top:5px;'>закрыть</a><iframe name='grab_site' style='width:100%; height:100%; border: 1px solid black;'></iframe></div>
            ";
            //echo $ret;
            return $ret;
        }
        
        
        function update_url_list_task($id) {
            
            extract($_POST);
            $db=new db();

            $zapros="UPDATE `rss_sites` 
                            SET `razdel`='$razdel',
                                `base_url`='$base_urls',
                                `login`='".$_SESSION['username']."', 
                                `pravilo`='$pravilo' 
                                 WHERE  `id`=$id LIMIT 1;";
            $db->query($zapros);
            
            header("Location:".WWW_BASE_PATH."task");
        }
        

        
function get_rss($base_url) {
$rss = new simplepie();
$rss->set_feed_url($base_url);
$rss->init();
$rss->handle_content_type();
if ($rss->error):
		$out[0]= $rss->error();
else:
foreach($rss->get_items() as $item):

	// Let's give ourselves a reference to the parent $feed object for this particular item.
	$rss = $item->get_feed();
        $link= $item->get_permalink();
        $title=html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8')."<br />";

                    $out[]=array('link'=>$link,'zagolovok'=>$title);
	endforeach ;
        return $out;
 endif;
      	 }                   
?>