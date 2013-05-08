<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vizra {$VVERSION} Yönetim Paneli</title>

    <link media="screen" rel="stylesheet" type="text/css" href="{$turl}css/login.css"/>
    <!--[if lte IE 6]>
    <link media="screen" rel="stylesheet" type="text/css" href="{$turl}css/login-ie.css"/><![endif]-->
    <!--    <link media="screen" rel="stylesheet" type="text/css" href="{$turl}css/login-dark.css"  />-->
    <script src="{$vurl}js/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript">
        {literal}$(document).ready(function () {{/literal}
            {if $smarty.get.p == '1'}
            parent.location.replace("login.php");
            {else}
            $('#email_a').focus();
            {/if}
            {literal}
        });
        {/literal}
    </script>
</head>

<body>
<!--[if !IE]>start wrapper<![endif]-->
<div id="wrapper">
    <div id="wrapper2">
        <div id="wrapper3">
            <div id="wrapper4">
                <span id="login_wrapper_bg"></span>

                <div id="stripes">

                    <!--[if !IE]>start login wrapper<![endif]-->
                    <div id="login_wrapper">
                        {if $error}
                            <div class="error">
                                <div class="error_inner">
                                    <strong>Hata!</strong> | <span>Yanlış email / şifre</span>
                                </div>
                            </div>
                        {/if}
                        <!--[if !IE]>start login<![endif]-->
                        <form name="loginform" id="loginform" method="post">
                            <input type="hidden" name="action" value="authenticate">
                            <input type="hidden" name="token" value="{1|getToken}">
                            <fieldset>
                                <h1>Vizra {$VVERSION} Yönetim Paneli</h1>

                                <div class="formular">
                                    <span class="formular_top"></span>

                                    <div class="formular_inner">
                                        <label>
                                            <strong>Email:</strong>
                            <span class="input_wrapper">
                                <input type="text" name="email_a" id="email_a" tabindex="10" maxlength="40"/>
                            </span>
                                        </label>
                                        <label>
                                            <strong>Şifre:</strong>
                            <span class="input_wrapper">
                                <input type="password" name="password_a" id="password_a" tabindex="20" maxlength="40"/>
                            </span>
                                        </label>
                                        <label class="inline">
                                            <input class="checkbox" name="remember" type="checkbox" value="1"/>
                                            Beni bu bilgisayarda hatırla
                                        </label>
                                        <ul class="form_menu">
                                            <li><span class="button"><span><span><em>Giriş</em></span></span><input
                                                            type="submit" name=""/></span></li>
                                            <li><span class="button"><span><span><a href="../">Müşteri Paneli</a></span></span></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <span class="formular_bottom"></span>
                                </div>
                            </fieldset>
                        </form>
                        <!--[if !IE]>end login<![endif]-->

                        <!--[if !IE]>start reflect<![endif]-->
                        <span class="reflect"></span>
                        <span class="lock"></span>
                        <!--[if !IE]>end reflect<![endif]-->

                    </div>

                    <!--[if !IE]>end login wrapper<![endif]-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--[if !IE]>end wrapper<![endif]-->
</body>


</html>

