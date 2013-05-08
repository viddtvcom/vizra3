<h3>Sunucu Prob Ekle</h3>
<p class="msg_ok">
    Eklediğiniz probların sağ sütunda görünebilmesi için isim olarak http, mysql, ftp, pop, smtp verebilirsiniz.
</p>
<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="add">
    <fieldset>
        <ol>
            <li>
                <label>Sunucu:</label>
                <select name="serverID">
                    {foreach from=$servers item=s}
                        <option value="{$s.serverID}">{$s.serverName}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label>İsim</label>
                <input type="text" name="title"/>
            </li>
            <li>
                <label>Port</label>
                <input type="text" name="port"/>
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ekle"/></p>
</form>

