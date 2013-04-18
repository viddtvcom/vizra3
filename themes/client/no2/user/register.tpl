<script src="{$vurl}js/jquery.maskedinput.js" type="text/javascript"></script>

<h2 class="title2">##NewClientRegistration##</h2>
<div class="article">

    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $("#type").change(function () {
                    $(".type").hide();
                    $("." + $(this).val()).fadeIn(500);
                });
                $("#type").change();
                $(".phone").mask('999 999 99 99');
                $("#zip").mask('99999');
            });
        </script>
    {/literal}

    <form action="{$vurl}?p=user&s=register" method="post" class="cmxform">
        <input type="hidden" name="action" value="validate">
        <input type="hidden" name="token" value="{4|getToken}"/>
        <fieldset>
            <ol class="main_form">
                <li>
                    <label>##ClientDetails%AccountType##</label>
                    <select name="type" id="type">
                        <option value="individual" {if $rClient->type == 'individual'}selected{/if}>##Individual##
                        </option>
                        <option value="corporate" {if $rClient->type == 'corporate'}selected{/if}>##Corporate##</option>
                    </select>
                </li>
                <li>
                    <label>##ClientDetails%NameSurname##</label>
                    <input type="text" name="name" value="{$rClient->name}" class="tinput {if $lerrors.name}error{/if}">
                </li>
                <li class='corporate type'><label>##ClientDetails%CompanyName##</label>
                    <input type="text" name="company" value="{$rClient->company}"
                           class="tinput {if $lerrors.company}error{/if}">
                </li>
                <li><label>##ClientDetails%Email##</label>
                    <input type="text" name="email" value="{$rClient->email}"
                           class="tinput {if $lerrors.email}error{/if}">
                </li>

                <li><label>##ClientDetails%Address##</label>
                    <input type="text" name="address" value="{$rClient->address|formdisplay}"
                           class="tinput {if $lerrors.address}error{/if}">
                </li>
                <li><label>##ClientDetails%City##</label>
                    <input type="text" name="city" value="{$rClient->city}" class="tinput {if $lerrors.city}error{/if}">
                </li>
                <li><label>##ClientDetails%State##</label>
                    <input type="text" name="state" value="{$rClient->state}"
                           class="tinput {if $lerrors.state}error{/if}">
                </li>
                <li><label>##ClientDetails%Zip##</label>
                    <input type="text" name="zip" id="zip" value="{$rClient->zip}"
                           class="tinput {if $lerrors.zip}error{/if}">
                </li>
                <li><label>##ClientDetails%Country##</label>
                    <select name="country">
                        {foreach from=$ccodes item=name key=code}
                            <option value="{$code}" {if $code == 'TR'}selected{/if}>{$name}</option>
                        {/foreach}
                    </select>
                </li>
                <li><label>##ClientDetails%Phone##</label>
                    <input type="text" name="phone" value="{$rClient->phone}"
                           class="phone tinput {if $lerrors.phone}error{/if}">
                    <span> 212 333 XX XX</span>
                </li>
                <li><label>##ClientDetails%Cell##</label>
                    <input type="text" name="cell" value="{$rClient->cell}"
                           class="phone tinput {if $lerrors.cell}error{/if}">
                    <span> 532 333 XX XX</span>
                </li>
                {foreach from=$rClient->extras item=extra key=attrID}
                    <li>
                        <label>{$extra->label}</label>
                        {if $extra->type == "textbox" ||  $extra->type == "password"}
                            <input type="text" name="extras[{$attrID}]" style="width:{$extra->width}px;"
                                   value="{$extra->value}" class="tinput"/>
                        {elseif $extra->type == "checkbox"}
                            <input type="checkbox" name="extras[{$attrID}]" class="tinput" value="1"
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
                {if $tos_url}
                    <li><label>Hizmet Sözleşmesi</label>
                        <input type="checkbox" name="tos_url" value="1"> <a href="http://{$tos_url}" target="_blank">Hizmet
                            Sözleşmesini okudum ve kabul ediyorum</a>
                    </li>
                {/if}
            </ol>
        </fieldset>
        <p>
            <input type="submit" value="##Continue##" class="button right br_5"/>
        </p>
    </form>
</div>