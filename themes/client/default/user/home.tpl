<h2 class="title700">##Dashboard%Title##</h2>
<div class="content_right">

    {if $payments}
        <table cellpadding="0" cellspacing="0" class="main_table">
            <thead>
            <tr>
                <td colspan="5"><h3>##Dashboard%PendingPayments##</h3></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th width="20"></th>
                <th width="70" id="center">##Payment## No</th>
                <th>##Status##</th>
                <th width="95">##DateCreated##</th>
                <th width="50" id="right">##Amount##</th>
            </tr>
            {foreach from=$payments item=p}
                <tr {cycle values=",class='alt'"}>
                    <td><img src="images/ico_{if $p.paymentStatus == "paid"}active{else}pending{/if}.png" width="13">
                    </td>
                    <td id="center">{$p.paymentID}</td>
                    <td>
                        {if $p.paymentStatus == "paid"}##Approved##
                        {elseif $p.paymentStatus == "pending-payment"}
                            ##PendingPayment## (
                            <img src="images/074.png">
                            <a href="javascript:void(0);" onclick="$('#tr_{$p.paymentID}').show();">##Finance%ClickHereIfPaid##</a>
                            )
                        {elseif $p.paymentStatus == "pending-approval"}
                            ##PendingApproval##
                        {/if}
                    </td>
                    <td>{format_date date=$p.dateAdded}</td>
                    <td id="right">{$p.amount} {getCurrencyById id=$p.paycurID}</td>
                </tr>
                {if $p.paymentStatus == 'pending-payment'}
                    <tr id="tr_{$p.paymentID}" style="display:none;">
                        <td colspan="6" align="center">
                            <div align="center" style="padding: 5px;" class="msg_warn">
                                ##PleasePostDetailsAboutYourPayment##
                                <br/>

                                <form method="post" action="{$vurl}?p=user&s=finance">
                                    <input type="hidden" name="action" value="declare">
                                    <input type="hidden" name="paymentID" value="{$p.paymentID}">
                                    ##Description##: <input type="text" name="description" style="width:300px;">
                                    <input type="submit" value="##Save##" class="button">
                                </form>
                            </div>
                        </td>
                    </tr>
                {/if}
            {/foreach}
            </tbody>
        </table>
        <br/>
    {/if}

    <table cellpadding="0" cellspacing="0" class="main_table">
        <thead>
        <tr>
            <td colspan="5"><h3>##Dashboard%LastTickets##</h3></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th width="100">##Support%ticketID##</th>
            <th>##Support%TicketSubject##</th>
            <th width="150" id="right">##Support%LastReplier##</th>
            <th width="150" id="right">##Support%LastUpdateTime##</th>
        </tr>
        {foreach from=$ticketsInProgress item=t}
            <tr class="{cycle values=',alt'}">
                <td><a href="{$vurl}?p=user&s=support&a=viewTicket&tID={$t.ticketID}">{$t.ticketID}</a></td>
                <td>{$t.subject}</td>
                <td id="right">{if $t.adminName}{$t.adminName}{else}{$Client->name}{/if}</td>
                <td id="right">{format_date date=$t.dateUpdated mode="datetime"}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <br/>

    <form method="post" action="{$vurl}?p=user&s=renew">
        <input type="hidden" name="return" value="?p=user">
        <table cellpadding="0" cellspacing="0" class="main_table">
            <thead>
            <tr>
                <td colspan="5"><h3>##Dashboard%ExpiringDomains##</h3></td>
            </tr>
            </thead>
            <tbody>
            {if !$domains}
                <tr>
                    <td colspan="4">30 gün içinde süresi dolacak alan adınız bulunmamaktadır</td>
                </tr>
            {else}
                <tr>
                    <th width="20"><input type="checkbox" name="sel"
                                          onclick="$('.dmcheck').attr('checked',$(this).attr('checked'))"></th>
                    <th>##Domain##</th>
                    <th>##EndDate##</th>
                    <th></th>
                </tr>
                {foreach from=$domains item=d}
                    <tr {cycle values=",class='alt'"}>
                        <td><input type="checkbox" name="selected[]" class="dmcheck" value="{$d.orderID}"></td>
                        <td>{$d.domain}</td>
                        <td>{$d.dateExp|formatDate:date}</td>
                        <td></td>
                    </tr>
                {/foreach}
                <tr>
                    <td colspan="4"><p align="left"><label>##WithSelected## : <input type="submit" value="##Renew##"
                                                                                     class="button"></label></p></td>
                </tr>
            {/if}
            </tbody>
        </table>
        <br/></form>

    <form method="post" action="{$vurl}?p=user&s=renew">
        <input type="hidden" name="return" value="?p=user">
        <table cellpadding="0" cellspacing="0" class="main_table">
            <thead>
            <tr>
                <td colspan="5"><h3>##Dashboard%ExpiringOrders##</h3></td>
            </tr>
            </thead>
            <tbody>
            {if !$orders}
                <tr>
                    <td colspan="4">30 gün içinde süresi siparişiniz bulunmamaktadır</td>
                </tr>
            {else}
                <tr>
                    <th width="20"><input type="checkbox" name="sel"
                                          onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                    <th width="20"></th>
                    <th>##PackageName##</th>
                    <th id="right">##EndDate##</th>
                </tr>
                {foreach from=$orders item=o}
                    <tr {cycle values=",class='alt'"}>
                        <td>{if $o.status == 'active' || $o.status == 'suspended'}<input type="checkbox"
                                                                                         name="selected[]"
                                                                                         class="mcheck"
                                                                                         value="{$o.orderID}">{/if}</td>
                        <td><img src="images/{$icons[$o.status]}" width="13"></td>
                        <td><a href="{$vurl}?p=user&s=orders&a=details&oID={$o.orderID}">{$o.title}</a></td>
                        <td id="right">{format_date date=$o.dateEnd}</td>
                    </tr>
                {/foreach}
                <tr>
                    <td colspan="4"><p align="left"><label>##WithSelected## : <input type="submit" value="##Renew##"
                                                                                     class="button"></label></p></td>
                </tr>
            {/if}
            </tbody>
        </table>
        <br/>
    </form>

    <table cellpadding="0" cellspacing="0" class="main_table">
        <thead>
        <tr>
            <td colspan="5"><h3>##Dashboard%UnpaidBills##</h3></td>
        </tr>
        </thead>
        <tbody>
        {if !$bills}
            <tr>
                <td colspan="4">Ödenmemiş borcunuz bulunmamaktadır. Teşekkür ederiz.</td>
            </tr>
        {else}
            <tr>
                <th width="20"></th>
                <th>##PackageName##</th>
                <th id="right">##DueDate##</th>
                <th width="80" id="right">##Amount##</th>
            </tr>
            {foreach from=$bills item=b}
                <tr {cycle values=",class='alt'"}>
                    <td><img src="images/ico_{if $b.status == "paid"}active{else}suspended{/if}.png" width="13"></td>
                    <td><a href="{$vurl}?p=user&s=orders&a=details&oID={$b.orderID}">{$b.title}</a></td>
                    <td id="right">{format_date date=$b.dateDue}</td>
                    <td id="right">{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                </tr>
            {/foreach}
        {/if}
        </tbody>
    </table>
    <br/>
</div>

