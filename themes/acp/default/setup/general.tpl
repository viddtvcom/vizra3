<script src="{$vurl}js/jquery.koocie.js" type="text/javascript"></script>

{if $show_cron_note}
    <ul class="system_messages">
        <li class="yellow"><span class="ico"></span><strong class="system_title">
                Otomasyon sisteminin çalışabilmesi için, GÜNDE 1 DEFA çalışacak şekilde aşağıdaki cron ayarını yapmış
                olmanız gerekmektedir.
                <br/><input type="text" value="php {$config.BASE_PATH}engine/cron/daily.php"
                            style="width: 500px; margin:10px 20px;">
                <br/>veya
                <br/><input type="text" value="GET {$config.HTTP_HOST}scripts/cron.php?t=daily&key={''|get_cron_key}"
                            style="width: 500px; margin:10px 20px;">

                <br/>Son çalışma zamanı: {'daily.php'|cronLastRun}</strong>

        </li>
    </ul>
    <br/>
{/if}

<div class="forms_wrapper" style="width:100%;">
    <form method="post" class="search_form general_form">
        <input type="hidden" name="action" value="update">


        <div class="table_tabs_menu">
            <ul class="table_tabs">
                {foreach from=$settings item=items key=grp}
                    <li>
                        <a href="/" rel="{$grp}">
                            <span><span>##SetupGeneral%{$grp}##</span></span>
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>


        <div class="table_wrapper">
            <div class="table_wrapper_inner">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            {foreach from=$settings item=items key=grp}
                                <fieldset id="{$grp}" class="tab_content" style="padding-bottom:30px;">
                                    {foreach from=$items item=obj key=setting}
                                        <div class="row">
                                            <label>{$obj->label}</label>

                                            <div class="inputs" style="width:701px;">
                                                {$obj->predescription}
                                                {if $obj->type == "textbox" ||  $obj->type == "password"}
                                                    <span class="input_wrapper" style="width:{$obj->width}px;">
                            <input class="text" type="text" name="_set[{$grp}][{$setting}]" value="{$obj->value}">
                        </span>
                                                {elseif $obj->type == "checkbox"}
                                                    <input type="checkbox" name="_set[{$grp}][{$setting}]" value="1"
                                                           {if $obj->value == '1'}checked{/if}>
                                                {elseif $obj->type == "combobox"}
                                                    <span class="input_wrapper select_wrapper">
                        <select name="_set[{$grp}][{$setting}]">
                            {foreach from=$obj->options item=v key=k}
                                <option value="{$k}" {if $obj->value == $k }selected{/if}>{$v}</option>
                            {/foreach}
                        </select>
                        </span>
                                                {elseif $obj->type == "textarea"}
                                                    <span class="input_wrapper textarea_wrapper"
                                                          style="width:{$obj->width}px; height:{$obj->height}px;">
                            <textarea class="text" name="_set[{$grp}][{$setting}]">{$obj->value}</textarea>
                        </span>
                                                {/if} &nbsp;&nbsp;{$obj->description}
                                            </div>
                                        </div>
                                    {/foreach}

                                    {if $grp == 'notify'}
                                        <div class="clear"></div>
                                        <div class="table_wrapper">
                                            <div class="table_wrapper_inner">
                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <th width="200"></th>
                                                        <th>Email</th>
                                                        <th>SMS</th>
                                                        <th>MSN</th>
                                                    </tr>
                                                    {foreach from=$items._binary item=d key=k}
                                                        <tr {cycle values=',class=alt'}>
                                                            <td>{$d->label}</td>
                                                            <td><input type="checkbox" name="_notify[{$k}][0]" value="1"
                                                                       {if $d->value.0 == '1'}checked{/if}></td>
                                                            <td><input type="checkbox" name="_notify[{$k}][1]" value="1"
                                                                       {if $d->value.1 == '1'}checked{/if}></td>
                                                            <td><input type="checkbox" name="_notify[{$k}][2]" value="1"
                                                                       {if $d->value.2 == '1'}checked{/if}></td>
                                                        </tr>
                                                    {/foreach}
                                                </table>
                                            </div>
                                        </div>
                                    {/if}


                                    <br/><br/><br/><br/>
                                </fieldset>
                            {/foreach}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="inputs">
                <span class="button green_button"><span><span>##Update##</span></span><input name=""
                                                                                             type="submit"/></span>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">

    {literal}
    $(document).ready(function () {
        $(".tab_content").hide();

        $("ul.table_tabs li").click(function () {
            $("ul.table_tabs li").find("a").removeClass("selected");
            $(this).find("a").addClass("selected");
            $(".tab_content").hide();
            var activeTab = $(this).find("a").attr('rel'); //Find the rel attribute value to identify the active tab + content
            $('#' + activeTab).fadeIn(); //Fade in the active content
            $.cookie('setting_tab', activeTab);

            doParentIframe();
            return false;
        });

        var curtab;
        if ($.cookie('setting_tab')) {
            curtab = $.cookie('setting_tab');
        } else {
            curtab = 'compinfo';
        }

        $("ul.table_tabs li").find('a[rel=' + curtab + ']').trigger('click');
    });
</script>
{/literal}
