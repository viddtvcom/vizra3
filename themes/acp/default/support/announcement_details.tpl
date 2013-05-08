<h3>{$rec.title}</h3>
<form method="post">
    <input type="hidden" name="action" value="update">
    <table width="99%" border="0" cellspacing="0" cellpadding="4" id="datagrid">
        <tr>
            <th>Eklenme Tarihi</th>
            <td>{$rec.dateAdded|formatDate:datetime}</td>
        </tr>
        <tr>
            <th width=200>Durum</th>
            <td>
                <select name="status">
                    <option value="active" {if $rec.status == 'active'}selected{/if}>Aktif - Herkes Görebilir</option>
                    <option value="clients-only" {if $rec.status == 'clients-only'}selected{/if}>Sadece Kayıtlı
                        Kullanıcılar
                    </option>
                    <option value="inactive" {if $rec.status == 'inactive'}selected{/if}>##Inactive##</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Başlık</th>
            <td><input type="text" name="title" style="width: 100%;" value="{$rec.title}"></td>
        </tr>
        <tr>
            <th>Duyuru</th>
            <td><textarea name="body" rows="20" style="width: 100%;" class="wysiwyg2">{$rec.body}</textarea></td>
        </tr>
    </table>
    <p align="right"><input type="submit" value="##Update##"></p>
</form>


{literal}
<script language="JavaScript">
$(document).ready(function () {
    $('.wysiwyg2').wysiwyg();
});
</script>{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>