<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <th width="20"></th>
            <th>Departman</th>
            <th width="150">Email</th>
            <th width="20"></th>
            {foreach from=$deps item=d}
                <tr  {cycle values=',class=alt'}>
                    <td align="center"><a href="?p=176&depID={$d.depID}"><img src="{$turl}images/file-edit.png"></a>
                    </td>
                    <td>{$d.depTitle}</td>
                    <td>{$d.depEmail}</td>
                    <td align="center"><a href="?p=175&act=del&depID={$d.depID}"
                                          onclick="return confirm('Emin misiniz?');"><img
                                    src="{$turl}images/ico_delete.png"></a></td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>