<h2 class="title700">##Nav%PaymentSelection##</h2>
<div class="content_right">
    <h4>##PleaseSelectAPaymentMethod##</h4>

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="gateway">
        <fieldset>
            <ol>
                <li>
                    <fieldset style="font-size: 1.5em;">
                        {foreach from=$modules item=title key=moduleID}
                            <label><input type="radio" name="moduleID" value="{$moduleID}" checked> {$mtitles.$moduleID}
                            </label>
                        {/foreach}
                        {if $paymode == 'balance_full' && $smarty.get.a != 'addFunds'}
                            <label><input type="radio" name="moduleID" value="balance_full" checked> Bakiye Hesabından
                                ({$balance|number_format:2} {getCurrencyById id=$paycurID})</label>
                        {/if}

                    </fieldset>
                </li>
                {if $smarty.get.a == 'addFunds'}
                    <li><label>##Amount##</label>
                        <input type="text" name="amount" style="width: 80px; text-align: right;" class="tinput">
                        <select name="paycurID" class="tinput">
                            {foreach from=$config.CURTABLE item=cur key=curID}
                                {if $cur.status == 'active'}
                                    <option value="{$curID}">{$cur.symbol}</option>
                                {/if}
                            {/foreach}
                        </select>
                    </li>
                {else}
                    <li><label>##Amount##</label><b>{$total} {getCurrencyById id=$paycurID}</b></li>
                {/if}
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="##Continue##" class="button"></p>
    </form>

</div>


