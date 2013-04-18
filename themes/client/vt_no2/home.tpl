<div style="margin:20px 0;">
    {include file="banner.tpl"}
</div>

<div class="left" style="width:660px;">

    {php} $this->_tpl_vars['extensions'] = getDomainExtensions(); {/php}
    {if $extensions}
        <form action="{$vurl}?p=shop&s=domain&a=check" method="post">
            {$formToken}

            <div class="left">
                <h2 style="color:#e06; padding:5px 10px; ">##Nav%DomainRegistration##</h2>

                <div class="br_5 domain">
                    <div class="br_5 in">

                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="width:7%;">www.</td>
                                <td><textarea name="domain" id="domain" rows="1" cols="5"
                                              onkeyup="return taCount('domain','NODISPLAY');" class="br_5"></textarea>
                                </td>
                                <td style="width:20%; text-align:right;"><input type="submit" class="button br_5"
                                                                                value="##Search##"/></td>
                            </tr>
                        </table>

                    </div>
                </div>


                <ul class="clear domain_uzantilari" style="width: 280px;">

                    {foreach from=$extensions key=ext item=i}
                        <li><input type="checkbox" name="ext[]" value="{$ext}" checked>.{$ext}</li>
                    {/foreach}

                </ul>
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

    {/if}

    {literal}
        <script type="text/javascript">

            $(function () {
                $(".paketler .pack_box:eq(1)").addClass("paket1");
                $(".paketler .pack_box:eq(2)").addClass("paket1");
                $(".paketler .pack_box:eq(4)").addClass("paket1");
                $(".paketler .pack_box:eq(5)").addClass("paket1");
            });

        </script>
    {/literal}

    <br class="clear"/>
    <br class="clear"/>
    <br class="clear"/>

    <h2 class="title700" style="margin-bottom:0;">Öne Çıkan Hosting Paketleri</h2>

    <div class="paketler">
        {foreach from=$sh_services item=s key=serviceID}
            <div class="pack_box">
                <div class="pb_top" style="position: relative;">
                    <!--<img src="{$vurl}?p=image&t=service&f={$s.avatar}.jpg&w=48&h=48" alt="{$s.service_name}"  style="position: absolute; top:30px; right:1px; padding:0px 2px 2px 2px;" />-->
                    <h2>{$s.service_name}</h2>
                    {$s.description}
                </div>

                <div class="pb_bottom">

                    <p class="right">
                        <input type="button" class="button br_5" value="##AddToCart##"
                               onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$serviceID}';"
                               style="margin-bottom:5px;"/><br/>
                        <span class="onsale">{if $s.onsale}##OnSale##!{else}&nbsp;&nbsp;&nbsp;{/if}</span>
                    </p>

                    <p class="left">
                        {if $s.price.price > 0}
                            {if $s.onsale}<span
                                    class="price2">{$s.price.price+$s.price.discount|number_format:2}</span>{/if}
                            <span class="price">{$s.price.price|number_format:2}</span>
                            <span class="ptext">{$s.paycurID|getCurrencyById} / {$s.price.display2.1}</span>
                        {else}
                            {if $s.setup > 0}
                                {if $s.onsale}<span
                                        class="price2">{$s.setup+$s.setup_discount|number_format:2}</span>{/if}
                                <span class="price">{$s.setup|number_format:2}</span>
                                <span class="ptext">{$s.paycurID|getCurrencyById}<br/></span>
                            {else}
                                <span class="onsale">##Free##!</span>
                            {/if}
                        {/if}
                    </p>
                </div>

            </div>
            <!-- /pack_box -->
        {/foreach}
    </div>
</div><!-- /home left -->

<div class="right" style="width:300px;">

    <a href="#"><img src="css/layout/ads_example.gif" alt="" style="margin-bottom:20px;"/></a>

    <!-- Duyurularin Listelenmesi -->
    {if $announcements}
        <h2 class="home_right_box_title br_5">##Nav%Announcements##</h2>
        <ul class="home_right_box br_5">
            {foreach from=$announcements  item=a}
                <li>
                    <a href="{$vurl}?p=announcements#{$a.recID}"> {$a.title}</a>
                    <em style="display:block; padding-left:14px;">{$a.dateAdded|formatDate:datetime:short}</em>
                </li>
            {/foreach}
        </ul>
    {/if}

    <!-- 7/24 Teknik Destek -->
    <h2 class="home_right_box_title br_5">7/24 Teknik Destek</h2>

    <div class="home_right_box br_5">
        <a href="#"><img src="css/layout/7-24_teknik_destek.png" alt="Teknik Destek" style="padding:10px 15px;"/></a>
    </div>

    <!-- Müsterilerimiz -->
    <h2 class="home_right_box_title br_5">Müşterilerimiz</h2>

    <div class="home_right_box br_5">
        <a href="{$vurl}musterilerimiz.php"><img src="css/layout/musterilerimiz.png" alt="Teknik Destek"
                                                 style="padding:10px;"/></a>
    </div>


</div><!-- /home right -->


<div class="clear"></div>