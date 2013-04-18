<h2 class="title1">Servisler Sub Menü</h2>
<ul class="article_2 sidebar_nav_2 br_b_5">
    <li>
        <a href="{$vurl}?p=shop&s=sf&a=list&gID={$main.groupID}">Hosting</a>
        <ul>
            <li><a href="#">Windows</a></li>
            <li><a href="#">Linux</a></li>
        </ul>
    </li>

    <li>
        <a href="{$vurl}?p=shop&s=sf&a=list&gID={$main.groupID}">Reseller</a>
        <ul>
            <li><a href="#">Windows</a></li>
            <li><a href="#">Linux</a></li>
        </ul>
    </li>
</ul>


<br/>


<h2 class="title1">##Nav%ProductsServices##</h2>
<ul class="sidebar_nav">
    {foreach item=main key=key from=$productMenu}
        <li>{if $seo}
                <a href="{$vurl}{$main.seolink}.html">{$main.group_name}</a>
            {else}
                <a href="{$vurl}?p=shop&s=sf&a=list&gID={$main.groupID}">{$main.group_name}</a>
            {/if}
        </li>
    {/foreach}
</ul>


