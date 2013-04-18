<div id="inner_content" class="addon_form">
    <form method="post" class="cmxform" style="width:99%;" action="index.php?p=415&m=compact&orderID={$Order->orderID}"
          id="addon_form">
        <input type="hidden" name="action" value="update">
        <fieldset>
            <ol>
                <li>
                    <label>Eklenti No:</label>
                    {$Order->orderID}
                </li>
                <li>
                    <label>Paket Adı:</label>
                    <input name="title" value="{$Order->title}" style="width:200px;">
                </li>
                <li>
                    <label>Durum:</label>
                    <select name="status" id='status'>
                        {foreach from=$vars.ORDER_STATUS_TYPES item=i}
                            <option value="{$i}" {if $i == $Order->status}selected{/if}>##OrderDetails%{$i}##</option>
                        {/foreach}
                    </select>&nbsp;
                <span id='updateOnServer' style="display: none;">
                    <input type="checkbox" name="updateOnServer" value="1"> Sunucu üzerinde güncelle
                </span>
                </li>
                <li>
                    <label>Ücret:</label>
                    <input name="price" value="{$Order->price}" style="text-align:right;"
                           size="8"> {getCurrencyById id=$Order->paycurID}
                </li>
                <li>
                    <label>Sipariş Tarihi:</label>
                    {format_date date=$Order->dateAdded mode=datetime}
                </li>
                <li>
                    <label>Başlangıç:</label>
                    {format_date date=$Order->dateStart}
                </li>
                <li>
                    <label>Bitiş:</label>
                    {format_date date=$Order->dateEnd}
                </li>

                {foreach from=$srv item=obj key=setting}
                    <li>
                        <label>{$obj->label}</label>
                        {if $obj->type == "textbox" ||  $obj->type == "password"}
                            {$obj->value}
                        {elseif $obj->type == "checkbox"}
                            {if $obj->value == '1'}Evet{else}Hayır{/if}
                        {elseif $obj->type == "combobox"}
                            {assign var=value value=$obj->value}{$obj->options.$value}
                        {/if} {$obj->description}
                    </li>
                {/foreach}
            </ol>
        </fieldset>
        <p align="right"><span id='st_img'></span>&nbsp;&nbsp;<input type="submit" value="##Update##" align="right"
                                                                     onclick="$('#st_img').html('<img src='+turl+'images/loading.gif>'); return true;"/>
        </p>
    </form>
</div>
{literal}
    <script language="JavaScript">
        $('#addon_form').ajaxForm({
            dataType: 'json',
            success: processJson
        });
        function processJson(data) {
            if (data.st) {
                $('#st_img').html('<img id=ok_img src=' + turl + 'images/ok.png>');
                $('#ok_img').fadeOut(5000);
            } else {
                $('#st_img').html('<img src=' + turl + 'images/stop2.png>');
            }
        }
        $('#status').change(function () {
            $("#updateOnServer").show();
        });


        $(document).ready(function () {
            doParentIframe();
        });
    </script>
{/literal}


