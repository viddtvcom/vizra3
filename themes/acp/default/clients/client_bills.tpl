<div class="table_wrapper">
    <div class="table_wrapper_inner">

        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th width="20"></th>
                <th>Sipariş</th>
                <th id="right">Dönem</th>
                <th id="right">Son Ödeme</th>
                <th id="right" width="100">Miktar</th>
                <th width="20"></th>
                <th width="20"></th>
            </tr>
            {foreach from=$bills item=b}
                <form method="post">
                    <tr class="{cycle values="first,second"}">
                        <td><a href="#" class="stbut" rel="{$b.billID}"><img src="{$turl}images/plus.png"
                                                                             style="cursor: pointer;" class="stimg"></a>
                        </td>
                        <td><img src="{$turl}images/{if $b.status == 'paid'}ok{else}stop{/if}.png"></td>
                        <td>{if $b.orderID}
                                <a href="?p=311&tab=orders&orderID={$b.orderID}">{$b.orderTitle}</a>
                            {else}
                                {$b.description}
                            {/if}
                        </td>
                        <td id='right'>{format_date date=$b.dateStart mode=date}
                            - {format_date date=$b.dateEnd mode=date}</td>
                        <td id='right'>{format_date date=$b.dateDue mode=date}</td>
                        <td id='right'>{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                        <td>
                            <input type="hidden" name="action" value="delbill">
                            <input type="hidden" name="billID" value="{$b.billID}">
                            <input type="image" src="{$turl}images/ico_delete.png"
                                   onclick="return confirm('Borç kaydı sistemden silinecek. Emin  misiniz?');">

                        </td>
                        <td>
                            <img class="but_send" src="{$turl}images/ico_email.png" rel="{$b.billID}">
                        </td>
                    </tr>
                    <tr style="display:none;" id="bill_{$b.billID}">
                        <td colspan="8" style=" padding-left:30px;"></td>
                    </tr>
                </form>
            {/foreach}
        </table>

    </div>
</div>
{include file="paging.tpl"}

{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('.stbut').click(function () {
                var billID = $(this).attr('rel');
                var init = $(this).children(':first').attr('rel');
                $('[id^=bill]').hide();
                $('.bill_form').remove();
                $('.stimg').attr('src', turl + 'images/plus.png');
                if (init == 'open') {
                    $(this).children(':first').attr('rel', '');
                } else {
                    $('.stimg').attr('rel', '');
                    $(this).children(':first').attr('src', turl + 'images/minus.png').attr('rel', 'open');
                    $('#bill_' + billID).show().children(':first').load('?p=516&m=compact&billID=' + billID);
                }
                return false;
            });

            $('.but_send').click(function () {
                if (!confirm('Borç kaydı müşteriye email ile gönderilecek. Emin  misiniz?')) return false;
                var billID = $(this).attr('rel');
                $.post("ajax.php", { action: "sendBillToClient", billID: billID },
                        function (data) {
                            raise_msg(billID + ' nolu borç kaydı gönderildi', 'green');
                        }, "json");
            });
        });
    </script>
{/literal}



