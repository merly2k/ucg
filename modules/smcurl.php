<!DOCTYPE HTML>
<html lang="ru" xml:lang="ru">
    <meta charset="utf-8" />
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
error_reporting(0);
ini_set("max_execution_time", 0);
ini_set('output_buffering', 'on');
ini_set('zlib.output_compression', 0);
//ini_set('implicit_flush',1);
ob_implicit_flush();

require_once "./libs/simple_html_dom.php";
$task_id = $this->param[0];
$db = new db();
$zapros = "SELECT  `id`, `razdel`, `base_url`,  `grab_mask`,  `pravilo` FROM `urllist_sites` where `login`='" . $_SESSION['username'] . "' and `id`='$task_id'";
$db->get_rows($zapros);
//print_r($db->result[0]);
extract($db->result[0]);
//echo "<pre>";
$urllist = list_uri($base_url, $grab_mask);
$all = count($urllist);
$cur = 0;
//print_r($all);
$db->close();

foreach ($urllist as $pey => $pages) {
    $cur++; //print_r($pages);
    $page = multi_request($pages[link], $all, $cur);
    //print_r($pages[link]);

    $html = str_get_html($page);

    $a = $html->find($pravilo);
    $zagolovok = $pages["zagolovok"];
    $domain = "http://" . parse_url($base_url, PHP_URL_HOST);
//echo $domain;
//echo"$grab_mask";
//print_r($a);
    foreach ($a as $key => $element) {
        $document = $element->innertext;
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

    $context = $context1;
    $context=mb_eregi_replace($zagolovok, "",$context);
    $wbd = new db();
   
    $zapros = "INSERT INTO `grabed` (`razdel`,`site`, `full_URI`,`zagolovok`, `g_content`) VALUES ('$razdel','$base_url', '" . $pages["link"] . "','$zagolovok', '" . strip_tags($context, $aloved) . "');";
    //echo $zapros;
    $wbd->query($zapros);
    echo '<script>document.getElementById("error").innerHTML ="' . $wbd->lastState . '";</script>';
echo str_repeat(" ", 500);
ob_flush();
flush();
    $wbd->close();
}

echo "<script>location.href='" . WWW_BASE_PATH . "task'; </script>";
echo str_repeat(" ", 500);
ob_flush();
flush();

function multi_request($url, $all, $cur) {
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
    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to 
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable 
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s 
    $result = curl_exec($ch); // run the whole process 
    curl_close($ch);
    return $result;
}

function list_uri($base_url, $grab_mask) {
    $domain = "http://" . parse_url($base_url, PHP_URL_HOST);

    $html = file_get_html($base_url);
    //print_r($html);
    $a = $html->find("$grab_mask");
    foreach ($a as $key => $element) {
        $documents[] = $element->innertext;
    }
    $html->clear();
    unset($html);
    if (count($documents) > 0):
        foreach ($documents as $key => $document) {
            $o = str_get_html($document);
            foreach ($o->find('a') as $k => $e) {
                if (preg_match("#http:#", $e->href)) {
                    $link = $e->href;
                    $zagolovok = $e->title;
                } else {
                    $link = $domain . $e->href;
                    $zagolovok = $e->innertext;
                }
                if (!empty($zagolovok)) {
                    $out[] = array('link' => $link, 'zagolovok' => $zagolovok);
                }
            }
        }
    else:
        die("не валидная маска поиска");
    endif;
    //print_r($out);
    return $out;
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
?>
</html>