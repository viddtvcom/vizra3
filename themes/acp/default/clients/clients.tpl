<div id="inner_content">
    <div id="filterbox">
        <form method="post" class="cmxform" id="filterform">
            <fieldset style="width: 40%; float: left;">
                <ol class='alt'>
                    <li><label>Müşteri No:</label>

                        <input type="text" name="clientID" class="filter" style="width: 80px;" maxlength="7">
                    </li>
                    <li><label>İsim / Email</label>

                        <input type="text" name="name_email" class="filter" style="width: 150px;"
                               value="{$smarty.get.name_email}">
                    </li>
                </ol>
            </fieldset>
            <fieldset style="width: 50%; float: left;">
                <ol class='alt'>
                    <li><label>Durum:</label>
                        <select name="status" class="filter">
                            <option value="all">Bütün Müşteriler</option>
                            {foreach from=$vars.CLIENT_STATUS_TYPES item=ost}
                                <option value="{$ost}" {if $smarty.get.status == $ost}selected{/if}>
                                    ##{$ost}##
                                </option>
                            {/foreach}
                        </select>
                    </li>
                    <li><label>Sırala:</label>
                        <select name="sort" class="filter">
                            <option value="">Kayıt Tarihi</option>
                            <option value="balance" {if $smarty.get.sort == 'balance'}selected{/if}>Bakiye</option>
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
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="20"></th>
                        <th width="60">#</th>
                        <th>Müşteri</th>
                        <th id="right" width="100">Kayıt Tarihi</th>
                        <th width="60">Bakiye</th>
                        <th width="20"></th>
                    </tr>
                    {foreach from=$clients item=c}
                        <tr class="{cycle values="first,second"}">
                            <td><img src="{$turl}/images/status_{$c.status}.png" width="13"></td>
                            <td>&nbsp;<a href="?p=311&clientID={$c.clientID}">{$c.clientID}</a></td>
                            <td>
                                <a href="?p=311&clientID={$c.clientID}">{if $c.type == 'corporate'}{$c.company}{else}{$c.name}{/if}</a>
                            </td>
                            <td id="right">{format_date date=$c.dateAdded}</td>
                            <td>{$c.balance} TL</td>
                            <td>
                                <a href="?p=310&act=delClient&clientID={$c.clientID}"
                                   onclick='return confirm("Bu müşteriye ait bütün bilgileri silenecek. Emin misiniz?");'>
                                    <img src="{$turl}images/ico_delete.png">
                                </a></td>
                        </tr>
                    {/foreach}
                </table>
            </form>
        </div>
    </div>
    {include file="paging.tpl"}


</div>