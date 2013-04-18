<div class="forms_wrapper">
<form method="post" class="search_form general_form" id="frmClient">
<input type="hidden" name="action" value="update">
<fieldset>
    <div class="forms">
        <div class="row">
            <label>Hesap Tipi</label>

            <div class="inputs">
                    <span class="input_wrapper select_wrapper">
                    <select name="type" id="type" class="text">
                        <option value="individual" {if $client->type == 'individual'}selected{/if}>##Individual##
                        </option>
                        <option value="corporate" {if $client->type == 'corporate'}selected{/if}>##Corporate##</option>
                    </select>   
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Adı Soyadı</label>

            <div class="inputs">
                <span class="input_wrapper"><input class="text" type="text" name="name" value="{$client->name}"/></span>
            </div>
        </div>
        <div class="row">
            <label>Durum</label>

            <div class="inputs">
                    <span class="input_wrapper select_wrapper">
                     <select name="status">
                         {foreach from=$vars.CLIENT_STATUS_TYPES item=t}
                             <option value="{$t}" {if $t == $client->status }selected{/if}>##{$t}##</option>
                         {/foreach}
                     </select>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Şirket</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="company" value="{$client->company}"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Email</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="email" value="{$client->email}"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Şifre</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="password" autocomplete="off"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Adres</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="address" value="{$client->address}"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Semt / Posta Kodu</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="state" value="{$client->state}"/>
                    </span>
                    <span class="input_wrapper short_input">
                        <input class="text" type="text" name="zip" value="{$client->zip}"/>
                    </span>

            </div>
        </div>
        <div class="row">
            <label>Şehir</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="city" value="{$client->city}"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Ülke</label>

            <div class="inputs">
                    <span class="input_wrapper select_wrapper">
                    <select name="country" id="country" class="tinput">
                        {foreach from=$countries item=country }
                            <option cc="{$country.calling_code}" ccmask="{$country.calling_code_mask}"
                                    value="{$country.country_code}"
                                    {if $client->country  == $country.country_code}selected{/if}
                                    >
                                {$country.country}</option>
                        {/foreach}
                    </select>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Telefon</label>

            <div class="inputs">
                    <span class="input_wrapper short_input">
                        + <span class="ccode" style="font-size: 13px;"></span> 
                    </span>
                    <span class="input_wrapper medium_input">
                        <input class="text phone" type="text" name="phone" id="phone" value="{$client->phone}"/>
                    </span>

            </div>
        </div>
        <div class="row">
            <label>GSM</label>

            <div class="inputs">
                    <span class="input_wrapper short_input">
                        + <span class="ccode" style="font-size: 13px;"></span> 
                    </span>
                    <span class="input_wrapper medium_input">   
                        <input class="text phone" type="text" name="cell" id="cell" value="{$client->cell}"/>
                    </span>
            </div>
        </div>
        <div class="row">
            <label>Otomasyon</label>

            <div class="inputs">
                <input type="checkbox" value="1" name="autoSuspend" {if $client->autoSuspend == '1'}checked{/if}/>
                Bu müşterinin hesaplarında otomatik askıya alma ve silme işlemi yapılabilir.
            </div>
        </div>
        <div class="row">
            <label>Flash Not</label>

            <div class="inputs">
                    <span class="input_wrapper">
                        <input class="text" type="text" name="fnote" id="fnote" value="{$client->fnote}"/>
                    </span>
            </div>
        </div>

        <div class="row">
            <label>Notlar</label>

            <div class="inputs">
                    <span class="input_wrapper textarea_wrapper" style="width: 300px;">
                        <textarea class="text" name="notes" id="notes" rows="5" cols="40">{$client->notes}</textarea>
                    </span>
            </div>
        </div>


        {foreach from=$client->extras item=extra key=attrID}
            <div class="row">
                <label>{$extra->label}</label>

                <div class="inputs">
                    {if $extra->type == "textbox" ||  $extra->type == "password"}
                        <span class="input_wrapper">
                    <input class="text" type="text" name="extras[{$attrID}]" style="width:{$extra->width}px;"
                           value="{$extra->value}">
                </span>
                    {elseif $extra->type == "checkbox"}
                        <input type="checkbox" name="extras[{$attrID}]" value="1" {if $extra->value == '1'}checked{/if}>
                    {elseif $extra->type == "combobox"}
                        <span class="input_wrapper select_wrapper">
                <select name="extras[{$attrID}]">
                    {foreach from=$extra->options item=v key=k}
                        <option value="{$k}" {if $extra->value == $k }selected{/if}>{$v}</option>
                    {/foreach}
                </select>
                </span>
                    {elseif $extra->type == "textarea"}
                        <textarea name="extras[{$attrID}]"
                                  style="width:{$extra->width}px; height:{$extra->height}px;">{$extra->value}</textarea>
                    {/if}

                    {if $extra->visibility == 'hidden'}
                        <span class="system negative">{$extra->description}</span>
                    {else}
                        {$extra->description}
                    {/if}
                </div>
            </div>
        {/foreach}
        <div class="row">
            <label>Kayıt Tarihi</label>

            <div class="inputs">
                {format_date date=$client->dateAdded mode="datetime"}
            </div>
        </div>
        <div class="row">
            <label>Son Login Tarihi</label>

            <div class="inputs">
                {format_date date=$client->dateLogin mode="datetime"}
            </div>
        </div>
        <div class="row">
            <label>Son Login Ip</label>

            <div class="inputs">
                {$client->ipLogin}
            </div>
        </div>

    </div>
</fieldset>
<p align="right"><input type="submit" name="submit" value="##Update##"></p>
</form>
</div>
<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>
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
