<div id="inner_content">
    <form method="post" class="cmxform" style="width:700px;">
        <input type="hidden" name="action" value="add">
        <fieldset>
            <ol>
                <li>
                    <label>Kupon Kodu</label>
                    <input type="text" name="code" value="{$CPN->code}"/>
                </li>
                <li>
                    <label>İndirim Oranı</label>
                    % <input type="text" name="amount" value="{$CPN->amount}" style="width: 50px;"/>
                </li>
                <li>
                    <label>Bitiş tarihi</label>
                    <input type="text" name="dateExpires" id="dateExpires"
                           style="width:100px; {if !$CPN->dateExpires}display:none;{/if}" value="{$CPN->dateExpires}">

                    <input type="checkbox" name="never_expires" value="1" {if !$CPN->dateExpires}checked="checked"{/if}
                           id="never_expires"> Hiç bir zaman
                </li>

                <li>
                    <label>Geçerli servisler</label>
                    <select name="services[]" multiple="multiple">
                        {foreach from=$services item=srv}
                            <option value="{$srv.serviceID}">{$srv.service_name}</option>
                        {/foreach}
                    </select>
                </li>
            </ol>
        </fieldset>
        <p align="right"><input type="submit" value="Güncelle"/></p>
    </form>

</div>

{literal}
<script language="JavaScript">
$(document).ready(function () {
    $("#dateExpires").datepicker({dateFormat: 'dd-mm-yy'});

    $('#never_expires').click(function () {
        if (!$(this).is(':checked')) {
            $('#dateExpires').show();
        } else {
            $('#dateExpires').hide();
        }
    });
});
</script>{/literal}
