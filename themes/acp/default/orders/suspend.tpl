<div id="inner_content">
    <div id="filterbox">
        {literal}
        <script language="JavaScript">
        $(document).ready(function () {
            $("#groupID").change(function () {
                var selID = $(this).val();
                if (selID == '') {
                    $("#serviceID").html('<option value="all">Bütün Servisler</option>');
                } else {
                    loadselect(selID);
                }
            });

            function loadselect(selID, selected) {
                $.post('ajax.php', {groupID: selID, action: 'getServices'},
                        function (data) {
                            if (!data) {
                                $("#serviceID").html('<option value="all">Bütün Servisler</option>');
                                return;
                            }
                            var options = '<option value="all">Bütün Servisler</option>';
                            for (var i = 0; i < data.length; i++) {
                                options += '<option value="' + data[i].serviceID + '"';
                                if (data[i].serviceID == selected) options += ' selected';
                                options += '>' + data[i].service_name + '</option>';
                            }
                            $("#serviceID").html(options);
                        }, 'json');
            }

            if ($("#groupID").val() != '') {
                loadselect($("#groupID").val(), '{/literal}{$smarty.get.serviceID}{literal}');
            }

            $('#butDelete').click(function () {
                var any = false;
                $('[name*=selected]').each(function () {
                    if ($(this).is(':checked')) any = true;
                });
                if (!any) {
                    alert('En az bir sipariş seçmelisiniz');
                    return any;
                }
                $('#send_mail').val(confirm('Müşteri email göndermek istiyor musunuz?'));
                return confirm('Seçili siparişler SİLİNECEK. Emin misiniz?');
            });
        });
        </script>{/literal}
        <form method="post" class="cmxform" id="filterform">
            <fieldset style="width: 40%; float: left;">
                <ol class='alt'>
                    <li><label>Sipariş No:</label> <input type="text" name="orderID" class="filter" style="width: 80px;"
                                                          maxlength="7"></li>
                    <li><label>Durum:</label>
                        <select name="status" class="filter">
                            <option value="all">Bütün Siparişler</option>
                            {foreach from=$vars.ORDER_STATUS_TYPES item=ost}
                                <option value="{$ost}" {if $smarty.get.status == $ost}selected{/if}>
                                    ##OrderDetails%{$ost}##
                                </option>
                            {/foreach}
                        </select>
                    </li>
                    <li><label>Paket Adı:</label> <input type="text" name="title" class="filter" style="width: 150px;"
                                                         value="{$smarty.get.title}"></li>
                </ol>
            </fieldset>
            <fieldset style="width: 50%; float: left;">
                <ol class='alt'>
                    <li><label>Servis Grubu:</label>
                        <select name="groupID" class="filter" id="groupID">
                            <option value="all">Bütün Gruplar</option>
                            {foreach from=$groups item=g}
                                <option value="{$g.groupID}"
                                        {if $smarty.get.groupID == $g.groupID }selected{/if}>{$g.group_name}</option>
                            {/foreach}
                        </select>
                    </li>
                    <li id='subcatdiv'><label>Servisler:</label>
                        <select name="serviceID" class="filter" id="serviceID">
                            <option value="all">Bütün Servisler</option>
                        </select>
                    </li>
                    <li><label>Bitiş</label>
                        <select name="ending" class="filter">
                            <option value="all" {if $smarty.get.ending == 'all'}selected{/if}>Hepsi</option>
                            <option value="expired" {if $smarty.get.ending == 'expired'}selected{/if}>Süresi bitmiş
                            </option>
                            <option value="7" {if $smarty.get.ending == '7'}selected{/if}>1 hafta içinde bitecek
                            </option>
                            <option value="30" {if $smarty.get.ending == '30'}selected{/if}>1 ay içinde bitecek</option>
                        </select>
                    </li>
                </ol>
            </fieldset>
            <div style="width: 10%; float: left; text-align: center; padding-top: 20px;">
                <input type="image" src="{$turl}images/search48.png" id="butfilter">
            </div>
        </form>
        <div class="clear"></div>
    </div>


    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <form method="post">
                <input type="hidden" name="send_mail" id="send_mail">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th width="16"><input type="checkbox" name="sel"
                                              onclick="$('.mcheck').attr('checked',$(this).attr('checked'))"></th>
                        <th width="16"></th>
                        <th width="70" id="center">Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Paket Adı</th>
                        <th id="right" width="140">Sipariş Bitiş</th>
                        <th id="right" width="120">Son Ödeme</th>
                    </tr>
                    {foreach from=$orders item=o}
                        <tr class="{cycle values="first,second"}">
                            <td><input type="checkbox" name="selected[]" class="mcheck" value="{$o.orderID}"></td>
                            <td><img src="{$turl}images/{$icons[$o.status]}" title="##OrderDetails%{$o.status}##"></td>
                            <td id="center"><a
                                        href="?p=311&tab=orders&orderID={$o.orderID}&subtab=attrs">{$o.orderID}</a></td>
                            <td>
                                <a href="?p=311&clientID={$o.clientID}">{if $o.clientType == 'individual'}{$o.name}{else}{$o.company}{/if}
                                    ({$o.balance})</a></td>
                            <td><a href="?p=311&tab=orders&orderID={$o.orderID}">{$o.title}</a></td>
                            <td id="right">{format_date date=$o.dateEnd}</td>
                            <td id="right">{format_date date=$o.dateDue}</td>
                        </tr>
                    {/foreach}
                    <tr>
                        <td></td>
                        <td colspan="2">Seçili Siparişleri:</td>
                        <td colspan="10">
                            <input id="butSuspend" type="submit" name="action" value="Askıya Al">
                            <input id="butTerminate" type="submit" name="action" value="Sunucudan Sil">
                            {*
                                          *}
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    {include file="paging.tpl"}

</div>
