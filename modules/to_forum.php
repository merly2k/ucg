<?php
/**
 *  Testing vbPost class
 */
//print_r($this);
//echo $this->param[0]; //получаем номер записи
$id=$this->param[0];

$pdb= new db();
$zapros="Select * from grabed where `id`='$id';";
echo $zapros;
$pdb->get_rows($zapros);
print_r($pdb->result);
extract($pdb->result[0]);

 $title =$zagolovok ;
 $post_body = html_entity_decode($g_content);//newthread.php?do=postthread&amp;f=267
 $postsites = array(
				 array(
					"siteurl" => "http://mskforum.ru/",
					"username" => "Редакция",
					"password" => "1111111",
                                     	"categoryid" => "267",
					"cookiefile" => "cookie.txt"
					)
                );

/**
 * Process 6 sites in one multi request, so if there are 60 total sites in $postsites
 * it will split request to 10
 */
$maxSitesToProcess = 1;

// paths
$path = __DIR__. "/";
$cookiesPath = APP_PATH;

// Post on site
$p = new VBPost($postsites, $cookiesPath, $maxSitesToProcess);
$p->post($title,$post_body);
echo "опубликовано";


?>
