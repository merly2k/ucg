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
		

		// Функция открывает выделятор - попап c iframe для выбора блока
		showSelectBlock = function(){
			$('#blockSelector').show();
			var baseURL = $('#base_url').val();

			// Проверяем если пользователь забыл вписать протокол в УРЛ - дописываем его автоматически
			if( baseURL.substr(0,7)!=='http://' && baseURL.substr(0,8)!=='https://' ) baseURL='http://'+baseURL;

			// Получаем нужную страницу
			window.frames.grab_site.location.href='/ucg/get_page/?site='+baseURL;
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


        $context="добавление задачи ";
        if(empty($_POST['ok'])){
        $context.=form_addtask();
        }else{addtask();}
        
        include TEMPLATE_DIR.DS.$template.".html";
        
        function form_addtask() {
          //[base_url] [grab_mask][start_url] [end_url]  [pravilo][start]
            $ret="<form method='post' class='box'>
                <label>Раздел новостей<input type='text' placeholder='название раздела' name='razdel' id='razdel' required />
                <label>адрес сайта<input type='url' placeholder='http://адрес сайта' name='base_url' id='base_url' /></label> <label>что собирает<input type='search' placeholder='div.content>span.news' name='grab_mask' id='grab_mask' /></label><input type='button' onclick='showSelectBlock();' name='select' value='выбрать'><br />
                <label>адреса с: <input type='text' name='start_url' /></label>  <label>до: <input type='text' name='end_url' /></label><br />
                <label>суфикс адреса<input type='text' name='pravilo' /></label><br />
                <input type='submit' name='ok' value='ok'>
            </form>
            <div id='blockSelector' style='width:80%; height:75%; border: 1px solid black; background-color: #8AA1B6; position: absolute; top:10%; left:10%; padding: 10px 10px; padding-top: 30px;' hidden><div id='path' style='position:absolute; top:7px; left:10px;'></div><a href='' onclick='hideSelectBlock();' style='margin-right: 10px; right: 0px; position: absolute; top:5px;'>закрыть</a><iframe name='grab_site' style='width:100%; height:100%; border: 1px solid black;'></iframe></div>
            
				";
            return $ret;
        }
        
        function addtask() {
            extract($_POST);
            $db=new db();
            $zapros="INSERT INTO `sites` (`base_url`,`razdel`, `login`, `grab_mask`, `start_url`, `end_url`, `pravilo`) 
                        VALUES ('$base_url','$razdel' ,'".$_SESSION['username']."', '$grab_mask', '$start_url', '$end_url', '$pravilo');";
            $db->query($zapros);
            header("Location:task");
            //echo $db->lastState.'';
        }

        ?>
 