<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th>Kupon</th>
                <th>Oran</th>
                <th>Bitiş</th>
                <th width="20"></th>
            </tr>
            {foreach from=$coupons item=c}
                <tr  {cycle values=',class=alt'}>
                    <td align="center"><a href="?p=178&couponID={$c.couponID}"><img
                                    src="{$turl}images/file-edit.png"></a></td>
                    <td>{$c.code}</td>
                    <td>% {$c.amount}</td>
                    <td>{$c.dateExpires|formatDate}</td>
                    <td align="center"><a href="?p=177&act=del&couponID={$c.couponID}"
                                          onclick="return confirm('Emin misiniz?');"><img
                                    src="{$turl}images/ico_delete.png"></a></td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>