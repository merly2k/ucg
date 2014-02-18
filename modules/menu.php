<?php
/**
 * Description of menu
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
$admins[]='admin';
$admins[]='merl';
if(in_array($_SESSION["username"],$admins)){$access=9;}else{$access=1;}
$mnu=new db();
$zapros="select * from menu where `acl`='$access'";
$mnu->get_rows($zapros);
foreach ($mnu->result as $key => $value) {
    extract($value);
    echo "<a class='button black' href='".WWW_BASE_PATH."$meny_link'><span class='ss_sprite $icon'>&nbsp;</span>$menu_name</a>";
}
?>
