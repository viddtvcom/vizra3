<div id="inner_content">
    <form method="post" class="cmxform" style="width:700px;">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>Departman Adı</label>
                    <input type="text" name="depTitle" value="{$Dep->depTitle}"/>
                </li>
                <li>
                    <label>Departman Emaili</label>
                    <input type="text" name="depEmail" value="{$Dep->depEmail}"/>
                </li>
                <li>
                    <label>Durum</label>
                    <select name="status">
                        <option value="active" {if $Dep->status == 'active' }selected{/if}>##Active##</option>
                        <option value="inactive" {if $Dep->status == 'inactive' }selected{/if}>##Inactive##</option>
                    </select>
                </li>
                <li>
                    <label>Destek Uyarıları</label>
                    <input type="checkbox" name="notifyOnTicket" value="1" {if $Dep->notifyOnTicket == '1'}checked{/if}>
                    Yeni bilet açıldığında email ile haber ver.
                </li>
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>

</div>
