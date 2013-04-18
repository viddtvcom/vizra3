<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>

<h2 class="title700">##NewClientRegistration##</h2>
<div class="content_right">

    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $("#type").change(function () {
                    $(".type").hide();
                    $("." + $(this).val()).fadeIn(500);
                });
                $("#type").change();
                //$("#zip").mask('99999');

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

    <form action="{$vurl}?p=user&s=register" method="post" class="cmxform">
        <input type="hidden" name="action" value="validate">
        <input type="hidden" name="token" value="{4|getToken}"/>
        <fieldset>
            <ol>
                <li>
                    <label>##ClientDetails%AccountType##</label>
                    <select name="type" id="type" class="tinput_2">
                        <option value="individual" {if $rClient->type == 'individual'}selected{/if}>##Individual##
                        </option>
                        <option value="corporate" {if $rClient->type == 'corporate'}selected{/if}>##Corporate##</option>
                    </select>
                </li>
                <li>
                    <label>##ClientDetails%NameSurname##</label>
                    <input type="text" name="name" value="{$rClient->name}"
                           class="tinput_2 w_300 {if $lerrors.name}error{/if}">
                </li>
                <li class='corporate type'><label>##ClientDetails%CompanyName##</label>
                    <input type="text" name="company" value="{$rClient->company}"
                           class="tinput_2 w_300 {if $lerrors.company}error{/if}">
                </li>
                <li><label>##ClientDetails%Email##</label>
                    <input type="text" name="email" value="{$rClient->email}"
                           class="tinput_2 w_300 {if $lerrors.email}error{/if}">
                </li>

                <li><label>##ClientDetails%Address##</label>
                    <input type="text" name="address" value="{$rClient->address|formdisplay}"
                           class="tinput_2 w_300 {if $lerrors.address}error{/if}">
                </li>
                <li><label>##ClientDetails%City##</label>
                    <input type="text" name="city" value="{$rClient->city}"
                           class="tinput_2 w_300 {if $lerrors.city}error{/if}">
                </li>
                <li><label>##ClientDetails%State##</label>
                    <input type="text" name="state" value="{$rClient->state}"
                           class="tinput_2 w_300  {if $lerrors.state}error{/if}">
                </li>
                <li><label>##ClientDetails%Zip##</label>
                    <input type="text" name="zip" id="zip" value="{$rClient->zip}"
                           class="tinput_2 w_300  {if $lerrors.zip}error{/if}">
                </li>
                <li><label>##ClientDetails%Country##</label>
                    <select name="country" id="country" class="tinput_2">
                        {foreach from=$countries item=country }
                            <option cc="{$country.calling_code}" ccmask="{$country.calling_code_mask}"
                                    value="{$country.country_code}"
                                    {if $country.default == '1' && $rClient->country == ''}selected{/if}
                                    {if $rClient->country  == $country.country_code}selected{/if}
                                    >
                                {$country.country}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="phone" value="{$rClient->phone}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.phone}error{/if}">

                </li>
                <li><label>##ClientDetails%Cell##</label>
                    + <span class="ccode" style="font-size: 13px;"></span>
                    <input type="text" name="cell" value="{$rClient->cell}" style="width: 200px;" maxlength="15"
                           class="phone tinput_2  {if $lerrors.cell}error{/if}">
                </li>
                {foreach from=$rClient->extras item=extra key=attrID}
                    <li class="{if $extra->client_type != 'all'}{$extra->client_type} type{/if}">
                        <label>{$extra->label}</label>
                        {if $extra->type == "textbox" ||  $extra->type == "password"}
                            <input type="text" name="extras[{$attrID}]" style="width:{$extra->width}px;"
                                   value="{$post_extras.$attrID}" class="tinput_2">
                        {elseif $extra->type == "checkbox"}
                            <input type="checkbox" name="extras[{$attrID}]" value="1"
                                   {if $post_extras.$attrID == '1'}checked{/if}>
                        {elseif $extra->type == "combobox"}
                            <select name="extras[{$attrID}]">
                                {foreach from=$extra->options item=v key=k}
                                    <option value="{$k}" {if $post_extras.$attrID == $k }selected{/if}>{$v}</option>
                                {/foreach}
                            </select>
                        {elseif $extra->type == "textarea"}
                            <textarea name="extras[{$attrID}]"
                                      style="width:{$extra->width}px; height:{$extra->height}px;">{$post_extras.$attrID}</textarea>
                        {/if} {$extra->description}
                        &nbsp;&nbsp;&nbsp;
                    </li>
                {/foreach}
                {if $tos_url}
                    <li><label>Hizmet Sözleşmesi</label>
                        <input type="checkbox" name="tos_url" value="1"> <a href="http://{$tos_url}" target="_blank">Hizmet
                            Sözleşmesini okudum ve kabul ediyorum</a>
                    </li>
                {/if}
            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Continue##" class="button right"/>
        </p>
    </form>

</div>

