<!DOCTYPE html>
<html>
<head>

    <title>{"compinfo_name"|getSetting}{if $title} : {$title}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{$turl}css/screen.css" charset="utf-8"/>
    <!-- Footer'in sayfanin altına yapisik gelmesini sagliyor -->
    <link rel="stylesheet" type="text/css" href="{$turl}css/sticky.css" charset="utf-8"/>

    <!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="{$turl}css/ie6.css" charset="utf-8"/><![endif]-->
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="{$turl}css/ie7.css" charset="utf-8"/><![endif]-->

    <script src="{$vurl}js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="{$vurl}js/common.js" type="text/javascript"></script>
    <!-- sekmeli yapi icin gerekli -->
    <script src="{$turl}js/jquery.tabs.min.js" type="text/javascript"></script>
    <!-- basliklar icin -->
    <script src="{$turl}js/cufon-yui.js" type="text/javascript"></script>
    <script src="{$turl}js/Myriad_Pro_700.font.js" type="text/javascript"></script>
    <!-- sayfa icindeki scriptleri tetiklemek icin -->
    <script src="{$turl}js/no2.js" type="text/javascript"></script>
    <script type="text/javascript">
        var vurl = '{$vurl}';
    </script>

    <base href="{$turl}"/>
</head>
<body>

<div id="wrapper">
    <div id="main">


        <!-- header -->
        <div class="header">

            <h1 class="left logo"><a href="{$vurl}"><img src="images/layout/vizra_logo.png" alt="Vizra"/></a></h1>

            <ul style="position:absolute; top:0; right:0;">
                <li class="nav_user_left left">&nbsp;</li>
                <li class="nav_user_repeat left">

                    {if $Client->clientID}
                        <ul>
                            <li class="left"><a href="{$vurl}?p=user"
                                                class="user">{if $Client->type == 'individual'}{$Client->name}{else}{$Client->company}{/if}</a>
                            </li>
                            <li class="left" style="color:#999;">|</li>
                            <li class="left"><em class="last_login">##LastLoginDate##
                                    : {$Client->dateLogin|formatDate:datetime:short}</em></li>
                            <li class="left" style="color:#999;">|</li>
                            <li class="left"><a href="{$vurl}?p=user&s=logout">##Logout##</a></li>
                        </ul>
                    {else}
                        <ul>
                            <li class="left"><a href="{$vurl}?p=user" class="btn_giris_yapin">##Login##</a></li>
                            <li class="left" style="color:#999;">|</li>
                            <li class="left"><a href="{$vurl}?p=user" class="btn_musteri_kaydi">##NewClientRegistration##</a>
                            </li>
                            <!--<li><a href="{$vurl}?p=cart">##Nav%MyCart##</a></li>-->
                        </ul>
                    {/if}

                </li>
                <li class="nav_user_right left">&nbsp;</li>
            </ul>


            <ul class="nav right">
                {if $Client->clientID}
                    <li><a href="{$vurl}?p=user&s=support" class="n_destek">##TopMenu%Support##</a></li>
                    <li><a href="{$vurl}?p=user&s=details" class="n_bilgilerim">##TopMenu%MyDetails##</a></li>
                    <li><a href="{$vurl}?p=user&s=orders" class="n_siparislerim">##TopMenu%MyOrders##</a></li>
                    <li><a href="{$vurl}?p=user&s=finance" class="n_finans">##TopMenu%Finance##</a></li>
                {/if}
                <li><a href="{$vurl}?p=cart" class="n_sepetim">##Nav%MyCart##</a></li>
                <li><a href="{$vurl}?p=kb" class="n_bilgi_bankasi" {if $smarty.get.p=="kb"}class="selected"{/if}><span>##TopMenu%KnowledgeBase##</span></a>
                </li>
                <li><a href="{$vurl}?p=dc" class="n_dosya_merkezi" {if $smarty.get.p=="dc"}class="selected"{/if}><span>##TopMenu%DownloadCenter##</span></a>
                </li>
            </ul>


        </div>
        <!-- /header -->


        {if $smarty.get.p != ''}
        <!-- content -->
        <div class="content">
            {/if}

            {include file="messages.tpl"}

            {if $smarty.get.p != ''}
            <div class="right sidebar">

                <h2 class="title1">Üye Girişi</h2>
                <ul class="article s_ug" style="margin-bottom:10px;">
                    <li>
                        <form action="{$vurl}?p=user&s=login" method="post" id="login_form2">
                            <input type="hidden" name="action" value="validate_login"/>
                            <input type="hidden" name="token" value="{1|getToken}"/>
                            <label for="su1" class="block left" style="margin-right:10px;">##YourEmail##</label>
                            <input type="text" id="su1" name="email" value="" class="tinput right block br_5"
                                   style="width:110px;"/>

                            <br class="clear"/>

                            <label for="su2" class="block left" style="margin-right:10px;">##YourPassword##</label>
                            <input id="su2" type="password" name="password" value="" class="tinput right block br_5"
                                   style="width:110px;"/>

                            <br class="clear"/>

                            <label><input type="checkbox" class="vm"/> Beni Hatırla </label>
                            <input id="su3" type="submit" value="##Login##" class="button br_5 right"/>
                            <br class="clear"/><br/><a href="#"
                                                       onclick="$('#login_form2').hide(); $('#reminder_form').fadeIn(1000); return false;">##CantRememberPassword##</a>
                        </form>

                        <form method="post" id="reminder_form" style='display:none;'>
                            <input type="hidden" name="action" value="remind_password"/>
                            <input type="hidden" name="token" value="{2|getToken}"/>
                            <h5>##PasswordReminder##</h5>
                            <label for="su1" class="block left">##YourEmail##</label>
                            <input type="text" id="su1" name="email" value="" class="tinput br_5" style="width:120px;"/>
                            <input id="su3" type="submit" value="##SendMyPassword##" class="button br_5 right"/>
                        </form>
                    </li>
                </ul>

                {include file="product_menu.tpl"}
                {include file="announcement_list.tpl"}
            </div>
            <!-- /sidebar -->

            <!-- content left -->
            <div class="left section">
{/if}