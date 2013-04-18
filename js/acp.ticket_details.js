function getMessages() {
    $.post('?p=212&ticketID=' + ticketID, {offset: timestamp, action: 'refresh_ticket'}, function (data) {
        if (data.messages) {
            var messages = data.messages;
            var count = messages.length;
            if (count > 0) {
                for (var i = 0; i < count; i++) {
                    if ($('#respli_' + messages[i].responseID).length > 0) continue;
                    addResponse(messages[i])
                }
                timestamp = messages[count - 1].timestamp;
            }
        }
        if (data.details) {
            getDetails(data.details);
        }
    }, "json");
}

function getDetails(data) {
    if (data) {
        if ($("#status_o").val() != data.status) {
            $("#status_o").val(data.status)
            $("#status").hide().val(data.status).fadeIn(2000);
        }
        if ($("#depID_o").val() != data.depID) {
            $("#depID_o").val(data.depID)
            $("#depID").hide().val(data.depID).fadeIn(2000);
        }
        if ($("#adminID_o").val() != data.adminID) {
            $("#adminID_o").val(data.adminID)
            $("#adminID").hide().val(data.adminID).fadeIn(2000);
        }
        if ($("#priority_o").val() != data.priority) {
            $("#priority_o").val(data.priority)
            $("#priority").hide().val(data.priority).fadeIn(2000);
        }
    }
}

function addResponse(d) {
    if (d.type == '') {
        d.name = client_name;
    }
    var html = '';
    html += '<li id="respli_' + d.responseID + '" class="' + d.type + '">';
    html += '<div class="ticket_post">';
    html += '    <span class="ticket_info">' + d.name + ' <a href="#" class="resp_edit right" rel="' + d.responseID + '">Düzenle</a></span>';
    html += '    <span class="response" id="resp_' + d.responseID + '">' + d.response + '</span>';
    html += '    <span class="ticket_post_date">' + d.dateAdded + '</span>';
    html += '</div></li><li class="clearfix">&nbsp;</li>';
    $("#ticket_content").prepend(html);
    //$(".liHidden").fadeIn(2000);
    doParentIframe();
}


$('#buttonSubmit').click(function () {
    var message = (mobile == true) ? $('#message').val() : $('#message').html();
    var setas_awaiting_reply = ($('#setas_awaiting_reply').attr('checked')) ? '1' : '';
    var private = ($('#private').attr('checked')) ? '1' : '';

    if (message == '') return false;

    $("#buttonSubmit").hide();
    $.post('?p=212&ticketID=' + ticketID, {action: 'add_response', message: message, setas_awaiting_reply: setas_awaiting_reply, private: private  },
        function (data) {
            (mobile == false) ? $("#message").html('') : $("#message").val('');
            $("#private").attr('checked', '');
            $("#buttonSubmit").fadeIn();
            getMessages();
        }, "json"
    );

    return false;
});

$('.select').change(function () {
    var name = $(this).attr('name');
    var value = $(this).val();
    $(this).prev().html('<img src="' + turl + 'images/loading.gif">');
    $.post('?p=212&ticketID=' + ticketID, {action: 'update_ticket', name: name, value: value, id: $(this).attr('id') }, function (data) {
        if (data.st == 'ok') {
            $('#' + data.id).prev().html('<img src="' + turl + 'images/ok.png">');
        } else {
            $('#' + data.id).prev().html('<img src="' + turl + 'images/stop.png">');
        }
        $('#' + data.id).prev().children(':first').fadeOut(7000);
    }, "json");

});

$('#qrepbut').click(function () {
    if (!$('#qreps').val()) return false;
    $('#message').html($('#message').val() + $('#qreps option:selected').text());
    return false;
});

$('.resp_edit').live('click', function () {
    var id = $(this).attr('rel');
    if ($('#tx_' + id).length) return false;


    var orig_resp = $('#resp_' + id).html();

    var html = '<textarea id="tx_' + id + '" style="width:600px;" rows="10">' + orig_resp + '</textarea>';
    html += '<br><p align="right"><input type="button" value="Kaydet" id="but_' + id + '"></p>';

    $('#resp_' + id).html(html);

    $('#tx_' + id).tinymce({                script_url: v_url + '/js/tiny_mce/tiny_mce.js',
        theme: "simple",
        plugins: "paste",
        encoding: "utf-8",
        entities: "",

        paste_auto_cleanup_on_paste: true  });

    $('#but_' + id).click(function () {
        var resp = $('#tx_' + id).val();
        $('#resp_' + id).html('<p align="center"><img src="' + turl + 'images/loading.gif"></p>');
        $.post('?p=212&ticketID=' + ticketID, { action: "update_response", responseID: id, response: resp },
            function (data) {
                if (data.st == true) {
                    $('#resp_' + data.id).html(resp).hide().fadeIn(5000);
                    $('#tx_' + id).remove();
                } else {
                    alert('Kayıt güncellenemedi:' + data.msg);
                    $('#resp_' + data.id).html(orig_resp);
                }
            }, "json");
    });

    doParentIframe();

    return false;
});

