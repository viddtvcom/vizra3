<h2 class="title2">##TopMenu%MyDetails## </h2>
<div class="article">

    <ul class="s_details br_5">
        <li class="left tleft">
            <img src="{$vurl}?p=image&f={$Client->getAvatarName()}&t=avatar" alt="" id="upload_button" class="left"
                 style="margin-right:10px; border:1px solid #CCCCCC; padding:1px;"/>

            <p style="display:block; margin-left:80px; line-height:14px;">
                <span style="color:#fff; font-size:14px;"><b>##ClientDetails%CLIENTNUMBER##:</b> #{$Client->clientID}</span><br/>
                <span style="color:#999;">{$client->name} test</span>
            </p>
        </li>
        <li class="right">

            <input type="button" class="button br_5" onclick="window.location='{$vurl}?p=user&s=details&a=password'"
                   value="##ClientDetails%ChangePassword##">
        </li>
    </ul>

    <p class="user_avatar" style="float: right;">

    </p>

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol class="main_form">
                <li style="margin-bottom:10px;">
                    <label>##ClientDetails%AccountType##</label>
                    <span style="padding-top:6px;float:left; display:block;">{if $Client->type == 'individual'}##Individual##{else}##Corporate##{/if}</span>
                </li>
                <li>
                    <label>##ClientDetails%NameSurname##</label>
                    <input type="text" name="name" value="{$Client->name}" class="tinput {if $lerrors.name}error{/if}">
                </li>
                {if $Client->type == 'corporate'}
                    <li><label>##ClientDetails%CompanyName##</label>
                        <input type="text" name="company" value="{$Client->company}"
                               class="tinput {if $lerrors.company}error{/if}">
                    </li>
                {/if}
                <li><label>##ClientDetails%Email##</label>
                    <input type="text" name="email" value="{$Client->email}"
                           class="tinput {if $lerrors.email}error{/if}">
                </li>

                <li><label>##ClientDetails%Address##</label>
                    <input type="text" name="address" value="{$Client->address|formdisplay}"
                           class="tinput {if $lerrors.address}error{/if}">
                </li>
                <li><label>##ClientDetails%City##</label>
                    <input type="text" name="city" value="{$Client->city}" class="tinput {if $lerrors.city}error{/if}">
                </li>
                <li><label>##ClientDetails%State##</label>
                    <input type="text" name="state" value="{$Client->state}"
                           class="tinput  {if $lerrors.state}error{/if}">
                </li>
                <li><label>##ClientDetails%Zip##</label>
                    <input type="text" name="zip" id="zip" value="{$Client->zip}"
                           class="tinput  {if $lerrors.zip}error{/if}">
                </li>
                <li><label>##ClientDetails%Country##</label>
                    <select name="country">
                        {foreach from=$ccodes item=name key=code}
                            <option value="{$code}" {if $code == 'TR'}selected{/if}>{$name}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    <input type="text" name="phone" value="{$Client->phone}"
                           class="phone tinput {if $lerrors.phone}error{/if}">
                    <span> 212 333 XX XX</span>
                </li>
                <li><label>##ClientDetails%Cell##</label>
                    <input type="text" name="cell" value="{$Client->cell}"
                           class="phone tinput {if $lerrors.cell}error{/if}">
                    <span> 532 333 XX XX</span>
                </li>
                {foreach from=$Client->extras item=extra key=attrID}
                    <li>
                        <label>{$extra->label}</label>
                        {if $extra->type == "textbox" ||  $extra->type == "password"}
                            <input type="text" name="extras[{$attrID}]" style="width:{$extra->width}px;" class="tinput"
                                   value="{$extra->value}">
                        {elseif $extra->type == "checkbox"}
                            <input type="checkbox" name="extras[{$attrID}]" value="1"
                                   {if $extra->value == '1'}checked{/if}>
                        {elseif $extra->type == "combobox"}
                            <select name="extras[{$attrID}]">
                                {foreach from=$extra->options item=v key=k}
                                    <option value="{$k}" {if $extra->value == $k }selected{/if}>{$v}</option>
                                {/foreach}
                            </select>
                        {elseif $extra->type == "textarea"}
                            <textarea name="extras[{$attrID}]" class="tinput"
                                      style="width:{$extra->width}px; height:{$extra->height}px;">{$extra->value}</textarea>
                        {/if} {$extra->description}
                        &nbsp;&nbsp;&nbsp;
                    </li>
                {/foreach}
            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Update##" class="button br_5"/>
        </p>
    </form>

</div>


<script src="{$vurl}js/jquery.ajaxupload.js" type="text/javascript"></script>
<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>
<script language="JavaScript">
    var origsrc;
</script>

{literal}
    <script language="JavaScript">
        new AjaxUpload('upload_button', {
            // Location of the server-side upload script
            // NOTE: You are not allowed to upload files to another domain
            action: vurl + '?p=ajax',
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

{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $(".phone").mask('999 999 99 99');
            $("#zip").mask('99999');
        });
    </script>
{/literal}

