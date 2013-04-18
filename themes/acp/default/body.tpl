{k}    {if $smarty.get.m == 'compact'}
    {include file=$tpl_content}
{else}
    <div class="iframe_sidebar">
        {include file="side_menu.tpl"}
    </div>
    <div class="iframe_content">
        <div class="content_header">
            <div id="breadcrumbs">
                {if $page_icon != ''}
                    <img src="{$turl}images/{$page_icon}">
                {/if}
                {$page_title}
            </div>
            <ul id="action_menu">
                {if count($action_menu)}
                    {foreach from=$action_menu item=link key=k}
                        <li {if $k+1 == count($action_menu)}class="last"{/if}><a href="{$link.1}">{$link.0}</a></li>
                    {/foreach}
                {/if}
            </ul>
            <div class="clear"></div>
        </div>
        {include file="messages.tpl"}
        {include file=$content}
    </div>
    {if $Admin->settings.staticProbesColumn == '1' && $right_probes}
        <div class="rightblock" style="float: left;">
            {include file="probes.tpl"}
        </div>
    {/if}
{/if}

<div class="clear"></div>