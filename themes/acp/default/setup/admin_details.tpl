{if $tab == 'gen' || $tab == ''}
    <form method="post" class="cmxform" style="width:500px;" autocomplete="off">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>Hesap Türü</label>
                    <select name="type">
                        <option value="admin" {if $eAdmin->type == 'admin' }selected{/if}>Admin</option>
                        <option value="super-admin" {if $eAdmin->type == 'super-admin' }selected{/if}>Süper Admin
                        </option>
                    </select>
                </li>
                <li>
                    <label>Durum</label>
                    <select name="status">
                        <option value="active" {if $eAdmin->status == 'active' }selected{/if}>##Active##</option>
                        <option value="inactive" {if $eAdmin->status == 'inactive' }selected{/if}>##Inactive##</option>
                    </select>
                </li>
                <li>
                    <label>Admin Adı</label>
                    <input type="text" name="adminName" value="{$eAdmin->adminName}"/>
                </li>
                <li>
                    <label>Email</label>
                    <input type="text" name="adminEmail" value="{$eAdmin->adminEmail}"/>
                </li>
                <li>
                    <label>MSN Adresi</label>
                    <input type="text" name="adminMsn" value="{$eAdmin->adminMsn}"/>
                </li>
                <li>
                    <label>Şifre</label>
                    <input type="text" name="adminPassword"/>
                </li>
                <li>
                    <label>Nick</label>
                    <input type="text" name="adminNick" value="{$eAdmin->adminNick}"/>
                </li>
                <li>
                    <label>Ünvan</label>
                    <input type="text" name="adminTitle" value="{$eAdmin->adminTitle}"/>
                </li>
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>
{elseif $tab == 'deps'}
    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="update_deps">
        <fieldset>
            {foreach from=$deps item=d key=depID }
                <ul>
                    <li><input type="checkbox" name="deps[]" value="{$depID}"
                               {if in_array($depID,$eAdmin->deps)}checked{/if}> {$d.depTitle}</li>
                </ul>
            {/foreach}
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>
{elseif $tab == 'privs'}
    <ul class="system_messages">
        <li class="yellow"><span class="ico"></span><strong class="system_title">
                Bu bölümdeki yetkilendirmeler bir sonraki versiyonda tam olarak çalışmaya başlayacaktır.
                Şu an için sadece Sayfayı Görme yetkisi çalışmaktadır.Lütfen bu bölüm ile bilgili hata bildiriminde
                bulunmayınız
            </strong>
        </li>
    </ul>
    <form method="post" class="cmxform">
        {foreach from=$pages item=mod key=k}
            <table width="100%" border="0" id="datalist" cellpadding="0" cellspacing="0">
                <tr>
                    <th>##mod_{$k}##</th>
                    {foreach from=$vars.ADMIN_ACTIONS item=action}
                        <th width="70">{$action}</th>
                    {/foreach}
                </tr>
                {foreach from=$mod item=m}
                    <tr  {cycle values=',class=alt'}>
                        <td height="35">&nbsp;&nbsp;##page_{$m.pageID}##</td>
                        {foreach from=$vars.ADMIN_ACTIONS item=action key=bit}
                            <td>
                                {if in_array($bit,$m.actions)}
                                    <span></span>
                                    <input class="dynacheck" type="checkbox"
                                           id="{$m.pageID}{$bit}"  {readbit priv=$privs[$m.pageID].priv bit=$bit}>
                                {/if}
                            </td>
                        {/foreach}
                    </tr>
                {/foreach}
            </table>
            <br/>
            <br/>
        {/foreach}
    </form>
    <script language="JavaScript">
        var adminID = {$eAdmin->adminID};
        {literal}$(document).ready(function () {
            $(".dynacheck").click(function () {
                var checked = $(this).is(':checked');
                $(this).hide();
                $(this).prev().html('<img src="' + turl + 'images/loading.gif">');

                $.post("index.php?p=111&adminID=" + adminID, {action: 'setPriv', id: $(this).attr('id'), setting: $(this).attr('name'), value: checked, pageID: $(this).attr('pageID'), bit: $(this).attr('bit')   },
                        function (data) {
                            if (!data.st) {
                                $("#" + data.id).attr('checked', !checked);
                            }
                            $("#" + data.id).prev().html('');
                            $("#" + data.id).show();
                        }, "json");

            });
        });
    </script>
{/literal}
{/if}
