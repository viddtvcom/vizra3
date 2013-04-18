<br/><br/>
<h3>Makale Düzenleme</h3>
<form method="post"
      {literal}onsubmit="if ($('#title').val() == '') { alert('Başlık boş olamaz'); return false;}"{/literal}>
    <input type="hidden" name="action" value="update">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" id="datagrid">
        <tr>
            <th width="200">Başlık</th>
            <td><input type="text" name="title" style="width: 100%;" value="{$entry->title|formdisplay}" id="title">
            </td>
        </tr>
        <tr>
            <th width="200">Kategori</th>
            <td>
                <select name="catID">
                    {foreach from=$cat_list item=c}
                        <option value="{$c.catID}" {if $c.catID == $entry->catID}selected{/if}>
                            {$c.title}
                        </option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th>Makale</th>
            <td><textarea name="body" rows="20" style="width: 100%;">{$entry->body}</textarea></td>
        </tr>
    </table>
    <p align="right"><input type="submit" value="##Add##"></p>
</form>
