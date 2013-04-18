{if $Order}
    {include file="orders/order_details.tpl"}
{else}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <form method="post">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th width="16"><input type="checkbox" name="sel"
                                              onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                        <th width="16"></th>
                        <th width="70" scope="col">Sipariş No</th>
                        <th scope="col">Paket Adı</th>
                        <th id="right">Sipariş Tarihi</th>
                        <th id="right">Bitiş Tarihi</th>
                    </tr>
                    {foreach from=$orders item=o}
                        <tr class="{cycle values="first,second"}">
                            <td><input type="checkbox" name="selected[]" class="mcheck" value="{$o.orderID}"></td>
                            <td><img src="{$turl}images/{$icons[$o.status]}" title="##OrderDetails%{$o.status}##"></td>
                            <td id="center">{$o.orderID}</td>
                            <td><a href="?p=311&tab=orders&orderID={$o.orderID}">{$o.title}</a></td>
                            <td id="right">{format_date date=$o.dateAdded mode=datetime}</td>
                            <td id="right">{format_date date=$o.dateEnd}</td>
                        </tr>
                    {/foreach}
                    <tr>
                        <td></td>
                        <td colspan="4">Seçili Siparişleri: <input id="butDelete" type="submit" name="action"
                                                                   value="Sil"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    {include file="paging.tpl"}
    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $('#butDelete').click(function () {
                    var any = false;
                    $('[name*=selected]').each(function () {
                        if ($(this).is(':checked')) any = true;
                    });
                    if (!any) {
                        alert('En az bir sipariş seçmelisiniz');
                        return any;
                    }
                    return confirm('Seçili siparişler SİLİNECEK. Emin misiniz?');
                });
            });
        </script>
    {/literal}

{/if}