<h3>Servis Ekle</h3>
<form method="post" id="formgrid" style="width:98%;">
    <input type="hidden" name="action" value="add">
    <ul>
        <li class="first">
            <label>Modül</label>
            <select name="moduleID">
                <option value="">##None##</option>
                {foreach from=$modules item=title key=moduleID}
                    <option value="{$moduleID}" {if $smarty.post.moduleID == $moduleID}selected{/if}>{$title}</option>
                {/foreach}
            </select>
        </li>
        <li>
            <label>Sipariş Tipi</label>
            <select name="addon">
                <option value="0" {if $smarty.post.addon == '0' }selected{/if}>Ana Sipariş</option>
                <option value="1" {if $smarty.post.addon == '1' }selected{/if}>Eklenti</option>
            </select>
        </li>
        <li>
            <label>Servis Tipi</label>

            <div>
                {foreach from=$vars.SERVICE_TYPES item=i key=k}
                    <label><input type="radio" name="type" value="{$k}"
                                  {if $k == 'shared'}checked{/if}> {$i}</label>
                {/foreach}
            </div>
        </li>
        <li class="clear">
            <label>Servis Grubu:</label>
            {$select_groups}
        </li>
        <li>
            <label>Servis Adı:</label>
            <input id="name" type="text" name="service_name" class="w_200" value="{$smarty.post.service_name}"/>
        </li>
    </ul>
    <p align="right"><input type="submit" value="Servisi Ekle"/></p>
</form>


