<h2 class="title2"><img src="{$turl}images/{$Order->getStatusIcon()}" class="middle" width="13"> {$Order->title}</h2>

<div class="article clear">

    <ul class="s_details br_t_5">
        <li class="left tleft">
            <span>##OrderDetails%ORDERNUMBER## #{$Order->orderID} / ##OrderDetails%ORDERSTATUS##:</span> <span
                    class="durum">##OrderDetails%{$Order->status}##</span><br/>
            <span></span>
        </li>

        <li class="right tright">
            ##OrderDetails%ORDERENDS##<br/>
            ( <a href="{$vurl}?p=user&s=renew&oID={$Order->orderID}">##OrderDetails%RenewOrder##</a> )
            <b>{format_date date=$Order->dateEnd}</b>
        </li>
    </ul>

    <ul class="s_tabb  br_b_5 clear" style="margin-top:0;">
        <li><a {if $tab == 'details' || $tab == ''}class="selected"{/if}
               href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=details">##Overview##</a></li>
        {if $Order->Service->groupID != 10}
            <li><a {if $tab == 'attrs'}class="selected"{/if}
                   href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=attrs">##OrderDetails%Attributes##</a>
            </li>
            <li><a {if $tab == 'addons'}class="selected"{/if}
                   href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=addons">##Addons##</a></li>
        {else}
            <li><a {if $tab == 'domain'}class="selected"{/if}
                   href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=domain">##DomainManagement##</a>
            </li>
        {/if}
        <li><a {if $tab == 'bills'}class="selected"{/if}
               href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=bills&boID=all">##Bills##</a></li>
        {if $Order->Service->file_cats && $Order->status == 'active'}
            <li><a {if $tab == 'files'}class="selected"{/if}
                   href="{$vurl}?p=user&s=orders&a=details&oID={$Order->orderID}&tab=files">##Files##</a></li>
        {/if}
        <li><a href="{$vurl}?p=user&s=renew&oID={$Order->orderID}">##OrderDetails%RenewOrder##</a></li>
    </ul>

    {if $tab == 'details' || $tab == ''}
        <form method="post" id="formgrid">
            <fieldset>
                <ol class="main_ol_content">
                    {if $Order->parentID}
                        <li>
                            <label>##OrderDetails%MainOrder##</label><a
                                    href="{$vurl}?p=user&s=orders&a=viewOrder&oID={$Parent->orderID}&tab=details">{$Parent->title}</a>
                        </li>
                    {/if}
                    <li>
                        <label>##OrderDetails%OrderDate##</label>{format_date date=$Order->dateAdded mode="datetime"}
                    </li>
                    <li>
                        <label>##OrderDetails%Price##</label>{if $Order->discount}{$Order->price_discounted|number_format:2}{else}{$Order->price}{/if} {$Order->curSymbol}
                    </li>
                    <li>
                        <label>##OrderDetails%OrderStarts##</label>{format_date date=$Order->dateStart}
                    </li>
                    <li>
                        <label>##OrderDetails%OrderEnds##</label>{format_date date=$Order->dateEnd}
                    </li>
                    {if $Order->payType == 'recurring'}
                        <li>
                            <label>##OrderDetails%Period##</label>{$Order->displayPeriod()}
                        </li>
                    {/if}
                </ol>
            </fieldset>
        </form>
    {elseif $tab == 'attrs'}
        {include file="user/pack_details.tpl"}
    {elseif $tab == 'domain'}
        {include file="user/domain_details.tpl"}
    {elseif $tab == 'addons'}

        {if $addonServices}
            <div class="addon_select" style="padding:0 10px;">
                <select id='addonSelect'>
                    {foreach from=$addonServices item=a}
                        <option value="?p=cart&s=srvconf&a=addon&sID={$a.serviceID}&oID={$Order->orderID}">{$a.service_name}</option>
                    {/foreach}
                </select>
                <input type="submit" value="##Add##" id='addonSubmit' class="button br_5" style="margin-left:10px;"/>
            </div>
            {literal}
                <script language="JavaScript">
                    $(document).ready(function () {
                        $('#addonSubmit').click(function () {
                            window.location = vurl + $('#addonSelect').val();
                            return false;
                        });
                    });
                </script>
            {/literal}
        {else}
            <p class="msg_warn">##NoAddonServiceForThisOrder##</p>
        {/if}

        {if $Order->addonOrders}
            <table border="0" cellspacing="0" cellpadding="0" class="main_table">
                <tr>
                    <th id="center" width="20"></th>
                    <th>##PackageName##</th>
                    <th width="130" id="right">##DateCreated##</th>
                    <th width="100" id="right">##Amount##</th>
                </tr>
                {foreach from=$Order->addonOrders item=ao}
                    <tr {cycle values=",class='alt'"}>
                        <td><img src="{$turl}images/{$icons[$ao.status]}"></td>
                        <td>{$ao.title}</td>
                        <td id="right">{format_date date=$ao.dateAdded mode=datetime}</td>
                        <td id="right">{$ao.price} {getCurrencyById id=$ao.paycurID}</td>
                    </tr>
                {/foreach}
            </table>
        {/if}



    {elseif $tab == 'bills'}
        <script language="JavaScript">
    var orderID = {$Order->orderID}
        {literal}$(document).ready(function() {
        $("#oselect").change(function(){
        window.location = vurl + '?p=user&s=orders&a=details&tab=bills&oID='+orderID+'&boID='+ $(this).val();
        });
        });
            </script>{/literal}
        {if $addons}
            <select id='oselect'>
                <option value="all">Bütün Borçlar</option>
                <option value="{$Order->orderID}"
                        {if $smarty.get.boID == $Order->orderID}selected{/if}>{$Order->title}</option>
                {foreach from=$addons item=title key=orderID}
                    <option value="{$orderID}" {if $smarty.get.boID == $orderID}selected{/if}>{$title}</option>
                {/foreach}
            </select>
        {/if}
        {if $Order->bills}
            <table border="0" cellspacing="0" cellpadding="0" class="main_table">
                <tr>
                    <th id="center" width="20"></th>
                    <th>##Description##</th>
                    <th id="right">##DueDate##</th>
                    <th id="right">##Period##</th>
                    <th id="right">##Amount##</th>
                </tr>
                {foreach from=$Order->bills item=b}
                    <tr {cycle values=",class='alt'"}>
                        <td><img src="images/led_{if $b.status == "paid"}green{else}red{/if}.png" width="13"></td>
                        <td>{$b.description}</td>
                        <td id="right">{format_date date=$b.dateDue}</td>
                        <td id="right">{format_date date=$b.dateStart} - {format_date date=$b.dateEnd}</td>
                        <td id="right">{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                    </tr>
                {/foreach}
            </table>
        {/if}

    {elseif $tab == 'files'}
        {if !$files}
            <p class="msg_warn">Siparişiniz ile ilgili indirebileceğiniz bir dosya bulunmamaktadır.</p>
        {else}
            <table border="0" cellspacing="0" cellpadding="0" class="main_table">
                <tr>
                    <th>##FileName##</th>
                    <th id="right">##DateAdded##</th>
                    <th id="right" width="100">##FileSize##</th>
                    <th id="right" width="30"></th>
                </tr>
                {foreach from=$files item=f key=fileID}
                    <tr {cycle values=",class='alt'"}>
                        <td>{$f.title} ({$f.origname})</td>
                        <td id="right">{$f.dateAdded|formatDate:datetime}</td>
                        <td id="right">{$f.size|formatFilesize}</td>
                        <td id="right"><a href="{$vurl}file.php?src=dc&id={$fileID}"><img src="images/download.png"
                                                                                          width="20"></a></td>
                    </tr>
                {/foreach}
            </table>
        {/if}
    {/if}

</div>