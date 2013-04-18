<div id="header">
    <div class="inner">
        <!--<h1 id="logo"><a href="#">websitename <span>Administration Panel</span></a></h1>-->
        <div id="user_details">
            <ul id="user_details_menu">
                <li class="welcome"><strong>{$Admin->adminName}</strong></li>
                <li>
                    <ul id="user_access">
                        <li class="first"><a href="?p=112" {if $iframe}target="mainframe"{/if}>Kişisel Ayarlar</a></li>
                        <li class="last"><a href="login.php?out">##Logout##</a></li>
                    </ul>
                </li>
            </ul>
            <div id="server_details">
                <dl>
                    <dt>Son Login :</dt>
                    <dd>{$Admin->ipLogin}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div id="main_menu">
    <div class="inner" style="width: 1024px;">
        <ul id="main_menu_ul">
            {foreach from=$page_modules item=pageID key=moduleID}
                <li>
                    <a href="?p={$pageID}"
                       {if $page.moduleID == $moduleID}class="selected_lk"{/if} {if $iframe}target="mainframe"{/if}>
                        <span class="l"><span></span></span>
                        <span class="m"><em>##mod_{$moduleID}##</em><span></span></span>
                        <span class="r"><span></span></span>
                    </a>
                    <ul class="submenu_ul" id="submenu_ul_{$moduleID}">
                        {foreach from=$submenu.$moduleID item=p}
                            <li>
                                <a href="?p={$p.pageID}"
                                   {if $page.pageID == $p.pageID}class="selected_lk"{/if} {if $iframe}target="mainframe"{/if}
                                   rel="sub">
                                    <span class="l"><span></span></span>
                                    <span class="m"><em>##page_{$p.pageID}##</em><span></span></span>
                                    <span class="r"><span></span></span>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </li>
            {/foreach}
        </ul>
    </div>
    <span class="sub_bg"></span>
</div>

<script language="JavaScript">
    var moduleID = '{$page.moduleID}';
    {literal}
    $(document).ready(function () {

        $('#main_menu_ul li a').click(function () {
            if ($(this).attr('rel') == 'sub') {
                $('#main_menu_ul li').find('a[rel=sub]').removeClass('selected_lk');
                $(this).addClass('selected_lk');
                return true;
            }
            $('.submenu_ul').hide();
            $('#main_menu_ul li').find('a[rel!=sub]').removeClass('selected_lk');
            $(this).addClass('selected_lk');
            $(this).next().show();

            //$(this).next().children(':first').trigger('click');

            return true;
        });

        $('.submenu_ul').hide();
        if (moduleID != '') $('#submenu_ul_' + moduleID).show();

        if ($('#mainframe').length > 0) {
            $('#mainframe').iframeAutoHeight();
        }

    });
</script>{/literal}