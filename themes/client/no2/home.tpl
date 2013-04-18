{include file="banner.tpl"}
<div class="content">

    <!-- domain sorgulama -->
    {if $extensions}
        <div class="domain_sorgulama">
            <!--<img src="images/layout/img_alan_adi_tescil.gif" alt="" class="left" />-->

            <p class="reklam left br_l_10">Alan Adı Tescil<br/> Yıllık 10 TL</p>

            <form action="{$vurl}?p=shop&s=domain&a=check" method="post">{$formToken}
                <ul class="br_r_10">
                    <li class="left"><textarea name="domain" id="domain" rows="1" cols="1" class="tinput br_10"
                                               style="padding:10px; width:300px; height:15px; margin:6px 0 0 10px;"
                                               onkeyup="return taCount('domain','NODISPLAY');"></textarea></li>
                    <li class="left" style="padding:17px 0 0 10px;">
                        {foreach from=$extensions key=ext item=i}
                            <label><input type="checkbox" name="ext[]" value="{$ext}" checked>.{$ext}</label>
                        {/foreach}
                    </li>

                    <li class="left" style="margin:10px;"><input type="submit" class="button clear right br_5"
                                                                 value="##Search##"/></li>
            </form>
            </ul>
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
    <!-- /domain sorgulama -->


    <div class="right sidebar">
        <h2 class="title1">Üye Girişi</h2>
        <ul class="article s_ug" style="margin-bottom:10px;">
            <li>
                <form action="{$vurl}?p=user&s=login" method="post" id="login_form2">
                    <input type="hidden" name="action" value="validate_login"/>
                    <input type="hidden" name="token" value="{1|getToken}"/>
                    <label for="su1" class="block left" style="margin-right:10px;">##YourEmail##</label>
                    <input type="text" id="su1" name="email" value="" class="tinput right block br_5"
                           style="width:110px;"/>

                    <br class="clear"/>

                    <label for="su2" class="block left" style="margin-right:10px;">##YourPassword##</label>
                    <input id="su2" type="password" name="password" value="" class="tinput right block br_5"
                           style="width:110px;"/>

                    <br class="clear"/>

                    <label><input type="checkbox" class="vm"/> Beni Hatırla </label>
                    <input id="su3" type="submit" value="##Login##" class="button br_5 right"/>
                    <br class="clear"/><br/><a href="#"
                                               onclick="$('#login_form2').hide(); $('#reminder_form').fadeIn(1000); return false;">##CantRememberPassword##</a>
                </form>

                <form method="post" id="reminder_form" style='display:none;'>
                    <input type="hidden" name="action" value="remind_password"/>
                    <input type="hidden" name="token" value="{2|getToken}"/>
                    <h5>##PasswordReminder##</h5>
                    <label for="su1" class="block left">##YourEmail##</label>
                    <input type="text" id="su1" name="email" value="" class="tinput br_5" style="width:120px;"/>
                    <input id="su3" type="submit" value="##SendMyPassword##" class="button br_5 right"/>
                </form>
            </li>
        </ul>


        {include file="product_menu.tpl"}
    </div>
    <!-- /sidebar -->

    <div class="section left">
        <!-- hosting paketlerinin arasindaki boslugu ayarlar -->
        {literal}
            <script type="text/javascript">$(document).ready(function () {
                    $(".pack_box:eq(1)").addClass('orta');
                    $(".pack_box:eq(4)").addClass('orta');
                });</script>
        {/literal}

        {foreach from=$sh_services item=s key=serviceID}
            <div class="pack_box br_5">
                <!--<img src="{$vurl}?p=image&t=service&f={$s.avatar}.jpg&w=48&h=48" alt="{$s.service_name}"  style="position: absolute; top:30px; right:1px; padding:0px 2px 2px 2px;" />-->
                <h2 class="title2">{$s.service_name}</h2>

                <div class="pb_content article br_b_5">

                    <!--aciklama-->
                    <div style="margin:0 0 10px; line-height:20px;">{$s.description}</div>
                    <!--/aciklama-->

                    <!--fiyat-->
                    <p class="fiyat">
                        <span class="onsale">{if $s.onsale}##OnSale##!{else}{/if}</span>
                        {if $s.price.price > 0}
                            {if $s.onsale}<span
                                    class="price2">{$s.price.price+$s.price.discount|number_format:2}</span>{/if}
                            <span class="price left">{$s.price.price|number_format:2}</span>
                            <span class="ptext left">{$s.paycurID|getCurrencyById}<br/>{$s.price.display2.1}</span>
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
                    <!--fiyat-->

                    <!--button-->
                    <a href="#"
                       style="background:url(images/layout/ico_detay.png) no-repeat left; padding-left:15px; margin:5px 0;"
                       class="right">Detay</a>

                    <div class="button left br_5"><img src="images/layout/ico_sepet.png"/> <input type="button"
                                                                                                  value="##AddToCart##"
                                                                                                  onclick="window.location='{$vurl}?p=cart&s=srvconf&a=add&ID={$serviceID}';"/>
                    </div>

                    <!--/button-->

                </div>
                <!-- /pb_content -->
            </div>
            <!-- /pack_box -->
        {/foreach}


        <!-- duyurular -->
        {if $announcements}
            <h2 class="title2 clear">##Nav%Announcements##</h2>
            <ul class="article duyurular br_b_5">
                {foreach from=$announcements  item=a}
                    <li class="left">
                        <p class="date left">
                            <em class="d_ay_yil">Kas 10</em>
                            <em class="d_gun">2</em>
                            <!--{$a.dateAdded|formatDate:datetime:short}-->
                        </p>

                        <h2 class="left"><a href="{$vurl}?p=announcements#{$a.recID}"> {$a.title}</a></h2>

                        <p class="clear" style="padding-top:10px;">Ücretsiz lisanslar da dahil olmak üzere kurulumunuzu
                            biz yapıyoruz. Herhangi bir lisans siparişinizi verip Destek Bilet'i açar....</p>
                    </li>
                {/foreach}
            </ul>
        {/if}
        <!-- duyurular -->


    </div>
    <!-- /section -->