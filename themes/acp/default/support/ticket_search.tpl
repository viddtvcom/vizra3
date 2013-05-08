<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>

<div id="filterbox">
    <form method="post" class="cmxform" id="filterform">
        <fieldset style="width: 40%; float: left;">
            <ol class='alt'>
                <li><label>Bilet ID:</label> <input type="text" name="ticketID" class="filter" style="width: 80px;"
                                                    maxlength="9"></li>
                <li><label>Konu</label> <input type="text" name="subject" class="filter" style="width: 150px;"
                                               value="{$smarty.get.subject}"></li>
            </ol>
        </fieldset>
        <fieldset style="width: 50%; float: left;">
            <ol class='alt'>
                <li><label>Durum:</label>
                    <select name="status" class="filter">
                        <option value="all">Bütün Biletler</option>
                        {foreach from=$vars.TICKET_STATUS_TYPES item=ost}
                            <option value="{$ost}" {if $smarty.get.status == $ost}selected{/if}>
                                ##TicketDetails%{$ost}##
                            </option>
                        {/foreach}
                    </select>
                </li>
                <li><label>Cevap</label> <input type="text" name="response" class="filter" style="width: 150px;"
                                                value="{$smarty.get.response}"></li>
            </ol>
        </fieldset>
        <div style="width: 10%; float: left; text-align: center; padding-top: 20px;">
            <input type="image" src="{$turl}images/search48.png" id="butfilter">
        </div>
    </form>
    <div class="clear"></div>
</div>

{include file="paging.tpl"}

<div class="module_top"><h5>Arama Sonuçları </h5></div>
<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th id="center" width="80">Bilet ID</th>
                <th width="140">Departman</th>
                <th width="140">Müşteri</th>
                <th>Konu</th>
                <th id="right" width="120">Durum</th>
                <th id="right" width="120">Son Güncelleme</th>
            </tr>
            {foreach from=$tickets item=t}
                <tr {cycle values='class=alt,'}>
                    <td><a href="?p=212&ticketID={$t.ticketID}">{$t.ticketID}</a></td>
                    <td>{$t.depTitle}</td>
                    <td>{$t.clientName}</td>
                    <td>{$t.subject}</td>
                    <td id="right">##TicketDetails%{$t.status}##</td>
                    <td id="right">{$t.dateUpdated|formatDate:datetime}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>

