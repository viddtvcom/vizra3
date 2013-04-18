<h2 class="title700">##TopMenu%Support##</h2>
<div class="content_right">
    {include file="user/support_submenu.tpl"}
    <table cellpadding="0" cellspacing="0" class="main_table">
        <thead>
        <tr>
            <td colspan="5"><h3>##Support%TicketsInProgress##</h3></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th width="100">##Support%ticketID##</th>
            <th width="120" id="center">##Status##</th>
            <th>##Support%TicketSubject##</th>
            <th width="150" id="right">##Support%LastReplier##</th>
            <th width="150" id="right">##Support%LastUpdateTime##</th>
        </tr>
        {foreach from=$ticketsInProgress item=t}
            <tr class="{cycle values=',alt'}">
                <td><a href="{$vurl}?p=user&s=support&a=viewTicket&tID={$t.ticketID}">{$t.ticketID}</a></td>
                <td id="center">##TicketDetails%{$t.status}##</td>
                <td>{$t.subject}</td>
                <td id="right">{if $t.adminName}{$t.adminName}{else}{$Client->name}{/if}</td>
                <td id="right">{$t.dateUpdated|formatDate:datetime:short}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    {if $closedTickets}
        <table cellpadding="0" cellspacing="0" class="main_table">
            <thead>
            <tr>
                <td colspan="5"><h3>##Support%ClosedTickets##</h3></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th width="100">##Support%ticketID##</th>
                <th>##Support%TicketSubject##</th>
                <th width="150" id="right">##Support%LastReplier##</th>
                <th width="150" id="right">##Support%LastUpdateTime##</th>
            </tr>
            {foreach from=$closedTickets item=t}
                <tr class="{cycle values=',alt'}">
                    <td><a href="{$vurl}?p=user&s=support&a=viewTicket&tID={$t.ticketID}">{$t.ticketID}</a></td>
                    <td>{$t.subject}</td>
                    <td id="right">{if $t.adminName}{$t.adminName}{else}{$Client->name}{/if}</td>
                    <td id="right">{$t.dateUpdated|formatDate:datetime:short}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}

</div>
