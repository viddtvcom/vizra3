$(document).ready(function () {

    $('#show_tickets').change(function () {
        for (var i = 0; i < chatTimer.length; i++) {
            clearInterval(chatTimer[i]);
        }
        checkAll();
    });

    $('.tslider').live('click', function () {

        var status = $(this).attr('rel');
        var ticketID = $(this).parent().parent().attr('id');


        $('.tslider').attr('src', turl + 'images/plus.png');
        $(this).attr('src', turl + 'images/minus.png');
        $('#temptr').remove();

        if (status == 'open') {
            $(this).attr('rel', '');
            $(this).attr('src', turl + 'images/plus.png');
            killTimer(ticketID);
        } else {
            $('.tslider').attr('rel', '');
            $(this).attr('src', turl + 'images/minus.png').attr('rel', 'open');
            $(this).parent().parent().after('<tr id="temptr"><td colspan="7" style="border:5px solid #CCC; line-height:140% !important;"></td></tr>');
            $('#temptr').children(':first').load('?p=212&ajax=true&m=compact&ticketID=' + ticketID);
        }

        return false;
    });

});

function killTimer(ticketID) {
    if (ticketID == '') return;
    var tname = "timer_" + ticketID.replace('-', '');
    eval("if (typeof(" + tname + ") != 'undefined') clearInterval(" + tname + ")");
}

function checkAll() {
    getTickets('new');
    getTickets('client-responded');
}
function getTickets(tableID) {
    $.post('ajax.php', {action: 'get_tickets', status: tableID, show_tickets: $('#show_tickets').val()}, function (data) {
        if (data) {
            var tbl = $('#' + tableID);
            var count = data.length;
            $('tr', tbl).each(function () {
                if ($(this).attr('id') == 'temptr' || $(this).attr('id') == '') return true;
                var exists = false;
                for (var i = 0; i < count; i++) {
                    if (data[i].ticketID == $(this).attr('id')) {
                        exists = true;
                    }
                }
                if (!exists) {
                    if ($(this).next().attr('id') != 'temptr') {
                        killTimer($(this).attr('id'));
                        $(this).remove();
                    }
                }
            });
            for (var i = 0; i < count; i++) {
                var exists = false;
                $('tr', tbl).each(function () {
                    if (data[i].ticketID == $(this).attr('id')) {
                        exists = true;
                    }
                });
                if (!exists) {
                    addRow(tableID, data[i]);
                }
            }
        }
        $("#killmeimg").fadeOut(3000);
        setTimeout('$("#killmeimg").remove();', 3000);

    }, "json");
    chatTimer.push(setTimeout('getTickets(\'' + tableID + '\');', 10000));
}

function addRow(tableID, t) {
    var str = '';
    str += '<tr id="' + t.ticketID + '">';
    str += '<td><img src="' + turl + 'images/plus.png" style="cursor: pointer;" class="tslider"></td>';
    str += '<td id="center"><a href="?p=212&ticketID=' + t.ticketID + '">' + t.ticketID + '</a></td>';
    str += '<td>' + t.depTitle + '</td>';
    str += '<td>' + t.clientName + '</td>';
    str += '<td>' + t.subject + '</td>';
    str += '<td id="right">' + t.dateAdded + '</td>';
    str += '<td id="right">' + t.dateUpdated + '</td>';
    str += '<td id="right">' + t.adminNick + '</td>';
    str += '</tr>';
    $('#' + tableID).append(str);
    doParentIframe();
}

var timestamp = 0;
var chatTimer = [];
checkAll();