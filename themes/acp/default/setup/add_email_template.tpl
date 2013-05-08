<br/><br/>
<form method="post" class="cmxform" style="width:600px;">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Şablon İsmi</label>
                <input type="text" name="title"/>
            </li>
            <li>
                <label>Kategori</label>
                <select name="type">
                    {foreach from=$types item=type}
                        <option value="{$type}" {if $type == 'welcome'}selected{/if}>##SetupEmailTemplates%{$type}##
                        </option>
                    {/foreach}
                </select>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>

