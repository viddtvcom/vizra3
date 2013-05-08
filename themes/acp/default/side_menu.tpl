<div id="sidebar_menu">
    <ul>
        {foreach from=$side_menu item=p key=k}
            <li{if !$k} class='first'{/if}><a href="?p={$p.pageID}">##page_{$p.pageID}##</a></li>
        {/foreach}
    </ul>
</div>
            