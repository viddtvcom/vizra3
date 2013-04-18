<p class="msg_warn">
    - Eklentilerin belli bir servis altında sipariş verilebilmeleri için, servis ile eklentinin aynı kurda olması
    gerekmektedir.
</p>
<form method="post" class="cmxform" style="width:500px;">
    <input type="hidden" name="action" value="addPriceOption">
    <fieldset>
        <ol>
            <li>
                <label>Ödeme Seçeneği</label>
                <input id="price" type="text" name="price" style="width: 60px; text-align: right;"/> {$cursymbol}&nbsp;
                <select name="period">
                    {foreach from=$vars.BILLING_CYCLES item=period}
                        <option value="{$period}">##BILLING_CYCLES_{$period}##</option>
                    {/foreach}
                </select>&nbsp;
                <input type="submit" value="Ekle"/>
            </li>
        </ol>
    </fieldset>
</form>
<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="updatePriceOptions">
    <fieldset>
        <ol>
            <li>
                <label>##Payment Currency##</label>
                {$select_currencies}
            </li>
            {if !$Service->priceOptions}
                <li>
                    <label>Geçerlilik Bitiş</label>
                    <input type="text" name="_expires_a" value="{$Service->_expires_a}"
                           style="width: 50px; text-align: right;"/>
                    <select name="_expires_p">
                        <option value="d" {if $Service->_expires_p == 'd'}selected{/if}>Gün</option>
                        <option value="m" {if $Service->_expires_p == 'm'}selected{/if}>Ay</option>
                        <option value="y" {if $Service->_expires_p == 'y'}selected{/if}>Yıl</option>
                        <option value="never" {if $Service->_expires_p == 'never'}selected{/if}>Hiç Bir Zaman</option>
                    </select> sonra &nbsp;&nbsp;&nbsp; (Sadece ücretsiz ve tek ödemelik siparişlerde geçerlidir)
                </li>
            {/if}
        </ol>
    </fieldset>
    <div class="clear"></div>
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20"></th>
                    <th width="150"></th>
                    <th>Normal Fiyat</th>
                    <th>İndirim</th>
                    <th id="right">Satış Fiyatı</th>
                    <th id="center" width="60">Varsayılan</th>
                </tr>
                <tr {cycle values=',class=alt'}>
                    <td></td>
                    <td>##Setup##</td>
                    <td><input type="text" name="setup" value="{$Service->setup}"
                               style="width: 50px; text-align: right;"/> {$cursymbol}</td>
                    <td><input type="text" name="setup_discount" value="{$Service->setup_discount}"
                               style="width: 50px; text-align: right;"/> {$cursymbol}</td>
                    <td id="right">{$Service->setup-$Service->setup_discount} {$cursymbol}</td>
                    <td></td>
                </tr>
                {foreach from=$Service->priceOptions item=data key=period}
                    <tr {cycle values=',class=alt'}>
                        <td>
                            <a href="?p=116&tab=payment&serviceID={$Service->serviceID}&act=remPrcOpt&period={$period}">
                                <img src="{$turl}images/ico_delete.png">
                            </a>
                        </td>
                        <td>##BILLING_CYCLES_{$period}##</td>
                        <td><input type="text" name="options[{$period}][price]" value="{$data.price}"
                                   style="width: 50px; text-align: right;"/> {$cursymbol}</td>
                        <td><input type="text" name="options[{$period}][discount]" value="{$data.discount}"
                                   style="width: 50px; text-align: right;"/> {$cursymbol}</td>
                        <td id="right">{$data.price-$data.discount} {$cursymbol}</td>
                        <td id="center"><input type="radio" name="default_option" value="{$period}"
                                               {if $data.default=='1'}checked{/if}></td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <br/>

    <p>
        <input type="checkbox" name="update_orders" value="1" id="uo_check"> Bu servisten verilmiş siparişlerin
        ücretlerini güncelleyin
    </p>

    <div id="payment_p" style="display: none; padding-left:40px; padding-top: 10px;">
        <input type="radio" name="update_order_type" value="1" checked="checked">Fiyatı değiştirilmemiş siparişleri
        güncellemek istiyorum (<i>Sipariş özelliklerinden manuel olarak fiyat değişikliği yaptığınız siparişleri
            güncellemez</i>) <br/>
        <input type="radio" name="update_order_type" value="2"> Bu servise ait bütün sipariş ücretlerini güncellemek
        istiyorum<br/>

        <hr>
        <input type="radio" name="update_bill_type" value="none" checked="checked"> Ödenmemiş borç kayıtlarını
        güncellemek istemiyorum<br/>
        <input type="radio" name="update_bill_type" value="last"> Sadece son ödenmemiş borç kaydını güncellemek
        istiyorum<sup>*</sup> <br/>
        <input type="radio" name="update_bill_type" value="all"> Ödenmemiş bütün borç kayıtlarını güncellemek
        istiyorum<sup>*</sup> <br/>
        <sup>*</sup> <i> (Sadece tekrarlanan borç kayıtları güncellenir. Kurulum vb. tek seferlik borç kayıtları
            güncellenmez)</i>
    </div>
    <p align="right"><input type="submit" value="Güncelle"/></p>

</form>

{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('#uo_check').click(function () {
                if ($(this).attr('checked') == true) {
                    if (confirm('Bu servise bağlı bütün sipariş ücretleri güncellenecek. Emin misiniz?')) {
                        $('#payment_p').slideDown();
                    }
                }
                else {
                    $('#payment_p').slideUp();
                }
            });
        });
    </script>
{/literal}
