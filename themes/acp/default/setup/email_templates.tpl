{foreach from=$templates item=tmps key=type}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="200">##SetupEmailTemplates%{$type}##</th>
                    <th>SMS</th>
                </tr>
                {foreach from=$tmps item=tmp}
                    <tr  {cycle values=',class=alt'}>
                        <td> &nbsp;&nbsp;<a href="index.php?p=135&act=details&templateID={$tmp.templateID}">
                                {$tmp.title}</a></td>
                        <td>{$tmp.sms}</td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <br/>
    <br/>
{/foreach}
  
  
  
