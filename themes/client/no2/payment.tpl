<h2 class="title2">##Nav%PaymentSelection##</h2>
<div class="article">
    <h4 style="padding:10px 0; font-size:1.2em;">##PleaseSelectAPaymentMethod##</h4>

    <form method="post" class="cmxform">
        <input type="hidden" name="action" value="gateway">
        <fieldset>
            <ol class="payment_ol">
                <li class="po_tur br_5">
                    <fieldset style="font-size: 1.5em;">
                        {foreach from=$modules item=title key=moduleID}
                            <label><input type="radio" name="moduleID" value="{$moduleID}" checked
                                          class="vm"> {$mtitles.$moduleID}</label>
                        {/foreach}
                        {if $paymode == 'balance_full' && $smarty.get.a != 'addFunds'}
                            <label><input type="radio" name="moduleID" value="balance_full" checked class="vm"> Bakiye
                                Hesabından ({$balance|number_format:2} {getCurrencyById id=$paycurID})</label>
                        {/if}

                    </fieldset>
                </li>
                {if $smarty.get.a == 'addFunds'}
                    <li style="text-align:right;"><label>##Amount##</label>
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
                    <li style="font-size:14px;"><label>##Amount##</label> :
                        <b>{$total} {getCurrencyById id=$paycurID}</b></li>
                {/if}
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="##Continue##" class="button br_5" style="margin-top:10px;"></p>
    </form>
</div>