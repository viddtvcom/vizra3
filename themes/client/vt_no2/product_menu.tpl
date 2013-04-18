<h2 class="title200" style="margin-bottom:10px;">##Nav%ProductsServices##</h2>
<ul class="right_nav">
    {foreach item=main key=key from=$productMenu}
        <li>{if $seo}
                <a href="{$vurl}{$main.seolink}.html">{$main.group_name}</a>
            {else}
                <a href="{$vurl}?p=shop&s=sf&a=list&gID={$main.groupID}">{$main.group_name}</a>
            {/if}
        </li>
    {/foreach}
</ul>
