/* 
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */


$(document).ready(function(){
                 
        function updateChat(){
                    $.ajax(
                            {
                            type: "POST",
                            url: "chat",
                            success: function(html){$("#right>div.chat").html(html); }
                            }
                        );
              }
          });