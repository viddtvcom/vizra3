{if $Order}
    {include file="orders/order_details.tpl"}
{else}
    <div id="filterbox">
        <form method="post" class="cmxform" id="filterform">
            <fieldset style="width: 40%; float: left;">
                <ol class='alt'>
                    <li><label>Durum:</label>
                        <select name="status" class="filter">
                            <option value="all">Bütün Siparişler</option>
                            <option value="valid" {if $smarty.get.status == 'valid'}selected{/if}>Aktif ve Askıda
                            </option>
                            {foreach from=$vars.ORDER_STATUS_TYPES item=ost}
                                <option value="{$ost}" {if $smarty.get.status == $ost}selected{/if}>
                                    ##OrderDetails%{$ost}##
                                </option>
                            {/foreach}
                        </select>
                    </li>
                </ol>
            </fieldset>
            <fieldset style="width: 50%; float: left;">
                <ol class='alt'>
                    <li><label>Paket Adı:</label> <input type="text" name="title" class="filter" style="width: 150px;"
                                                         value="{$smarty.get.title}"></li>
                </ol>
            </fieldset>
            <div style="width: 10%; float: left; text-align: center; padding-top: 0px;">
                <input type="image" src="{$turl}images/search48.png" id="butfilter" style="width: 24px; height: 24px;">
            </div>
        </form>
        <div class="clear"></div>
    </div>
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
                            <td id="center"><a
                                        href="?p=311&tab=orders&orderID={$o.orderID}&subtab=attrs">{$o.orderID}</a></td>
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