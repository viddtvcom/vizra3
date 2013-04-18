{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $("#domainID").change(function () {
                var selID = $(this).val();
                if (selID > 0) window.location = vurl + '?p=user&s=domains&dID=' + selID;
            });
        });
    </script>
{/literal}

<select id="domainID">
    <option value="0">##Choose##</option>
    {foreach from=$domains item=d}
        <option value="{$d.domainID}" {if  $Dom->domainID == $d.domainID }selected{/if}>{$d.domain}</option>
    {/foreach}
</select>

{if $Dom}
    <table width="700" border="0" cellspacing="0" cellpadding="0" id="datagrid">
        <tr>
            <th width=120>##Domain##</th>
            <td>{$Dom->domain}</td>
        </tr>
        <tr>
            <th>##Status##</th>
            <td>{$Dom->status}</td>
        </tr>
        <tr>
            <th>##Registered##</th>
            <td>{format_date date=$Dom->dateReg}</td>
        </tr>
        <tr>
            <th>##Expires##</th>
            <td>{format_date date=$Dom->dateExp}</td>
        </tr>
        {if $Dom->moduleID}
            <tr>
                <th>##DNS Server##1</th>
                <td><input type="text" name="ns1" value="{$Dom->ns1}"></td>
            </tr>
            <tr>
                <th>##DNS Server##2</th>
                <td><input type="text" name="ns2" value="{$Dom->ns2}"></td>
            </tr>
        {/if}
    </table>
{/if}
