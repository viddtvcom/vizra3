{literal}
<script type="text/javascript">
var timestamp = 0;
var licount = 0;
var lock = false;
var gm_lock = false;
getMessages();
setInterval('getMessages();', 5000);

$(document).ready(function () {
    $("#text_frm").ajaxForm({
        beforeSubmit: function () {
            if ($("#message").val() == "" || lock == true) {
                return false;
            } else {
                lock = true;
                return true;
            }
        },
        success: function () {
            $("#message").val('');
            lock = false;
            getMessages();
        }
    });
});


function getMessages() {
    $.post('ajax.php', {action: 'get_messages', offset: timestamp}, function (data) {
        if (data) {
            var count = data.length;
            if (!count) return false;
            for (var i = 0; i < count; i++) {
                if ($('#msg_' + data[i].messageID).length > 0) continue;

                line = '<li id="msg_' + data[i].messageID + '">';
                line += '<span><span>[' + data[i].timestamp + ']</span> ' + data[i].adminNick + ':</span> ' + data[i].message + '</li>';
                $("#chat_ul").append(line);
                if (licount++ > 150) {
                    $("#chat_div li:first-child").remove();
                }
            }
            $("#chat_div").scrollTop($("#chat_div")[0].scrollHeight);
            timestamp = data[count - 1].dateAdded;
        }
    }, "json");
}
</script>{/literal}
<style type="text/css" media="all">@import "{$turl}/css/chat.css";</style>
<div id="chat">
    <div id="chat_div" style="height: {if $smarty.get.h}{$smarty.get.h}{else}400{/if}px; background-color: #FFF;">
        <ul id="chat_ul">
        </ul>
    </div>
    <form id="text_frm" method="post" action="ajax.php">
        <input type="hidden" name="action" value="add_message">
        <!--        <img id="chat_led" src="{$turl}images/led_green.png">-->
        <input type="text" name="message" id="message"
               style="width: 100%; height: 25px; background-color: #F0F0F0; border: 0px; border-top:1px solid #999; font-size: 1.2em;">
    </form>
</div>  