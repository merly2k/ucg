<script language='JavaScript' type='text/javascript'>
        <!--
            function spoiler(obj)
            {
                  for (var i = 0; i < obj.childNodes.length; i++)
                  {
                        if (obj.childNodes[i].id == 'idTitle')
                            titleRow = obj.childNodes[i];
                    if (obj.childNodes[i].id == 'idSpoiler')
                    {
                        if (obj.childNodes[i].style.display != 'none')
                        {
                            obj.childNodes[i].style.display = 'none';
                            titleRow.innerHTML = '&nbsp;<b>Click to show spoiler</b>';
                        }
                        else
                        {
                            obj.childNodes[i].style.display = 'block';
                            titleRow.innerHTML = '&nbsp;<b>Click to hide spoiler</b>';
                        }
                    }
                }
            }
        //--></script>
<div width="100%" class="alt1" onclick="spoiler(this);" style="border-collapse: collapse; border: solid thin black;"><div id="idTitle" class="alt2" style="border-collapse: collapse; border: solid thin black; width: 100%;">&nbsp;<b>Click to show spoiler</b></div><div id="idSpoiler" style="display: none;">{param}</div></div><br />/* 


