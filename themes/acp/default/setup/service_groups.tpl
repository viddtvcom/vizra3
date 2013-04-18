<div id="inner_content">
    {foreach from=$groups item=g key=groupID}
        <h2><img src="{$turl}images/led_{if $g.status == 'active'}green{else}white{/if}.png"> <a
                    href="?p=117&act=group_details&groupID={$g.groupID}">{$g.group_name}</a></h2>
        <table border="0" id="datalist" cellpadding="0" cellspacing="0" style="margin-left:30px; width: 500px;">
            {foreach from=$g.childs item=g key=groupID}
                <tr>
                    <td width="20"><img src="{$turl}images/led_{if $g.status == 'active'}green{else}white{/if}.png">
                    </td>
                    <td><a href="?p=117&act=group_details&groupID={$g.groupID}">{$g.group_name}</a></td>
                </tr>
            {/foreach}
        </table>
    {/foreach}
</div>
