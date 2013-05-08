<form method="post" class="cmxform" style="width:500px;" id="frmClient">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Adı Soyadı</label>
                <input type="text" name="name" value="{$client->name}"/>
            </li>
            <li>
                <label>Email</label>
                <input type="text" name="email" value="{$client->email}"/>
            </li>
            <li>
                <label>Şifre</label>
                <input type="text" name="password" value="{6|generateCode:'easy'}"/>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>
