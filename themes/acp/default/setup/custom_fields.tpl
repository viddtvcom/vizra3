<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th scope="col">Özellik</th>
                <th width="150">Tip</th>
                <th width="20">&nbsp;</th>
            </tr>
            {foreach from=$attrs item=a}
                <tr {cycle values=',class=alt'}>
                    <td><a href="?p=180&act=details&attrID={$a.attrID}">{$a.label}</a></td>
                    <td>Müşteri Bilgisi</td>
                    <td>
                        <a href="index.php?p=180&act=del&attrID={$a.attrID}"
                           onclick="return confirm('Bu özellik bütün müşterilerden silinecek. Emin misiniz?');">
                            <img src="{$turl}images/ico_delete.png">
                        </a>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
