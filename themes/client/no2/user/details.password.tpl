<h2 class="title2">##ClientDetails%ChangePassword##</h2>
<div class="article">
    <form action="{$vurl}?p=user&s=details&a=password" method="post" id="formgrid">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol class="main_form">
                <li>
                    <label>##ClientDetails%OldPassword##</label>
                    <input type="password" name="oldpassword" class="tinput">
                </li>

                <li><label>##ClientDetails%NewPassword##</label>
                    <input type="password" name="newpassword" class="tinput">
                </li>
                <li><label>##ClientDetails%NewPassword## (##Again##)</label>
                    <input type="password" name="newpassword2" class="tinput">
                </li>
                <li>
                    <input type="submit" value="##Update##" class="button br_5"/>
                </li>
            </ol>
        </fieldset>
    </form>
</div>