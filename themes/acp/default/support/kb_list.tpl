{$entry_tree}

    {literal}
    <script language="JavaScript">

    $('#entry_tree').treeview({
        animated: "fast",
        collapsed: true,
        unique: true
    });

    $('.file').click(function () {
        var html = 'Aşağıdaki makaleden bilgi alabilirsiniz:';
        html += '<p style="padding:10px 10px 10px 30px;"><a href="' + v_url + '?p=kb&catID=30&entryID=' + $(this).attr('entryID') + '">' + $(this).html() + '</a></p>';
        (mobile == true) ? $('#message').val($('#message').val() + html) : $('#message').html($('#message').val() + html);
        $.facebox.close();
    });

    </script>{/literal}