{include file="head.tpl"}
<body>
<!--[if !IE]>start wrapper<![endif]-->
<div id="wrapper">
    {include file="header.tpl"}

    {if $iframe}
        <div class="iframe_wrapper">
            <div class="iframes" style="position: relative;">
                {if $Admin->settings.staticChatWindow == '1' || $Admin->settings.staticLogWindow == '1'}

                    {if $Admin->settings.staticChatWindow == '1'}
                        <iframe name="chatframe" id="chatframe" src="?p=215&m=compact&h=120" scrolling="no"
                                marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0"
                                style="width:{if $Admin->settings.staticLogWindow == '1'}49{else}100{/if}%; display:; clear:both; float:left;"></iframe>
                    {/if}
                    {if $Admin->settings.staticLogWindow == '1'}
                        <iframe name="chatframe" id="chatframe" src="?p=610&m=compact&h=150" scrolling="no"
                                marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0"
                                style="width:{if $Admin->settings.staticChatWindow == '1'}49{else}100{/if}%; display:; float:left; margin-left:5px;"></iframe>
                    {/if}
                    <div class="clear"></div>
                {/if}
                <iframe name="mainframe" id="mainframe" class="autoHeight" src="?p=210" scrolling="no" marginwidth="0"
                        marginheight="0" frameborder="0" vspace="0" hspace="0" style="width:100%; display:;"></iframe>
                {include file="probes.tpl"}
            </div>
        </div>
    {else}
        <div id="content">
            {include file="content.tpl"}
        </div>
    {/if}


</div>
<!--[if !IE]>end wrapper<![endif]-->

<div class="clear"></div>
<div id="footer">
    <div id="footer_inner">
        <div class="inner">

            <div id="footer_info">
                Vizra {$VVERSION}
            </div>

            <ul id="footer_menu">
                <li class="first"><a href="http://forum.vizra.com" target="_blank">Forum</a></li>
                <li><a href="http://www.vizra.com" target="_blank">vizra.com</a></li>
            </ul>

        </div>
    </div>
</div>
<!--[if !IE]>end footer<![endif]-->

</body>

