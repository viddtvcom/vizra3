<ul class="system_messages">
    <li class="blue"><span class="ico"></span><strong class="system_title">
            <a href="http://www.vizra.com/?p=kb&catID=39&entryID=34" target="_blank">Sunucu ayarları hakkında detaylı
                bilgi almak için tıklayınız</a>
        </strong></li>
</ul>

{if $Server->moduleCmds}
    <h2>Modül Komutları</h2>
    {foreach from=$Server->moduleCmds item=cmd}
        <form method="post" style="float: left; margin-left: 20px;">
            <input type="hidden" name="action" value="moduleOperation">
            <input type="hidden" name="moduleCmd" value="{$cmd}">
            <input type="submit" value="##ModuleCmd%{$cmd}##"
                   onclick="return confirm('Bu komut sunucu üzerinde çalıştırılacak. Emin misiniz?');">
        </form>
    {/foreach}
    <div class="clear"></div>
    <br/>
{/if}


<form method="post" class="cmxform" style="width:700px;"
      {literal}onsubmit="if ($('#serverName').val() == '') { alert('Sunucu adı boş olamaz'); return false;}"{/literal}>
    <input type="hidden" name="action" value="update">
    <fieldset>
        <ol>
            <li>
                <label>Sunucu Adı</label>
                <input type="text" name="serverName" value="{$Server->serverName}" id="serverName"/>
            </li>
            <li>
                <label>Durum</label>
                <select name="status">
                    <option value="active" {if $Server->status == 'active' }selected{/if}>##Active##</option>
                    <option value="inactive" {if $Server->status == 'inactive' }selected{/if}>##Inactive##</option>
                </select>
            </li>
            <li>
                <label>Modül</label>
                <select name="moduleID">
                    <option value="">Yok</option>
                    {foreach from=$modules item=title key=moduleID}
                        <option value="{$moduleID}" {if $Server->moduleID == $moduleID }selected{/if}>{$title}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                <label>Ana Ip</label>
                <input type="text" name="mainIp" value="{$Server->mainIp}"/>
            </li>
            <li>
                <label>Hostname</label>
                <input type="text" name="hostname" value="{$Server->hostname}"/>
            </li>
            <li>
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" value="{$Server->username}"/>
            </li>
            <li>
                <label>Şifre</label>
                <input type="text" name="password" value="{$Server->password}" style="display:none;"/>
                <a href="/" onclick="$(this).hide().prev().fadeIn(); return false;">Görmek için tıklayın</a>
            </li>
        </ol>
        <br/><br/>
        <ol>
            {foreach from=$modConfig item=obj key=setting}
                <li>
                    <label>{$obj->label}</label>
                    {if $obj->type == "textbox" ||  $obj->type == "password"}
                        <input type="text" name="_set[{$setting}]" style="width:{$obj->width}px;" value="{$obj->value}">
                    {elseif $obj->type == "checkbox"}
                        <input type="checkbox" name="_set[{$setting}]" value="1" {if $obj->value == '1'}checked{/if}>
                    {elseif $obj->type == "combobox"}
                        <select name="_set[{$setting}]">
                            {foreach from=$obj->options item=v key=k}
                                <option value="{$k}" {if $obj->value == $k }selected{/if}>{$v}</option>
                            {/foreach}
                        </select>
                    {elseif $obj->type == "textarea"}
                        <textarea style="width: {$obj->width}px; height: {$obj->height}px;"
                                  name="_set[{$setting}]">{$obj->value}</textarea>
                    {/if} {$obj->description}
                    &nbsp;&nbsp;&nbsp;
                </li>
            {/foreach}
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Güncelle"/></p>
</form>

