<h3>Yeni Duyuru Ekle</h3>
<form method="post">
    <input type="hidden" name="action" value="add">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" id="datagrid">
        <tr>
            <th width="200">Başlık</th>
            <td><input type="text" name="title" style="width: 100%;"></td>
        </tr>
        <tr>
            <th>Duyuru</th>
            <td><textarea name="body" rows="20" style="width: 100%;" class="wysiwyg2"></textarea></td>
        </tr>
    </table>
    <p align="right"><input type="submit" value="##Add##"></p>
</form>


{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('.wysiwyg2').wysiwyg();
        });
    </script>
{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>