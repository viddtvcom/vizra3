<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <form method="post">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th id="center" width="80">Bilet ID</th>
                    <th width="140">Departman</th>
                    <th>Konu</th>
                    <th id="right" width="100">Durum</th>
                    <th id="right" width="140">Son Güncelleme</th>
                </tr>
                {foreach from=$tickets item=t}
                    <tr class="{cycle values="first,second"}">
                        <td><a href="?p=212&ticketID={$t.ticketID}">{$t.ticketID}</a></td>
                        <td>{$t.depTitle}</td>
                        <td>{$t.subject}</td>
                        <td id="right">##TicketDetails%{$t.status}##</td>
                        <td id="right">{$t.dateUpdated|formatDate:datetime}</td>
                    </tr>
                {/foreach}
            </table>
        </form>
    </div>
</div>