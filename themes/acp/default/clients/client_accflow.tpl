{if $chart}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th width="100">Tarih</th>
                    <th width="50">İşlem</th>
                    <th>Açıklama</th>
                    <th width="80" id="right">Tutar</th>
                    <th width="80" id="right">Tutar ({getCurrencyById id=$smarty.const.MAIN_CUR_ID})</th>
                    <th width="80" id="right">Bakiye</th>
                </tr>
                {foreach from=$chart item=c}
                    <tr {cycle values=",class='alt'"}>
                        <td>{format_date date=$c.timestamp mode=date}</td>
                        <td class="bold {if $c.type == "bill"}red{else}green{/if}">{if $c.type == "bill"}Borç{else}Ödeme{/if}</td>
                        <td>
                            {if true}
                                &nbsp;{$c.description}
                            {else}
                                <a href="{$smarty.server.REQUEST_URI}&update={$c.type}&id={$c.id}">Kur bilgisi eksik,
                                    güncellemek için TIKLAYINIZ</a>
                            {/if}
                        </td>
                        <td id="right"
                            class="bold {if $c.type == "bill"}red{else}green{/if}">{$c.amount} {getCurrencyById id=$c.paycurID}</td>
                        <td id="right" class="bold {if $c.type == "bill"}red{else}green{/if}">
                            {$c.amount2|number_format:2} {getCurrencyById id=$smarty.const.MAIN_CUR_ID}
                        </td>

                        <td id="right"
                            class="bold {if $c.balance < 0}red{else}green{/if}">{$c.balance|number_format:2} {getCurrencyById id=$smarty.const.MAIN_CUR_ID}</td>
                    </tr>
                {/foreach}
                <tr class='alt2'>
                    <td></td>
                    <td id="right" class="bold" colspan="4">Bakiye:</td>
                    <td id="right"
                        class="bold {if $c.balance < 0}redx{else}greenx{/if}">{$c.balance|number_format:2} {getCurrencyById id=$smarty.const.MAIN_CUR_ID}</td>
                </tr>
            </table>
        </div>
    </div>
{else}Hesabınızda şu an finansal bir işlem bulunmuyor{/if}