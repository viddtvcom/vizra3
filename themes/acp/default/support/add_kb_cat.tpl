<br/><br/>
<h3>Yeni Kategori Ekle</h3>
<form method="post" class="cmxform" style="width:500px;">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="parentID" value="{$smarty.get.catID}">
    <fieldset>
        <ol>
            <li>
                <label>Kategori Adı</label>
                <input type="text" name="title"/>
            </li>
            <li>
                <label>Açıklama</label>
                <input type="text" name="description"/>
            </li>
            <li>
                <label>Görünebilirlik</label>
                <select name="visibility">
                    {if $parent.visibility == 'everyone'}
                        <option value="everyone">Herkes Görebilir</option>
                    {/if}
                    {if $parent.visibility == 'client' || $parent.visibility == 'everyone'}
                        <option value="client">Sadece Kayıtlı Kullanıcılar</option>
                    {/if}
                    <option value="admin">Sadece Yöneticiler</option>
                </select>
            </li>

        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="##Add##"/></p>
</form>
