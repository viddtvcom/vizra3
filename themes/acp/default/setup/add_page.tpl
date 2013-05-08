<h3>Yeni Sayfa Ekle</h3>
<form method="post" class="cmxform" style="width:500px;">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Modül</label>
                {$select_modules}
            </li>
            <li>
                <label>Dosya Adı</label>
                <input type="text" name="filename"/>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>

