{foreach from=$Order->moduleAdminCmds item=cmd}
    <form method="post" style="float: left; margin:10px 0 0 20px;">
        <input type="hidden" name="action" value="moduleOperation">
        <input type="hidden" name="moduleCmd" value="{$cmd}">
        <input type="submit" value="##ModuleCmd_{$cmd}##" onclick="return confirm('Emin misiniz?');">
    </form>
{/foreach}

<div class="clear"></div><br/>

<form method="post" class="cmxform" style="width:99%;">
    <input type="hidden" name="action" value="update_attrs">
    <fieldset>
        <ol>
            <li>
                <label>Sunucu</label>
                <select name="serverID">
                    <option value="0">Yok</option>
                    {foreach from=$servers item=s}
                        <option value="{$s.serverID}"
                                {if $s.serverID == $Order->serverID}selected{/if}>{$s.serverName}</option>
                    {/foreach}
                </select>
            </li>
            {foreach from=$Order->moduleLinks item=link key=label}
                <li  {cycle values=",class='odd'"}>
                    <label>##{$label}##</label>{$link}
                </li>
            {/foreach}
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Ayarları Güncelle" align="right"/></p>
</form>
<div id="tooltip">&nbsp;</div>
<br/><br/>
<form method="post" class="cmxform" style="width:99%;" id="orderattrsform">
    <input type="hidden" name="action" id="action" value="updateModuleSettings">
    <input type="hidden" name="cmd" id="cmd">

    <div class="clear"></div>
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>

                    <th width="150">Özellik</th>
                    <th>Değer</th>

                    <th width="100">Görülebilirlik*</th>
                    <th width="50">Güncelle</th>
                </tr>

                {foreach from=$srv item=obj key=setting}
                    <tr {cycle values=",class='alt'"}>
                        <td><strong>{$obj->label}</strong></td>
                        <td>
                            {if $obj->type == "textbox" ||  $obj->type == "password" || $obj->type == "server"}
                                <input type="text" name="srv[{$setting}]" style="width:{$obj->width}px;"
                                       value="{$obj->value}">
                            {elseif $obj->type == "textarea"}
                                <textarea name="srv[{$setting}]"
                                          style="width:{$obj->width}px; height:{$obj->height}px;">{$obj->value}</textarea>
                            {elseif $obj->type == "checkbox"}
                                <input type="checkbox" name="srv[{$setting}]" value="1"
                                       {if $obj->value == '1'}checked{/if}>
                            {elseif $obj->type == "combobox"}
                                <select name="srv[{$setting}]">
                                    {foreach from=$obj->options item=v key=k}
                                        <option value="{$k}" {if $obj->value == $k }selected{/if}>{$v}</option>
                                    {/foreach}
                                </select>
                            {/if} {$obj->description}  {$obj->desc_admin}
                        </td>
                        <td>
                            <input type="checkbox" name="{$setting}" id="{$setting}" class="dynacheck" value="1"
                                   {if $obj->clientCanSee == '1'}checked{/if}>
                        </td>
                        <td>
                            {if $obj->cmd != ''}
                                <img title="Sunucu üzerinde güncellemek için tıklayınız" src="{$turl}/images/reload.png"
                                     onclick="$('#cmd').val('{$obj->cmd}'); $('#action').val('moduleCmd'); $('#orderattrsform').submit();"
                                     style="cursor:pointer;">
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
    <p style="margin: 5px;">Görülebilirlik: İşaretli özellikler müşteri panelinde gösterilir.</p>

    <p align="right"><input type="button" value="Özellikleri Güncelle" id="updatebut"/></p>
</form>

<div id="question" style="display:none; cursor: default; height: 400px; font-size: 1.1em; vertical-align: middle;">
    <br/>

    <h1>Bu bilgiler sadece Vizra'da güncellenecek. <br/>Sunucu üzerinde güncelleme için ilgili özelliğin Güncelle
        butonuna tıklayınız<br/><br/>Devam etmek istiyor musunuz?</h1><br/>
    <input type="button" id="yes" value="Evet"/>
    <input type="button" id="no" value="Hayır"/>
</div>
<script src="{$vurl}js/jquery.blockui.js" type="text/javascript"></script>
<script language="JavaScript">
    var orderID = {$Order->orderID};
    {literal}$(document).ready(function () {
        $(".dynacheck").click(function () {
            var checked = $(this).is(':checked');
            $(this).hide();
            $(this).prev().html('<img src="' + turl + 'images/loading.gif">');

            $.post("index.php?p=311&tab=orders&subtab=attrs&orderID=" + orderID, {action: 'setModset', id: $(this).attr('id'), setting: $(this).attr('name'), value: checked  },
                    function (data) {
                        if (!data.st) {
                            $("#" + data.id).attr('checked', !checked);
                        }
                        $("#" + data.id).prev().html('');
                        $("#" + data.id).show();
                    }, "json");

        });

        $('#updatebut').click(function () {
            $.blockUI({ message: $('#question'), css: { width: '400px' } });
            return false;
        });

        $('#yes').click(function () {
            // update the block message 
            $.blockUI({ message: "<h1>Güncelleniyor...</h1>" });
            $('#orderattrsform').submit();
        });

        $('#no').click(function () {
            $.unblockUI();
            return false;
        });

        //$(".cmxform img[title]").tooltip('#tooltip');
        //$(".cmxform input[title]").tooltip('#tooltip');
    });
</script>{/literal} 