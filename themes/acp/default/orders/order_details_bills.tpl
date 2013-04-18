{if $addons}
    <script language="JavaScript">
var orderID = {$Order->orderID}
    {literal}$(document).ready(function() {
    $("#oselect").change(function(){
    window.location = '?p=311&tab=orders&subtab=bills&orderID='+orderID+'&boID='+ $(this).val();
    });
    });
        </script>{/literal}
    <div id="selectbox">
        <select id='oselect'>
            <option value="all">Bütün Borçlar</option>
            <option value="{$Order->orderID}"
                    {if $smarty.get.boID == $Order->orderID}selected{/if}>{$Order->title}</option>
            {foreach from=$addons item=title key=orderID}
                <option value="{$orderID}" {if $smarty.get.boID == $orderID}selected{/if}>{$title}</option>
            {/foreach}
        </select>
    </div>
{/if}


<form method="post">
    <input type="hidden" name="action" value="genbill">
    <input type="submit" value="Borç Kaydı Oluştur"
           onclick="return confirm('Borç kaydı oluşturulacak. Emin  misiniz?');">
</form>
<br/>
<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
            <tr>
                <th width="20"></th>
                <th width="20"></th>
                <th>Açıklama</th>
                <th id="right" width="120">Başlangıç</th>
                <th id="right" width="120">Bitiş</th>
                <th id="right" width="120">Son Ödeme Tarihi</th>
                <th id="right" width="90">Miktar</th>
                <th width="20"></th>
                <th width="20"></th>
            </tr>
            {foreach from=$Order->bills item=b}
                <tr {cycle values=",class='alt'"}>
                    <td><img class="stbut" src="{$turl}images/plus.png" style="cursor: pointer;"
                             onclick="$('[id^=bill]').hide(); $('.bill_form').remove(); $('.stbut').attr('src','{$turl}images/plus.png'); $(this).attr('src','{$turl}images/minus.png'); $('#bill_'+{$b.billID}).show().children(':first').load('?p=516&m=compact&billID={$b.billID}');">
                    </td>
                    <td><img src="{$turl}images/{if $b.status == 'paid'}ok{else}stop{/if}.png"></td>
                    <td>{$b.description}</td>
                    <td id='right'>{format_date date=$b.dateStart mode=date}</td>
                    <td id='right'>{format_date date=$b.dateEnd mode=date}</td>
                    <td id='right'>{format_date date=$b.dateDue mode=date}</td>
                    <td id='right'>{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="delbill">
                            <input type="hidden" name="billID" value="{$b.billID}">
                            <input type="image" src="{$turl}images/ico_delete.png"
                                   onclick="return confirm('Borç kaydı sistemden silinecek. Emin  misiniz?');">
                        </form>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="sendbill">
                            <input type="hidden" name="billID" value="{$b.billID}">
                            <input type="image" src="{$turl}images/ico_email.png"
                                   onclick="return confirm('Borç kaydı müşteriye email ile gönderilecek. Emin  misiniz?');">
                        </form>
                    </td>
                </tr>
                <tr style="display:none;" id="bill_{$b.billID}">
                    <td colspan="9" style=" padding-left:30px;"></td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
<br/>