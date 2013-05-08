<div id="inner_content">
    <form method="post" class="cmxform" style="width:700px;">
        <input type="hidden" name="action" value="list">
        <fieldset>
            <ol>
                <li>
                    <label>&nbsp;</label>
                    {if $pages}
                        Sayfa :
                        <select name="pg">
                            {section name=i start=1 loop=$pages+1}
                                <option value="{$smarty.section.i.index}"
                                        {if $smarty.post.pg == $smarty.section.i.index}selected{/if}>{$smarty.section.i.index}</option>
                            {/section}
                        </select>
                    {/if}
                    <input type="submit" value="Domainleri listele"/>
                </li>
            </ol>
        </fieldset>
    </form>

    {if $domains}
    <form method="post">
        <input type="hidden" name="action" value="import">
        <input type="hidden" name="pg" value="{$pg}">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="datalist">
            <tr>
                <th width="20"><input type="checkbox" name="sel"
                                      onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                <th>Domain</th>
                <th id="right">Başlangıç</th>
                <th id="right">Bitiş</th>
            </tr>
            {foreach from=$domains item=d key=domain}
                <tr {cycle values="class=alt,"}>
                    <td><input type="checkbox" name="selected[]" value="{$domain}" class="mcheck"></td>
                    <td>
                        {if $d.orderID}
                            <img src="{$turl}images/warning.png" id="middle" title="Domain zaten sistemde mevcut!">
                            <a href="?p=311&tab=orders&subtab=domain&orderID={$d.orderID}">{$domain}</a>
                        {else}
                            {$domain}
                        {/if}
                    </td>
                    <td id="right">{$d.dateReg|formatDate:date}</td>
                    <td id="right">{$d.dateExp|formatDate:date}</td>

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
                    {if $method == 'seperate'}
                        <div class="msg_warn">
                            DİKKAT! Seçtiğiniz alan adları,Eğer değilse DirectI panelinde de bu müşterinin hesabına
                            taşınacaktır.<br/>
                            (DirectI'da müşteri kaydı bulunmuyorsa otomatik olarak oluşturulur)
                        </div>
                    {/if}
                    &nbsp;&nbsp;<br/><br/>
                    <input type="submit" value="Seçili Hesapları Import Et">
                </td>
            </tr>
        </table>

        {/if}

</div>
