<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>

    <title>{"compinfo_name"|getSetting}{if $title} : {$title}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{$turl}css/screen.css" charset="utf-8"/>

    <!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="{$turl}css/ie6.css" charset="utf-8"/><![endif]-->
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="{$turl}css/ie7.css" charset="utf-8"/><![endif]-->


    <script src="{$vurl}js/jquery.min.js" type="text/javascript"></script>
    <script src="{$vurl}js/common.js" type="text/javascript"></script>
    <script type="text/javascript">
        var vurl = '{$vurl}';
    </script>

    <base href="{$turl}"/>
</head>
<body>

<div id="wrapper">

    <!-- header -->
    <div id="header">

        <h1 class="left logo"><a href="{$vurl}"><img src="images/vizra_logo.png" alt="Vizra"/></a></h1>

        <ul class="nav_top right">
            {if $Client->clientID}
                <li><a href="{$vurl}?p=user&s=support" class="destek">##TopMenu%Support##</a></li>
                <li><a href="{$vurl}?p=user&s=details" class="bilgilerim">##TopMenu%MyDetails##</a></li>
                <li><a href="{$vurl}?p=user&s=orders" class="siparislerim">##TopMenu%MyOrders##</a></li>
                <li><a href="{$vurl}?p=user&s=finance" class="finans">##TopMenu%Finance##</a></li>
            {/if}
            <li><a href="{$vurl}?p=kb"
                   {if $smarty.get.p=="kb"}class="selected"{/if}><span>##TopMenu%KnowledgeBase##</span></a></li>
            <li><a href="{$vurl}?p=dc"
                   {if $smarty.get.p=="dc"}class="selected"{/if}><span>##TopMenu%DownloadCenter##</span></a></li>
        </ul>
    </div>
    <!-- /header -->

    <!-- user_bar -->
    <div class="user_bar">
        {if $Client->clientID}
            <ul>
                <li><a href="{$vurl}?p=user"
                       class="user">{if $Client->type == 'individual'}{$Client->name}{else}{$Client->company}{/if}</a>
                </li>
                <li><em class="last_login">##LastLoginDate## : {$Client->dateLogin|formatDate:datetime:short}</em></li>
                <li><a href="{$vurl}?p=cart">##Nav%MyCart##</a></li>
            </ul>
            <a href="{$vurl}?p=user&s=logout" class="logout" style="float:right;">##Logout##</a>
        {else}
            <ul>
                <li><a href="{$vurl}?p=user">##NewClientRegistration##</a></li>
                <li><a href="{$vurl}?p=user">##Login##</a></li>
                <li><a href="{$vurl}?p=cart">##Nav%MyCart##</a></li>
            </ul>
        {/if}
    </div>
    <!-- /user_bar -->

    <!-- content -->
    <div id="content">


        <!-- content left -->
        <div class="left w200">
            {include file="product_menu.tpl"}
            {include file="announcement_list.tpl"}
        </div>
        <!-- /content left -->


        <!-- content right -->
        <div class="right w700">
{include file="messages.tpl"}