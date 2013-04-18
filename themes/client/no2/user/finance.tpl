<h2 class="title2">##TopMenu%Finance##</h2>
<div class="article clear">

    <ul class="s_tabb br_5 clear">
        <li><a {if $tab == 'bills' || $tab == ''}class="selected"{/if} href="{$vurl}?p=user&s=finance&tab=bills">##Bills##</a>
        </li>
        <li><a {if $tab == 'payments'}class="selected"{/if}
               href="{$vurl}?p=user&s=finance&tab=payments">##Payments##</a></li>
        <li><a {if $tab == 'accflow'}class="selected"{/if}
               href="{$vurl}?p=user&s=finance&tab=accflow">##AccountFlow##</a></li>
        <li><a href="{$vurl}?p=payment&a=addFunds">##AddFunds##</a></li>
    </ul>

    {if $tab == 'bills' || $tab == ''}
        {include file="paging.tpl"}
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main_table">
            <tr>
                <th width="20"></th>
                <th>##PackageName##
                <th id="right">##DueDate##</th>
                <th width="80" id="right">##Amount##</th>
            </tr>
            {foreach from=$bills item=b}
                <tr {cycle values=",class='alt'"}>
                    <td><img src="images/ico_{if $b.status == "paid"}active{else}suspended{/if}.png" width="13"></td>
                    <td>
                        {if $b.orderID}
                            <a href="{$vurl}?p=user&s=orders&a=details&oID={$b.orderID}">{$b.title}</a>
                        {else}{$b.description}{/if}
                    </td>
                    <td id="right">{format_date date=$b.dateDue}</td>
                    <td id="right">{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                </tr>
            {/foreach}
        </table>
    {elseif $tab == 'payments'}
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main_table">
            <tr>
                <th width="20"></th>
                <th width="70" id="center">##Payment## No</th>
                <th>##Description##</th>
                <th width="200">##Status##</th>
                <th width="80">##DateCreated##</th>
                <th width="50" id="right">##Amount##</th>
            </tr>
            {foreach from=$payments item=p}
                <tr {cycle values=",class='alt'"}>
                    <td><img src="images/ico_{if $p.paymentStatus == "paid"}active{else}pending{/if}.png" width="13">
                    </td>
                    <td id="center">{$p.paymentID}</td>
                    <td>{$module_titles[$p.moduleID]} {$p.description}</td>
                    <td>
                        {if $p.paymentStatus == "paid"}##Approved##
                        {elseif $p.paymentStatus == "pending-payment"}
                            ##PendingPayment## (
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
                                ##PleasePostDetailsAboutYourPayment##<br/>

                                <form method="post">
                                    <input type="hidden" name="action" value="declare">
                                    <input type="hidden" name="paymentID" value="{$p.paymentID}">
                                    ##Description##: <input type="text" name="description" style="width:300px;"
                                                            class="tinput">
                                    <input type="submit" value="##Save##" class="button">
                                </form>
                            </div>
                        </td>
                    </tr>
                {/if}
            {/foreach}
        </table>
    {elseif $tab == 'accflow'}
        {if $chart}
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main_table">
                <tr>
                    <th width="90">##Date##</th>
                    <th width="50">##Operation##</th>
                    <th>##Description##</th>
                    <th width="80" id="right">##Amount##</th>
                    <th width="80" id="right">##Balance##</th>
                </tr>
                {foreach from=$chart item=c}
                    <tr {cycle values=",class='alt'"}>
                        <td>{format_date date=$c.timestamp mode=date type=short}</td>
                        <td class="bold {if $c.type == "bill"}red{else}green{/if}"
                            width="13">{if $c.type == "bill"}##Bill##{else}##Payment##{/if}</td>
                        <td>{$c.description}</td>
                        <td id="right"
                            class="bold {if $c.type == "bill"}red{else}green{/if}">{$c.amount} {getCurrencyById id=$c.paycurID}</td>
                        <td id="right"
                            class="bold {if $c.balance < 0}red{else}green{/if}">{$c.balance|number_format:2} {getCurrencyById id=$smarty.const.MAIN_CUR_ID}</td>
                    </tr>
                {/foreach}
                <tr class='alt2'>
                    <td id="right" class="bold" colspan="4">Bakiye:</td>
                    <td id="right"
                        class="bold {if $c.balance < 0}redx{else}greenx{/if}">{$c.balance|number_format:2} {getCurrencyById id=$smarty.const.MAIN_CUR_ID}</td>
                </tr>
            </table>
        {else}##NoFinancialHistory##{/if}
    {/if}
</div>