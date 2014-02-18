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
ini_set('output_buffering', 'on');
ini_set('zlib.output_compression', 0);
//ini_set('implicit_flush',1);
ob_implicit_flush();


$task_id=$this->param[0];
$db=new db();
$zapros="select `id`,`razdel`,`base_url`,`login`,`grab_mask`,`start_url`,`end_url`,`pravilo`,`start` from sites  where `login`='" . $_SESSION['username'] . "';";
$db->get_rows($zapros);
//print_r($db->result[0]);
extract($db->result[0]);
//$url_array=range($start_url, $end_url);
//$a= strcmp($start_url, $end_url);
for ($index = (int)$start_url; $index < (int)$end_url; $index++) {
            $urllist[]=$base_url.$index.$pravilo;
  
}
$db->close();
$pages=multi_request($urllist);

require_once "./libs/simple_html_dom.php";
foreach ($pages as $pey=>$page) {
    //echo $page;
$html=str_get_html($page);

$a=$html->find($grab_mask);

$domain = "http://".parse_url($base_url, PHP_URL_HOST);
//echo"$grab_mask";
//print_r($a);
foreach($a as $key=>$element){
    $document=$element->innertext;}
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
$context = preg_replace('/src="\//', 'src = "'.$domain.'/', $context2);
$phrase = explode(".", strip_tags($context));
$zagolovok=mb_eregi_replace(" ( +)", "", $phrase['0']);
$context=mb_eregi_replace($zagolovok.'.', "",$context);
    
    
   $wbd=new db();
   $aloved="<p><a><br><ul><li><img><embed><index><div>";
   $zapros="INSERT INTO `grabed` (`razdel`,`site`, `full_URI`, `g_content`) VALUES ('$razdel','$base_url', '".$urllist[$pey]."', '".mysql_real_escape_string(strip_tags($context,$aloved))."');";
   //echo $zapros;
   $wbd->query($zapros);
   echo '<script>document.getElementById("error").innerHTML ="' . $wbd->lastState . '";</script>';
        echo str_repeat(" ", 500);
        ob_flush();
        flush();
   $wbd->close();
}

echo "<script>
   location.href='".WWW_BASE_PATH."tasks';
    </script>
";


function multi_request($urls) {
  $curly = array();
  $result = array();
  $mh = curl_multi_init();
  
 $all=count($urls);
 $cur=0;
  foreach ($urls as $id => $url) { //CURLOPT_RETURNTRANSFER
   $cur++;   
   $a = "<progress max='$all' value='$cur'><strong>Progress: $cur % done.</strong></progress><br /> получаем $url <br />";
    echo '<script>document.getElementById("progress").innerHTML ="' . $a . '";</script>';
    echo str_repeat(" ", 500);
    ob_flush();
    flush();
    $curly[$id] = curl_init();
    curl_setopt($curly[$id], CURLOPT_URL, $url);
    curl_setopt($curly[$id], CURLOPT_HEADER, 0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curly[$id], CURLOPT_TIMEOUT, 30);
    curl_setopt($curly[$id], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($curly[$id], CURLOPT_INTERFACE, ‘192.168.0.1′),
    //раскоментировать эту строку если нужно задавать разные айпи
    curl_multi_add_handle($mh, $curly[$id]);
  }
 
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
 
  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  //print_r($result);
  curl_multi_close($mh);
  return $result;
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