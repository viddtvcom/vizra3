<h2 class="title2">{$Service->service_name}</h2>
<div class="article">

    <form action="{$vurl}?p=cart&s=srvconf&a=update&key={$smarty.get.key}" method="post" id="formgrid"
          style="width:100%;" class="cart_product">
        {if $smarty.get.a == 'add'}
            <input type="hidden" name="serviceID" value="{$Service->serviceID}">
        {/if}
        <ul class="main_ol_content">
            {if $Service->setup > 0}
                <li>
                    <label>##Setup##</label>{$Service->setup-$Service->setup_discount} {$Service->paycurID|getCurrencyById}
                </li>
            {/if}
            {if $Service->priceOptions}
                <li>
                    <label>##PaymentMethod##</label>
                    <select name="period" class="tinput">
                        {foreach from=$Service->priceOptions item=price key=period}
                            <option value="{$period}"
                                    {if $period == $cartItem.period}selected{/if}>{$price|displayPrice:$period:$Service->paycurID}</option>
                        {/foreach}
                    </select>
                </li>
            {/if}
            {foreach from=$Service->getAttributes(true) item=obj key=setting}
                {if $setting == 'domain'}
                {else}
                    <li>
                        <label>{$obj->label}</label>
                        {if $obj->type == "textbox" ||  $obj->type == "password"}
                            <input class="tinput" type="text" name="att[{$setting}]" id="{$setting}"
                                   style="width:{$obj->width}px;" value={$cartItem.attributes.$setting}>
                        {elseif $obj->type == "checkbox"}
                            <input type="checkbox" name="att[{$setting}]" value="1"
                                   {if $cartItem.attributes.$setting == '1'}checked{/if}>
                        {elseif $obj->type == "combobox"}
                            <select name="att[{$setting}]">
                                {foreach from=$obj->options item=v key=k}
                                    <option value="{$k}"
                                            {if $cartItem.attributes.$setting == $k}selected{/if} >{$v}</option>
                                {/foreach}
                            </select>
                        {elseif $obj->type == "textarea"}
                            <textarea name="att[{$setting}]"
                                      style="width:{$obj->width}px; height:{$obj->height}px;">{$cartItem.attributes.$setting}</textarea>
                        {/if}
                        {$obj->description}
                    </li>
                {/if}
            {/foreach}
            <li>
                <label>##YourNote##</label>
                <textarea name="description" class="tinput" rows="3"
                          style="width:400px;">{$cartItem.description}</textarea>
            </li>
        </ul>

        {if $dom}
            <h3 class="title3">##Domain##</h3>
            <input type="hidden" name="att[domain]" id="domain" value="{$cartItem.attributes.domain}">
            {if $smarty.get.a == 'update-form'}
                <ul>
                    <li>{$cartItem.attributes.domain}</li>
                </ul>
            {else}
                <ul style="padding:10px;" id="domcontainer"
                    {if $smarty.get.a == 'update-form'}style="display:none;"{/if}>
                    <li><label><input class="domreg_radio vm" type="radio" name="domreg" id="domreg_new" value="new"
                                      checked="checked"/> ##IWantToRegisterThisDomain##</label></li>
                    <li><label><input class="domreg_radio vm" type="radio" name="domreg" id="domreg_existing"
                                      value="existing"/> ##IWillUseMyExistingDomain##</label></li>
                    <li>
                        www . <input type="text" id="domain_dom" class="tinput" style="width:250px;"
                                     onkeyup="return taCount('domain_dom','NODISPLAY');">
                        .
                        <select id="ext_new" class="tinput">
                            {foreach from=$exts item=i key=k}
                                <option value="{$k}">.{$k}</option>
                            {/foreach}
                        </select>
                        <input type="text" id="ext_existing" style="width:50px; display:none;" class="tinput">
                        <span style="display:inline;"></span>
                        <input type="submit" value="##Check##" class="button br_5" id="but_cont"/>
                    </li>
                    <li></li>
                </ul>
            {/if}
        {/if}

        {if $addons}
            <h3 class="title3">##Addons##</h3>
            <ul style="padding:10px;">
                {foreach from=$addons item=a}
                    <li>
                        <label><input type="checkbox" name="addons[{$a.serviceID}]" value="1"
                                      class="vm"/> {$a.service_name} - ({$a.price|displayPrice:$a.period:$a.paycurID}
                            )</label>
                    </li>
                {/foreach}
            </ul>
        {/if}

        <p align="right">
            {if $smarty.get.a == 'add'}
                <input type="submit" value="##AddToCart##" class="button br_5" style="display:{if $dom}none{/if};"
                       id="but_submit">
            {else}
                <input type="submit" value="##Update##" class="button br_5" id="but_submit">
            {/if}
        </p>
    </form>


    {if $dom}
        <script language="JavaScript">
            {if $smarty.get.a == 'add'}
            var formlock = true;
            var querylock = false;
            {/if}
            {literal}
            $(document).ready(function () {
                $('#formgrid').submit(function () {
                    if (formlock) return false;
                    if ($('#domreg_existing').val() == 'existing') {
                        $('#domain').val($('#domain_dom').val() + '.' + $('#ext_existing').val());
                    }
                });
                $('#but_cont').click(function () {
                    if (querylock) return false;
                    $("#but_submit").hide();
                    formlock = true;
                    $(this).hide().prev().show().css('display', 'inline').html('<img src="images/loading.gif">');
                    $.post(vurl + "?p=ajax", { action: "lookup_domain", domain: $('#domain_dom').val() + '.' + $('#ext_new').val(), select: "true"},
                            function (ret) {
                                var res = '';
                                if (ret.st) {
                                    var html = "<img src='images/check.png' id='middle'> " + '<input name="att[domain]" type="hidden" value="' + ret.domain + '"> ' + ret.domain;
                                    var select = '<select name="domperiod">' + ret.select + '</select>';
                                    $("#domcontainer li:last").html(html + "  (##AvailableForRegistration##) " + select);
                                    $("#but_submit").show();
                                    formlock = false;
                                } else {
                                    $("#domcontainer li:last").html("<img src='images/stop.png' id='middle'> " + ret.domain + " (##AlreadyRegistered##)");
                                }
                                $('#but_cont').show().prev().hide();
                            }, "json");

                    return false;
                });
                $(".domreg_radio").click(function () {
                    if ($(this).val() == 'new') {
                        formlock = true;
                        querylock = false;
                        $("#ext_new,#but_cont,#domcontainer li:last").show();
                        $("#ext_existing,#but_submit").hide();
                    } else {
                        formlock = false;
                        querylock = true;
                        $("#ext_existing,#but_submit").show();
                        $("#ext_new,#but_cont").hide();
                        $("#domcontainer li:last").html('');

                    }

                });
            });
            regExInvalidChars = /^([^a-zA-Z0-9-])$/i; //global settings  
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
    {/if}
</div>