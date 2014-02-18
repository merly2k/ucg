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
<div id="progress">
    начинаю обработку задачи
</div>
<div id="error"></div>
<?php
ini_set("max_execution_time", 0);
require_once "./libs/simple_html_dom.php";
$task_id = $this->param[0];
$db = new db();
$zapros = "SELECT  `id`, `razdel`, `base_url`,  `pravilo` FROM `rss_sites` where `login`='" . $_SESSION['username'] . "';";
//echo $zapros;
$db->get_rows($zapros);
foreach($db->result as $rum){
extract($rum);
$urllist = list_uri($base_url);
//print_r($urllist);
$db->close();
$all=count($urllist);
$cur=0;
foreach ($urllist as $pey => $pages){
//print_r($pages);
    $cur++;
$page = multi_request($pages['link'],$cur,$all);
    
    //echo "<br />$pravilo";
    $html = str_get_html($page);
    $a = $html->find($pravilo);
    $zagolovok=$pages["zagolovok"];
    $domain = "http://".parse_url($pages['link'], PHP_URL_HOST);
//echo $domain;
//echo"$grab_mask";
//print_r($a);
    foreach ($a as $key => $element) {
        $document= $element->innertext;
    }
    $html->clear();
    unset($html);
   $aloved = "<p><a><strong><br><ul><li><img><embed><index>";
    $clear=array('~<script[^>]*>.*?</script>~si','~<style[^>]*>.*?</style>~si');
    $context1=addslashes(
        strip_tags( //удаляем теги
                preg_replace( //удаляем яваскрипт
                $clear,
                '',
                html_entity_decode( //преобразуем всё к чистому коду без сущностей
                         preg_replace('/src="\//i', 'src = "' . $domain . '/', $document),
                        ENT_NOQUOTES,
                        'UTF-8'
                        )
                ),
                $aloved)
        );

    $context2 = $context1;
    $context = preg_replace('/src=(.\.\/|\/)/', 'src = "'.$domain.'/', $context2);
    $wbd = new db();
    $aloved="<p><a><br><ul><li><img><embed><index>";
    $zapros = "INSERT INTO `grabed` (`razdel`,`site`, `full_URI`,`zagolovok`, `g_content`) VALUES ('$razdel','$base_url', '" .$pages["link"]. "','$zagolovok', '" . mysql_real_escape_string(strip_tags($context,$aloved)) . "');";
    //echo $zapros;
    $wbd->query($zapros);
    echo '<script>document.getElementById("error").innerHTML ="' . $wbd->lastState . '";</script>';
        echo str_repeat(" ", 500);
        ob_flush();
        flush();
    $wbd->close();

}
}
echo "<script>location.href='" . WWW_BASE_PATH . "task'; </script>";

function list_uri($base_url) {
//$rss = new lastRSS;
$rss = new simplepie();
$rss->set_feed_url($base_url);
$rss->init();
// Make sure the page is being served with the UTF-8 headers.
$rss->handle_content_type();
if ($rss->error):
	$out[0]=$rss->error();
else:
    
// Try to load and parse RSS file from $base_url
foreach($rss->get_items() as $item):

	// Let's give ourselves a reference to the parent $feed object for this particular item.
	$rss = $item->get_feed();
        $link= $item->get_permalink();
        $title=html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8')."<br />";

                    $out[]=array('link'=>$link,'zagolovok'=>$title);
	endforeach ;
       // print_r($out);
        return $out;
        endif;
        }
        
function tr_str($text, $limit=1000) {
    $text = mb_substr($text, 0, $limit);
    /* если не пустая обрезаем до  последнего  пробела */
    if (mb_substr($text, mb_strlen($text) - 1, 1) && mb_strlen($text) == $limit) {
        $textret = mb_substr($text, 0, mb_strlen($text) - mb_strlen(strrchr($text, ' ')));
        if (!empty($textret)) {
            return $textret;
        }
    }
    return $text;
}
function multi_request($url,$cur,$all) {
    $curly = array();
    $result = array();
    $mh = curl_multi_init();

     //CURLOPT_RETURNTRANSFER
        $a = "<progress max='$all' value='$cur'><strong>Progress: $cur % done.</strong></progress><br /> получаем $url <br />";
    echo '<script>document.getElementById("progress").innerHTML ="' . $a . '";</script>';
    echo str_repeat(" ", 500);
    ob_flush();
    flush();
        $ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url); // set url to post to 
curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable 
curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s 
$result = curl_exec($ch); // run the whole process 
curl_close($ch);   
    return $result;
}
?>


	
