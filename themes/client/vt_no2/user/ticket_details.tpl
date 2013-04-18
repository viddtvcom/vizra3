<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>
<script src="{$vurl}js/jquery.form.js" type="text/javascript"></script>
<script src="{$vurl}js/jquery.ajaxupload.js" type="text/javascript"></script>

<h2 class="title700">
    {include file="user/support_submenu.tpl"}
    ##TopMenu%Support##
</h2>

<ul class="s_details">
    <li class="tleft">
        <span style="color:#666;">##TicketDetails%TICKETID##: {$T->ticketID} / ##STATUS##:</span> <span class="durum">##TicketDetails%{$T->status}
            ##</span><br/>
        <span style="color:#000; font-size: 1.3em; padding: 3px;">Konu: {$T->subject}</span>
    </li>
</ul>

<div class="content_right">

    <ul class="ticket_content">
        <li class="files">
            <ul id="files">
                {foreach from=$attachments item=a}
                    <li><a href="{$vurl}file.php?id={$a.fileID}">{$a.origname}</a></li>
                {/foreach}
            </ul>
        </li>
        <li class="clearfix">&nbsp;</li>
        <li class="response">
            <form id="addResponseForm" method="post" action="{$vurl}?p=user&s=support&a=addResponse">
                <input type="hidden" name="ticketID" value="{$T->ticketID}"/>

                <p class="user_avatar">
                    <img src="{$vurl}?p=image&f={$Client->getAvatarName()}&t=avatar"/><br/>
                    <span class="t_user">{$Client->name}</span><br/>
                    <span class="fs_10 c_999">##TicketDetails%AccountOwner##</span>
                </p>

                <p class="ticket_post">
                    <textarea cols="5" rows="1" name="response" id="response"
                              onclick="$(this).attr('rows',5);"></textarea>
                    <span id="upload_button"><img src="{$turl}/images/upload.png"
                                                  class="vm">##TicketDetails%AddFile##</span>
                    <input type="submit" value="##TicketDetails%AddResponse##" class="button right" id="buttonSubmit"/>
                </p>
            </form>
        </li>
    </ul>
    <ul class="ticket_content" id="ticket_content"></ul>


    <script language="JavaScript">
        var timestamp = 0;
        var chatTimer;
        var ticketID = '{$T->ticketID}';
        var turl = '{$turl}';
        getMessages();
        {literal}
        function getMessages() {
            $.post(vurl + '?p=ajax', {offset: timestamp, ticketID: ticketID, action: 'refresh_ticket'}, function (data) {
                if (data) {
                    var count = data.length;
                    if (!count) return false;
                    for (var i = 0; i < count; i++) {
                        if ($('#respli_' + data[i].responseID).length > 0) continue;
                        addResponse(data[i])
                    }
                    timestamp = data[count - 1].timestamp;
                }

            }, "json");
            chatTimer = setTimeout('getMessages();', 15000);
        }

        function addResponse(d) {
            var html = '';
            html += '<li id="respli_' + d.responseID + '" class="liHidden ' + d.type + '" style="display:none;"><p class="user_avatar">';
            html += '<img src="' + vurl + '?p=image&f=' + d.avatar + '&t=avatar" alt="" /><br />';
            html += '<span class="fs_11">' + d.name + '</span><br />';
            html += '<span class="fs_10 c_999">' + d.title + '</span></p>';
            html += '<div class="ticket_post">';
            html += '    <span class="response">' + d.response + '</span>';
            html += '    <span class="ticket_post_date">' + d.dateAdded + '</span>';
            html += '</div></li><li class="clearfix">&nbsp;</li>';
            $("#ticket_content").prepend(html);
            $(".liHidden").fadeIn(2000);
        }

        $(document).ready(function () {
            $('#addResponseForm').ajaxForm({
                beforeSubmit: function () {
                    $("#buttonSubmit").hide();
                }, success: function () {
                    //$('html, body').animate({scrollTop:0}, 2000);
                    $("#response").val('');
                    $("#buttonSubmit").fadeIn();
                    chatTimer = 0;
                    getMessages();
                }
            });
        });

    </script>{/literal}

    {literal}
        <script language="JavaScript">
            new AjaxUpload('upload_button', {
                // Location of the server-side upload script
                // NOTE: You are not allowed to upload files to another domain
                action: vurl + '?p=ajax',
                // File upload name
                name: 'file',
                // Additional data to send
                data: {
                    ticketID: ticketID,
                    action: 'uploadFile'
                },
                // Submit file after selection
                autoSubmit: true,
                // The type of data that you're expecting back from the server.
                // HTML (text) and XML are detected automatically.
                // Useful when you are using JSON data as a response, set to "json" in that case.
                // Also set server response type to text/html, otherwise it will not work in IE6
                responseType: "json",
                // Fired after the file is selected
                // Useful when autoSubmit is disabled
                // You can return false to cancel upload
                // @param file basename of uploaded file
                // @param extension of that file
                onChange: function (file, extension) {
                },
                // Fired before the file is uploaded
                // You can return false to cancel upload
                // @param file basename of uploaded file
                // @param extension of that file
                onSubmit: function (file, extension) {
                    var li = "<li class='delme'><img src='" + turl + "/images/loading.gif'></li>"
                    $("#files").append(li);
                },
                // Fired when file upload is completed
                // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
                // @param file basename of uploaded file
                // @param response server response
                onComplete: function (file, r) {
                    chatTimer = 0;
                    getMessages();
                    if (r.st) {
                        var li = "<li><a href='" + vurl + "file.php?id=" + r.fileID + "'>" + r.filename + '</a><li>';
                        $("#files").append(li);
                        $(".delme").remove();
                    } else {
                        alert(r.msg);
                    }
                }
            });
        </script>
    {/literal}


</div>


