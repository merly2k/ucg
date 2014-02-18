<?php
header("Content-Type: application/octet-stream; charset=utf-8"); 
$id = $this->param[0];
//echo $id;
$mydb=new db();
$zapros='SELECT  `id`,`razdel`, `site`,  `full_URI`,  `zagolovok`,  `g_content`,  `published` FROM `grabed` LIMIT 1000;';
$mydb->get_rows($zapros);
//$fh = fopen(DATAFILE, 'w') or die("can't open file");
foreach ($mydb->result as $key => $value) {
    
  echo json_encode($value)."\n";
}
//echo "export done!";
//fclose($fh);

?>
