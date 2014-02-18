<?php
print_r($_POST);

switch ($_POST[ok]) {
    case 'добавить':
        $sd= new db();
        $task_id=stripslashes($_POST['task_id']);
        $search_text=stripslashes($_POST['search_text']);
        $repl_text=stripslashes($_POST['repl_text']);
        $zapros="INSERT INTO `ppt` (`task_id`, `search_text`, `repl_text`) VALUES ('$task_id', '$search_text', '$repl_text');";
        $sd->query($zapros);
        echo($sd->lastState)."...";
        $sd->close();
        header("Location:".WWW_BASE_PATH."postprocess/test/setup");
        break;

    default:
        extract($_POST);
        $upd= new db();
        $zapros="UPDATE `ppt` SET `task_id`='$task_id', `search_text`='$search_text', `repl_text`='$repl_text' WHERE  `id`='$id' LIMIT 1;";
        $upd->query($zapros);
        echo($upd->lastState)."...";
        $upd->close();
        break;
}
/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */

/**
 * Description of save_ajax
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
?>
