<h2 class="title700">##Renewal##</h2>
<div class="content_right">
    <form method="post" action="{$vurl}?p=payment&a=renewOrder">
        <input type="hidden" name="return" value="{$smarty.post.return}">
        <table cellpadding="0" cellspacing="0" class="main_table">
            <tr>
                <th width="80">Sipariş No</th>
                <th scope="col">##PackageName##</th>
                <th id="right">##EndDate##</th>
            </tr>

            {foreach from=$orders item=o}
                <input type="hidden" name="selected[]" value="{$o.orderID}">
                <tr {cycle values=",class='alt'"}>
                    <td><a href="{$vurl}?p=user&s=orders&a=details&oID={$o.orderID}">{$o.orderID}</a></td>
                    <td>{$o.title}</a></td>
                    <td id="right">
                        <select name="multipliers[{$o.orderID}]" class="tinput">
                            {foreach from=$o.options item=data key=multiplier}
                                <option value="{$multiplier}" {if $multiplier == 1 }selected{/if}>
                                    {$data.timestamp|formatDate} ({$o.period*$multiplier} ##Months##)
                                    - {$data.price} {$o.paycurID|getCurrencyById}
                                </option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/foreach}
        </table>
        <p align="right"><label> <input type="submit" value="Devam" class="button"></label></p>
    </form>
</div>


