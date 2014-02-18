<?php
require_once "./libs/simple_html_dom.php";
/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 *
 * это что-то вроде загрузчика страниц для 
 */

$template="default"; //"index";
$site=$_GET['site'];
$html=file_get_html($site);

// Выдираем доменное имя
$domain = "http://".parse_url($site, PHP_URL_HOST);

// Удаляем все скрипты - с ними глючит страница
foreach($html->find('script') as $element) 
{
	$element->clear();
}

// Подправляем все ссылки в картинках
foreach($html->find('img') as $element) 
{
	if( $element->src[0]=='/' && $element->src[1]=='/' ) continue;
	if( !preg_match('/http:/', $element->src) )
	{
		if( $element->src[0]=='/' ) $element->src=$domain.$element->src;
		else $element->src=$domain.'/'.$element->src;
	}
}
// Подправляем все css ссылки
foreach($html->find('link') as $element) 
{
	if( $element->href[0]=='/' && $element->href[1]=='/' ) continue;
	if( !preg_match('/http:/', $element->href) )
	{
		if( $element->href[0]=='/' ) $element->href=$domain.$element->href;
		else $element->href=$domain.'/'.$element->href;
	}
}


// Выводим самю страницу и запиливаем в конец стили выделятора, 
echo $html."
		<style type='text/css'>
		   .test {
			  background-color: #9CC1E4;
			  border: 1px solid #095394;
		   }
		</style>
        <script type='text/javascript' language='javascript' src='".WWW_BASE_PATH."js/jquery.min.js'></script>
        <script type='text/javascript' language='javascript' src='".WWW_BASE_PATH."js/jquery.ui.js'></script>
        <script type='text/javascript' src='".WWW_BASE_PATH."js/jquery.form.js'></script>
		<script>				
			// Функция получает путь к указанному элементу
			getPath = function(e)
			{
				var path = '', tag, node = e;
				while( node[0]!=null )
				{
					if( node[0].tagName!=null ) path += ( 
						( path ? '>' : '' ) + 
						node[0].tagName.toLowerCase() + 
						( node[0].id ? '#'+node[0].id : '' ) + 
						( node.attr('class') ? '.'+node.attr('class') : '' )
						 );		  	
					node = node.parent();
				}
				return path;
			}
                        
                        getCath = function(e)
			{
				var path = '', tag, node = e;
				if( node[0]!=null )
				{
					if( node[0].tagName!=null ) path += ( 
						( path ? '>' : '' ) + 
						node[0].tagName.toLowerCase() + 
						( node[0].id ? '#'+node[0].id : '' ) + 
						( node.attr('class') ? '.'+node.attr('class') : '' )
						 );		  	
					//node = node.parent();
				}
				return path;
			}

			// Обработчики курсора при наведении и клике по любому из блоков
			var cnt=0;		// Счетчик блоков
			var sel=[];		// Список блоков, которые обрабатваються выделятором
			$('div, ul, li, p').mouseenter( function(){
				// Если у нас элементы идут в обратном порядке - новый элемент кладем в начало списка
				if( cnt>0 && sel[0].parent()[0]==$(this)[0] )
				{
					for( i=cnt; i>0; i-- )
					{
						sel[i]=sel[i-1];
					}
					sel[0]=$(this);
					cnt++;
				}
				// Если элементы идут в правильном порядке - новый элемент кладем в конец списка
				else
				{
					sel[cnt]=$(this);
					cnt++;
				}
				$('div, ul, li, p').removeClass('test');		
				sel[cnt-1].addClass('test');
				parent.path(getPath(sel[cnt-1]));
			}).mouseleave( function(){
				cnt--;
				$('div, ul, li, p').removeClass('test');		
				sel[cnt-1].addClass('test');	
				parent.path(getPath(sel[cnt-1]));
			});
			$('div, ul, li, p').click(function() {
                                $('div, ul, li, p').removeClass('test');
				parent.sendPath(getCath(sel[cnt-1]));
			});
		</script>
		";

//include TEMPLATE_DIR.DS."index.html";
//include TEMPLATE_DIR.DS.$template.".html";
?>
