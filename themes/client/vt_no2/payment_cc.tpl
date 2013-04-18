<h2 class="title700">##Nav%CreditCardDetails##</h2>

<script src="{$vurl}js/jquery.validate.js" type="text/javascript"></script>
<form method="post" class="cmxform" {if $use3d}action='https://{$tdsparams.3dsUrl}'{/if} id="ccform">
    <input type="hidden" name="action" value="doPayment">
    <input type="hidden" name="pan" id="pan">
    {if $use3d}
        {foreach from=$tdsparams item=v key=k}
            <input type="hidden" name="{$k}" value="{$v}">
        {/foreach}
    {/if}
    <fieldset>
        <ol>
            <li>
                <label>Kart Sahibi</label>
                <input type="text" name="cardHolder" size="30" class="required" minlength="5" value="">
            </li>
            <li>
                <label>Kart tipi</label>
                <select name="cardType">
                    <option value="1" selected="selected">Visa</option>
                    <option value="2">MasterCard</option>
                </select>
            </li>
            <li>
                <label>Kart numarası</label>
                <input name="pan" style="width:140px" maxlength="16" type="text" class="required" value=""><br/>
            </li>
            <li>
                <label>Son kullanma</label>
                <select name="cardExpMonth">
                    {foreach from=$months item=m}
                        <option value="{$m}" {if $m == 12}selected{/if}>{$m}</option>
                    {/foreach}
                </select> /
                <select name="cardExpYear">
                    {foreach from=$years item=y}
                        <option value="{$y}" {if $y == 12}selected{/if}>{$y}</option>
                    {/foreach}
                </select> (AA/YY)
            </li>
            <li>
                <label>CV2</label>
                <input type="text" name="cv2" style="width:30px" maxlength="3" class="required digits" minlength="3"
                       value="">
            </li>
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="##Continue##"></p>
</form>

{literal}
    <script language="JavaScript">
        function ccard(cell, ids) {
            var deger = cell.value
            var nexto = ids + 1;
            var cello = 'kkart' + nexto;
            if (deger.length == 4 && ids < 4) {
                document.getElementById(cello).focus();
            }
            if (deger.length == 2 && ids > 4 && ids < 7) {
                document.getElementById(cello).focus();
            }
        }

        function check(f) {
            f.pan.value = f.cc1.value + f.cc2.value + f.cc3.value + f.cc4.value;
            return true;
        }
        $(document).ready(function () {
            $('#ccform').validate();
        });
    </script>
{/literal}
