<h2 class="title700">##ClientDetails%ChangePassword##</h2>
<div class="content_right">

    <form action="{$vurl}?p=user&s=details&a=password" method="post" id="formgrid">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <li>
                <label>##ClientDetails%OldPassword##</label>
                <input type="password" name="oldpassword" class="tinput_2 w_300">
            </li>

            <li><label>##ClientDetails%NewPassword##</label>
                <input type="password" name="newpassword" class="tinput_2 w_300">
            </li>
            <li><label>##ClientDetails%NewPassword## (##Again##)</label>
                <input type="password" name="newpassword2" class="tinput_2 w_300">
            </li>
            <p>
                <input type="submit" value="##Update##" class="right button"/>
            </p>

        </fieldset>
    </form>

</div>
