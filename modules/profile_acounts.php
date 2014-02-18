<?php
//print_r($_POST);
switch ($_POST['step']) {
    case 'update':
        update_record();
//    [id] [system] [url] [user] [pasword][list_url]

        break;
case 'insert':
    //[id] [system] [url] [user] [pasword][list_url]
        insert_record();
        break;

    default:
        update_record();
        break;
}
function update_record(){
    extract($_POST);//[id] [system] [url] [user] [pasword][list_url]
    $bz=new db();
    $zapros="UPDATE `user_accounts` SET 
        `system`='$system',
        `url`='$url',
        `user`='$user',
        `pasword`='$pasword',
        `list_url`='$list_url'
         WHERE  `id`='$id' LIMIT 1;";
    $bz->query($zapros);
    return $bz->lastState;
    
    };
 
 function insert_record(){
    $ibi=new db();
    extract($_POST);
    $zapros="INSERT INTO `user_accounts` (`login`, `system`, `url`, `user`, `pasword`, `list_url`) 
    VALUES ('".$_SESSION['username']."', '$system', '$url', '$user', '$pasword', '$list_url');";
    $ibi->query($zapros);
    
    return $ibz->lastState; 
 };
?>
