{if !$modules}
    Sistemde modül bulunamadı.
{else}

    {foreach from=$modules item=mod key=type}
        <div class="table_wrapper">
            <div class="table_wrapper_inner">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <th colspan="2">{$type}</th>
                    {foreach from=$mod item=title key=moduleID}
                        <tr  {cycle values=',class=alt'}>
                            <td width="20"><img
                                        src="{$turl}images/led_{if $status.$moduleID == 'active'}green{else}white{/if}.png"
                                        width="13"></td>
                            <td><a href="index.php?p=170&type={$type}&act=settings&moduleID={$moduleID}">{$title}</a>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        <br/>
        <br/>
    {/foreach}
{/if}    
