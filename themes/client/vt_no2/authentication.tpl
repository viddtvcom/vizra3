<h2 class="title700">##Welcome##!</h2>

<!-- Login.html -->
<ul class="left w50 login">

    <form action="{$vurl}?p=user&s=register" method="post" id="register_form">
        <input type="hidden" name="action" value="check_email"/>
        <input type="hidden" name="token" value="{3|getToken}"/>
        <li><h5>##NewClientRegistration##</h5></li>
        <li><label for="si1" class="block">##YourEmail##</label></li>
        <li><input type="text" id="si1" name="email" value="" class="w75"/></li>
        <li><input type="submit" id="si2" value="##CreateNewAccount##" class="button"/></li>
    </form>

</ul>
<!-- /sign_in -->

<ul class="right w50 login">

    <form action="{$vurl}?p=user&s=login" method="post" id="login_form2">
        <input type="hidden" name="action" value="validate_login"/>
        <input type="hidden" name="token" value="{1|getToken}"/>
        <li><h5>##RegisteredUsers##</h5></li>
        <li><label for="su1" class="block">##YourEmail##</label></li>
        <li><input type="text" id="su1" name="email" value="" class="w75"/></li>
        <li><label for="su2" class="block">##YourPassword##</label></li>
        <li><input id="su2" type="password" name="password" value="" class="w75"/></li>
        <li><input id="su3" type="submit" value="##Login##" class="button"/></li>
        <br/><a href="#" onclick="$('#login_form2').hide(); $('#reminder_form').fadeIn(1000); return false;">##CantRememberPassword##</a>
    </form>

    <form method="post" id="reminder_form" style='display:none;'>
        <input type="hidden" name="action" value="remind_password"/>
        <input type="hidden" name="token" value="{2|getToken}"/>
        <li><h5>##PasswordReminder##</h5></li>
        <li><label for="su1" class="block">##YourEmail##</label></li>
        <li><input type="text" id="su1" name="email" value="" class="w75"/></li>
        <li><input id="su3" type="submit" value="##SendMyPassword##" class="button"/></li>
    </form>

</ul>
<!-- /sign_up -->