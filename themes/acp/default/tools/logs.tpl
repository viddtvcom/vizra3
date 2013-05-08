{literal}
<script type="text/javascript">
var timestamp = 0;
var licount = 0;
getMessages();
setInterval('getMessages();', 10000);

function getMessages() {
    $.post('ajax.php', {action: 'get_logs', offset: timestamp}, function (data) {
        if (data) {
            var count = data.length;
            if (!count) return false;
            for (var i = 0; i < count; i++) {
                if ($('#log_' + data[i].logID).length > 0) continue;

                line = '<li id="log_' + data[i].logID + '" class="' + data[i].type + '">[' + data[i].timestamp + '] <span>' + data[i].label + ':</span> ' + data[i].message + '</li>';

                $("#logs_ul").prepend(line);
                if (licount++ > 60) {
                    $("#logs_ul li:last-child").remove();
                }
            }

            timestamp = data[count - 1].dateAdded;
        }

    }, "json");
}
</script>{/literal}
<style type="text/css" media="all">@import "{$turl}/css/logs.css";</style>
<div id="logs">
    <div id="logs_div" style="height:{if $smarty.get.h}{$smarty.get.h}{else}400{/if}px;">
        <ul id="logs_ul">
        </ul>
    </div>
</div>
