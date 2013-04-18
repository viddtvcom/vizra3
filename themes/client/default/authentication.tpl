<h2 class="title700">##Welcome##!</h2>
<div class="content_right">


    <!-- Login.html -->
    <ul class="si_su">
        <li class="sign_in left">
            <form action="{$vurl}?p=user&s=register" method="post" id="register_form">
                <input type="hidden" name="action" value="check_email"/>
                <input type="hidden" name="token" value="{3|getToken}"/>
                <h5>##NewClientRegistration##</h5>
                <label for="si1" class="block">##YourEmail##</label>
                <input type="text" id="si1" name="email" value="" class="tinput block w75 br_5"/>
                <input type="submit" id="si2" value="##CreateNewAccount##" class="button br_5 w75"/>
            </form>
        </li>
        <!-- /sign_in -->

        <li class="sign_up left">
            <form action="{$vurl}?p=user&s=login" method="post" id="login_form2">
                <input type="hidden" name="action" value="validate_login"/>
                <input type="hidden" name="token" value="{1|getToken}"/>
                <h5>##RegisteredUsers##</h5>
                <label for="su1" class="block">##YourEmail##</label>
                <input type="text" id="su1" name="email" value="" class="tinput w75 br_5"/>

                <label for="su2" class="block">##YourPassword##</label>
                <input id="su2" type="password" name="password" value="" class="tinput block w75 br_5"/>
                <input id="su3" type="submit" value="##Login##" class="button br_5 w75"/>
                <br/><a href="#" onclick="$('#login_form2').hide(); $('#reminder_form').fadeIn(1000); return false;">##CantRememberPassword##</a>
            </form>

            <form method="post" id="reminder_form" style='display:none;'>
                <input type="hidden" name="action" value="remind_password"/>
                <input type="hidden" name="token" value="{2|getToken}"/>
                <h5>##PasswordReminder##</h5>
                <label for="su1" class="block">##YourEmail##</label>
                <input type="text" id="su1" name="email" value="" class="tinput w75 br_5"/>
                <input id="su3" type="submit" value="##SendMyPassword##" class="button br_5 w75"/>
            </form>


        </li>
        <!-- /sign_up -->
    </ul>
    <!-- /Login.html -->


</div>
            
