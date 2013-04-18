<div id="inner_content">
    <form method="post" class="cmxform" style="width:700px;">
        <input type="hidden" name="action" value="list">
        <fieldset>
            <ol>
                <li>
                    <label>Sunucu</label>
                    <select name="serverID">
                        {foreach from=$servers item=s}
                            <option value="{$s.serverID}"
                                    {if $smarty.post.serverID == $s.serverID}selected{/if}>{$s.serverName}</option>
                        {/foreach}
                    </select>
                    <input type="submit" value="Hesapları listele"/>
                </li>
            </ol>
        </fieldset>
    </form>

    {if $accs}
    <form method="post">
        <input type="hidden" name="action" value="import">
        <input type="hidden" name="serverID" value="{$smarty.post.serverID}">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="datalist">
            <tr>
                <th width="20"><input type="checkbox" name="sel"
                                      onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                <th>Domain</th>
                <th>Username</th>
                <th>Plan</th>
                <th>Açılış</th>
                <th>Yeni Plan</th>
            </tr>
            {foreach from=$accs item=a}
                <tr {cycle values="class=alt,"}>
                    <td><input type="checkbox" name="selected[]" value="{$a.user}" class="mcheck"></td>
                    <td>
                        {if $a.orderID}
                            <img src="{$turl}images/warning.png" id="middle" title="Domain zaten sistemde mevcut!">
                            <a href="?p=411&tab=attrs&orderID={$a.orderID}">{$a.domain}</a>
                        {else}
                            {$a.domain}
                        {/if}
                    </td>
                    <td>{$a.user}</td>
                    <td>{$a.plan}</td>
                    <td>{$a.startdate|formatDate:date}</td>
                    <td>
                        <select name="serviceID[{$a.user}]">
                            {foreach from=$services item=s}
                                <option value="{$s.serviceID},{$s.period}">{$s.service_name} {$s.period} Aylık</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/foreach}
            <tr>
                <td colspan="10" align="center">
                    Müşteri:
                    <select name="clientID">
                        {foreach from=$clients item=c key=clientID}
                            <option value="{$clientID}">{if $c.type == 'individual'}{$c.name}{else}{$c.company}{/if}</option>
                        {/foreach}
                    </select>
                    &nbsp;&nbsp;
                    <input type="submit" value="Seçili Hesapları Import Et">
                </td>
            </tr>
        </table>

        {/if}

</div>
