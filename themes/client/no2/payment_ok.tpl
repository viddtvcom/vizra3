<h2 class="title2">##Payment%OrderResult##</h2>
<div class="article">

    <div style="line-height: 180%; font-size: 1.2em;">
        <br/>
        Tebrikler!, Ödeme işleminiz başarı ile tamamlandı! <br/><br/>

        Kartınızdan çekilen toplam tutar: {$parms.amount} {getCurrencyById id=$parms.paycurID}<br/>
        Ödemenizin sistemimizdeki kayıt numarası: {$parms.paymentID}<br/><br/>

        {if $parms.action == 'addFunds'}
            Kartınızdan çekilen tutar hesabınıza artı bakiye olarak eklenmiştir.
        {elseif $parms.action == 'renewOrder'}
            {$parms.orderID} nolu siparişiniz yenilenmiştir.
            <a href="{$vurl}?p=user&s=orders&a=viewOrder&oID={$parms.orderID}">Sipariş detaylarınızı görüntülemek için
                tıklayınız</a>
        {elseif $parms.action == 'checkout'}
            Satın aldığınız hizmetler sistemimiz tarafından işleme alınmıştır. Hesap açılışlarınız tamamlandığı zaman detayları size email ile göndereceğiz.
        {/if}
        <br/><br/>
        Teşekkür ederiz.
    </div>

</div>