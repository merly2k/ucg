<style>
    progress,          /* All HTML5 progress enabled browsers */
    progress[role]     /* polyfill */
    {

        /* Turns off styling - not usually needed, but good to know. */
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;

        /* gets rid of default border in Firefox and Opera. */
        border: none;

        /* Needs to be in here for Safari polyfill so background images work as expected. */
        background-size: auto;

        /* Dimensions */
        width: 400px;
        height: 16px;

    }

    /* Polyfill */
    progress[role]:after {
        background-image: none; /* removes default background from polyfill */
    }

    /* Ensure fallback text doesn't appear in polyfill */
    progress[role] strong {
        display: block ;
        font-size: 8px;
    }
</style>
<?php
ini_set("max_execution_time", 0);
ini_set('output_buffering', 'on');
ini_set('zlib.output_compression', 0);
//ini_set('implicit_flush',1);
ob_implicit_flush();

// UI(user interfaces)
$template = "default"; //"index";
$ajax.="
        $('#togo').change(function() {window.location = $('#togo').val();  });
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
        
$('#all').click(function() {
         $('.cheker').attr('checked', true);
        });
$('#bum').click(function() {
        $('.cheker').attr('checked', false);
            });
        
        $('.rte').rte({
    content_css_url: 'css/rte.css',
    media_url: '',
});

       ";
$postPrint.= "  
 
function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}
       ";
//print_r($this->param);
$task_id = $this->param[0];
$list = site_list();
$context = "Публикация в форум :".$this->param[1];
switch ($task_id) {
    case '':
        $context.= "<select name='site' id='togo'>";
        $context.= "<option value='' >select</option>";
        foreach ($list as $ky => $vl) {
            extract($vl);

            $context.= "<option value='" . WWW_BASE_PATH . "ptf/pub/url/$razdel' >$razdel</option>";
        }
        $context.= "</select'>";

        break;
    case 'url':
        $rl = $this->param[1];
        $context.= "<br/>" . grabbage_result($rl);
        break;
    case 'publikate':
        $context.="<div id='process' ><br />";
        ob_flush();
        flush();
        $tread=$_POST['forumid'];
        $ph = new vbtool("http://mskforum.ru/", 'msk.txt');
        if ($ph->vB_login('Редакция', '1111111')) {
        
            $context.= '<br />вошли на форум!<br />';
            $all=count();
            foreach ($_POST['art'] as $y => $pe) {
                $pe = (int) $pe;
                $bar = new db();
                $zapros = "select * from `grabed` where `id`=$pe limit 1;";
                $bar->get_rows($zapros);
                extract($bar->result[0]);
                $bar->close();
                //$tread = 267; //hfpltk
                if ($ph->vB_post_thread($tread, html_entity_decode($zagolovok), html_entity_decode($g_content))) {
                    $up = new db();
                    $zapros = "UPDATE `grabed` SET `published`=now() WHERE  `id`=$pe LIMIT 1;";
                    $up->query($zapros);
                    $a = "<progress max='$all' value='$cur'><strong>Progress: $cur % done.</strong></progress><br /> получаем $url <br />";
                    echo '<script>document.getElementById("process").innerHTML ="' . $a . '";</script>';
                    echo str_repeat(" ", 500);
                    ob_flush();
                    flush();
                    $up->close();
                    sleep(2);
                }
                $context.= '<br /><br /><br />';
            }
        } else {
            $context.= '<br />данные авторизации не верны или форум не доступен';
        }

        break;
    case 'truncate':
        $zapros=$zapros = "TRUNCATE `grabed`;";
        $bzz=new db();
        $bzz->query($zapros);
        $bzz->close();
        header("Location:" . WWW_BASE_PATH . "ptf/");
        break;
    case 'del':
        foreach ($_POST['art'] as $y => $pe) {
                $pe = (int) $pe;
                $bar = new db();
                $zapros = "delete from `grabed` where `id`=$pe limit 1;";
                $bar->query($zapros);
                $bar->close();
            }
        

        break;

    default:
        break;
}



$context.= "<br />";

include TEMPLATE_DIR . DS . $template . ".html";

function site_list() {
    $db = new db();
    $zapros = "select `base_url`,`razdel` from sites where `login`='" . $_SESSION['username'] . "' 
        union
        select `base_url`,`razdel` from urllist_sites where `login`='" . $_SESSION['username'] . "' 
        union 
        select `base_url`,`razdel` from rss_sites where `login`='" . $_SESSION['username'] . "' 
        ;";
    $db->get_rows($zapros);
    return $db->result;
}

function grabbage_result($razdel) {
    $SDB = new db();
    $zapros = "Select `id`, `full_URI`,`zagolovok` from grabed where `razdel`='$razdel' and `published`='';";
    //echo $zapros;
    $SDB->get_rows($zapros);
    //print_r($SDB->result. $SDB->lastState);
    $out.="<form name='publikator' method='POST'>\r\n
                    <table border='1'>\r\n
                     <thead>
                    <tr>\r\n
                    <td>выбор:<br /><a href='#' id='all'>всё</a> | <a href='#' id='bum'>нет</a></td>\r\n
                    <td>статья</td>\r\n
                    </tr>\r\n
                     </thead>
                     <tbody>";
    foreach ($SDB->result as $key => $grabed) {
        extract($grabed);
        $out.="<tr>\r\n
                    <td><input type='checkbox' class='cheker' name='art[$key]' value='$id' /></td>\r\n
                    <td>$zagolovok</td>\r\n
                    </tr>\r\n";
    }
    $out.="</tbody></table>
        <br />
        ";
    $out.=file_get_contents(APP_PATH.DS.'libs'.DS.'selector.php').'<br />';
    $out.="<input formaction='" . WWW_BASE_PATH . "ptf/pub/publikate' type='submit' name='ok' value='Публиковать'><br /> 
        <input formaction='" . WWW_BASE_PATH . "ptf/pub/del' type='submit' name='del' value='Удалить из базы'><br /><br />
        <input formaction='" . WWW_BASE_PATH . "ptf/pub/truncate' type='submit' name='dellall' value='полностью очистить базу' onclick=\"return confirm('действительно очистить ВСЮ базу?')\"><br /><br />
          ";
    return $out;
}

/**
 * todo: хлебные крошки
 * todo: список форумов
 */
?>
 
