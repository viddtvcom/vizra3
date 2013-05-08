<h3>Sunucu Ekle</h3>
<form method="post" class="cmxform" style="width:500px;"
      {literal}onsubmit="if ($('#serverName').val() == '') { alert('Sunucu adı boş olamaz'); return false;}"{/literal}>
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Modül</label>
                <select name="moduleID">
                    <option value="">Yok</option>
                    {foreach from=$modules item=title key=moduleID}
                        <option value="{$moduleID}" {if $Server->moduleID == $moduleID }selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label for="name">Sunucu Adı:</label>
                <input type="text" name="serverName" id="serverName"/>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Sunucuyu Ekle"/></p>
</form>

