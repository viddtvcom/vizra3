<h3>{$Tpl->title}</h3>
<form method="post" class="cmxform" style="width:99%;">
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li>
                <label>Title</label>
                <input type="text" name="title" value="{$Tpl->title}"/>
            </li>
            <li>
                <label>Kimden (İsim)</label>
                <input type="text" name="fromName" value="{$Tpl->fromName}"/>
            </li>
            <li>
                <label>Kimden (Email)</label>
                <input type="text" name="fromEmail" value="{$Tpl->fromEmail}"/>
            </li>
            <li>
                <label>Konu</label>
                <input type="text" name="subject" value="{$Tpl->subject}"/>
            </li>
            <li>
                <label>Kopya</label>
                <input type="text" name="copyTo" value="{$Tpl->copyTo}"/>
            </li>
            <li>
                <label>SMS</label>
                <textarea name="sms" rows="3" cols="50">{$Tpl->sms}</textarea>
            </li>
        </ol>
    </fieldset>
    <br/>

    <p><textarea rows="20" style="width:100%;" name="body" class="wysiwyg2">{$Tpl->body}</textarea></p>

    <p align="right"><input type="submit" value="Güncelle"/></p>
</form>


{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('.wysiwyg2').wysiwyg();
        });
    </script>
{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>


{include file="setup/email_template_vars.tpl"}
