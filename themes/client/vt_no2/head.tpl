<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>

    <title>{"compinfo_name"|getSetting}{if $title} : {$title}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{$turl}css/screen.css" charset="utf-8"/>

    <script src="{$vurl}js/jquery.min.js" type="text/javascript"></script>
    <script src="{$vurl}js/common.js" type="text/javascript"></script>

    <script type="text/javascript">
        var vurl = '{$vurl}';
    </script>

    <base href="{$turl}"/>
</head>
<body>

<div id="wrapper">

    <div class="top">
        <ul style="width:100%; overflow:hidden;">
            <li><a href="{$vurl}?p=cart">##Nav%MyCart##</a></li>

            {if $Client->clientID}

            <li>
                <a href="{$vurl}?p=user">{if $Client->type == 'individual'}{$Client->name}{else}{$Client->company}{/if}</a>
            </li>
            <li><em class="last_login">##LastLoginDate## : {$Client->dateLogin|formatDate:datetime:short}</em></li>
            <li style="float:right"><a href="{$vurl}?p=user&s=logout" class="g_btn">##Logout##</a></li>

            {else}
            <li style="float:right"><a href="{$vurl}?p=user" class="p_btn">##NewClientRegistration## / ##Login##</a>
            </li>
        </ul>
        {/if}

    </div>


    <div style="width:980px; margin:auto;">

        <!-- header -->
        <div class="header">

            <h1 class="left logo"><a href="{$vurl}"><img src="css/layout/logo.png" alt="Vizra"/></a></h1>

            <ul class="nav_top right">
                <li class="first">&nbsp;</li>
                {if $Client->clientID}
                    <li><a href="{$vurl}?p=user&s=support" style="border-left:0;">##TopMenu%Support##</a></li>
                    <li><a href="{$vurl}?p=user&s=details">##TopMenu%MyDetails##</a></li>
                    <li><a href="{$vurl}?p=user&s=orders">##TopMenu%MyOrders##</a></li>
                    <li><a href="{$vurl}?p=user&s=finance">##TopMenu%Finance##</a></li>
                {/if}
                <li><a href="{$vurl}?p=kb" {if $smarty.get.p=="kb"}class="selected"{/if}>##TopMenu%KnowledgeBase##</a>
                </li>
                <li><a href="{$vurl}?p=dc" {if $smarty.get.p=="dc"}class="selected"{/if} style="border-right:0;">##TopMenu%DownloadCenter##</a>
                </li>
                <li class="last">&nbsp;</li>
            </ul>
        </div>
        <!-- /header -->


        {if $smarty.get.p != ''}
        <!-- content -->
        <div class="content">

            <!-- content left -->
            <div class="right w200">
                {include file="product_menu.tpl"}
                {include file="announcement_list.tpl"}
            </div>
            <!-- /content left -->


            <!-- content right -->
            <div class="left w700">
                {include file="messages.tpl"}

                {/if}
        