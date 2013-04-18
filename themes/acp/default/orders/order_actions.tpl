<h3>Sipariş Taşıma</h3>
<form method="post" class="cmxform" style="width:99%;">
    <input type="hidden" name="action" value="transfer_order">
    <fieldset>
        <ol>
            <li>
                <label>Yeni Müşteri No:</label>
                <input type="text" name="clientID" style="width:70px;" maxlength="7">
            </li>
            <li>
                <label>Bakiye Transferi:</label>
                <input type="checkbox" name="transfer_funds" value="1"> Bakiyeleri güncelle. (Siparişin ödenmiş tutarını
                eski müşteriden düşer, yeni müşteriye ekler)
            </li>

        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Siparişi Taşı"/></p>
</form>
<br/><br/>

