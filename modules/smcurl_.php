<?php

ini_set("max_execution_time", 0);
require_once "./libs/simple_html_dom.php";
$task_id = $this->param[0];
$db = new db();
$zapros = "SELECT  `id`, `razdel`, `base_url`,  `grab_mask`,  `pravilo` FROM `urllist_sites` where `login`='" . $_SESSION['username'] . "' and `id`='$task_id'";
$db->get_rows($zapros);
//print_r($db->result[0]);
extract($db->result[0]);
$urllist = array_unique(list_uri($base_url, $grab_mask));
//print_r($urllist);
$db->close();
$pages = multi_request($urllist);

foreach ($pages as $pey => $page) {
    //echo $page;

    $html = str_get_html($page);

    $a = $html->find($pravilo);
//echo"$grab_mask";
//print_r($a);
    foreach ($a as $key => $element) {
        $document = $element->innertext;
    }
    $html->clear();
    unset($html);
    $order = array("\r\n", "\n", "\r");
    $replace = "<br />";
    $context1 = str_replace($order, $replace, $document);
    $tabs = "\t";
    $blan = '';
    $context = str_replace($tabs, $blan, $context1);
    $phrase = explode(".", strip_tags($context));
    $zagolovok=mb_eregi_replace(" ( +)", "", $phrase['0']);
    $context=mb_eregi_replace($zagolovok.'.', "",$context);
    $wbd = new db();
    
    $aloved="<p><a><br><ul><li><img>";
    $zapros = "INSERT INTO `grabed` (`razdel`,`site`, `full_URI`,`zagolovok`, `g_content`) VALUES ('$razdel','$base_url', '" . $urllist[$pey] . "','$zagolovok', '" . mysql_real_escape_string(strip_tags($context,$aloved)) . "');";
    //echo $zapros;
    $wbd->query($zapros);
    echo $wbd->lastState;

    $wbd->close();
}

echo "<script>location.href='" . WWW_BASE_PATH . "tasks'; </script>";

function multi_request($urls) {
    $curly = array();
    $result = array();
    $mh = curl_multi_init();

    foreach ($urls as $id => $url) { //CURLOPT_RETURNTRANSFER
        echo "получаем $url <br />";
        $curly[$id] = curl_init();
        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt($curly[$id], CURLOPT_HEADER, 0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curly[$id], CURLOPT_TIMEOUT, 30);
        curl_setopt($curly[$id], CURLOPT_PROXY, '192.168.0.1:3128'); //Закоментировать если работа без прокси
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
    } while ($running > 0);

    foreach ($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }
    //print_r($result);
    curl_multi_close($mh);
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
                    $out[] = $e->href;
                } else {
                    $out[] = $domain . $e->href;
                }
            }
        }
    else:
        die("не валидная маска поиска");
    endif;
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