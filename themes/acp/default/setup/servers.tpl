<div class="table_wrapper">
    <div class="table_wrapper_inner">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th width="20"></th>
                <th scope="col">Sunucu</th>
                <th width="20"></th>
            </tr>
            {foreach from=$servers item=s}
                <tr {cycle values=',class=alt'}>
                    <td><img src="{$turl}images/led_{if $s.status == 'active'}green{else}white{/if}.png" width="13">
                    </td>
                    <td><a href="?p=125&act=server_details&serverID={$s.serverID}">{$s.serverName}</a></td>
                    <td align="center"><a href="?p=125&act=del&serverID={$s.serverID}"
                                          onclick="return confirm('Emin misiniz?');"><img
                                    src="{$turl}images/ico_delete.png"></a></td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>