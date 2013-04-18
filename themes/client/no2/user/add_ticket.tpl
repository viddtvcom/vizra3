<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>
<h2 class="title2">##Support%AddNewTicket##</h2>
<div class="article">
    {include file="user/support_submenu.tpl"}
    <script src="{$vurl}js/jquery.validate.js" type="text/javascript"></script>
    <form class="cmxform" action="{$vurl}?p=user&s=support&a=addTicket" method="post" enctype="multipart/form-data"
          name="form1" id="addticketform">
        <fieldset>
            <ol class="main_form">
                <li>
                    <label>##TicketDetails%Department##</label>
                    <select name="depID" class="required tinput">
                        <option value="">Seçiniz</option>
                        {foreach from=$deps item=d}
                            <option value="{$d.depID}"
                                    {if $smarty.post.depID == $d.depID}selected{/if}>{$d.depTitle}</option>
                        {/foreach}
                    </select>
                </li>
                <li>
                    <label>##TicketDetails%Priority##</label>
                    <select name="priority" class="tinput">
                        {foreach from=$vars.PRIORITY_OPTIONS item=p key=k}
                            <option value="{$k}" {if $k==3}selected{/if}>##TicketDetails%{$p}##</option>
                        {/foreach}
                    </select>
                </li>
                <li>
                    <label>##TicketDetails%Subject##</label>
                    <input type="text" name="subject" class="tinput w_250 required"
                           value="{$smarty.post.subject|formdisplay}" minlength="5" maxlength="60">
                </li>
                <li class="last">
                    <label>##TicketDetails%Attachment##</label>
                    <input name="file" type="file" style="margin-right:10px;"/>
                </li>
            </ol>
        </fieldset>
        <ul class="ticket_content" id="ticket_content">
            <li class="response br_5">
                <p class="user_avatar">
                    <img src="{$vurl}?p=image&f={$Client->getAvatarName()}&t=avatar"/><br/>
                    <span class="t_user">{$Client->name}</span><br/>
                    <span class="fs_10 c_999">##TicketDetails%AccountOwner##</span>
                </p>

                <p class="ticket_post" style="padding:0 10px;">
                    <textarea cols="5" rows="5" name="response" id="response" class="required br_5"
                              minlength="5">{$smarty.post.response|stripslashes}</textarea>
                    <input type="submit" value="##TicketDetails%AddTicket##" class="button br_5 right"/>
                </p>
            </li>
        </ul>
    </form>
    {literal}
        <script language="JavaScript">
            $(document).ready(function () {
                $('#addticketform').validate();
            });
        </script>
    {/literal}

</div>