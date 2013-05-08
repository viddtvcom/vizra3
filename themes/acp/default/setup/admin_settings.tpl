{if $tab == 'general' || $tab == ''}
    <img src="{$vurl}?p=image&f={$Admin->getAvatarName()}&t=avatar" alt="" id="upload_button" align="right"/>
    <form method="post" class="cmxform" style="width:100%;">
        <input type="hidden" name="action" value="update">

        {foreach from=$settings item=grp key=grp_title}
            <h3>##{$grp_title}##</h3>
            <fieldset>
                <ol>
                    {foreach from=$grp item=item}
                        <li>
                            <label>{$item.title}</label>
                            {if $item.type == "combobox"}
                                <select name="{$item.setting}">
                                    {foreach from=$item.values item=v key=k}
                                        <option value="{$k}" {if $item.selected == $k}selected{/if}>{$v}</option>
                                    {/foreach}
                                </select>
                            {elseif $item.type == 'checkbox'}
                                <input type="checkbox" name="values[{$item.settingID}]" value="1"
                                       {if $item.value == '1'}checked{/if} />
                            {else}
                                <input type="text" name="{$item.setting}" value="{$item.selected}"
                                       style="width: {$item.size}px;"/>
                            {/if}
                            &nbsp;{$item.description}
                        </li>
                    {/foreach}
                </ol>
            </fieldset>
        {/foreach}


        <p align="right"><input type="submit" value="##Update##"/></p>
    </form>
    <script src="{$vurl}js/jquery.ajaxupload.js" type="text/javascript"></script>
    <script language="JavaScript">
        var origsrc;
    </script>
        {literal}
        <script language="JavaScript">
        new AjaxUpload('upload_button', {
            // Location of the server-side upload script
            // NOTE: You are not allowed to upload files to another domain
            action: '?p=112',
            // File upload name
            name: 'avatar',
            // Additional data to send
            data: {
                action: 'uploadAvatar'
            },
            // Submit file after selection
            autoSubmit: true,
            // The type of data that you're expecting back from the server.
            // HTML (text) and XML are detected automatically.
            // Useful when you are using JSON data as a response, set to "json" in that case.
            // Also set server response type to text/html, otherwise it will not work in IE6
            responseType: "json",
            // Fired after the file is selected
            // Useful when autoSubmit is disabled
            // You can return false to cancel upload
            // @param file basename of uploaded file
            // @param extension of that file
            onChange: function (file, extension) {
            },
            // Fired before the file is uploaded
            // You can return false to cancel upload
            // @param file basename of uploaded file
            // @param extension of that file
            onSubmit: function (file, extension) {
                origsrc = $("#upload_button").attr('src');
                $("#upload_button").attr('src', 'images/loading.gif');
            },
            // Fired when file upload is completed
            // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
            // @param file basename of uploaded file
            // @param response server response
            onComplete: function (file, r) {
                if (r.st) {
                    var ts = Math.round(new Date().getTime() / 1000);
                    var src = $("#upload_button").attr('src');
                    $("#upload_button").attr('src', origsrc + '&' + ts);
                } else {
                    alert(r.msg);
                }
            }
        });
        </script>
    {/literal}


{elseif $tab == 'qreps'}
    <form method="post" style="margin-left:30px;">
        <input type="hidden" name="action" value="addQrep">
        <textarea name="reply" style="width: 500px; float: left; margin: 5px;" rows="3"></textarea>
        <input type="submit" value="Ekle" style="margin:5px;">
    </form>
    <div class="clear"></div>
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th>Hazır Cevap</th>
                    <th width="20"></th>
                </tr>
                {foreach from=$qreps item=r}
                    <tr {cycle values='class=alt,'}>
                        <td>{$r.reply}</td>
                        <td><a href="?p=112&act=delQrep&qrepID={$r.qrepID}"><img src="{$turl}images/ico_delete.png"></a>
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
{/if} 


