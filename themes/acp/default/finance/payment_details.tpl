<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="update" id="action">
    <fieldset>
        <ol>
            <li>
                <label>Ödeme No:</label>
                {formatId id=$Payment->paymentID}
            </li>
            <li class="odd">
                <label>Müşteri:</label>
                <a href="?p=311&clientID={$Client->clientID}">{$Client->name}</a>
            </li>
            <li>
                <label>Durum:</label>
                {if $Payment->paymentStatus == 'paid'}
                    <img src="{$turl}images/ok.png" id="middle">
                    Ödeme Onaylandı
                {else}
                    <select name="paymentStatus" class="filter">
                        <option value="pending-approval" {if $Payment->paymentStatus == 'pending-payment'}selected{/if}>
                            Onay Bekliyor
                        </option>
                        <option value="pending-payment" {if $Payment->paymentStatus == 'pending-payment'}selected{/if}>
                            Bildirim Bekliyor
                        </option>
                    </select>
                    <input type="submit" value="Ödemeyi Onayla"
                           onclick="var resp = confirm('Emin misiniz?');if (resp) $('#action').val('approve'); else return false;">
                {/if}
            </li>
            <li class="odd">
                <label>Modül:</label>
                <select name="moduleID">
                    <option value=''>Yok</option>
                    {foreach from=$modules item=title key=moduleID}
                        <option value="{$moduleID}" {if $moduleID == $Payment->moduleID}selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label>Miktar:</label>
                <input name="amount" value="{$Payment->amount}" size="8"> {getCurrencyById id=$Payment->paycurID}
            </li>
            <li>
                <label>Açıklama:</label>
                <input name="description" value="{$Payment->description}" style="width: 300px;">
            </li>

            <li class="odd">
                <label>Eklenme Tarihi:</label>
                {format_date date=$Payment->dateAdded mode=datetime}
            </li>
            {if $Payment->paymentStatus == 'paid'}
                <li>
                    <label>Onaylandığı Tarih:</label>
                    {format_date date=$Payment->datePayed}
                </li>
                <li>
                    <label>Onaylayan:</label>
                    {$Payment->adminID|getAdminNickFromAdminId}
                </li>
            {/if}
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Güncelle" align="right"/></p>
</form>

<h3>Bu ödemeye bağlı kuyruk işlemleri:</h3>
<table border="0" cellspacing="0" cellpadding="0" id="datalist" width="100%">
    <tr>
        <th width="20"></th>
        <th width="120">Durum</th>
        <th width="80">Sipariş No</th>
        <th>Komut</th>
        <th>Sonuç</th>
        <th>İşlem</th>
        <th width="100">Tarih</th>
        <th width="20"></th>
    </tr>
    {foreach from=$jobs item=j key=jobID}
        <tr {cycle values=',class=alt'}>
            <td>
                {if $j.status == 'completed'}
                    <img src="{$turl}/images/ok.png">
                {elseif $j.status == 'inprocess'}
                    <img src="{$turl}/images/loading.gif">
                {elseif $j.status == 'error'}
                    <img src="{$turl}/images/stop2.png">
                {elseif $j.status == 'pending-payment'}
                    <img src="{$turl}/images/dollar.png">
                {elseif $j.status == 'pending'}
                    <img src="{$turl}/images/hourglass.png">
                {/if}
            </td>
            <td>##{$j.status}##</td>
            <td>{if $j.orderID}<a href='?p=411&orderID={$j.orderID}'>{$j.orderID}</a>{else}-{/if}</td>
            <td><strong>{$j.job}</strong> (
                {if $j.job == 'sendMail'}
                {$j.params.subject} &raquo; {$j.params.to}
                {elseif $j.job=='setOrderStatus'}
                {$j.params.orderStatus}
                {else}
                {$j.params.cmd}
                {/if})
            </td>
            <td>{$j.result}</td>
            <td>{if $j.status != 'completed' && $j.status != 'inprocess'}
                    <a href='{$vurl}?p=queue&jobID={$jobID}&code={$j.code}' target='_blank'
                       onclick='return confirm("İşlem başlatılacak. Emin misiniz?");'>
                        {if $j.status == 'pending' || $j.status == 'pending-payment'}
                            Başlat
                        {elseif $j.status == 'error'}
                            Tekrar Dene
                        {/if}
                    </a>
                {/if}
            </td>
            <td>{$j.dateAdded|formatDate:datetime:short}</td>
            <td><a href="?p=511&paymentID={$Payment->paymentID}&act=delQJob&jobID={$jobID}"
                   onclick='return confirm("İşlem silinecek. Emin misiniz?");'>
                    <img src="{$turl}images/ico_delete.png">
                </a>
            </td>
        </tr>
    {/foreach}
</table>


