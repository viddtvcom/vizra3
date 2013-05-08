{if $tab == 'attrs'}
    {include file='setup/service_details_attrs.tpl'}
{elseif $tab == 'general'  || $tab == ''}
    {include file='setup/service_details_general.tpl'}
{elseif $tab == 'payment'}
    {include file='setup/service_details_price.tpl'}
{else if $tab == 'files'}
    <p class="msg_ok">
        Bu servise bağlı aktif siparişi olan müşteriler, aşağıdaki seçili kategorilerden dosya indirebilir.
    </p>
    <form method="post" class="cmxform" style="width:500px;">
        <input type="hidden" name="action" value="updateFiles">
        <fieldset>
            <ol>
                {foreach from=$cats item=c}
                    <li>
                        <input type="checkbox" name="selected[]" value="{$c.catID}"
                               {if in_array($c.catID,$Service->file_cats)}checked{/if}>
                        <a href="?p=230&catID={$c.catID}">{$c.title}</a>
                    </li>
                {/foreach}
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>
{/if}
<div class="clear"></div>
