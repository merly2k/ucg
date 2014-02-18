<?php

/*напоминание пароля
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */
$id=$this->param[0];
$template="default"; //"index";
/**
 * Description of reminder
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
if($_POST['mail']){
    $context.=remind($_POST['mail']);
    
    }else{
        $context.=remind_form();
        
        }

function remind_form() {
    $out.="
<div class='box'>
        Обязательно заполните поля помеченные &quot;*&quot;
<form accept-charset='utf-8'>
<fieldset><legend>Данные для входа в систему</legend>
<p><label class='span-3 column'>* e-mail: </label>
<input type='mail' name='mail' placeholder='e-mail'/></p>
<p><label class='span-3 column'> телефон: </label><input type='text' name='phone' /></p>
<button type='submit' value='remind'>Напомнить пароль</button>
</fieldset>
</form>
";
    return $out;
}
include TEMPLATE_DIR.DS.$template.".html";
?>
