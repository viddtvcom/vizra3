<div id="filterbox">
    <form method="post" class="cmxform" id="filterform">
        <fieldset style="width: 40%; float: left;">
            <ol class='alt'>
                <li><label>Sipariş No:</label> <input type="text" name="orderID" class="filter" style="width: 80px;"
                                                      maxlength="7"></li>
                <li><label>Borç No:</label> <input type="text" name="billID" class="filter" style="width: 80px;"
                                                   maxlength="7"></li>
                <li><label>Açıklama:</label> <input type="text" name="description" class="filter" style="width: 150px;"
                                                    maxlength="50"></li>
            </ol>
        </fieldset>
        <fieldset style="width: 50%; float: left;">
            <ol class='alt'>
                <li><label>Durum:</label>
                    <select name="bstatus" class="filter">
                        <option value="unpaid" {if $smarty.get.bstatus == 'unpaid'}selected{/if}>Ödenmemiş Borçlar
                        </option>
                        <option value="all" {if $smarty.get.bstatus == 'all'}selected{/if}>Bütün Borçlar</option>
                        <option value="paid" {if $smarty.get.bstatus == 'paid'}selected{/if}>Ödenmiş Borçlar</option>
                    </select>
                </li>
                <li><label>Sipariş Durumu:</label>
                    <select name="ostatus" class="filter">
                        <option value="active">Aktif Siparişler</option>
                        <option value="all" {if $smarty.get.ostatus == 'all'}selected{/if}>Bütün Siparişler</option>
                        <option value="suspended" {if $smarty.get.ostatus == 'suspended'}selected{/if}>Askıda Olanlar
                        </option>
                    </select>
                </li>
                <li><label>Son Ödeme Tarihi</label>
                    <select name="sort" class="filter">
                        <option value="0" {if $smarty.get.sort == '0'}selected{/if}>Hepsi</option>
                        <option value="1" {if $smarty.get.sort == '1'}selected{/if}>Geçmiş</option>
                        <option value="7" {if $smarty.get.sort == '7'}selected{/if}>1 hafta içinde geçecek</option>
                        <option value="30" {if $smarty.get.sort == '30'}selected{/if}>1 ay içinde geçecek</option>
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
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th width="70" id="center">Borç No</th>
                <th>Sipariş</th>
                <th>Müşteri</th>
                <th id="right" width="80">Miktar</th>
                <th id="right" width="120">Son Ödeme Tarihi</th>
            </tr>
            {foreach from=$bills item=b}
                <tr {cycle values=",class='alt'"}>
                    <td><img src="{$turl}images/{if $b.status == 'paid'}check{else}stop{/if}.png"></td>
                    <td id="center"><a href="?p=516&billID={$b.billID}">{$b.billID}</a></td>
                    <td>
                        {if $b.orderID}
                        <img src="{$turl}images/{$icons[$b.orderStatus]}" id='middle' width="10"> <a
                                href="?p=411&orderID={$b.orderID}">{$b.orderTitle}
                            {else}
                            {$b.description}
                            {/if}
                    </td>
                    <td>
                        <a href="?p=311&clientID={$b.clientID}">{if $b.clientType == 'individual'}{$b.name}{else}{$b.company}{/if}
                    </td>
                    <td id='right'>{$b.amount} {getCurrencyById id=$b.paycurID}</td>
                    <td id='right'>{format_date date=$b.dateDue mode=date}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
{include file="paging.tpl"}
  



