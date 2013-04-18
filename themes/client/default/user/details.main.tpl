<h2 class="title700">##TopMenu%MyDetails## </h2>
<span class="right" style="margin: 2px;">
        <input class="button" type="button" onclick="window.location='{$vurl}?p=user&s=details&a=password'"
               value="##ClientDetails%ChangePassword##">
    </span>
<ul class="s_details">
    <li class="left tleft">
        <span style="color:#666;">##ClientDetails%CLIENTNUMBER## #{$Client->clientID}</span><br/>
        <span style="color:#000;">{$client->name} </span>
    </li>
    <li class="right tright">
        <br/>
        <b></b>
    </li>
</ul>

<div class="content_right">

    <br/>

    <p class="user_avatar" style="float: right;">
        <img src="{$vurl}?p=image&f={$Client->getAvatarName()}&t=avatar" alt="" id="upload_button"/><br/>
    </p>

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>##ClientDetails%AccountType##</label>
                    {if $Client->type == 'individual'}##Individual##{else}##Corporate##{/if}
                </li>
                <li>
                    <label>##ClientDetails%NameSurname##</label>
                    <input type="text" name="name" value="{$Client->name}"
                           class="tinput_2 w_300 {if $lerrors.name}error{/if}">
                </li>
                {if $Client->type == 'corporate'}
                    <li><label>##ClientDetails%CompanyName##</label>
                        <input type="text" name="company" value="{$Client->company}"
                               class="tinput_2 w_300 {if $lerrors.company}error{/if}">
                    </li>
                {/if}
                <li><label>##ClientDetails%Email##</label>
                    <input type="text" name="email" value="{$Client->email}"
                           class="tinput_2 w_300 {if $lerrors.email}error{/if}">
                </li>

                <li><label>##ClientDetails%Address##</label>
                    <input type="text" name="address" value="{$Client->address|formdisplay}"
                           class="tinput_2 w_300 {if $lerrors.address}error{/if}">
                </li>
                <li><label>##ClientDetails%City##</label>
                    <input type="text" name="city" value="{$Client->city}"
                           class="tinput_2 w_300 {if $lerrors.city}error{/if}">
                </li>
                <li><label>##ClientDetails%State##</label>
                    <input type="text" name="state" value="{$Client->state}"
                           class="tinput_2 w_300  {if $lerrors.state}error{/if}">
                </li>
                <li><label>##ClientDetails%Zip##</label>
                    <input type="text" name="zip" id="zip" value="{$Client->zip}"
                           class="tinput_2 w_300  {if $lerrors.zip}error{/if}">
                </li>
                <li><label>##ClientDetails%Country##</label>
                    <select name="country" id="country" class="tinput_2">
                        {foreach from=$countries item=country }
                            <option cc="{$country.calling_code}" ccmask="{$country.calling_code_mask}"
                                    value="{$country.country_code}"
                                    {if $Client->country  == $country.country_code}selected{/if}
                                    >
                                {$country.country}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="phone" value="{$Client->phone}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.phone}error{/if}">

                </li>
                <li><label>##ClientDetails%Cell##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="cell" value="{$Client->cell}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.cell}error{/if}">
                </li>
                {foreach from=$Client->extras item=extra key=attrID}
                    <li class="{if $extra->client_type != 'all'}{$extra->client_type} type{/if}">
                        <label>{$extra->label}</label>
                        {if $extra->type == "textbox" ||  $extra->type == "password"}
                            <input type="text" name="extras[{$attrID}]" style="width:{$extra->width}px;"
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
                            <textarea name="extras[{$attrID}]"
                                      style="width:{$extra->width}px; height:{$extra->height}px;">{$extra->value}</textarea>
                        {/if} {$extra->description}
                        &nbsp;&nbsp;&nbsp;
                    </li>
                {/foreach}
            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Update##" class="right button"/>
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
            $("#country").change(function () {
                $('.ccode').html($('option:selected', this).attr('cc'));
                if ($('option:selected', this).attr('ccmask') != '') {
                    $(".phone").mask($('option:selected', this).attr('ccmask'));
                }
                else {
                    $(".phone").unmask();
                }
            });
            $("#country").change();
        });
    </script>
{/literal}

