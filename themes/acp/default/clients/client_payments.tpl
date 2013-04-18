<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <form method="post">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20"></th>
                    <th width="70" id="center">Ödeme No</th>
                    <th>Modül</th>
                    <th id="right" width="100">Miktar</th>
                    <th id="right" width="160">Eklenme Tarihi</th>
                    <th id="right" width="160">Onay Tarihi</th>
                </tr>
                {foreach from=$payments item=p}
                    <tr class="{cycle values="first,second"}">
                        <td><img src="{$turl}images/led_{if $p.paymentStatus == 'paid'}green{else}yellow{/if}.png"
                                 width="13"></td>
                        <td id="center"><a href="?p=511&paymentID={$p.paymentID}">{$p.paymentID}</a></td>
                        <td>{$modules[$p.moduleID]} {if $p.description}{$p.description}{/if}</td>
                        <td id='right'>{$p.amount} {getCurrencyById id=$p.paycurID}</td>
                        <td id='right'>{format_date date=$p.dateAdded mode=datetime}</td>
                        <td id='right'>{format_date date=$p.datePayed mode=datetime}</td>
                    </tr>
                {/foreach}
            </table>
        </form>
    </div>
</div>
{include file="paging.tpl"} 
