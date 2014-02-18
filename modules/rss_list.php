        <?php
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


        $context="добавление rss задачи ";
        if(empty($_POST['ok'])){
        $context.=form_addtask();
        }elseif($_POST['step']=="2")
        {
         $context.=step2();}
        elseif($_POST['step']=="3")
            {add_url_list_task();}
        
        include TEMPLATE_DIR.DS.$template.".html";
        
        function form_addtask($param) {
          //[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
            $ret="<form method='post' class='box'>
                <label>Раздел новостей <input type='text' placeholder='название раздела' name='razdel' id='razdel' required />
                <label>адрес RSS фида <input type='url' placeholder='http://адрес сайта' name='base_url' id='base_url' /></label>
                <input type='hidden' name='step' value='2' />
                <input type='submit' name='ok' value='OK'>
            </form>
            ";
            return $ret;
        }
        
        function step2($param) {
          //[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
          extract($_POST);
          $surl=get_rss($base_url);
          //
            $ret="<form method='post' class='box'>
                $base_url<br>$surl
                <input type='hidden' name='razdel' id='razdel' value='$razdel'>
                <input type='hidden' name='base_urls' id='base_urls' value='$base_url' />
                <input type='hidden' name='base_url' id='base_url' value='$surl'>
                <input type='hidden' name='grab_mask'  value='$grab_mask'>
                <label>что собирает<input type='search' placeholder='div.content>span.news' name='pravilo' id='grab_mask' /></label>
                <input type='button' onclick='showSelectBlock();' name='select' value='выбрать'><br />
                <input type='hidden' name='step' value='3' />
                <input type='submit' name='ok' value='save'>
            </form>
            <div id='blockSelector' style='width:80%; height:75%; border: 1px solid black; background-color: #8AA1B6; position: absolute; top:10%; left:10%; padding: 10px 10px; padding-top: 30px;' hidden><div id='path' style='position:absolute; top:7px; left:10px;'></div><a href='' onclick='hideSelectBlock();' style='margin-right: 10px; right: 0px; position: absolute; top:5px;'>закрыть</a><iframe name='grab_site' style='width:100%; height:100%; border: 1px solid black;'></iframe></div>
            ";
            return $ret;
        }
        
        function add_url_list_task($param) {
            //print_r($_POST);
            extract($_POST);
            $db=new db();
            $zapros="INSERT INTO `rss_sites` (`base_url`,`razdel`, `login`, `pravilo`) 
                        VALUES ('$base_urls','$razdel', '".$_SESSION['username']."', '$pravilo');";
            $db->query($zapros);
            header("Location:task");
        }
        
        function first_uri($base_url,$grab_mask){
          
          $domain = "http://".parse_url($base_url, PHP_URL_HOST);
          require_once "./libs/simple_html_dom.php";
          $html=file_get_html($base_url);
          $a=$html->find("$grab_mask");
          //print_r($html->find("$grab_mask"));
          foreach($a as $key=>$element){
                $document=$element->innertext;}
                $html->clear();
          unset($html);
          $o=str_get_html($document);
          foreach($o->find('a') as $k=>$e){
              $out=$e->href;
          }
          if (preg_match("#http:#", $out)){
          return $out;}else{
          return $domain.$out;
          }
        }
        
        function get_rss($feed){
             $rss = new lastrss;

// Set cache dir and cache time limit (1200 seconds)
// (don't forget to chmod cahce dir to 777 to allow writing)
$rss->cache_dir = '';
$rss->cache_time = 0;
$rss->cp = 'UTF-8';
$rss->date_format = 'l';

// Try to load and parse RSS file from $base_url
$rs = $rss->get($feed);
if (!empty($rs)) {
    
        $out=(!empty($rs[items][0][link]))?$rs[items][0][link]:$rs[entry][0][link];
        } else {
    echo "Error:не удалось получить данные с $rssurl...";
} 
    return $out;
        }
        ?>