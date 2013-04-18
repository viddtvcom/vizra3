<script src="{$vurl}js/jquery-ui.min.js" type="text/javascript"></script>
<div class="bill_form">
    <form method="post" class="cmxform" id='bill_form' action="?p=516&billID={$OrderBill->billID}">
        <input type="hidden" name="action" value="update" id="action">
        <fieldset>
            <ol>
                <li>
                    <label>Borç No:</label>
                    {formatId id=$OrderBill->billID}
                </li>
                <li>
                    <label>Sipariş:</label>
                    {if $OrderBill->orderID}
                        <a href="?p=311&tab=orders&orderID={$Order->orderID}">{$Order->title}</a>
                    {else}
                        Genel Borç Kaydı
                    {/if}
                </li>
                <li>
                    <label>Müşteri:</label>
                    <a href="?p=311&clientID={$Client->clientID}">{$Client->name}</a>
                </li>
                <li>
                    <label>Durum:</label>
                    {if $OrderBill->previous_unpaid}
                        ##{$OrderBill->status|ucfirst}## (Öncesinde ödenmemiş borç kaydı bulunduğu için, borç durumunu güncelleyemezsiniz)
                    {else}
                        <select name="status" id="status">
                            <option value="paid" {if $OrderBill->status == 'paid'}selected{/if}>##Paid##</option>
                            <option value="unpaid" {if $OrderBill->status == 'unpaid'}selected{/if}>##Unpaid##</option>
                        </select>
                    {/if}
                </li>
                {if $OrderBill->status == 'unpaid'}
                    <li class="hidden">
                        <label>Sipariş Bitiş Tarihi:</label>
                        <input type="checkbox" name="updateOrderDateEnd" value="1" checked="checked"> Sipariş bitiş
                        tarihini güncelle
                    </li>
                {/if}
                <li>
                    <label>Miktar:</label>
                    <input name="amount" value="{$OrderBill->amount}"
                           size="8"> {getCurrencyById id=$OrderBill->paycurID}
                </li>
                <li>
                    <label>Açıklama:</label>
                    <input name="description" value="{$OrderBill->description}" style="width:300px;">
                </li>

                <li>
                    <label>Son Ödeme Tarihi:</label>
                    <input type="text" name="dateDue" class="datepicker" style="width:80px"
                           value="{$OrderBill->dateDue}">
                </li>
                <li>
                    <label>Dönem Baş/Bit:</label>
                    <input type="text" name="dateStart" class="datepicker" style="width:80px"
                           value="{$OrderBill->dateStart}"> -
                    <input type="text" name="dateEnd" class="datepicker" style="width:80px"
                           value="{$OrderBill->dateEnd}">
                </li>
                {if $OrderBill->status == 'paid'}
                    <li>
                        <label>Ödendiği Tarih:</label>
                        {format_date date=$OrderBill->datePayed}
                    </li>
                    {if $OrderBill->paymentID}
                        <li>
                            <label>Onaylandığı Ödeme:</label>
                            <a href="?p=511&paymentID={$OrderBill->paymentID}">{formatId id=$OrderBill->paymentID}</a>
                        </li>
                    {/if}
                {/if}
            </ol>
        </fieldset>
        <p align="right"><span id='st_img'></span>&nbsp;&nbsp;<input type="submit" value="##Update##" align="right"
                                                                     onclick="$('#st_img').html('<img src='+turl+'images/loading.gif>'); return true;"/>
        </p>
    </form>
</div>

<script language="JavaScript">
    var billID = '{$OrderBill->billID}';
    {literal}
    $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});

    $('#bill_form').ajaxForm({
        dataType: 'json',
        success: processJson
    });
    function processJson(data) {
        if (data.st) {
            $('#st_img').html('<img id=ok_img src=' + turl + 'images/ok.png>');
            $('#ok_img').fadeOut(5000);
        } else {
            $('#st_img').html('<img src=' + turl + 'images/stop2.png>');
        }
    }
    $('#status').change(function () {
        if ($(this).val() == 'paid')
            $(".hidden").show();
        else
            $(".hidden").hide();
    });
    $('.hidden').hide();

    function mark_as_paid() {
        var resp = confirm('Emin misinizz?');
        if (resp) {
            $('#action').val('approve');
            $('#bill_form').submit();
            if ($('#bill_' + billID).length) {
                $('#bill_' + billID).children(':first').load('?p=516&m=compact&billID=' + billID);
            } else {
                window.location = '?p=516&billID=' + billID;
            }
        } else {
            return false;
        }
    }

    doParentIframe();
</script>{/literal}


