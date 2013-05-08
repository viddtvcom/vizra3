<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <form method="post">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20"></th>
                    <th width="70">Sipariş No</th>
                    <th>Domain</th>
                    <th>Müşteri</th>
                    <th>Tescil Tarihi</th>
                    <th>Bitiş Tarihi</th>
                </tr>
                {foreach from=$domains item=d}
                    <tr class="{cycle values="first,second"}">
                        <td align="center"><img src="{$turl}images/{$icons[$d.status]}" width="13"></td>
                        <td><a href="?p=311&tab=orders&orderID={$d.orderID}">{$d.orderID}</a></td>
                        <td><a href="?p=311&tab=orders&orderID={$d.orderID}&subtab=domain">{$d.domain}</a></td>
                        <td><a href="?p=311&clientID={$d.clientID}">{$d.name}</td>
                        <td>{format_date date=$d.dateReg mode=datetime}</td>
                        <td>{format_date date=$d.dateExp}</td>
                    </tr>
                {/foreach}
            </table>
        </form>
    </div>
</div>
{include file="paging.tpl"}  
