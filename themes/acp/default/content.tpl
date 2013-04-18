{if $smarty.get.m != 'compact'}
    <div class="inner">
        <div class="section">
            <div class="title_wrapper">
                <span class="title_wrapper_top"></span>

                <div class="title_wrapper_inner">
                    <span class="title_wrapper_middle"></span>

                    <div class="title_wrapper_content">
                        <h2>{if $page_icon != ''}
                                <img src="{$turl}images/{$page_icon}">
                            {/if}{$page_title}
                        </h2>
                        {if $tpl_tabmenu}{include file=$tpl_tabmenu}{/if}
                        {if count($action_menu)}
                            <ul class="section_menu section_nav right">
                                {foreach from=$action_menu item=link key=k}
                                    <li>
                                        <a href="{$link.1}">
                                            <span class="l"><span></span></span><span
                                                    class="m"><em>{$link.0}</em><span></span></span><span
                                                    class="r"><span></span></span>
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                    </div>
                </div>
                <span class="title_wrapper_bottom"></span>
            </div>
            <div class="section_content">
                <span class="section_content_top"></span>

                <div class="section_content_inner">
                    {include file="messages.tpl"}
                    {include file=$tpl_content}
                    <br/><br/><br/><br/><br/>
                </div>
                <span class="section_content_bottom"></span>
            </div>
        </div>
    </div>
{else}
    <div class="inner" style="width:auto;">
        {include file=$tpl_content}
    </div>
{/if}
