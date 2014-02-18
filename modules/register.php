<?php

/* регистрация пользователей
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */
$template="default"; //"index";
/**
 * Description of register
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
$context.= "<h2>регистрация пользователя</h2>";
if($_POST['reg']) {
    $context.=register_user($_POST);
}else{
    $context.=reg_form();
}
    
function gen_rand_str($length = 8){
  $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
  $numChars = strlen($chars);
  $string = '';
  for ($i = 0; $i < $length; $i++) {
    $string .= substr($chars, rand(1, $numChars) - 1, 1);
  }
  return $string;
}    
    
    
function register_user($param) {
    extract($param);
    $userbase=new db();
    $str=gen_rand_str();
    $ion=md5($str);
    $zapros="INSERT INTO `users` (`login`, `password`, `name`, `s_name`, `fname`, `mail`, `registred`, `cod`, `phone`) 
                VALUES ('$login', md5('$password'), '$name', '$m_name', '$f_name', '$mail', now(),'".$ion."' , '$phone');";
    //echo $zapros.'<br>';
    $userbase->query($zapros);
    if($userbase->lastState){
        $out='не удалось создать пользователя'; 
        $userbase->close();
        
        }else{
$message="Уважаемый $f_name $name $m_name,
    Вы или ктото от вашего имени, зарегистрировались в сервиесе грабинга контента
    Ваши учетные данные логин: $login 
                        пароль: $password
                        телефон:$phone 
    В случае утери пароля вы можете его восстановить использовав форму восстановления(новый пароль будет сгенерирован и выслан вам)
    и указав в ней телефон и e-mail использованные для регистрации
    ";
$m= new libmail('utf-8');  // можно сразу указать кодировку, можно ничего не указывать ($m= new Mail;)
$m->From( "система регистрации пользователей;noreplay@site.com" ); // от кого Можно использовать имя, отделяется точкой с запятой
$m->To($mail);   // кому, в этом поле так же разрешено указывать имя
$m->Subject( "регистрация в сервисе Универсальный грабер" );
$m->Body($message);
$m->Priority(4) ;	// установка приоритета
$m->Send();	// отправка
$out="Уважаемый пользователь Вам отправлено письмо следующего содержания:<br><pre>".$m->Get()."</pre>"; 

    }
    return $out;
}

function reg_form() {
    $out.="
<div class='box'>
        Обязательно заполните поля помеченные &quot;*&quot;
<form method='POST' accept-charset='utf-8'>
<fieldset><legend>Данные для входа в систему</legend>
<p><label class='span-3 column'>* Логин: </label>
<input type='text' name='login' placeholder='логин' required /></p>
<p><label class='span-3 column'>* Пароль:</label>
<input type='text' name='password' placeholder='пароль' required /></p>
</fieldset>
<fieldset><legend>Личные данные</legend>
<p><label class='span-3 column'> Фамилия: </label>
<input type='text' name='f_name' placeholder='Фамилия' /></p>
<p><label class='span-3 column'> Имя: </label>
<input type='text' name='name' placeholder='Имя'/></p>
<p><label class='span-3 column'> Отчество: </label>
<input type='text' placeholder='Отчество' name='m_name' /></p>
<p><label class='span-3 column'>* e-mail: </label><input type='email' name='mail' required /></p>
<p><label class='span-3 column'> телефон: </label><input type='text' name='phone' /></p>
</fieldset>
<button type='submit' name='reg' value='register'>Зарегистрироваться</button>
</form>
";
    return $out;
}
include TEMPLATE_DIR.DS.$template.".html";
?>
