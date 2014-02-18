<?php

$task_id=(int)$argv[1];
include "./config.php";
include "./classes/db.php";
$db=new db();
$zapros="select `id`,`base_url`,`login`,`grab_mask`,`start_url`,`end_url`,`pravilo`,`start` from sites where id=$task_id ;";
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
//echo"$grab_mask";
//print_r($a);
foreach($a as $key=>$element){
    $document=$element->innertext;}
    $html->clear();
    unset($html);
$order=array("\r\n", "\n", "\r");
$replace="<br />";
$context1 = str_replace($order, $replace, $document); 
$tabs="\t";
$blan='';
$context = str_replace($tabs, $blan, $context1);
    
   $wbd=new db();
   $zapros="INSERT INTO `grabed` (`site`, `full_URI`, `g_content`) VALUES ('$base_url', '".$urllist[$pey]."', '".mysql_real_escape_string($context)."');";
   //echo $zapros;
   $wbd->query($zapros);
   //echo $wbd->lastState;
   
   $wbd->close();
}

echo "<script>
   location.href='".WWW_BASE_PATH."/tasks';
    </script>
";


function multi_request($urls) {
  $curly = array();
  $result = array();
  $mh = curl_multi_init();
 
  foreach ($urls as $id => $url) { //CURLOPT_RETURNTRANSFER
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
?>