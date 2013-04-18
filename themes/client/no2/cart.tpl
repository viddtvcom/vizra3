<h2 class="title2">
    <a href="{$vurl}?p=cart&s=do&a=empty" class="right">##EmptyCart##</a>
    ##Nav%YourCart##
</h2>

<div class="article">

    {if $cart->item_count}

        {if $cart->services}
            <table cellpadding="0" cellspacing="0" class="main_table">
                <thead>
                <tr>
                    <td colspan="5"><h3>##Services##</h3></td>
                </tr>
                </thead>
                {foreach from=$cart->services item=s key=itemID}
                    <tr>
                        <td valign="middle" width="30">
                            <a href="{$vurl}?p=cart&s=srvconf&a=rm&key={$itemID}"><img src="images/ico_delete.png"
                                                                                       width="16" height="16"/></a>
                        </td>
                        <td>
                            {$s.service_name}&nbsp;&nbsp;&nbsp;(<a
                                    href="{$vurl}?p=cart&s=srvconf&a=update-form&key={$itemID}">##Update##</a>)
                        </td>
                        <td width="100">
                            {if $s.payPrice > 0}
                                {if $s.oneTime}
                                    ##OneTime##
                                {else}
                                    {$s.period|displayPeriod}
                                {/if}
                            {/if}
                        </td>
                        <td width="80">
                            <div align="right">{if $s.payPrice > 0}{$s.payPrice} {$mainCur}{else}Ücretsiz{/if}</div>
                        </td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $cart->addons}
            <table cellpadding="0" cellspacing="0" class="main_table">
                <thead>
                <tr>
                    <td colspan="5"><h3>##Addons##</h3></td>
                </tr>
                </thead>
                {foreach from=$cart->addons item=a key=itemID}
                    <tr>
                        <td valign="middle" width="30">
                            <a href="{$vurl}?p=cart&s=srvconf&a=rmaddon&key={$itemID}"><img src="images/ico_delete.png"
                                                                                            width="16" height="16"/></a>
                        </td>
                        <td>
                            {$a.service_name}&nbsp;&nbsp;&nbsp;{if $a.orderID}(Sipariş No: {$a.orderID}){/if}
                        </td>
                        <td width="100"></td>
                        <td width="80">
                            <div align="right">{$a.payPrice} {$mainCur}</div>
                        </td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $cart->domains}
            <table cellpadding="0" cellspacing="0" class="main_table">
                <thead>
                <tr>
                    <td colspan="5"><h3>##Domains##</h3></td>
                </tr>
                </thead>
                {foreach from=$cart->domains item=d key=domain}
                    <tr>
                        <td valign="middle" width="30">
                            <a href="{$vurl}?p=cart&s=domconf&a=rm&d={$domain}"><img src="images/ico_delete.png"
                                                                                     width="16" height="16"/></a>
                        </td>
                        <td>
                            {$domain}
                        </td>
                        <td width="150">({$d.period} ##xYearsOfRegistration##)</td>
                        <td>
                            <div align="right">{$d.payPrice} {$mainCur}</div>
                        </td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $cart->coupon}
            <table cellpadding="0" cellspacing="0" class="main_table">
                <tr>
                    <td valign="middle" width="30">
                        <form method="post">
                            <input type="hidden" name="action" value="remove_coupon">
                            <input type="image" src="images/ico_delete.png">
                        </form>
                    </td>
                    <td>
                        İndirim Kupon Kodu: {$cart->coupon}
                    </td>
                </tr>
            </table>
        {/if}
        <table cellpadding="0" cellspacing="0" class="main_table">
            <tr class="alt">
                <td align="right" style="font-weight: bold;">##Total## : &nbsp;{$cart->totals.all} {$mainCur}</td>
            </tr>
        </table>
        <table width="100%" border="0">
            <tr>
                <td width="50%" align="center">
                    {if !$cart->coupon}
                        <form method="post">
                            <input type="hidden" name="action" value="validate_coupon">
                            İndirim Kuponu: <input type="text" name="coupon" class="tinput br_5">
                            &nbsp;<input type="submit" value="Ekle" class="button">
                        </form>
                    {/if}
                </td>
                <td width="50%" align="center">
                    <input type="button" value="##ProcessOrder##" onclick="window.location=vurl+'?p=payment&a=checkout'"
                           class="button">
                </td>
            </tr>
        </table>
    {else}
        <p class="msg_warn">
            ##YourCartIsEmpty##
        </p>
    {/if}

</div>