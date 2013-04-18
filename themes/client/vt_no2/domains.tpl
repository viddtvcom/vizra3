<h2 class="title700">##Nav%DomainRegistration##</h2>

<form action="{$vurl}?p=shop&s=domain&a=check" method="post">{$formToken}
    <div style="font-size: 1.5em;">www.</div>
    <textarea name="domain" id="domain" rows="2" cols="5" class="da_tarea br_5"
              onkeyup="return taCount('domain','NODISPLAY');" style="width:708px; margin:10px 0;"></textarea>
    <ul class="domain_uzantilari">
        {php} $this->_tpl_vars['extensions'] = getDomainExtensions(); {/php}
        {foreach from=$extensions key=ext item=i}
            <li><input type="checkbox" name="ext[]" value="{$ext}" checked>.{$ext}</li>
        {/foreach}
    </ul>

    <br class="clear"/>

    <input type="submit" class="button clear br_5" style="margin-top:10px;" value="##Search##"/>
</form>

<div class="clear"></div><br/><br/>


{literal}
    <script language="JavaScript">
        regExInvalidChars = /^([^a-zA-Z0-9-\n])$/i; //global settings
        function taCount(ident, displayId) {
            taObj = document.getElementById(ident);
            taLength = taObj.value.length;
            oldLength = 0;

            while (oldLength < taLength) { //validate characters
                tChar = taObj.value.charAt(oldLength);
                if (regExInvalidChars.test(tChar)) {
                    tStr = taObj.value;
                    tail = tStr.substring(oldLength + 1);
                    taObj.value = tStr.substring(0, oldLength) + tail;
                    taLength--;
                } else {
                    oldLength++;
                }
            }
            if (displayId.toLowerCase() == "nodisplay") {
                return;
            } // suppress display
            dispObj = document.getElementById(displayId);
            dispObj.innerHTML = (maxLength - taObj.value.length);
        }
    </script>
{/literal}

{if $post}

    {literal}
        <script language="JavaScript">

            $(document).ready(function () {
                function lookup_domain($domain, $k) {
                    $.post(vurl + "?p=ajax", { action: "lookup_domain", domain: $domain, key: $k },
                            function (ret) {
                                var res = '';
                                if (ret.st) {
                                    $("#res_" + ret.key).html("##AvailableForRegistration##");
                                    $("#check_" + ret.key).html('<input name="domains[' + ret.key + ']" type="checkbox" value="' + ret.domain + '" onclick="showHide(\'tr_' + ret.key + '\')" />');
                                } else {
                                    var html = "##AlreadyRegistered##";
                                    html += ' - <a href="http://www.' + ret.domain + '" target="_blank">[www]</a>';
                                    html += ' - <a href="http://whois.domaintools.com/' + ret.domain + '" target="_blank">[whois]</a>';

                                    $("#res_" + ret.key).html(html);
                                    $("#check_" + ret.key).html('<img src="images/stop.png">');
                                }
                            }, "json");

                }

                $("div[id*=dom]").each(function () {
                    var id = $(this).attr('id').replace('dom_', '');
                    lookup_domain($(this).html(), id);
                });

            });

            function checkboxes() {
                var any = false;
                $('[name*=domains]').each(function () {
                    if ($(this).is(':checked')) any = true;
                });
                if (!any) alert('##PleaseChooseAtLeastOneDomain##');
                return any;
            }

        </script>
    {/literal}
    <form action="{$vurl}?p=cart&s=domconf" method="post">
        <table cellpadding="0" cellspacing="0" class="main_table">
            <tr>
                <th></th>
                <th>##Domain##</th>
                <th>##Status##</th>
                <th>##Price##</th>
            </tr>
            {foreach from=$results item=i key=k}
                <tr>
                    <td>
                        <div id="check_{$k}"><img src="images/loading.gif"></div>
                    </td>
                    <td>
                        <div id="dom_{$k}">{$i.domain}</div>
                    </td>
                    <td>
                        <div id="res_{$k}"></div>
                    </td>
                    <td>{$i.priceRegister} {$i.symbol} / 1 ##Years##</td>
                </tr>
                <tr id="tr_{$k}" style="display: none;">
                    <td colspan="4"
                        style="padding:10px 10px; background-color: #F5F5F5; text-align: center; font-size: 1.2em;">
                        <input type="hidden" name="action[{$k}]" value="register">
                        <strong>NS 1:</strong> <input type="text" name="ns1[{$k}]" value="{$defaultns.1}"
                                                      class="tinput">
                        <strong>NS 2: </strong><input type="text" name="ns2[{$k}]" value="{$defaultns.2}"
                                                      class="tinput">
                        <strong>Period: </strong><select name="period[{$k}]" class="tinput">
                            {section name=s start=0 loop=$i.periodMax}
                                <option value="{$smarty.section.s.index+1}">{$smarty.section.s.index+1} ##Years##
                                    ({$i.priceRegister+$smarty.section.s.index*$i.priceRenew|string_format:"%.2f"} {$i.symbol}
                                    )
                                </option>
                            {/section}
                        </select><br/><br/><br/>
                    </td>
                </tr>
                <tr id="transfer_{$k}" style="display: none;">
                    <td colspan="4"
                        style="padding:10px 10px; background-color: #F5F5F5; text-align: center; font-size: 1.2em;">
                        <input type="hidden" name="action[{$k}]" value="transfer">
                        <strong>Transfer Kodu:</strong> <input type="text" name="auth[{$k}]" class="tinput">
                    </td>
                </tr>
            {/foreach}
        </table>
        <p align="right"><input type="submit" value="##AddToCart##" class="button" onclick="return checkboxes();"></p>

    </form>
{/if}