<ul class="system_messages">
    <li class="blue"><span class="ico"></span><strong class="system_title">
            - Bildirim Bekleyen Ödemeler, müşterinin ödeme kaydını oluşturup henüz bildirim yapmadığı ödemelerdir.<br/>
            - Onay Bekleyen Ödemeler, müşteri tarafından bildiriminin yapıldığı ve onayınızı bekleyen ödemelerdir.
        </strong></li>
</ul>

<div id="filterbox">
    <form method="post" class="cmxform" id="filterform">
        <fieldset style="width: 40%; float: left;">
            <ol class='alt'>
                <li><label>Ödeme No:</label>

                    <input type="text" name="paymentID" class="filter" style="width: 80px;" maxlength="7">
                </li>
                <li><label>Onaylayan</label>
                    <select name="adminID" class="filter" id="adminID">
                        <option value='all' {if $smarty.get.adminID == 'all'}selected{/if}>Hepsi</option>
                        {foreach from=$admins item=a}
                            <option value="{$a.adminID}"
                                    {if $a.adminID  == $smarty.get.adminID }selected{/if}>{$a.adminNick}</option>
                        {/foreach}
                    </select>
                </li>
            </ol>
        </fieldset>
        <fieldset style="width: 50%; float: left;">
            <ol class='alt'>
                <li><label>Durum:</label>
                    <select name="paymentStatus" class="filter">
                        <option value="pending-approval">Onay Bekleyen</option>
                        <option value="pending-payment"
                                {if $smarty.get.paymentStatus == 'pending-payment'}selected{/if}>Bildirim Bekleyen
                        </option>
                        <option value="all" {if $smarty.get.paymentStatus == 'all'}selected{/if}>Bütün Ödemeler</option>
                        <option value="paid" {if $smarty.get.paymentStatus == 'paid'}selected{/if}>Onaylanmış</option>
                    </select>
                </li>
                <li><label>Modül:</label>
                    <select name="moduleID" class="filter">
                        <option value="all">Bütün Modüller</option>
                        {foreach from=$modules item=title key=moduleID}
                            <option value="{$moduleID}"
                                    {if $smarty.get.moduleID == $moduleID }selected{/if}>{$title}</option>
                        {/foreach}
                    </select>
                </li>
                <!--                    <li><label>Sıralama</label>
                    <select name="sort" class="filter">
                            <option value="7" {if $smarty.get.sort == '7'}selected{/if}>Son Ödeme Tarihi Geçmiş</option>
                            <option value="7" {if $smarty.get.sort == '7'}selected{/if}>Son Ödeme Tarihi (Son 1 Hafta)</option>
                            <option value="30" {if $smarty.get.sort == '30'}selected{/if}>Son Ödeme Tarihi (Son 1 Ay)</option>
                            <option value="90" {if $smarty.get.sort == '90'}selected{/if}>Son Ödeme Tarihi (Son 3 Ay)</option>
                    </select>
                    </li>-->
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
                    <th width="16">
                        <input type="checkbox" name="sel"
                               onclick="$('.mcheck').attr('checked',$(this).attr('checked'))">
                    </th>
                    <th width="16"></th>
                    <th width="50" id="center">Ödeme No</th>
                    <th>Müşteri</th>
                    <th>Açıklama</th>
                    <th>Modül</th>
                    <th id="right" width="70">Miktar</th>
                    <th id="right" width="110">Eklenme Tarihi</th>
                </tr>
                {foreach from=$payments item=p}
                    <tr {cycle values=",class='alt'"}>
                        <td>
                            <input type="checkbox" name="selected[]" class="mcheck" value="{$p.paymentID}">
                        </td>
                        <td><img src="{$turl}images/{if $p.paymentStatus == 'paid'}ok{else}stop{/if}.png"></td>
                        <td id="center"><a href="?p=511&paymentID={$p.paymentID}">{$p.paymentID}</a></td>
                        <td>
                            <a href="?p=311&clientID={$p.clientID}">{if $p.clientType == 'individual'}{$p.name}{else}{$p.company}{/if}
                        </td>
                        <td>{$p.description}</td>
                        <td>{$modules[$p.moduleID]}</td>
                        <td id='right'>{$p.amount} {getCurrencyById id=$p.paycurID}</td>
                        <td id='right'>{format_date date=$p.dateAdded mode=datetime}</td>
                    </tr>
                {/foreach}
                <tr>
                    <td></td>
                    <td colspan="2">Seçili Ödemeleri:</td>
                    <td colspan="10">
                        <input id="butDelete" type="submit" name="action" value="Sil">
                    </td>
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
                    alert('En az bir ödeme seçmelisiniz');
                    return any;
                }
                $('#send_mail').val(confirm('Müşteri email göndermek istiyor musunuz?'));
                return confirm('Seçili ödemeler SİLİNECEK. Emin misiniz?');
            });
        });
    </script>
{/literal}

