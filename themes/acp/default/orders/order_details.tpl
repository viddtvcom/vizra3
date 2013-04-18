<div class="order_overview">
    <div><img src="{$turl}images/status_{$Order->status}.png" hspace="4" id="middle">
        <span>{$Order->orderID}</span> {$Order->title}  </div>
</div>

<div class="table_tabs_menu">
    <!--[if !IE]>start  tabs<![endif]-->
    <ul class="table_tabs">
        <li>
            <a {if $subtab == 'general' || $subtab == ''}class="selected"{/if}
               href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=general">
                <span><span>Genel</span></span>
            </a>
        </li>
        {if $Order->Service->type == 'domain'}
            <li>
                <a {if $subtab == 'domain'}class="selected"{/if}
                   href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=domain">
                    <span><span>Alan Adı</span></span>
                </a>
            </li>
        {else}
            <li>
                <a {if $subtab == 'attrs'}class="selected"{/if}
                   href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=attrs">
                    <span><span>Özellikler</span></span>
                </a>
            </li>
            <li>
                <a {if $subtab == 'addons'}class="selected"{/if}
                   href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=addons">
                    <span><span>Eklentiler</span></span>
                </a>
            </li>
        {/if}
        <li>
            <a {if $subtab == 'bills'}class="selected"{/if}
               href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=bills">
                <span><span>Borçlar</span></span>
            </a>
        </li>
        <li>
            <a {if $subtab == 'actions'}class="selected"{/if}
               href="?p=311&tab=orders&orderID={$Order->orderID}&subtab=actions">
                <span><span>İşlemler</span></span>
            </a>
        </li>
    </ul>
</div>

<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>

                    {if $subtab == 'general' || $subtab == ''}
                        <script src="{$vurl}js/jquery-ui.min.js" type="text/javascript"></script>
                        <form method="post" class="cmxform" style="width:99%;">
                            <input type="hidden" name="action" value="update">
                            <fieldset>
                                <ol>
                                    <li>
                                        <label>Müşteri:</label>
                                        <img src="{$turl}/images/status_{$Order->Client->status}.png" id="middle"
                                             width="12"> <a
                                                href="?p=311&clientID={$Order->clientID}">{$Order->Client->name}</a>
                                    </li>
                                    {if $Order->parentID}
                                        <li>
                                            <label>Ana Sipariş</label>
                                            <a href="?p=411&orderID={$Order->parentID}">{$Parent->title}</a>
                                        </li>
                                    {/if}
                                    <li>
                                        <label>Sipariş No:</label>
                                        {$Order->orderID}
                                    </li>
                                    <li>
                                        <label>Sipariş Tarihi:</label>
                                        {format_date date=$Order->dateAdded mode=datetime}
                                    </li>
                                    <li>
                                        <label>Paket Adı:</label>
                                        <input name="title" value="{$Order->title}" style="width:400px;">
                                    </li>
                                    <li>
                                        <label>Durum:</label>
                                        <select name="status">
                                            {foreach from=$vars.ORDER_STATUS_TYPES item=i}
                                                <option value="{$i}" {if $i == $Order->status}selected{/if}>
                                                    ##OrderDetails%{$i}##
                                                </option>
                                            {/foreach}
                                        </select>
                                    </li>
                                    <li>
                                        <label>Ödeme Tipi:</label>
                                        <select name="payType">
                                            {foreach from=$vars.ORDER_PAY_TYPES item=i}
                                                <option value="{$i}" {if $i == $Order->payType}selected{/if}>
                                                    ##OrderDetails%{$i}##
                                                </option>
                                            {/foreach}
                                        </select>
                                    </li>
                                    {if !$Dom && $Order->payType == 'recurring'}
                                        <li>
                                            <label>Periyod:</label>
                                            {if $Order->parentID}
                                                ##BILLING_CYCLES_{$Order->period}##
                                            {else}
                                                <select name="period">
                                                    {foreach from=$vars.BILLING_CYCLES item=period}
                                                        <option value="{$period}"
                                                                {if $period == $Order->period }selected{/if}>
                                                            ##BILLING_CYCLES_{$period}##
                                                        </option>
                                                    {/foreach}
                                                </select>
                                            {/if}
                                        </li>
                                    {/if}
                                    {if $Order->payType != 'free'}
                                        <li>
                                            <label>Ücret:</label>
                                            <input name="price" value="{$Order->price}"
                                                   size="8"> {getCurrencyById id=$Order->paycurID}
                                        </li>
                                        <li>
                                            <label>İndirim Kuponu:</label>
                                            <select name="couponID">
                                                <option value="0">Yok</option>
                                                {foreach from=$coupons item=c}
                                                    <option value="{$c.couponID}"
                                                            {if $Order->couponID == $c.couponID}selected{/if}>{$c.code}</option>
                                                {/foreach}
                                            </select>
                                            &nbsp; {if $Order->CPN->active} (Aktif) {else} (Aktif Değil) {/if}
                                        </li>
                                        {if $Order->price_discounted}
                                            <li>
                                                <label>İndirimli Ücret:</label>
                                                {$Order->price_discounted|number_format:2} {getCurrencyById id=$Order->paycurID}
                                            </li>
                                        {/if}
                                    {/if}
                                    <li>
                                        <label>Başlangıç:</label>
                                        <input type="text" name="dateStart" id="dateStart" style="width:100px"
                                               value="{$Order->dateStart}">
                                    </li>
                                    <li>
                                        <label>Bitiş:</label>

                                        <input type="text" name="dateEnd" id="dateEnd"
                                               style="width:100px; {if !$Order->dateEnd}display:none;{/if}"
                                               value="{$Order->dateEnd}">

                                        <input type="checkbox" name="never_expires" value="1"
                                               {if !$Order->dateEnd}checked="checked"{/if} id="never_expires"> Hiç bir
                                        zaman
                                    </li>
                                    <li>
                                        <label>Otomasyon</label>
                                        <input type="checkbox" value="1" name="autoSuspend"
                                               {if $Order->autoSuspend == '1'}checked{/if}/>
                                        Bu siparişte otomatik askıya alma ve silme işlemi yapılabilir.
                                    </li>
                                    <li>
                                        <label>Notlar:</label>
                                        <textarea name="description" rows="5"
                                                  style="width:400px;">{$Order->description}</textarea>
                                    </li>
                                </ol>
                            </fieldset>
                            <p align="right"><input type="submit" value="Siparişi Güncelle" align="right"/></p>
                        </form>
                        <h3>Sipariş Mailleri</h3>
                        <form method="post" class="cmxform" style="width:99%;">
                            <input type="hidden" name="action" value="sendmail">
                            <fieldset>
                                <ol>
                                    <li>
                                        <label>Email:</label>
                                        <select name="templateID">
                                            {foreach from=$order_emails item=e key=templateID}
                                                <option value="{$templateID}">{$e.title}</option>
                                            {/foreach}
                                        </select>
                                    </li>
                                </ol>
                            </fieldset>
                            <p align="right"><input type="submit" value="Gönder" align="right"/></p>
                        </form>
                        <br/>
                        <br/>
                        {literal}
                            <script language="JavaScript">
                                $(document).ready(function () {
                                    $("#dateStart,#dateEnd").datepicker({dateFormat: 'dd-mm-yy'});

                                    $('#never_expires').click(function () {
                                        if (!$(this).is(':checked')) {
                                            $('#dateEnd').show();
                                        } else {
                                            $('#dateEnd').hide();
                                        }
                                    });
                                });
                            </script>
                        {/literal}


                    {elseif $subtab == 'domain'}
                        {include file="orders/domain_details.tpl"}
                    {elseif $subtab == 'actions'}
                        {include file="orders/order_actions.tpl"}
                    {elseif $subtab == 'addons'}
                        {include file="orders/order_details_addons.tpl"}
                    {elseif $subtab == 'bills'}
                        {include file="orders/order_details_bills.tpl"}
                    {elseif $subtab == 'attrs'}
                        {include file="orders/order_details_attrs.tpl"}
                    {/if}

                </td>
            </tr>
        </table>
    </div>

</div> 


