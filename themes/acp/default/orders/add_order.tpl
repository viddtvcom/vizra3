<script src="{$vurl}js/jquery-ui.min.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
    $(document).ready(function () {
        $("#serviceID").change(function () {
            if ($("#serviceID").val() != "0") {
                window.location = 'index.php?p=412&clientID={/literal}{$Client->clientID}{literal}&serviceID=' + $("#serviceID").val();
            }
        });
        $("#dateStart").datepicker({dateFormat: 'dd-mm-yy'});
    });

    function form_check() {
        if ($('#chk_provision').attr('checked') && $('#serverID').val() == '0') {
            alert('Hesabın sunucu üzerinde açılabilmesi için bir Sunucu seçmelisiniz');
            return false;
        }
        return true;
    }
</script>
{/literal}
<form method="post" class="cmxform" style="width:500px;" onsubmit="return form_check();">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="clientID" value="{$Client->clientID}">
    <fieldset>
        <ol>
            <li>
                <label for="groupID">Müşteri:</label>
                {$Client->name}
            </li>
            <li>
                <label>Servis:</label>
                {$select_services}
            </li>
            {if $Service->serviceID}
                {if $servers}
                    <li>
                        <label>Sunucu</label>
                        <select name="serverID" id="serverID">
                            <option value="0">Yok</option>
                            {foreach from=$servers item=s}
                                <option value="{$s.serverID}"
                                        {if $s.serverID == $Service->serverID}selected{/if}>{$s.serverName}</option>
                            {/foreach}
                        </select>
                    </li>
                    <li>
                        <label>Hesap Açılışı</label>
                        <input type="checkbox" name="provision" value="1" id="chk_provision"> Hesabı sunucu üzerinde aç
                    </li>
                {/if}
                <li>
                    <label>Ödeme Şekli</label>
                    {if $Service->priceOptions}
                        <select name="period">
                            {foreach from=$Service->priceOptions item=price key=period}
                                <option value="{$period}">{$price|displayPrice:$period:$Service->paycurID}</option>
                            {/foreach}
                        </select>
                    {else}
                        ##Free##
                    {/if}
                </li>
                <li>
                    <label>Ödeme Durumu</label>
                    <select name="paymentStatus">
                        <option value="1">Ödendi</option>
                        <option value="0">Ödenmedi</option>
                    </select>
                </li>
                <li>
                    <label>Borç Kaydı</label>
                    <select name="billStatus">
                        <option value="1">Oluştur</option>
                        <option value="0">Oluşturma</option>
                    </select>
                </li>
                <li>
                    <label>Başlama Tarihi</label>
                    <input type="text" name="dateStart" id="dateStart" style="width:100px"
                           value="{$smarty.now|date_format:$config.DATE_FORMAT}">
                </li>
                {if $Service->groupID == 10}
                    <li>
                        <label>Alan Adı</label>
                        <input type="text" name="domain" style="width:200px">
                    </li>
                {else}
                    {foreach from=$Service->getAttributes(true) item=obj key=setting}
                        <li>
                            <label>{$obj->label}</label>
                            {if $obj->type == "textbox" ||  $obj->type == "password"}
                                <input type="text" name="att[{$setting}]" style="width:{$obj->width}px;">
                            {elseif $obj->type == "checkbox"}
                                <input type="checkbox" name="att[{$setting}]" value="1">
                            {elseif $obj->type == "combobox"}
                                <select name="att[{$setting}]">
                                    {foreach from=$obj->options item=v key=k}
                                        <option value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            {/if}
                            {$obj->description}
                        </li>
                    {/foreach}
                {/if}
            {/if}
        </ol>
    </fieldset>
    {if $Service->serviceID}
        <p align="right"><input type="submit" value="Siparişi Ekle"/></p>
    {/if}
</form>

