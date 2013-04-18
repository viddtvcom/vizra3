{php} $this->_tpl_vars['extensions'] = getDomainExtensions(); {/php}
{if $extensions}
    <h2 class="title700">##Nav%DomainRegistration##</h2>
    <div class="content_right" style="margin-bottom:20px;">
        <form action="{$vurl}?p=shop&s=domain&a=check" method="post">{$formToken}
            <div style="width: 50px; float: left; padding-top: 20px; font-size: 1.5em;">www.</div>
            <textarea name="domain" id="domain" rows="2" cols="5" class="da_tarea br_5"
                      onkeyup="return taCount('domain','NODISPLAY');"></textarea>
            <ul class="domain_uzantilari" style="width: 280px; float: left;">
                {foreach from=$extensions key=ext item=i}
                    <li><input type="checkbox" name="ext[]" value="{$ext}" checked>.{$ext}</li>
                {/foreach}
            </ul>
            <div style="float:right; padding-right: 20px;">
                <input type="submit" class="button clear right br_5" style="margin-top:10px;" value="##Search##"/>
            </div>
        </form>
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
    </div>
{/if}

{include file="banner.tpl"}

<h2 class="title700">&nbsp;</h2>
<div class="content_right" style="margin-right: 0px; padding: 10px 3px;">

    {foreach from=$sh_services item=s key=serviceID}
        <div class="pack_box br_5">
            <div class="pb_top" style="position: relative;">
                <img src="{$vurl}?p=image&t=service&f={$s.avatar}.jpg&w=48&h=48" alt="{$s.service_name}"
                     style="position: absolute; top:30px; right:1px; padding:0px 2px 2px 2px;"/>

                <h2>{$s.service_name}</h2>
                {$s.description}

            </div>
            <!-- /pb_top -->
            <div class="pb_bottom">
                <p>
                    <span class="onsale">{if $s.onsale}##OnSale##!{else}&nbsp;&nbsp;&nbsp;{/if}</span>
                    {if $s.price.price > 0}
                        {if $s.onsale}<span
                                class="price2">{$s.price.price+$s.price.discount|number_format:2}</span>{/if}
                        <span class="price">{$s.price.price|number_format:2}</span>
                        <span class="ptext">{$s.paycurID|getCurrencyById}<br/>{$s.price.display2.1}</span>
                    {else}
                        {if $s.setup > 0}
                            {if $s.onsale}<span class="price2">{$s.setup+$s.setup_discount|number_format:2}</span>{/if}
                            <span class="price">{$s.setup|number_format:2}</span>
                            <span class="ptext">{$s.paycurID|getCurrencyById}<br/></span>
                        {else}
                            <span class="onsale">##Free##!</span>
                        {/if}
                    {/if}
                </p>
                <input type="button" class="right button" value="##AddToCart##"
                       onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$serviceID}';">
            </div>


        </div>
        <!-- /pack_box -->

    {/foreach}


</div>
