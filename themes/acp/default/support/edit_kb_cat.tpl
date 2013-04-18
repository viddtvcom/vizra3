<br/><br/>
<h3>Kategori Düzenleme</h3>
<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li>
                <label>Kategori Adı</label>
                <input type="text" name="title" value="{$cat.title}"/>
            </li>
            <li>
                <label>Ana Kategori</label>
                <select name="parentID">
                    {foreach from=$cat_list item=c}
                        {if $c.catID != $cat.catID}
                            <option value="{$c.catID}" {if $c.catID == $cat.parentID}selected{/if}>
                                {$c.title}
                            </option>
                        {/if}
                    {/foreach}
                </select>
            </li>
            <li>
                <label>Açıklama</label>
                <input type="text" name="description" value="{$cat.description}"/>
            </li>
            <li>
                <label>Görünebilirlik</label>
                <select name="visibility">
                    {if $parent.visibility == 'everyone'}
                        <option value="everyone" {if $cat.visibility == 'everyone'}selected{/if}>Herkes Görebilir
                        </option>
                    {/if}
                    {if $parent.visibility == 'client' || $parent.visibility == 'everyone'}
                        <option value="client" {if $cat.visibility == 'client'}selected{/if}>Sadece Kayıtlı
                            Kullanıcılar
                        </option>
                    {/if}
                    <option value="admin" {if $cat.visibility == 'admin'}selected{/if}>Sadece Yöneticiler</option>
                </select>
            </li>

        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="##Update##"/></p>
</form>


