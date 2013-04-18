{foreach from=$templates item=tmps key=type}
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <th colspan="2">##SetupEmailTemplates%{$type}##</th>
                {foreach from=$tmps item=tmp}
                    <tr  {cycle values=',class=alt'}>
                        <td> &nbsp;&nbsp;<a href="index.php?p=135&act=details&templateID={$tmp.templateID}">
                                {$tmp.title}</a></td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <br/>
    <br/>
{/foreach}
  
  
  
