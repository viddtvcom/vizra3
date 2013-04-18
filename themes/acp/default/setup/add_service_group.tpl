<h3>Servis Grubu Ekle</h3>
<form method="post" class="cmxform" style="width:500px;">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Servis Grup Adı</label>
                <input type="text" name="group_name"/>
            </li>
            <li>
                <label>Durum</label>
                <select name="status">
                    <option value="active">##Active##</option>
                    <option value="inactive">##Inactive##</option>
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
    </fieldset>
    <p align="right"><input type="submit" value="##Add##"/></p>
</form>


