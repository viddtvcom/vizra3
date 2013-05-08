{if $addonServices}
    <div class="addon_select" style="text-align: right; padding: 3px 0; display: none;">
        <select id='addonSelect'>
            {foreach from=$addonServices item=a}
                <option value="?p=cart&s=srvconf&a=addon&sID={$a.serviceID}&oID={$Order->orderID}">{$a.service_name}</option>
            {/foreach}
        </select>
        <input type="submit" value="Ekle" id='addonSubmit'>
    </div>
{/if}

{if $Order->addonOrders}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <th id="center" width="20"></th>
                    <th>Servis</th>
                    <th width="130" id="right">Eklenme Tarihi</th>
                    <th width="100" id="right">Miktar</th>
                </tr>
                {foreach from=$Order->addonOrders item=ao}
                    <tr {cycle values=",class='alt'"} style="cursor: pointer;"
                                                      onclick="$('[id^=addon]').hide(); $('.addon_form').remove(); $('#addon_'+{$ao.orderID}).show().children(':first').load('?p=415&m=compact&orderID={$ao.orderID}');">
                        <td><img src="{$turl}images/{$icons[$ao.status]}" width="13"></td>
                        <td>{$ao.title}</td>
                        <td id="right">{format_date date=$ao.dateAdded mode='datetime'}</td>
                        <td id="right">{$ao.price} {getCurrencyById id=$ao.paycurID}</td>
                    </tr>
                    <tr style="display:none" id="addon_{$ao.orderID}">
                        <td colspan="4"></td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
{/if}