<br/><br/>
<h3>Yeni Dosya Ekle</h3>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="catID" value="{$smarty.get.catID}">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" id="datagrid">
        <tr>
            <th width="200">Başlık</th>
            <td><input type="text" name="title" style="width: 80%;"></td>
        </tr>
        <tr>
            <th>Açıklama</th>
            <td><textarea name="description" rows="4" style="width: 80%;"></textarea></td>
        </tr>
        <tr>
            <th>Yükleme Metodu</th>
            <td><label><input type="radio" name="method" value="web" checked="checked"> Web</label> &nbsp;&nbsp;
                <label><input type="radio" name="method" value="ftp"> FTP</label></td>
        </tr>
        <tr class="hidden ftp" style="display: none;">
            <th>Dosya Adı</th>
            <td><input type="text" name="filename" style="width: 40%;"> Dosyayı FTP ile TMP klasörüne yükleyiniz</td>
        </tr>
        <tr class="hidden web">
            <th>Dosya</th>
            <td><input type="file" name="file" style="width: 40%;"> (Sunucunuzun müsaade ettiği maks dosya
                boyutu: {$max})
            </td>
        </tr>

    </table>
    <p align="right"><input type="submit" value="##Add##"></p>
</form>

{literal}
<script language="JavaScript">
$(document).ready(function () {
    $('form input:radio').click(function () {
        $('.hidden').hide();
        $('.' + $(this).val()).fadeIn();
    });
});
</script>{/literal}


