<h2 class="title2">##TopMenu%MyOrders##</h2>
<div class="article">
    <form method="post" action="{$vurl}?p=user&s=renew">
        <input type="hidden" name="return" value="?p=user&s=orders">
        <table cellpadding="0" cellspacing="0" class="main_table" style="margin-bottom:10px;">
            <tbody>
            <tr>
                <th width="20"><input type="checkbox" name="sel"
                                      onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                <th width="30"></th>
                <th width="80">##Order## No</th>
                <th scope="col">##PackageName##
                <th id="right">##OrderDetails%OrderDate##</th>
                <th id="right">##EndDate##</th>
            </tr>
            {foreach from=$orders item=o}
                <tr {cycle values=",class='alt'"}>
                    <td>
                        {if ($o.status == 'active' || $o.status == 'suspended') && $o.payType == 'recurring'}
                            <input type="checkbox" name="selected[]" class="mcheck" value="{$o.orderID}">
                        {/if}
                    </td>
                    <td id="center"><img src="images/{$icons[$o.status]}" width="13"></td>
                    <td><a href="{$vurl}?p=user&s=orders&a=details&oID={$o.orderID}">{$o.orderID}</a></td>
                    <td><a href="{$vurl}?p=user&s=orders&a=details&oID={$o.orderID}">{$o.title}</a></td>
                    <td id="right">{format_date date=$o.dateAdded mode='datetime'}</td>
                    <td id="right">{format_date date=$o.dateEnd}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        <p align="left"><label>##WithSelected## : <input type="submit" value="##Renew##" class="button br_5"/></label>
        </p>
    </form>
</div>