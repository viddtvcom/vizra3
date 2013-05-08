<script src="{$vurl}js/jquery.blockui.js" type="text/javascript"></script>
{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $.blockUI.defaults.overlayCSS.opacity = 0.8;
            $.blockUI.defaults.overlayCSS.backgroundColor = '#FFF';
            function checkModuleSetting(moduleID) {
                if (moduleID == '') {
                    $('.modulevis').hide();
                } else {
                    $('.modulevis').show();
                }
            }

            $("#moduleID").change(function () {
                checkModuleSetting($(this).val());
            });
            checkModuleSetting($("#moduleID").val());

            $(".controller").change(function () {
                $('.' + $(this).attr('id')).block({ message: null });
                $('.' + $(this).val()).unblock();
            });

            $(".controller").each(function () {
                $('.' + $(this).attr('id')).block({ message: null });
                $('.' + $(this).val()).unblock();
            });


            //$(".cmxform input[title]").tooltip('#tooltip');
        });
    </script>
{/literal}


<div id="tooltip">&nbsp;</div>
{if $Service->addon != '1'}
    <form method="post" class="cmxform msg_ok">

        <input type="hidden" name="action" value="addAttr">

        <div style="float:left; width: 230px;">
            <fieldset>
                <ol>
                    <li>
                        <select name="setting">
                            {foreach from=$attr_types item=label key=setting}
                                <option value="{$setting}">{$label}</option>
                            {/foreach}
                        </select>
                        <input type="submit" value="Ekle"/>
                    </li>
                </ol>
            </fieldset>
        </div>
        <div style="float:left; width: 500px;">
            Bu servis planına yeni özellik ekleyebilirsiniz. Listede olmayan yeni bir özellik eklemek için Servis
            Özellikleri bölümünü kullanınız.
        </div>
        <div class="clear"></div>
    </form>
{/if}
<form method="post" class="cmxform" style="width:99%;">
    <input type="hidden" name="action" value="updateModuleSettings">
    <fieldset>
        <ol>
            <li>
                <label>Modül</label>
                <select name="moduleID" id="moduleID">
                    <option value="">Yok</option>
                    {foreach from=$modules item=title key=moduleID}
                        <option value="{$moduleID}" {if $Service->moduleID == $moduleID }selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>

            {if $module_inactive == true}
            <li>
                <label></label>

                <div class="msg_warn">Bu modül aktif olmadığı için ayarları güncelleyemezsiniz. Ayarlar > Modüller
                    bölümünden modülü aktif edebilirsiniz.
                </div>
            </li>
            {else}


            {if $Service->addon != '1' && !$Service->domain}
                <li class='modulevis'>
                    <label>Sunucu</label>
                    <select name="serverID">
                        <option value="0">Yok</option>
                        {foreach from=$servers item=s}
                            <option value="{$s.serverID}"
                                    {if $s.serverID == $Service->serverID}selected{/if}>{$s.serverName}</option>
                        {/foreach}
                    </select>
                </li>
            {/if}
            <li class='modulevis'>
                <label>Hesap Açılışı</label>
                {$select_provisionTypes}
            </li>
            {if $Service->addon == '1'}
                <li class='modulevis'>
                    <label>İlgili Modül Komutu</label>
                    <select name="moduleCmd">
                        <option value="">Yok</option>
                        {foreach from=$cmds item=cmd}
                            <option value="{$cmd}" {if $Service->moduleCmd == $cmd }selected{/if}>{$cmd}</option>
                        {/foreach}
                    </select>
                </li>
            {elseif !$Service->domain}
                <li><label>Tanımlayıcı</label>
                    <select name="settingID">
                        <option value="">Yok</option>
                        {foreach from=$custom_attrs item=obj key=setting}
                            <option value="{$obj->settingID}"
                                    {if $Service->settingID  == $obj->settingID}selected{/if}>{$obj->label}</option>
                        {/foreach}
                    </select> (Seçtiğiniz özellik, paket adına otomatik olarak kaydedilir, ve listemede gözükür)
                </li>
            {/if}

        </ol>
    </fieldset>

    <div class="clear"></div>
    <div class="table_wrapper">
        <div class="table_wrapper_inner">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th width="20"></th>
                    <th width="150">Özellik</th>
                    <th>Değer</th>
                    <th width="100">Görülebilirlik*</th>
                </tr>

                {foreach from=$srv item=obj key=setting}
                    <tr class='{cycle values=',alt '}{if $obj->source != 'custom'}modulevis {/if}{if $obj->class} {$obj->class} {/if}'>
                        <td>
                            {if $obj->source == 'custom'}
                                <a href="?p=116&tab=attrs&serviceID={$Service->serviceID}&act=delAttr&key={$setting}">
                                    <img src="{$turl}/images/ico_delete.png">
                                </a>
                            {/if}
                        </td>
                        <td>{$obj->label}</td>

                        <td>

                            {if $obj->valueBy == 'service'}
                                <input type="hidden" name="src[{$setting}]" value="{$obj->source}">
                                {if $obj->type == "textbox" ||  $obj->type == "password"}
                                    <input type="text" name="srv[{$setting}]" style="width:{$obj->width}px;"
                                           value="{$attrs.$setting.value}">
                                {elseif $obj->type == "checkbox"}
                                    <input type="checkbox" name="srv[{$setting}]" value="1"
                                           {if $attrs.$setting.value == '1'}checked{/if}>
                                {elseif $obj->type == "combobox"}
                                    <select name="srv[{$setting}]" {if $obj->controller}class='controller'
                                            id='{$setting}'{/if}>
                                        {foreach from=$obj->options item=v key=k}
                                            <option value="{$k}"
                                                    {if $attrs.$setting.value == $k }selected{/if}>{$v}</option>
                                        {/foreach}
                                    </select>
                                {/if}
                                {$obj->description} {$obj->desc_admin}
                            {elseif $obj->valueBy == 'client' }
                                Bu özellik kullanıcıdan alınır.
                            {elseif $obj->valueBy == 'module' }
                                Bu özellik modül tarafından otomatik olarak belirlenir.
                            {/if}
                        </td>
                        <td>
                            <input type="checkbox" name="ccs[{$setting}]" value="1"
                                   {if $attrs.$setting.clientCanSee == '1'}checked{/if}>
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>

    {/if}
    <p style="margin: 5px;">Görülebilirlik: İşaretli özellikler müşteri panelinde gösterilir.</p>

    <p align="right"><input type="submit" value="Güncelle"/></p>
</form>


