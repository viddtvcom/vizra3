<div id="inner_content">
    <h2>Admin Detayları</h2>

    <form method="post" class="cmxform" style="width:500px;">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>Hesap Türü</label>
                    <select name="type">
                        <option value="admin" {if $Admin->type == 'admin' }selected{/if}>Admin</option>
                        <option value="super-admin" {if $Admin->type == 'super-admin' }selected{/if}>Süper Admin
                        </option>
                    </select>
                </li>
                <li>
                    <label>Durum</label>
                    <select name="status">
                        <option value="active" {if $Admin->status == 'active' }selected{/if}>##Active##</option>
                        <option value="inactive" {if $Admin->status == 'inactive' }selected{/if}>##Inactive##</option>
                    </select>
                </li>
                <li>
                    <label>Admin Adı</label>
                    <input type="text" name="adminName" value="{$Admin->adminName}"/>
                </li>
                <li>
                    <label>Email</label>
                    <input type="text" name="adminEmail" value="{$Admin->adminEmail}"/>
                </li>
                <li>
                    <label>Şifre</label>
                    <input type="text" name="adminPassword"/>
                </li>
                <li>
                    <label>Nick</label>
                    <input type="text" name="adminNick" value="{$Admin->adminNick}"/>
                </li>
                <li>
                    <label>Ünvan</label>
                    <input type="text" name="adminTitle" value="{$Admin->adminTitle}"/>
                </li>
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>

    <h2>Departmanlar</h2>

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="update_deps">
        <fieldset>
            {foreach from=$deps item=d key=depID }
                <ul>
                    <li><input type="checkbox" name="deps[]" value="{$depID}"
                               {if in_array($depID,$Admin->deps)}checked{/if}> {$d.depTitle}</li>
                </ul>
            {/foreach}
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>



    {if $Admin->type != 'super-admin'}
        <h2>Sayfa Yetkileri</h2>
        <form method="post" class="cmxform">
            <input type="hidden" name="action" value="update_page_privs">
            <fieldset>
                {foreach from=$pages item=mod key=k}
                    <strong>##mod_{$k}##</strong>
                    <ul>
                        {foreach from=$mod item=m}
                            <li><input type="checkbox" name="priv[{$k}][{$m.bit}]"
                                       value="1" {readbit priv=$privs.$k.priv bit=$m.bit }> ##page_{$m.pageID}##
                            </li>
                        {/foreach}
                    </ul>
                    <br>
                    <br>
                {/foreach}
            </fieldset>
            <p align="right"><input type="submit" value="Güncelle"/></p>
        </form>
    {/if}

</div> 
