<form method="post" id="formgrid">
    <input type="hidden" name="action" id="action" value="moduleCmd">
    <input type="hidden" name="cmd" id="cmd">
    <fieldset>
        <ol class="main_ol_content">
            {foreach from=$srv item=obj key=setting}
                {if $obj->value !== ''}
                    <li>
                        <label>{$obj->label}</label>
                        {if $obj->type == "textbox" ||  $obj->type == "password"}
                            {if $obj->cmd != ''}
                                <input type="text" name="srv[{$setting}]" value="{$obj->value}">
                            {else}
                                {$obj->value}
                            {/if}
                        {elseif $obj->type == "textarea"}
                            {if $obj->cmd != ''}
                                <textarea name="srv[{$setting}]"
                                          style="width: {$obj->width}; height: {$obj->height};">{$obj->value}</textarea>
                            {else}
                                {$obj->value}
                            {/if}
                        {elseif $obj->type == "checkbox"}
                            {if $obj->value == '1'}Evet{else}Hayır{/if}
                        {elseif $obj->type == "combobox"}
                            {assign var=value value=$obj->value}{$obj->options.$value}
                        {/if} {$obj->description}
                        &nbsp;&nbsp;&nbsp;
                        {if $obj->cmd != ''}
                            <input type="submit" value="##Update##" onclick="$('#cmd').val('{$obj->cmd}');">
                        {/if}
                    </li>
                {/if}
            {/foreach}
            {if $Order->Server}
                <li><label>Sunucu</label> {$Order->Server->serverName}</li>
                {if $Order->Server->settings.ns1}
                    <li><label>1.DNS Sunucusu</label> {$Order->Server->settings.ns1}</li>
                {/if}
                {if $Order->Server->settings.ns2}
                    <li><label>2.DNS Sunucusu</label> {$Order->Server->settings.ns2}</li>
                {/if}
            {/if}
            {foreach from=$Order->moduleLinks item=link key=label}
                <li><label>##{$label}##</label>{$link|linkify:1}&nbsp;</li>
            {/foreach}
        </ol>
    </fieldset>
</form>