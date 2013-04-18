<form method="post" class="cmxform" style="width:90%;">
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li><label>##Domain##</label>{$Dom->domain}</li>
            <li><label>##Status##</label>##DomainDetails%{$Dom->status}##</li>
            <li><label>##DateRegistered##</label>{format_date date=$Dom->dateReg}</
            </li>
            <li><label>##DateExpires##</label>{format_date date=$Dom->dateExp}</
            </li>
            {if $Dom->moduleID && $Dom->status == 'active'}
                <li><label>NS 1</label><input type="text" name="ns1" value="{$Dom->ns1}"></li>
                <li><label>NS 2</label><input type="text" name="ns2" value="{$Dom->ns2}"></li>
                {if $contact_types}
                    {foreach from=$contact_types item=title key=type}
                        <li><label>{$title}</label>
                            <select name="contacts[{$type}]">
                                {foreach from=$contacts item=con}
                                    <option value="{$con.contactID}"
                                            {if $contact_details.$type.contactID == $con.contactID}selected{/if}>{$con.name}</option>
                                {/foreach}
                            </select>
                            (<a href="{$vurl}?p=user&s=contacts&a=ndc&t={$type}&dID={$Dom->domainID}">##AddNew##</a>)
                            &nbsp;&nbsp;(<a
                                    href="{$vurl}?p=user&s=contacts&a=ec&cID={$contact_details.$type.contactID}">##Update##</a>)
                        </li>
                    {/foreach}
                {/if}
            {/if}
        </ol>
        {if $Dom->moduleID && $Dom->status == 'active'}
            <p align="right" id='pbutton'><input type="submit" value="##Update##" onclick="return loading();"
                                                 class="button"></p>
            <p align="right" id='ploading' style="display: none;"><img src={$turl}images/loading.gif></p>
        {/if}
    </fieldset>
</form>

<script language="JavaScript">
    var dID = '{$Dom->domainID}';
    {literal}
    function loading() {
        $('#pbutton').hide();
        $('#ploading').show();
        return true;
    }

</script>{/literal} 
