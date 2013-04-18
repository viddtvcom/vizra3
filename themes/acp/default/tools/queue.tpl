<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <th width="20"></th>
            <th width="120">Durum</th>
            <th width="80">Sipariş No</th>
            <th width="80">Ödeme No</th>
            <th>Komut</th>
            <th>Sonuç</th>
            <th>İşlem</th>
            <th width="100">Tarih</th>
            <th width="20"></th>
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
                        {elseif $j.status == 'pending' || $j.status == 'pending-cron' || $j.status == 'scheduled'}
                            <img src="{$turl}/images/hourglass.png">
                        {/if}
                    </td>
                    <td>##{$j.status}##</td>
                    <td>{if $j.orderID}<a href='?p=411&orderID={$j.orderID}'>{$j.orderID}</a>{else}-{/if}</td>
                    <td>{if $j.paymentID}<a href='?p=511&paymentID={$j.paymentID}'>{$j.paymentID}</a>{else}-{/if}</td>
                    <td><strong>{$j.job}</strong> (
                        {if $j.job == 'sendmail'}
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
                                {if $j.status == 'pending' || $j.status == 'pending-payment' || $j.status == 'scheduled'}
                                    Başlat
                                {elseif $j.status == 'error' || $j.status == 'inprocess' }
                                    Tekrar Dene
                                {/if}
                            </a>
                        {/if}
                    </td>
                    <td>{if $j.dateFire > 0} {$j.dateFire|formatDate:datetime:short}{else}{$j.dateAdded|formatDate:datetime:short}{/if}</td>
                    <td><a href="?p=615&act=delQJob&jobID={$jobID}"
                           onclick='return confirm("İşlem silinecek. Emin misiniz?");'>
                            <img src="{$turl}images/ico_delete.png">
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>