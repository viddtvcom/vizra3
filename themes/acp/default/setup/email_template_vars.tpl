{foreach from=$vars item=data key=title}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th colspan="2">{$title}</th>
                </tr>
                {foreach from=$data item=v key=k}
                    <tr {cycle values=',class=alt'}>
                        <td width="200"><a href="/" class="inserter">{$k}</a></td>
                        <td>{$v}</td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <br/>
{/foreach}

    {literal}
    <script language="JavaScript">
    $(document).ready(function () {
        $('.inserter').click(function () {
            $('.wysiwyg2').wysiwyg('insertHtml', $(this).html());
            return false;
        });
    });
    </script>{/literal}