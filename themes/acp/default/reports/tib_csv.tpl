<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="hosting">
    <table cellpadding="0" cellspacing="0" class="datagrid" width="100%">

        <tr>
            <th width="200">Hosting Hizmet Seçimi</th>
            <td width="300">
                <select name="services[]" multiple="multiple" id="services" size="10">
                    {foreach from=$services item=s}
                        <option value="{$s.serviceID}">{$s.service_name}</option>
                    {/foreach}
                </select>
            </td>
            <td>
                <input type="submit" value=" Hosting Raporu Al">
            </td>
        </tr>
    </table>
</form>


<form method="post" class="cmxform" style="width:100%;">
    <input type="hidden" name="action" value="domain">
    <table cellpadding="0" cellspacing="0" class="datagrid" width="100%">

        <tr>
            <th width="200">Domain Raporu</th>
            <td width="300"> &nbsp;</td>
            <td>
                <input type="submit" value="Domain Raporu Al">
            </td>
        </tr>
    </table>
</form>

{literal}
<script language="JavaScript">
$(document).ready(function () {
    $('#services option').each(function () {
        $(this).attr("selected", "selected");
    });
});
</script>{/literal}
