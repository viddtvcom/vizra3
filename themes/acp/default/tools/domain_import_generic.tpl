<ul class="system_messages">
    <li class="blue"><span class="ico"></span>
        <strong class="system_title">
            - Domainlerinizi Vizra'ya import etmek için bu aracı kullanabilirsiniz. Aşağıdaki alana import etmek
            istediğiniz domainleri altalta olacak şekilde girin ve Domainleri Liste'ye tıklayın.<br/>
            - Daha sonra oluşturulan listede, domainin bağlı olduğu modülü ve siparişin oluşmasını istediğiniz
            müşterinizi seçin ve Import işlemini gerçekleştirin.<br/>
            - Eğer domain modülünüzün ayarları doğru olarak yapıldı ise, Vizra, domain hesabınıza bağlanarak, domainin
            başlangıç ve bitiş tarihlerini, DNS bilgilerini otomatik olarak almaya çalışacaktır.
        </strong>
    </li>
</ul>
<div id="inner_content">
    <form method="post" class="cmxform" style="width:700px;">
        <input type="hidden" name="action" value="list">
        <fieldset>
            <ol>
                <li>
                    <textarea name="domains" rows="10" cols="50"></textarea>
                </li>
            </ol>
        </fieldset>
        <p align="center"><input type="submit" value="Domainleri listele"/></p>
    </form>

    {if $domains}
    <form method="post">
        <input type="hidden" name="action" value="import">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="datalist">
            <tr>
                <th width="20"><input type="checkbox" name="sel"
                                      onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                <th>Domain</th>
                <th id="right">Modül</th>
                <th id="right">Müşteri</th>
            </tr>
            {foreach from=$domains key=domain item=orderID }
                <tr {cycle values="class=alt,"}>
                    <td><input type="checkbox" name="selected[]" value="{$domain}" class="mcheck"></td>
                    <td>
                        {if $orderID}
                            <img src="{$turl}images/warning.png" id="middle" title="Domain zaten sistemde mevcut!">
                            <a href="?p=311&tab=orders&subtab=domain&orderID={$orderID}">{$domain}</a>
                        {else}
                            {$domain}
                        {/if}
                    </td>
                    <td id="right">
                        <select name="moduleID[{$domain}]">
                            {foreach from=$modules item=module key=moduleID}
                                <option value="{$moduleID}">{$module}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td id="right">
                        <select name="clientID[{$domain}]">
                            {foreach from=$clients item=c key=clientID}
                                <option value="{$clientID}">{if $c.type == 'individual'}{$c.name}{else}{$c.company}{/if}</option>
                            {/foreach}
                        </select>
                    </td>

                </tr>
            {/foreach}
            <tr>
                <td colspan="10" align="center">
                    &nbsp;&nbsp;<br/><br/>
                    <input type="submit" value="Seçili Hesapları Import Et">
                </td>
            </tr>
        </table>

        {/if}

</div>
