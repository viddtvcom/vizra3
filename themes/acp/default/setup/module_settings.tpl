{if $moduleCmds}
    <h2>Modül Komutları</h2>
    {foreach from=$moduleCmds item=cmd}
        <form method="post" style="float: left; margin-left: 20px;">
            <input type="hidden" name="action" value="moduleOperation">
            <input type="hidden" name="moduleCmd" value="{$cmd}">
            <input type="submit" value="##ModuleCmd_{$cmd}##"
                   onclick="return confirm('Bu komut çalıştırılacak. Emin misiniz?');">
        </form>
    {/foreach}
    <div class="clear"></div>
    <br/>
{/if}

{if $modConfig.notes.setting}
    <p class="msg_warn">{$modConfig.notes.setting}</p>
{/if}
<form method="post" class="cmxform" style="width:99%;">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="status" value="{$modConfig.sys.status->value}">
    <fieldset>
        <ol>
            {foreach from=$modConfig.sys item=obj key=setting}
                <li>
                    <label>{$obj->label}</label> {$obj->predescription}
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
                        <textarea name="_set[{$setting}]" style="width:99%; height:{$obj->height}px;"
                                  class="wysiwyg2">{$obj->value}</textarea>
                    {/if} {$obj->description}
                    &nbsp;&nbsp;&nbsp;
                </li>
            {/foreach}
        </ol>
    </fieldset>
    <p align="right"><input type="submit" value="Güncelle" align="right"/></p>
</form>

{literal}
    <script language="JavaScript">
        $(document).ready(function () {
            $('.wysiwyg2').wysiwyg();
        });
    </script>
{/literal}
<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css"/>
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>