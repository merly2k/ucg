<?php

// UI(user interfaces)
$template = "default"; //"index";
$ajax.="
      
      $('#login_form').ajaxForm({ 
        // target identifies the element(s) to update with the server response
        url:    'login',
        target: '#login', 
        type:   'POST',
        success: function() { 
            $('#login').fadeIn('slow'); 
        } 
        });       
       
        ";

$ajax.="
    //id system url user pasword list_url
  $('.edit_tr').click(function(){
        var ID=$(this).attr('id');
        $('#system_'+ID).hide();
        $('#url_'+ID).hide();
        $('#user_'+ID).hide();
        $('#pasword_'+ID).hide();
        $('#list_url_'+ID).hide();
                
        $('#system_input_'+ID).show();
        $('#system_input_'+ID).val($('#system_'+ID).text());
        $('#url_input_'+ID).show();
        $('#user_input_'+ID).show();
        $('#pasword_input_'+ID).show();
        $('#list_url_input_'+ID).show();
        

   }).change(function() {
        var ID=$(this).attr('id');
        var system=$('#system_input_'+ID).val();
        var url=$('#url_input_'+ID).val();
        var user=$('#user_input_'+ID).val();
        var pasword=$('#pasword_input_'+ID).val();
        var list_url=$('#list_url_input_'+ID).val();
        var step=$('#step_'+ID).val();

        var dataString = 'id='+ ID +'&system='+system+'&url='+url+
                        '&user='+user+'&pasword='+pasword+
                        '&list_url='+list_url+'&step='+step;
        $('#first_'+ID).html('<img src=\"load.gif\" />'); // Loading image
        if(system.length>0&& url.length>0&& user.length>0&& pasword.length>0&& list_url.length>0){
            $.ajax({
                    type: 'POST',
                    contentType: 'application/x-www-form-urlencoded',
                    url: 'profile_acounts',
                    data: dataString,
                    cache: false,
                    success: function(response)
                    {
                    //$('#ajax_status').html(response);
                    location.reload();
                    }
                    });
         }
         else
         {
            alert('Enter something.');
            }
            });

        // Edit input box click action
        $('.editbox').mouseup(function()
        {
            return false
        });

        // Outside click action
        $(document).mouseup(function()
        {
        $('.editbox').hide();
        $('.text').show();
        });          

";
if(!empty($_POST)){ save_profile();}
$context = "Профиль";
$db = new db();
$zapros = "select * from users where login='".$_SESSION['username']."';";
$db->get_rows($zapros);
//var_dump($db->result);
$context.= profile_form($db->result[0]);
$db->close();

$dp=new db();
$z="select `id`, `login`, `system`, `url`, `user`, `pasword`, `list_url` from `user_accounts` where `login`='".$_SESSION['username']."';";
$dp->get_rows($z);

$context.= "<table width='100%'><tr>
                    <th>#</th>
                    <th>тип сайта</th>
                    <th>адрес сайта</th>
                    <th>логин</th>
                    <th>пароль</th>
                    <th>адрес публикатора</th>
                    <th>  </th>
                    </tr>";
foreach ($dp->result as $k => $val) {
    extract($val);
    $context.= "<tr id='$id' class='edit_tr'>
                    <td>$id</td>
                    <td>
                    <span id='system_$id' class='text'>$system</span>
                    <select class='editbox'  id='system_input_$id'  name='system'>
                    <option disabled>Выберите значение</option>
                    <option value='USS Enterptise'>USS Enterptise</option>
                    <option value='VBulletin'>VBulletin</option>
                    </select></td>
                    <td>
                    <span id='url_$id' class='text'>$url</span>
                    <input type='text' class='editbox'  id='url_input_$id'  name='url' value='$url'>
                    </td>
                    <td>
                    <span id='user_$id' class='text'>$user</span>
                    <input type='text' class='editbox'  id='user_input_$id'  name='url' value='$user'>
                    </td>
                    <td><span id='pasword_$id' class='text'>$pasword</span>
                    <input type='text' class='editbox'  id='pasword_input_$id'  name='password' value='$pasword'></td>
                    <td><span id='list_url_$id' class='text'>$list_url</span>
                    <input type='text' class='editbox'  id='list_url_input_$id'  name='list_url' value='$list_url'></td>
                    <td><input type='hidden' class='editbox'  id='step_$id'  name='step' value='update'></td>
                </tr>";
    $mid=$id+1;
}
$context.= "<tr id='$mid' class='edit_tr'>
                    <td>+</td>
                    <td>
                    <span id='system_$mid' class='text'>$system</span>
                    <select class='editbox'  id='system_input_$mid'  name='system'>
                    <option disabled>Выберите значение</option>
                    <option value='USS Enterptise'>USS Enterptise</option>
                    <option value='VBulletin'>VBulletin</option>
                    </select></td>
                    <td>
                    <span id='url_$mid' class='text'>url</span>
                    <input type='text' class='editbox'  id='url_input_$mid'  name='url'>
                    </td>
                    <td>
                    <span id='user_$mid' class='text'>user login</span>
                    <input type='text' class='editbox'  id='user_input_$mid'  name='url'>
                    </td>
                    <td><span id='pasword_$mid' class='text'>pasword</span>
                    <input type='text' class='editbox'  id='pasword_input_$mid'  name='password'></td>
                    <td><span id='list_url_$mid' class='text'>list_url</span>
                    <input type='text' class='editbox'  id='list_url_input_$mid'  name='list_url'></td>
                    <td><input type='hidden' class='editbox'  id='step_$mid'  name='step' value='insert'></td>
                </tr>";
$context.="</table><br/><br />";

$dp->close();


function profile_form($dataarray) {
    extract($dataarray);
    $out.="
<div class='box'>
        Обязательно заполните поля помеченные &quot;*&quot;
<form method='POST' accept-charset='utf-8'>
<fieldset><legend>Данные для входа в систему</legend>
<p><label class='span-3 column'>* Логин: </label>
<input type='text' name='login' value='$login' required /></p>
<p><label class='span-3 column'>* Пароль:</label>
<input type='text' name='password' placeholder='новый пароль' /></p>
</fieldset>
<fieldset><legend>Личные данные</legend>
<p><label class='span-3 column'> Фамилия: </label>
<input type='text' name='f_name' value='$fname' /></p>
<p><label class='span-3 column'> Имя: </label>
<input type='text' name='name' value='$name'/></p>
<p><label class='span-3 column'> Отчество: </label>
<input type='text' value='$s_name' name='m_name' /></p>
<p><label class='span-3 column'>* e-mail: </label><input type='email' name='mail' value='$mail' /></p>
<p><label class='span-3 column'> телефон: </label><input type='text' name='phone' value='$phone'/></p>
</fieldset>
<button type='submit' name='reg' value='register'>Сохранить</button>
</form></div>
";
    return $out;
}

function save_profile(){
    //print_r($_POST);
    extract($_POST);
    $up= new db();
    if(strlen($password)>3){$passtru="`password`=md5('$password')";}else{$passtru="";}
    //login password f_name name m_name mail phone 
    $za="UPDATE `users` SET 
            `login`='$login',"
            .$passtru
            ."`name`='$name',
            `s_name`='$m_name',
            `fname`='$f_name',
            `mail`='$mail',
            `phone`='$phone' 
            WHERE  `login`='".$_SESSION['username']."';";
    $up->query($za);
    //echo $up->lastState;
}

//<a href='#' onClick='updateElement(\".help\",\"test1\");'>test</a>
$postPrint.="
        function updateTooltip(tag,urls){
            $(tag).tooltip({
                    txt: $.ajax({
                        url: urls,
                        async: false 
                                }).responseText,
                    effect: 'show',
                    duration: 90
               });        
        }
        function clearElement(tag){
        var clean='';
         $(tag).html(clean);
        }
        ";
include TEMPLATE_DIR . DS . $template . ".html";
?>
 