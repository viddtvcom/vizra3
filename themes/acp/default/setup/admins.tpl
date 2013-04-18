<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="16" scope="col">&nbsp;</th>
                <th>Admin</th>
                <th width="130" align="right">Son Login</th>
                <th width="130" align="right">Son Login IP</th>
                <th width="20"></th>
            </tr>
            {foreach from=$admins item=a}
                <tr  {cycle values=',class=alt'}>
                    <td><img src="{$turl}images/led_{if $a.status == 'active'}green{else}white{/if}.png" width="13">
                    </td>
                    <td><a href="?p=111&adminID={$a.adminID}">{$a.adminName}</a></td>
                    <td align="right">{format_date date=$a.dateLogin mode=datetime}</td>
                    <td align="right">{$a.ipLogin}</td>
                    <td align="center"><a href="?p=110&act=del&adminID={$a.adminID}"
                                          onclick="return confirm('Emin misiniz?');"><img
                                    src="{$turl}images/ico_delete.png"></a></td>

                </tr>
            {/foreach}
        </table>
    </div>
</div>