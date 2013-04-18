<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="120">Eklenme</th>
                <th>Başlık</th>
                <th>Ekleyen</th>
                <th width="20"></th>
            </tr>
            {foreach from=$recs item=r}
                <tr>
                    <td>{$r.dateAdded|formatDate:datetime}</td>
                    <td><a href="?p=225&act=edit&recID={$r.recID}">{$r.title}</a></td>
                    <td>{$r.adminName}</td>
                    <td><a href="?p=225&act=del&recID={$r.recID}"
                           onclick="return confirm('Duyuru silinecek. Emin misiniz?');">
                            <img src="{$turl}images/ico_delete.png">
                        </a></td>
                </tr>
            {/foreach}
        </table>

    </div>
</div>