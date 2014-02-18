        <?php
        // UI(user interfaces)

        $template="default"; //"index";
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
        $context="Система предназначена для сбора информации в сети интернет ";
                  $postPrint.="
        function updateElement(tag,urls){
        //alert(tag);
                $.ajax(
                         {
                            type: \"get\",
                            url: urls,
                            success: function(html){                                     
                            $(tag).html(html);
                            }
                          }
                        );
              }
        function clearElement(tag){
        var clean='';
         $(tag).html(clean);
        }
        ";
        $sidebar->right .= "<div class='help'></div><a href='#' onClick='updateElement(\".help\",\"help/id\");'>test</a>";
        
        include TEMPLATE_DIR.DS.$template.".html";
        ?>
 