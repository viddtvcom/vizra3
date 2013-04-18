<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li>
                <label>Servis Grup Adı</label>
                <input type="text" name="group_name" value="{$g.group_name}"/>
            </li>
            <li>
                <label>Seo Link</label>
                <input type="text" name="seolink" value="{$g.seolink}"/>.html
            </li>
            <li>
                <label>Durum</label>
                <select name="status">
                    <option value="active" {if $g.status == 'active' }selected{/if}>##Active##</option>
                    <option value="inactive" {if $g.status == 'inactive' }selected{/if}>##Inactive##</option>
                </select>
            </li>
            <input type="hidden" name="parentID" value="1">
            <!--            <li>
                <label>Ana Kategori</label>
                <select name="parentID">
                    <option value="1">Genel</option>
                    {foreach from=$groups item=ag}
                        <option value="{$ag.groupID}">{$ag.group_name}</option>
                    {/foreach}
                </select>
            </li> -->
        </ol>
        <br/>

        <h3>Açıklama</h3>
        <br/>

        <p align="center"><textarea rows="20" style="width:95%" name="description">{$g.description}</textarea></p>
    </fieldset>
    <p align="right"><input type="submit" value="Güncelle"/></p>
</form>

{include file="text_editor.tpl"}


