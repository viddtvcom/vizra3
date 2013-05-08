<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>
<div id="inner_content">
    <div id="ticket_list">
        <ul>
            <li>
                <ul>
                    <li class="ticketID header">Bilet ID</li>
                    <li class="department header">Departman</li>
                    <li class="client_name header">Müşteri</li>
                    <li class="subject header">Konu</li>
                    <li class="date header">Açılış</li>
                    <li class="date header">Son Güncelleme</li>
                </ul>
            </li>
        </ul>
        <ul id='tickets'>
            <!--        {foreach from=$tickets item=t}
            <li id='{$t.ticketID}'>
            <ul>
                <li class="ticketID"><a href="index.php?p=210&act=view_ticket&ticketID={$t.ticketID}">{$t.ticketID}</a></li>
                <li class="client_name">{$t.clientName}</li>
                <li class="subject">{$t.subject}</li>
                <li class="date">{format_date date=$t.dateAdded mode=datetime type="short"}</li>
                <li class="date">{format_date date=$t.dateUpdated mode=datetime type=short}</li>
            </ul>
            </li>
            {/foreach}-->
        </ul>
    </div>
</div>
<script type="text/javascript">
    var timestamp = 0;
    var chatTimer;
    getTickets();
    {literal}
    function getTickets() {
        $.post('ajax.php', {action: 'getTickets'}, function (data) {
            if (data) {
                var count = data.length;

                $('#tickets > li').each(function () {
                    var exists = false;
                    for (var i = 0; i < count; i++) {
                        if (data[i].ticketID == $(this).attr('id')) {
                            exists = true;
                        }
                    }
                    if (!exists) {
                        $(this).fadeOut(3000);
                        setTimeout('$("#' + $(this).attr('id') + '").remove();', 3000);
                        //$(this).remove();
                    }
                });

                for (var i = 0; i < count; i++) {
                    var exists = false;
                    $('#tickets > li').each(function () {
                        if (data[i].ticketID == $(this).attr('id')) {
                            exists = true;
                        }
                    });
                    if (!exists) {
                        addLi('tickets', data[i]);
                    }
                }

            }

        }, "json");
        chatTimer = setTimeout('getTickets();', 5000);
    }

    function addLi(ulID, t) {
        var str = '';
        str += '<li id="' + t.ticketID + '" class="hidden" style="display:none;">';
        str += '<ul>';
        str += '<li class="ticketID"><a href="index.php?p=210&act=view_ticket&ticketID=' + t.ticketID + '">' + t.ticketID + '</a></li>';
        str += '<li class="department">' + t.depTitle + '</li>';
        str += '<li class="client_name">' + t.clientName + '</li>';
        str += '<li class="subject">' + t.subject + '</li>';
        str += '<li class="date">' + t.dateAdded + '</li>';
        str += '<li class="date">' + t.dateUpdated + '</li>';
        // str +=        '<li class="icon"></li>';
        str += '</ul>';
        str += '</li>';
        $('#' + ulID).append(str);
        $(".hidden").fadeIn(3000);
    }


</script>{/literal}
